<?php

namespace App\Console\Commands;

use App\Events\Paid;
use App\Receipt;
use App\Services\Wallet\TransactionTypes;
use App\Services\Wallet\WalletService;
use App\User;
use App\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompleteReceipt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var Receipt
     */
    private $receipt;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * Create a new command instance.
     *
     * @param Receipt $receipt
     * @param User $user
     * @param Vendor $vendor
     */
    public function __construct(Receipt $receipt, User $user, Vendor $vendor)
    {
        parent::__construct();

        $this->receipt = $receipt;
        $this->user = $user;
        $this->vendor = $vendor;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        DB::beginTransaction();
        $wallet = new WalletService();
        $wallet->debtor($this->user, $this->receipt->amount, TransactionTypes::Withdraw, trans('transaction.payment', ['name' => $this->vendor->name], 'fa'), $this->receipt->id);
        $wallet->creditor($this->vendor, $this->receipt->total, TransactionTypes::Deposit, "Deposit from a Customer", $this->receipt->id);
        $this->receipt->status = Receipt::Done;
        $this->receipt->save();
        DB::commit();

        event(new Paid($this->user, $this->vendor, $this->receipt));

        return $this->receipt->status;
    }
}
