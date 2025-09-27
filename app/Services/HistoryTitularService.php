<?php

namespace App\Services;

use App\Models\HistoryTitular;

class HistoryTitularService{
    public function createNewTitular($request, $client){
        $titular = new HistoryTitular();
        $titular->names_lastnames = $request->namesLastnames;
        $titular->dni = $request->dni;
        $titular->client_id = $client->id;
        $titular->save();
    }

    public function updateTitular($id, $request, $client){
        // $titular = HistoryTitular::findOrFail($id);
        $titular = HistoryTitular::where('client_id', $client->id)->orderBy('id', 'desc')->first();

        if ($request->has('namesLastnames') && $request->has('dni')) {
            $titular->update([
                'is_current' => 0
            ]);

            $this->createNewTitular($request, $client);
        }

        return $titular;
    }
}