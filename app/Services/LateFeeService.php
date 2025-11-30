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
}