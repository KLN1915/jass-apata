<?php

namespace App\Services;

use Carbon\Carbon;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Models\Client;

class ClientService{
    public function createClient(StoreClientRequest $request, $occupation){
        $client = new Client();
        $client->phone_number = $request->phoneNumber;
        $client->datebirth = $request->datebirth;
        $client->grade = $request->grade;
        $client->datebirth = Carbon::parse($request->datebirth)->format('Y-m-d');
        $client->occupation_id = $occupation->id ?? null;
        $client->save();

        return $client;
    }
}