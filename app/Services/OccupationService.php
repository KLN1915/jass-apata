<?php

namespace App\Services;

use App\Http\Requests\Clients\StoreClientRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Occupation;

class OccupationService{
    public function existsOrCreate(StoreClientRequest $request){
        $occupation = $request->occupation;

        if(!$occupation){
            return ;
        }

        if(ctype_digit($occupation)){
            try{
                $existingOccupation = Occupation::findOrFail($occupation);
                return $existingOccupation;
            }catch(ModelNotFoundException $e){
                throw new \Exception('Ocupación no encontrada');
            }
        }else{
            $newOccupation = Occupation::firstOrCreate(['name' => $occupation]);
            return $newOccupation;
        }
    }
}