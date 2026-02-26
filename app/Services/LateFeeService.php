<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\LateFee;

class LateFeeService{
    public function createLateFee($service, $request){
        if($service->late_fee){
            if($service->charge_period == 'ANUAL'){
                LateFee::create([
                    'amount' => $request->latefee_amount,
                    'end_date' => sprintf('1900-%02d-%02d', $request->period_month, $request->period_day),
                    'service_id' => $service->id,
                ]);
            }
        }
    }

    // public function evaluateFee($service, $request){
    //     $lateFee = LateFee::firstWhere('service_id', $service->id);

    //     if (!$lateFee) {
    //         return;
    //     }

    //     $lateFee->end_date = $request->period_day;
    //     $lateFee->end_date = $request->period_month;
    //     $lateFee->amount = $request->latefee_amount;

    //     if($lateFee->isDirty()){
    //         $lateFee->save();
    //     }
    // }

    public function evaluateFee($id, $request)
    {
        $lateFee = LateFee::firstWhere('service_id', $id);

        if (!$lateFee) {
            return;
        }

        $currentDay = $lateFee->end_date->day;
        $currentMonth = $lateFee->end_date->month;

        if (
            $currentDay != $request->period_day ||
            $currentMonth != $request->period_month ||
            $lateFee->amount != $request->latefee_amount
        ) {
            $lateFee->end_date = sprintf('1900-%02d-%02d', $request->period_month, $request->period_day);

            $lateFee->amount = $request->latefee_amount;

            $lateFee->save();
        }
    }
}