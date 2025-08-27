<?php

namespace App\Services;

use App\Http\Requests\Clients\StoreClientRequest;
use App\Models\Direction;

class DirectionService{
    public function createDirections(StoreClientRequest $request, $client){
        for($i=0; $i < count($request->directions); $i++){
            $direction = new Direction();
            $direction->name = $request->directions[$i];
            $direction->cant_beneficiaries = $request->cant_beneficiaries[$i];
            $direction->permanence = $request->permanence[$i];
            $direction->drains = $request->drains[$i];
            $direction->material = $request->material[$i];
            $direction->client_id = $client->id;
            $direction->zone_id = $request->zone_id[$i];
            $direction->save();
        }
    }
}