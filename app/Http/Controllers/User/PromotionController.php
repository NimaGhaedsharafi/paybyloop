<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Voucher\VoucherService;
use App\Services\Wallet\WalletService;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PromotionController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function redeem(Request $request)
    {
        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        /** @var Voucher $result */
        $voucher = $service->redeem(Auth::user()->id, $request->input('code'));

        /** @var WalletService $walletService */
        $walletService = app(WalletService::class);
        $walletService->creditor(Auth::user(), $voucher->amount, 3, $voucher->title);

        return response(['status' => 'ok']);
    }
}
