<?php

namespace App\Services;

use App\Models\Direction;

class DirectionService{
    private function addDirection($i, $request, $client){
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

    public function createDirections($request, $client){
        for($i=0; $i < count($request->directions); $i++){
            $this->addDirection($i, $request, $client);
        }
    }

    public function updateDirections($request, $client){
        for($i=0; $i < count($request->directions); $i++){
            if($request->direction_ids[$i] === null){
                $this->addDirection($i, $request, $client);
            }else{
                $direction = Direction::findOrFail($request->direction_ids[$i]);
                
                $direction->name = $request->directions[$i];
                $direction->cant_beneficiaries = $request->cant_beneficiaries[$i];
                $direction->permanence = $request->permanence[$i];
                $direction->drains = $request->drains[$i];
                $direction->material = $request->material[$i];
                $direction->client_id = $client->id;
                $direction->zone_id = $request->zone_id[$i];

                if($direction->isDirty()){
                    $direction->save();
                }
            }
        }
    }

        // public function createDirections($request, $client){
    //     for($i=0; $i < count($request->directions); $i++){
    //         $direction = new Direction();

    //         $direction->name = $request->directions[$i];
    //         $direction->cant_beneficiaries = $request->cant_beneficiaries[$i];
    //         $direction->permanence = $request->permanence[$i];
    //         $direction->drains = $request->drains[$i];
    //         $direction->material = $request->material[$i];
    //         $direction->client_id = $client->id;
    //         $direction->zone_id = $request->zone_id[$i];
    //         $direction->save();
    //     }
    // }
}