<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\AdditionalService;

class AdditionalServiceService{
    public function createAddService($request){
        $addService = AdditionalService::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return $addService;
    }
}