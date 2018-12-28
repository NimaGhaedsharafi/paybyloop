<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Services\Gift\Exceptions\GiftException;
use App\Services\Gift\GiftService;
use App\Services\Wallet\TransactionTypes;
use App\Services\Wallet\WalletService;
use App\Gift;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        /** @var User $user */
        $user = auth()->user;

        DB::beginTransaction();
        try {
            /** @var Gift $result */
            $gift = $service->redeem($user->id, $request->input('code'));
        } catch (GiftException $exception) {
            throw new ApiException(1004, $exception->getMessage());
        }

        /** @var WalletService $walletService */
        $walletService = app(WalletService::class);
        $walletService->creditor($user, $gift->amount, TransactionTypes::Voucher, trans('transaction.giftcard', [], 'fa'));
        DB::commit();

        return response(['status' => 'ok']);
    }
}
