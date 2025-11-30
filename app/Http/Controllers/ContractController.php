<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contracts\StoreContractRequest;
use App\Http\Requests\Contracts\UpdateContractRequest;
use App\Models\Contract;
use App\Services\ContractService;
use App\Services\DebtService;
use App\Services\AdditionalDebtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function __construct(
        private ContractService $contractService,
        private DebtService $debtService,
        private AdditionalDebtService $additionalDebtService,
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return $this->contractService->getContractsData();
        }
        return view('contracts.index');
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
    public function store(StoreContractRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();

            $contract = $this->contractService->evaluateContract($request);
            $this->debtService->evaluateDebts($contract, $request);
            $this->additionalDebtService->evaluateInstallation($contract, $request);

            DB::commit();

            return response()->json([
                'message' => 'Contrato creado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al registrar contrato',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->contractService->getContractData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, string $id)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            // Actualizar cambios en contrato
            $contract = $this->contractService->updateContract($id, $request);
            // Actualizar cambios en deudas
            $this->debtService->updateDebts($contract, $request);
            // Actualizar deuda adicional
            $this->additionalDebtService->updateAdditionalDebt($contract, $request);
            // Actualizar tipo de contrato
            $this->contractService->changeAdditionalContractData($contract, $request);

            DB::commit();

            return response()->json([
                'message' => 'Contrato actualizado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al actualizar contrato',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function changeContractState($id, Request $request){
        try{
            DB::beginTransaction();
            // Actualizar cambios en contrato
            $contract = $this->contractService->changeContractState($id);
            //Crear deuda por reconexion
            $this->additionalDebtService->createReconexionDebt($contract->id, $request);

            DB::commit();

            return response()->json([
                'message' => 'Estado de contrato cambiado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al actualizar estado de contrato',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}