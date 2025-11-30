<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clients\StoreClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Services\ClientService;
use App\Services\HistoryTitularService;
use App\Services\OccupationService;
use App\Services\DirectionService;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $clientService,
        private OccupationService $occupationService,
        private HistoryTitularService $titularService,
        private DirectionService $directionService,
    ) {}

    public function getAssociateds(Request $request)
    {
        $data = $this->clientService->getAssociateds($request);

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->clientService->getClientsData();
        }

        return view('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try{
            DB::beginTransaction();

            // Evaluar ocupacion
            $occupation = $this->occupationService->resolveOccupation($request->occupation);
            // Crear cliente
            $client = $this->clientService->createClient($request, $occupation);
            // Crear titular
            $this->titularService->createNewTitular($request, $client);
            // Crear direcciones
            $this->directionService->createDirections($request, $client);

            DB::commit();

            return response()->json([
                'message' => 'Cliente creado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al registrar cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->clientService->getClientData($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            // Evaluar o actualizar ocupacion
            $this->occupationService->updateOccupation($id, $request->occupation);
            // Actualizar cliente
            $client = $this->clientService->updateClient($id, $request);
            // Actualizar titular
            $this->titularService->updateTitular($id, $request, $client);
            // Actualizar o crear direcciones
            $this->directionService->updateDirections($request, $client);

            DB::commit();

            return response()->json([
                'message' => 'Cliente actualizado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al actualizar cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
