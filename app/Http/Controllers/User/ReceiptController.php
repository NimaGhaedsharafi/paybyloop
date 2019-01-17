<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 1/17/19
 * Time: 12:51 PM
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Receipt;
use App\Services\Utility;
use App\Wallet;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class ReceiptController extends Controller
{
    public function show(Request $request, $reference)
    {
        $transaction = Wallet::with('receipt', 'receipt.vendor')->where('reference', $reference)->first();

        if ($transaction == null || $transaction->has_receipt == 0) {
            return view('receipt.invalid');
        }

        /** @var Receipt $receipt */
        $receipt = $transaction->receipt;

        $data = [
            'total' => $receipt->getCameraReadyNumber('total'),
            'amount' => $receipt->getCameraReadyNumber('amount'),
            'date' => app(Utility::class)->en2fa(Jalalian::fromCarbon($receipt->created_at)->format('%d %B %Y')),
            'time' => app(Utility::class)->en2fa($receipt->created_at->format('H:i')),
            'vendor' => $receipt->vendor->name,
            'reference' => $receipt->reference,
        ];

        return view('receipt.valid', $data);
    }
}