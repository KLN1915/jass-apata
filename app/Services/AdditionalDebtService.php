<?php

namespace App\Services;

use App\Models\AdditionalDebt;
use App\Models\Contract;

class AdditionalDebtService
{
    // Crear deuda adicional
    public function evaluateInstallation($contract, $request)
    {
        $contractType = $request->contractType;
        $contractId = $contract->id;

        if ($contractType == 'existing') {
            $this->createForExisting($request, $contractId);
        }

        if ($contractType == 'new') {
            $this->createForNew($request, $contractId);
        }
    }

    private function createForNew($request, $contractId)
    {
        AdditionalDebt::create([
            'concept' => 'INSTALACION',
            'amount_payed' => 0,
            'original_amount' => $request->new_installation_cost,
            'contract_id' => $contractId,
        ]);
    }

    private function createForExisting($request, $contractId)
    {
        $originalAmount = $request->installation_cost;
        $amountPayed = $request->amount_payed;

        if ($originalAmount == $amountPayed) {
            AdditionalDebt::create([
                'concept' => 'INSTALACION',
                'amount_payed' => $amountPayed,
                'original_amount' => $originalAmount,
                'payed' => 1,
                'contract_id' => $contractId,
            ]);
        } else {
            AdditionalDebt::create([
                'concept' => 'INSTALACION',
                'amount_payed' => $amountPayed,
                'original_amount' => $originalAmount,
                'contract_id' => $contractId,
            ]);
        }
    }

    // Actualizar deuda adicional
    public function updateAdditionalDebt($contract, $request)
    {
        $contractType = $contract->type;

        //si el tipo de contrato ha cambiado
        if ($contractType != $request->contractType) {
            //cambiar tipo de contrato
            // Contract::findOrFail($contract->id)->update(['type' => $request->contractType]);
            //eliminar anteriores registros
            $this->deletePreviousData($contract->id);
            //crear nueva deuda
            $this->evaluateInstallation($contract, $request);
        } else {
            //si el tipo de contrato es el mismo            
            if ($request->contractType == 'existing') {
                $originalAmount = AdditionalDebt::where('contract_id', $contract->id)->where('concept', 'INSTALACION')->value('original_amount');
                $amountPayed = AdditionalDebt::where('contract_id', $contract->id)->where('concept', 'INSTALACION')->value('amount_payed');

                if ($originalAmount != $request->installation_cost && $amountPayed != $request->amount_payed) {
                    $this->deletePreviousData($contract->id);
                    $this->evaluateInstallation($contract, $request);
                } else if ($originalAmount != $request->installation_cost || $amountPayed != $request->amount_payed) {
                    $this->deletePreviousData($contract->id);
                    $this->evaluateInstallation($contract, $request);
                }
            }

            if ($request->contractType == 'new') {
                $installationDebt = AdditionalDebt::where('contract_id', $contract->id)->where('concept', 'INSTALACION')->first();

                $installationDebt->original_amount = $request->new_installation_cost;
                $installationDebt->amount_payed = 0;

                if ($installationDebt->isDirty()) {
                    $installationDebt->save();
                }
            }
        }
    }

    private function deletePreviousData($contractId)
    {
        AdditionalDebt::where('contract_id', $contractId)->where('concept', 'INSTALACION')->delete();
    }

    // Crear deuda por reconexion
    public function createReconexionDebt($contractId, $request)
    {
        if (!$request) {
            return;
        }

        $reconexionAmount = $request->reconexion_amount;

        if ($reconexionAmount > 0) {
            AdditionalDebt::create([
                'concept' => 'RECONEXION',
                'amount_payed' => 0,
                'original_amount' => $reconexionAmount,
                'payed' => 0,
                'contract_id' => $contractId
            ]);
        }
    }
}
