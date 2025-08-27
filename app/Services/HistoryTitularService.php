<?php

namespace App\Services;

use App\Http\Requests\Clients\StoreClientRequest;
use App\Models\HistoryTitular;

class HistoryTitularService{
    public function createNewTitular(StoreClientRequest $request, $client){
        $titular = new HistoryTitular();
        $titular->names_lastnames = $request->namesLastnames;
        $titular->dni = $request->dni;
        $titular->client_id = $client->id;
        $titular->save();
    }
}