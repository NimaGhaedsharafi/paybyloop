<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Services\Gift\GiftService;
use App\Services\Wallet\TransactionTypes;
use App\Services\Wallet\WalletService;
use App\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PromotionController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function gift(Request $request)
    {
        /** @var GiftService $service */
        $service = app(GiftService::class);
        /** @var Gift $result */
        $voucher = $service->redeem(Auth::user()->id, $request->input('code'));

        if ($voucher === null) {
            throw new ApiException(1004, trans('voucher.gift.invalid', [], 'fa'));
        }

        /** @var WalletService $walletService */
        $walletService = app(WalletService::class);
        $walletService->creditor(Auth::user(), $voucher->amount, TransactionTypes::Voucher, trans('transaction.giftcard', [], 'fa'));

        return response(['status' => 'ok']);
    }
}
