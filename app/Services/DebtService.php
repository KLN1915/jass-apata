<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Debt;
use App\Models\Service;
use App\Models\Contract;
use Carbon\Carbon;

class DebtService
{
    //Crear deudas
    public function evaluateDebts($contract, $request)
    {
        $contractId = $contract->id;
        $contractType = $request->contractType;

        if ($contractType == 'existing') {
            $this->createDebts($request, $contractId);
        }

        if ($contractType == 'new') {
            $this->createDebt($request, $contractId);
        }
    }

    private function createDebts($request, $contractId)
    {
        $serviceId = $request->service_id;
        if ($request->debt_since) {
            $startDebtYear = $request->debt_since;
        } else {
            $startDebtYear = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $startDebtYear = $startDebtYear->year;            
        }
        $currentYear = now()->year;

        $debts = [];

        for ($year = $startDebtYear; $year <= $currentYear; $year++) {
            if ($year < 2025) {
                $debts[] = [
                    'period' => $year,
                    'type' => 'NORMAL',
                    'amount' => 30.00,
                    'contract_id' => $contractId,
                ];
            } else if ($year >= 2025) {
                $debts[] = [
                    'period' => $year,
                    'type' => 'NORMAL',
                    'amount' => Service::find($serviceId)->price,
                    'contract_id' => $contractId,
                ];
            }
        }
        DB::table('debts')->insert($debts);
    }

    private function createDebt($request, $contractId)
    {
        $serviceId = $request->service_id;
        $currentYear = now()->year;

        Debt::create([
            'period' => $currentYear,
            'type' => 'NORMAL',
            'amount' => Service::find($serviceId)->price,
            'contract_id' => $contractId
        ]);
    }

    //Actualizar deudas
    // public function updateDebts($contract, $request){
    //     $contractType = $contract->type;

    //     //si el tipo de contrato ha cambiado
    //     if($contractType != $request->contractType){
    //         //eliminar anteriores registros
    //         $this->deletePreviousData($contract->id);
    //         //crear nuevas deudas
    //         $this->evaluateDebts($contract, $request);
    //     }else{
    //         //si el tipo de contrato es el mismo            
    //         if($request->contractType == 'existing'){
    //             $startDate = $contract->start_date;
    //             $debtSince = Debt::where('contract_id', $contract->id)->first();

    //             if($startDate != $request->start_date && $debtSince->period != $request->debt_since){
    //                 $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
    //                 if($contract->isDirty()){
    //                     $contract->save();
    //                 }
    //                 // Contract::findOrFail($contract->id)->update(['start_date', $contract->start_date]);
    //                 $this->deletePreviousData($contract->id);
    //                 $this->createDebts($request, $contract->id);
    //             }
    //             else if($startDate != $request->start_date || $debtSince->period != $request->debt_since){
    //                 $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
    //                 if($contract->isDirty()){
    //                     $contract->save();
    //                 }
    //                 $this->deletePreviousData($contract->id);
    //                 $this->createDebts($request, $contract->id);
    //             }
    //         }
    //     }
    // }
    // public function updateDebts($contract, $request)
    // {
    //     // Si el tipo de contrato ha cambiado → reemplazar todo.
    //     if ($contract->type !== $request->contractType) {
    //         $this->deletePreviousData($contract->id);
    //         $this->evaluateDebts($contract, $request);
    //         return;
    //     }

    //     // Si el tipo de contrato es "existing"
    //     if ($request->contractType === 'existing') {

    //         $startDateChanged = $contract->start_date != $request->start_date;

    //         $currentDebt = Debt::where('contract_id', $contract->id)->first();

    //         // Cuando no existe debt_since en request
    //         $debtSinceMissing = !isset($request->debt_since);

    //         // Cuando existe y cambió
    //         $debtSinceChanged = $currentDebt && $request->has('debt_since') &&
    //             $currentDebt->period != $request->debt_since;

    //         // Debemos regenerar deudas si:
    //         // - cambió el start_date
    //         // - o cambió debt_since
    //         // - o debt_since no vino en el request
    //         $needsRecreate = $startDateChanged || $debtSinceChanged || $debtSinceMissing;

    //         if ($needsRecreate) {

    //             // Actualizar fecha de inicio si cambió
    //             // if ($startDateChanged) {
    //             //     $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');

    //             //     if ($contract->isDirty()) {
    //             //         $contract->save();
    //             //     }
    //             // }

    //             // recrear deudas
    //             $this->deletePreviousData($contract->id);
    //             $this->createDebts($request, $contract->id);
    //         }
    //     }
    // }
    public function updateDebts($contract, $request)
{
    // Si el tipo de contrato cambió → recrear todo
    if ($contract->type !== $request->contractType) {
        $this->deletePreviousData($contract->id);
        $this->evaluateDebts($contract, $request);
        return;
    }

    // Solo para contracts existing
    if ($request->contractType === 'existing') {

        // Detectar cambios reales
        $startDateChanged = $contract->start_date != $request->start_date;

        $currentDebt = Debt::where('contract_id', $contract->id)->first();

        // Definir año origen usado realmente
        $originRequest = $request->debt_since ?? $request->start_date;
        $originCurrent = $currentDebt?->period;

        $originChanged = $originCurrent != $originRequest;

        // Si no cambió NADA → no hacer nada
        if (!$startDateChanged && !$originChanged) {
            return;
        }

        // Actualizar start_date si cambió
        if ($startDateChanged) {
            $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            if ($contract->isDirty()) {
                $contract->save();
            }
        }

        // Re-crear deudas
        $this->deletePreviousData($contract->id);
        $this->createDebts($request, $contract->id);
    }
}


    private function deletePreviousData($contractId)
    {
        Debt::where('contract_id', $contractId)->delete();
    }
}
