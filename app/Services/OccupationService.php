<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Occupation;
use App\Models\Client;

class OccupationService{
    // public function existsOrCreate(StoreClientRequest $request){
    //     $occupation = $request->occupation;

    //     if(!$occupation){
    //         return ;
    //     }

    //     if(ctype_digit($occupation)){
    //         try{
    //             $existingOccupation = Occupation::findOrFail($occupation);
    //             return $existingOccupation;
    //         }catch(ModelNotFoundException $e){
    //             throw new \Exception('Ocupación no encontrada');
    //         }
    //     }else{
    //         $newOccupation = Occupation::firstOrCreate(['name' => $occupation]);
    //         return $newOccupation;
    //     }
    // }

    public function resolveOccupation($occupation){
        if(!$occupation){
            return ;
        }

        if(ctype_digit($occupation)){            
            return Occupation::findOrFail($occupation);
        }

        return Occupation::firstOrCreate(['name' => $occupation]);
    }

    // public function updateOccupation(UpdateClientRequest $request, string $id){
    //     $clientOccupation = Client::findOrFail($id)->occupation_id;
    //     $newOccupation = $request->occupation;

    //     if($clientOccupation == null){
    //         if($newOccupation){
    //             $this->existsOrCreate($newOccupation);
    //         }else{
    //             return ;
    //         }
    //     }else{
    //         if($newOccupation == ''){
    //             $clientOccupation->update([
    //                 'occupation_id' => null
    //             ]);
    //         }else{
    //             $clientOccupation->update([
    //                 'occupation_id' => $newOccupation
    //             ]);
    //         }
    //     }
    // }

    public function updateOccupation($clientId, $occupationInput){
        var_dump('ocupacion del request:', $occupationInput);
        $client = Client::findOrFail($clientId);

        if($occupationInput === null || $occupationInput === ''){
            $client->update([
                'occupation_id' => null
            ]);

            return $client;
        }

        $occupation = $this->resolveOccupation($occupationInput);
        var_dump('occupation_id', $occupation->id);

        $client->update([
            'occupation_id' => $occupation->id
        ]);

        return $occupation;
    }
}