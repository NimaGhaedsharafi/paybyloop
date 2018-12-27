<?php

namespace App\Http\Controllers\User;

use App\Console\Commands\CompleteReceipt;
use App\Events\Paid;
use App\Exceptions\ApiException;
use App\Receipt;
use App\Services\Voucher\Exceptions\VoucherException;
use App\Services\Voucher\VoucherService;
use App\Services\Wallet\Exception\InsufficientCredit;
use App\Services\Wallet\TransactionTypes;
use App\Services\Wallet\WalletService;
use App\User;
use App\Vendor;
use App\Voucher;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        return Wallet::where('user_id', Auth::user()->id)->where('user_type', 1)->latest()->get();
    }

    public function pay(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'vendor_id' => 'required'
        ]);

        /** @var User $user */
        $user = Auth::user();
        /** @var Vendor $vendor */
        $vendor = Vendor::where('vendor_id', $request->input('vendor_id'))->firstOrFail();

        $amount = $request->input('amount');

        // set a default value for promotion
        $promotion = 0;
        $voucherId = 0;
        if ($request->has('voucher_code') && strlen(trim($request->input('voucher_code'))) > 0) {
            /** @var VoucherService $voucher */
            $voucher = app(VoucherService::class);
            try {
                $promotion = $voucher->isUserEligible($user, $request->input('voucher_code'), $vendor, $amount);
                if ($promotion != 0) {
                    $voucherId = Voucher::select('id')->where('code', $request->input('voucher_code'))->first()->id;
                }
            } catch (VoucherException $exception) {
                throw new ApiException(1002, 'voucher can\'t be applied');
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
            $receipt->reference = 'LOP-' . Carbon::now()->dayOfYear . '-' . rand(1000000, 9999999);
            $receipt->status = Receipt::Initiate;
            $receipt->save();

            /** @var WalletService $wallet */
            $wallet = new WalletService();
            if ($wallet->balance($user) < $receipt->amount) {
                $query = [
                    'ref' => $receipt->reference,
                    'amount' => max(1000, $receipt->amount - $wallet->balance($user))
                ];

                return redirect(route('v1.user.charge.auto') . '/?' . http_build_query($query), 301);
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
            $promotion = app(VoucherService::class)->isUserEligible($user, $request->input('voucher_code'), $vendor, $amount);
        } catch (VoucherException $exception) {
            throw new ApiException(1002, 'voucher can\'t be applied');
        }

        return response()->json([
            'amount' => $amount - $promotion
        ]);
    }
}
