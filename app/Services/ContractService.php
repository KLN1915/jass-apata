<?php

namespace App\Services;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\Contract;
use App\Models\Direction;
use App\Models\Debt;
use App\Models\AdditionalDebt;
use Symfony\Component\HttpFoundation\Request;

class ContractService
{
    public function getContracts($request)
    {
        $search = $request->get('search');

        $contracts = Contract::query()
            ->where('code', 'LIKE', "%{$search}%")
            ->orWhereHas('direction', function($q) use ($search){
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('client.currentTitular', function ($q) use ($search) {
                    $q->where('names_lastnames', 'LIKE', "%{$search}%");
                    // ->orWhere('dni', 'LIKE', "%{$search}%");
                });
            })
            // ->where('status', 'ACTIVO')
            ->limit(5)
            ->get();

        $data = $contracts->map(function($contract){
            return [
                'id' => $contract->id,
                'text' => 
                    $contract->code . ' - ' .
                    $contract->direction->client->currentTitular->names_lastnames . ' - ' .
                    $contract->direction->name
            ];
        });

        return $data;
    }

    //Datatable info
    public function getContractsData(Request $request)
    {
        try {
            $contracts = Contract::with(['direction.client.currentTitular']);

            return DataTables::of($contracts)
                ->addIndexColumn()
                ->addColumn('code', function ($contract) {
                    return $contract->code;
                })
                ->addColumn('contract', function ($contract) {
                    return $contract->direction->client->currentTitular->names_lastnames . ' - ' . $contract->direction->name;
                })
                ->addColumn('debt', function ($contract) {
                    $totals = $contract->totalDebts();
                    $statusForCut = $contract->statusForCut();

                    if ($totals['total'] != 0) {
                        $html = 'S/. ' . $totals['total'];
                        
                        if ($statusForCut) {
                            $html .= '<br/><span class="badge bg-warning mb-1">' . $statusForCut . '</span>';
                        }
                        
                        $html .= '<br/><button type="button" class="btn btn-link btnDebts" data-id="' . $contract->id . '">Ver deudas</button>';
                        
                        return $html;
                    } else {
                        return 'SIN DEUDA';
                    }
                })
                ->addColumn('status', function ($contract) {
                    if ($contract->status == 'ACTIVO') {
                        return '<span class="badge bg-success mb-1">' . e($contract->status) . '</span>';
                    } else {
                        return '<span class="badge bg-danger mb-1">' . e($contract->status) . '</span>';
                    }
                })
                ->addColumn('actions', function ($contract) {
                    if ($contract->status == 'ACTIVO') {
                        return '
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <button type="button" class="btn btn-success btnEdit" data-id="' . $contract->id . '">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-danger btnChangeState" data-type="deactivate" data-id="' . $contract->id . '">
                                    <i class="fas fa-tint-slash"></i>
                                </button>
                            </div>
                        ';
                    } else {
                        return '
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <button type="button" class="btn btn-success btnEdit" data-id="' . $contract->id . '">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-info btnChangeState" data-type="activate" data-id="' . $contract->id . '">
                                    <i class="fas fa-hand-holding-water"></i>
                                </button>
                            </div>
                        ';
                    }
                })
                ->rawColumns(['debt', 'status', 'actions'])
                ->filter(function ($query) use ($request){
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

    private function applyFilters($query)
    {
        $search = request('search')['value'] ?? null;
        $zone = request('zone');
        $debts = request('debts');
        $status = request('status');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('direction.client.currentTitular', function ($qd) use ($search) {
                        $qd->where('names_lastnames', 'like', "%{$search}%");
                    })
                    ->orWhereHas('direction', function ($qd) use ($search) {
                        $qd->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($zone && $zone != 'Seleccionar') {
            $query->whereHas('direction', function ($q) use ($zone) {
                $q->where('zone_id', $zone);
            });
        }

        if ($status && $status != 'Seleccionar') {
            $query->where('status', $status);
        }

        if ($debts === '+3 AÑOS') {
            $query->forCut();
        }
    }

    //Crear contrato
    public function evaluateContract($request)
    {
        $contractType = $request->contractType;
        if ($contractType == 'existing') {
            return $this->createExistingContract($request);
        }

        if ($contractType == 'new') {
            return $this->createNewContract($request);
        }
    }

    private function createNewContract($request)
    {
        $contract = Contract::create([
            'type' => $request->contractType,
            'direction_id' => $request->direction_id,
            'service_id' => $request->service_id,
            'start_date' => now(),
            'status' => 'ACTIVO',
        ]);

        $this->changeDirectionContracted($request->direction_id);

        return $contract;
    }

    private function createExistingContract($request)
    {
        $contract = Contract::create([
            'type' => $request->contractType,
            'direction_id' => $request->direction_id,
            'service_id' => $request->service_id,
            'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
            'status' => 'ACTIVO',
        ]);

        $this->changeDirectionContracted($request->direction_id);

        return $contract;
    }

    private function changeDirectionContracted($newId, $oldId = null)
    {
        if ($oldId) {
            Direction::findOrFail($oldId)->update(['contracted' => 0]);
        }
        Direction::findOrFail($newId)->update(['contracted' => 1]);
    }

    //Detalles de contrato
    public function getContractData($id)
    {
        $contract = Contract::with(['direction.client.currentTitular', 'service'])->findOrFail($id);
        $debt = null;
        if ($contract->type == 'existing') {
            $debt = Debt::where('contract_id', $id)->first();
        }
        $installation = AdditionalDebt::where('contract_id', $id)->first();

        return [
            'contract' => $contract,
            'debt' => $debt,
            'installation' => $installation
        ];
    }

    //Editar contrato
    public function updateContract($id, $request)
    {
        $contract = Contract::findOrFail($id);
        $currentDirectionId = $contract->direction_id;

        $contract->direction_id = $request->direction_id;
        $contract->service_id = $request->service_id;
        // $contract->type = $request->contractType;

        if ($contract->isDirty()) {
            $contract->save();
        }

        if ($currentDirectionId != $contract->direction_id) {
            $this->changeDirectionContracted($request->direction_id, $currentDirectionId);
        }

        return $contract;
    }

    public function changeAdditionalContractData($contract, $request)
    {
        $newType = $request->contractType;

        //actualizar tipo de contrato
        $contract->type = $newType;
        if ($contract->isDirty()) {
            $contract->save();
        }

        //actualizar fecha de inicio de contrato
        if ($newType == 'new') {
            $contract->update([
                'start_date' => now(),
            ]);
        } else if ($newType == 'existing') {
            $contract->update([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
            ]);
        }
    }

    public function changeContractState($id){
        $contract = Contract::findOrFail($id);

        if($contract->status == 'ACTIVO'){
            $contract->update([
                'status' => 'SUSPENDIDO'
            ]);

            return $contract;
        }else if($contract->status == 'SUSPENDIDO'){
            $contract->update([
                'status' => 'ACTIVO'
            ]);

            return $contract;
        }
    }
}
