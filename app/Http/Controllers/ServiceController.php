<?php

namespace App\Http\Controllers;

use App\Http\Requests\Services\StoreServiceRequest;
use App\Http\Requests\Services\UpdateServiceRequest;
use App\Models\AdditionalService;
use App\Models\Service;
use App\Services\ServiceService;
use App\Services\LateFeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceService $serviceService,
        private LateFeeService $lateFeeService,
    ) {}

    public function getServices()
    {
        return response()->json(Service::select('id', 'name', 'charge_period', 'price')->get());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('lateFee')->get();
        $addServices = AdditionalService::get();

        return view('settings.services.index', compact('services', 'addServices'));
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
    public function store(StoreServiceRequest $request)
    {
        try{
            DB::beginTransaction();

            $service = $this->serviceService->createService($request);
            $this->lateFeeService->createLateFee($service, $request);

            DB::commit();

            return response()->json([
                'message' => 'Servicio creado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al registrar servicio',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->serviceService->getServiceData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $service = $this->serviceService->updateService($request, $id);
            $this->lateFeeService->evaluateFee($service->id, $request);

            DB::commit();

            return response()->json([
                'message' => 'Servicio actualizado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al actualizar servicio',
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
