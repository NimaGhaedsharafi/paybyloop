<?php

namespace App\Http\Controllers\User;

use App\Console\Commands\CompleteReceipt;
use App\Enums\ErrorCode;
use App\Exceptions\ApiException;
use App\Receipt;
use App\Services\Voucher\Exceptions\VoucherException;
use App\Services\Voucher\VoucherService;
use App\Services\Wallet\Exception\InsufficientCredit;
use App\Services\Wallet\WalletService;
use App\User;
use App\Vendor;
use App\Voucher;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        return Wallet::where('user_id', Auth::user()->id)->where('user_type', 1)->latest('id')->get();
    }

    public function pay(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'vendor_id' => 'required'
        ]);
        
        $amount = $request->input('amount');
        if ($amount > config('wallet.limits.max') || $amount < config('wallet.limits.min')) {
            throw new ApiException(ErrorCode::InvalidAmount, 'حداقل مبلغ قابل پرداخت ۱۰۰۰ تومان است.');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->isBlocked()) {
            throw new ApiException(1003, trans('system.blocked', [], 'fa'), 403);
        }
        /** @var Vendor $vendor */
        $vendor = Vendor::where('vendor_id', $request->input('vendor_id'))->firstOrFail();

        // set a default value for promotion
        $promotion = 0;
        $voucherId = 0;
        if ($request->has('voucher_code') && strlen(trim($request->input('voucher_code'))) > 0) {
            /** @var VoucherService $voucher */
            $voucher = app(VoucherService::class);
            try {
                $promotion = $voucher->canUse($user, $request->input('voucher_code'), $vendor, $amount);
                if ($promotion != 0) {
                    $voucherId = Voucher::select('id')->where('code', $request->input('voucher_code'))->first()->id;
                }
            } catch (VoucherException $exception) {
                throw new ApiException(1002, $exception->getMessage());
            }
        }

        try {

            /** @var Receipt $receipt */
            $receipt = new Receipt();
            $receipt->user_id = $user->id;
            $receipt->vendor_id = $vendor->id;
            $receipt->voucher_id = $voucherId;
            $receipt->saving = $promotion;
            $receipt->amount = $amount - $promotion;
            $receipt->total = $amount;
            $receipt->reference = 'LOP-' . str_pad(Carbon::now()->dayOfYear, 3, '0', STR_PAD_LEFT) . '-' . rand(1000000, 9999999);
            $receipt->status = Receipt::Initiate;
            $receipt->save();

            /** @var WalletService $wallet */
            $wallet = new WalletService();
            if ($wallet->balance($user) < $receipt->amount) {
                $query = [
                    'ref' => $receipt->reference,
                    'amount' => max(1000, $receipt->amount - $wallet->balance($user))
                ];

                // FIXME: totally Anti-pattern
                $request->json()->add(['ref' => $receipt->reference]);
                $request->json()->set('amount', max(1000, $receipt->amount - $wallet->balance($user)));
                return app(PaymentController::class)->auto($request);

                // return redirect(route('v1.user.charge.auto') . '/?' . http_build_query($query), 301);
            }

            $this->dispatch(new CompleteReceipt($receipt, $user, $vendor));

            return response()->json([
                'status' => 'success'
            ]);
        } catch (InsufficientCredit $exception) {
            throw new ApiException(1001, 'credit is insufficient');
        }
    }

    /**
     *
     */
    public function balance()
    {
        return response()->json([
            'balance' => (new WalletService())->balance(Auth::user())
        ]);
    }

    /**
     * @param Request $request
     */
    public function voucherCheck(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'vendor_id' => 'required',
            'voucher_code' => 'required'
        ]);

        /** @var User $user */
        $user = Auth::user();
        /** @var Vendor $vendor */
        $vendor = Vendor::where('vendor_id', $request->input('vendor_id'))->firstOrFail();
        $amount = $request->input('amount');


        try {
            $promotion = app(VoucherService::class)->canUse($user, $request->input('voucher_code'), $vendor, $amount);
        } catch (VoucherException $exception) {
            throw new ApiException(1002, 'voucher can\'t be applied');
        }

        return response()->json([
            'amount' => $amount - $promotion
        ]);
    }

    /**
     * @param Request $request
     * @param $code
     * @return
     */
    public function receipt(Request $request, $code)
    {
        /** @var User $user */
        $user = auth()->user();

        $wallet = Wallet::with(['receipt', 'receipt.vendor'])
            ->where('reference', trim($code))
            ->where('user_id', $user->id)
            ->where('user_type', 1)
            ->first();

        if ($wallet === null) {
            throw new ApiException(1005, 'Not Found');
        }

        $receipt = $wallet->receipt;
        $data = [
            'vendor' => $receipt->vendor->name,
            'reference' => $receipt->reference,
            'has_voucher' => $receipt->has_voucher,
            'saving' => $receipt->getCameraReadyNumber('saving'),
            'paid' => $receipt->getCameraReadyNumber('amount'),
            'total' => $receipt->getCameraReadyNumber('total'),
            'created_at' => $receipt->updated_at->toDateTimeString()
        ];

        return response()->json($data);
    }
}
