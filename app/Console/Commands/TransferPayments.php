<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RidePayment;
use App\Models\User;
use App\Models\UserAccount;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class TransferPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Payment to Bank accounts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $stripeService = new StripeService();
            $ridePayment = RidePayment::where('is_paid', 0)->get();
            $count = 0;
            foreach ($ridePayment as $payment) {
                $user = User::find($payment->driver_id);
                if($user->is_broad == 1) {
                    $userAccount = UserAccount::where('user_id', $user->id)->first();
                    $trsnsfer = $stripeService->bankTransfer($payment->driver_ammount,$userAccount->stripe_account_id);
                    $payment->is_paid = 1;
                    $payment->stripe_transfer_id = $trsnsfer->id;
                    $payment->save();
                    $count++;
                }
            }
            Log::info('payment send done'.$count);
            return 0;
        } catch (\Exception $e) {
            Log::info("Something went wrong " . $e->getMessage());
        }
    }
}
