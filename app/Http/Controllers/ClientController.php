<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clients\StoreClientRequest;
use Illuminate\Http\Request;
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
        private DirectionService $directionservice,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $zones = Zone::all();

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
            $occupation = $this->occupationService->existsOrCreate($request);
            // Crear cliente
            $client = $this->clientService->createClient($request, $occupation);
            // Crear titular
            $this->titularService->createNewTitular($request, $client);
            // Crear direcciones
            $this->directionservice->createDirections($request, $client);

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
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
