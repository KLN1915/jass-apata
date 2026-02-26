<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Service;

class ServiceService{
    public function createService($request){
        $service = Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'charge_period' => $request->chargePeriod,
            'late_fee' => $request->lateFee ?? 0,
        ]);

        return $service;
    }

    public function updateService($request, $id){

        $service = Service::findOrFail($id);
        
        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'charge_period' => $request->chargePeriod,
            'late_fee' => $request->lateFee ?? 0,
        ]);

        return $service;
    }

    public function getServiceData($id){
        $service = Service::with('lateFee')->find($id);

        return response()->json($service);
    }
}