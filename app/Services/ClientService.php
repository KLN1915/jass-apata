<?php

namespace App\Services;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\Client;
use App\Models\HistoryTitular;

class ClientService{
    public function getAssociateds($request){
        $search = $request->get('search');

        // $clients = Client::with('currentTitular')
        //     ->orWhere('names_lastnames', 'LIKE', "%{$search}%")
        //     ->orWhere('dni', 'LIKE', "%{$search}%")
        //     ->limit(5)
        //     ->get();

        $titulars = HistoryTitular::query()
            ->where('is_current', 1)
            ->where(function ($q) use ($search) {
                $q->where('names_lastnames', 'LIKE', "%{$search}%")
                ->orWhere('dni', 'LIKE', "%{$search}%");
            })
            // ->with('client') // si quieres también el cliente
            ->limit(5)
            ->get();

        $data = $titulars->map(function($titular){
            return [
                'id' => $titular->client_id,
                'text' => $titular->names_lastnames . ' - ' . $titular->dni,
            ];
        });

        return $data;
    }

    public function getClientsData(){
        try {
            $clients = Client::with(['currentTitular', 'directions.zone']);

            return DataTables::of($clients)
                ->addIndexColumn()
                ->addColumn('names_lastnames', function ($client) {
                    return $client->currentTitular ? $client->currentTitular->names_lastnames : 'Sin titular';
                })
                ->addColumn('dni', function ($client) {
                    return $client->currentTitular ? $client->currentTitular->dni : 'Sin titular';
                })
                ->addColumn('directions', function ($client) {
                    return $client->directions->map(function($direction){
                        return '<span class="badge bg-warning d-block mb-1">'.e($direction->name).'</span>';
                    })->implode(' ');
                })
                ->addColumn('zones', function ($client) {
                    return $client->directions->map(function($direction){
                        return '<span class="badge bg-secondary d-block mb-1">'.e(optional($direction->zone)->name).'</span>';
                    })->implode(' ');
                })
                ->addColumn('actions', function ($client) {
                    return '
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <button type="button" class="btn btn-info btnShow" data-id="' . $client->id . '">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-success btnEdit" data-id="' . $client->id . '">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['directions', 'zones', 'actions'])
                ->filter(function ($query) {
                    $this->applyFilters($query);
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc'); // aquí cambias la columna
                })
                ->make(true);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    private function applyFilters($query){
        $search = request('search')['value'] ?? null;

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('currentTitular', function ($qt) use ($search) {
                    $qt->where('names_lastnames', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
                })
                ->orWhereHas('directions', function ($qd) use ($search) {
                    $qd->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('directions.zone', function ($qz) use ($search) {
                    $qz->where('name', 'like', "%{$search}%");
                });
            });
        }
    }

    public function getClientData($id){
        $client = Client::with(['currentTitular', 'otherTitulars', 'occupation', 'directions.zone'])->findOrFail($id);

        return response()->json($client);
    }

    public function createClient($request, $occupation){
        $client = new Client();
        $client->phone_number = $request->phoneNumber;
        $client->datebirth = Carbon::parse($request->datebirth)->format('Y-m-d');
        $client->grade = $request->grade;
        $client->occupation_id = $occupation->id ?? null;
        $client->save();

        return $client;
    }

    public function updateClient($id, $request){
        $client = Client::findOrFail($id);

        $client->phone_number = $request->phoneNumber;
        $client->datebirth = $request->datebirth ? Carbon::parse($request->datebirth)->format('Y-m-d') : null;
        $client->grade = $request->grade;
        // $client->occupation_id = $occupation->id ?? null;

        if ($client->isDirty()) {
            $client->save();
        }
        return $client;
    }
}