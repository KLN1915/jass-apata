<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Debt;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApplyLateFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debts:apply-late-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = Service::where('late_fee', 1)
            ->with('lateFee')
            ->get();

        foreach($services as $service){
            if (!$service->lateFee) {
                continue;
            }

            $feeAmount = $service->lateFee->amount;

            $due = Carbon::parse($service->lateFee->end_date);

            $annualDueDate = Carbon::create(
                now()->year,
                $due->month,
                $due->day
            );

            if (now()->greaterThan($annualDueDate)) {
                $this->info("Servicio {$service->name}: vencido");
                $contracts = Contract::where('service_id', $service->id)
                    ->where('status', 'ACTIVO')
                    ->get();

                foreach($contracts as $contract){
                    $debtIds = Debt::where('contract_id', $contract->id)
                        ->where('type', 'NORMAL')
                        ->where('payed', 0)
                        ->where('period', '>=', 2025)
                        ->pluck('id');

                    Debt::whereIn('id', $debtIds)
                        ->update([
                            // 'interest_amount' => $service->lateFee->amount
                            'interest_amount' => DB::raw(
                                'COALESCE(interest_amount, 0) + ' . $feeAmount
                            )
                        ]);
                }
            }
        }
    }
}
