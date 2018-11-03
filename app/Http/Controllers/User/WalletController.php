<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ApiException;
use App\Services\Wallet\Exception\InsufficientCredit;
use App\Services\Wallet\WalletService;
use App\User;
use App\Vendor;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        return Wallet::where('user_id', Auth::user()->id)->where('user_type', 1)->get();
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

        try {

            DB::transaction(function () use ($amount, $user, $vendor) {
                /** @var WalletService $wallet */
                $wallet = new WalletService();
                $wallet->debtor($user, $amount, 1, "Pay to a vendor");
                $wallet->creditor($vendor, $amount, 2, "Pay be a customer");
            });
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
}
