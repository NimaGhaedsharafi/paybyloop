<?php

namespace App\Http\Controllers\User;

use App\Enums\ErrorCode;
use App\Exceptions\ApiException;
use App\Payping;
use App\Services\Wallet\TransactionTypes;
use App\Services\Wallet\WalletService;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use function GuzzleHttp\json_decode as json_decode;

class PaymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function ipg(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required'
        ]);

        $amount = $request->input('amount');
        if ($amount > config('payment.limits.max') || $amount < config('payment.limits.min')) {
            throw new ApiException(ErrorCode::InvalidAmount, 'amount is not acceptable');
        }

        /** @var User $user */
        $user = auth()->user();
        /** @var Client $client */

        $client = new Client([
            'base_uri' => config('payping.base_uri')
        ]);

        /** @var Payping $payment */
        $payment = new Payping();
        $payment->user_id = $user->id;
        $payment->amount = $amount;
        $payment->status = Payping::Initiated;
        $payment->code = '';
        $payment->reference_id = strtolower(str_random(20) . time());
        $payment->save();

        $params = [
            'payerName' => $user->getName(),
            'payerIdentity' => $user->cellphone,
            'amount' => $amount,
            'clientRefId' => $payment->reference_id,
            'returnUrl' => route('v1.user.charge.ipg.callback', ['code' => $payment->code]),
        ];

        try {

            /** @var Response $response */
            $response = $client->request('POST', 'v1/pay', [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'bearer ' . config('payping.token'),
                ],
                RequestOptions::BODY => json_encode($params),
                RequestOptions::TIMEOUT => 5
            ]);

            $result = json_decode($response->getBody()->getContents());
            $payment->code = $result->code;
            $payment->status = Payping::Requested;
            $payment->save();

            // https://api.payping.ir/v1/pay/gotoipg/%7Bcode%7D
            return redirect(config('payping.base_uri') . 'v1/pay/gotoipg/' . $payment->code, 301);
        } catch (\Exception $exception) {
            Log::error('IPG: ' . $exception->getMessage());
            throw new ApiException(ErrorCode::PaymentInitiationFailed, 'Something went wrong!');
        }
    }

    /**
     * @param Request $request
     * @param $code
     * @return mixed
     */
    public function ipgCallback(Request $request, $code)
    {
        if (count(config('payping.ips')) != 0 && in_array($request->ip(), config('payping.ips')) == false) {
            \Log::error('IPG: ' . 'A Doggy guy is trying to come in with this IP address(' . $request->ip() . ')');
            return abort(403);
        }

        /** @var Payping $payment */
        $payment = Payping::where('code', trim($code))->where('status', Payping::Requested)->latest()->firstOrFail();

        $client = new Client([
            'base_uri' => config('payping.base_uri')
        ]);

        $payment->status = Payping::Verifying;
        $payment->save();

        $params = [
            'amount' => $payment->amount,
            'refId' => $payment->reference_id,
        ];

        try {
            $client->request('POST', 'v1/pay/verify', [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'bearer ' . config('payping.token'),
                ],
                RequestOptions::BODY => json_encode($params),
                RequestOptions::TIMEOUT => 5
            ]);

            $payment->status = Payping::Verified;
            $payment->save();

            /** @var User $user */
            $user = User::find($payment->user_id);

            /** @var WalletService $wallet */
            $wallet = app(WalletService::class);
            $balance = $wallet->creditor($user, $payment->amount, TransactionTypes::IPG, 'IPG Payment‌');

            return view('payment.success', [
                'amount' => $payment->getCameraReadyNumber(),
                'ref' => $code,
            ]);
        } catch (\Exception $exception) {
            Log::error('IPG: ' . $exception->getMessage());
            return view('payment.fail', [
                'code' => $code,
            ]);
        }
    }
}
