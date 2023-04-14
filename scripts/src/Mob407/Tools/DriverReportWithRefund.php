<?php

namespace App\Mob407\Tools;

use App\Mob407\Models\Driver;
use App\Mob407\Models\PaymentHistory;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Output\Output;

class DriverReportWithRefund
{
    public static function print(array|Collection $drivers, Output $output): void
    {
        $drivers->each(function (Driver $driver) use ($output) {
            $output->writeln(sprintf('"Driver ID:",%d,,,', $driver->id));
            $output->writeln("session_id,amount,balance_before,balance_after,session_date");

            $driver->payments->each(function (PaymentHistory $history) use ($output) {
                $output->writeln(sprintf(
                    '%d,%s,%s,%s,"%s"',
                    $history->session_id ?: $history->payment_type,
                    $history->amount,
                    $history->balance_before,
                    $history->balance_after,
                    $history->created_at
                ));
            });

            $toRefund = $driver->payments()
                ->selectRaw('sum(amount) as to_refund')
                ->whereNull('session_id')
                ->where('balance_diff', '>', 0)
                ->first()
                ->to_refund;

            $output->writeln(sprintf('"Amount to refund:", %f,,,', $toRefund));

            $output->writeln(',,,,');
            $output->writeln(',,,,');
        });
    }
}