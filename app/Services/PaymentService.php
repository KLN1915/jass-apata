<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Debt;
use App\Models\Service;
use Yajra\DataTables\DataTables;
use App\Models\Contract;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function getPaymentData($id)
    {
        return Payment::with('contract.direction.client.currentTitular')->findOrFail($id);
    }

    //Datatable info
    public function getPaymentsData()
    {
        try {
            $payments = Payment::with(['contract.direction.client.currentTitular', 'user']);

            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('contract', function ($payment) {
                    return
                        $payment->contract->code . ' - ' .
                        $payment->contract->direction->client->currentTitular->names_lastnames . ' - ' .
                        $payment->contract->direction->name;
                })
                ->addColumn('created_at', function ($payment) {
                    return $payment->created_at->format('d-m-Y H:i');
                })
                ->addColumn('total', function ($payment) {
                    return 'S/.' . $payment->total;
                })
                ->addColumn('user', function ($payment) {
                    return $payment->user->name;
                })
                ->addColumn('status', function ($payment) {
                    if ($payment->nulled) {
                        return '<span class="badge bg-danger mb-1">ANULADO</span>';
                    } else {
                        return '<span class="badge bg-success mb-1">EFECTUADO</span>';
                    }
                })
                ->addColumn('actions', function ($payment) {
                    if ($payment->nulled) {
                        return '
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <button type="button" class="btn btn-info btnReceipt" data-id="' . $payment->id . '">
                                    <i class="fas fa-receipt"></i>
                                </button>
                            </div>
                        ';
                    } else {
                        return '
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <button type="button" class="btn btn-info btnReceipt" data-id="' . $payment->id . '">
                                    <i class="fas fa-receipt"></i>
                                </button>
                                <button type="button" class="btn btn-danger btnNull" data-type="activate" data-id="' . $payment->id . '">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </div>
                        ';
                    }
                })
                ->rawColumns(['status', 'actions'])
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

    private function applyFilters($query)
    {
        $search = request('search')['value'] ?? null;

        if ($search) {
            $query->where(function ($q) use ($search) {
                // 🔹 Contrato (código)
                $q->whereHas('contract', function ($qc) use ($search) {
                    $qc->where('code', 'like', "%{$search}%");
                });

                // 🔹 Fecha de pago (YYYY-MM-DD)
                $q->orWhereDate('created_at', $search);

                // 🔹 Monto
                $q->orWhere('total', 'like', "%{$search}%");

                // 🔹 Responsable (usuario)
                $q->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                });

                // 🔹 Titular (extra, ya lo tenías)
                $q->orWhereHas('contract.direction.client.currentTitular', function ($qt) use ($search) {
                    $qt->where('names_lastnames', 'like', "%{$search}%");
                });

                // 🔹 Dirección (extra)
                $q->orWhereHas('contract.direction', function ($qd) use ($search) {
                    $qd->where('name', 'like', "%{$search}%");
                });
            });
        }
    }

    public function createPayment($request)
    {
        $titular = Contract::with('direction.client.currentTitular')
            ->findOrFail($request->contract_id);
        $namesLastnames = $titular->direction->client->currentTitular->names_lastnames;

        $payment = Payment::create([
            'titular' => $namesLastnames,
            'total' => $request->total,
            'user_id' => Auth::id(),
            'contract_id' => $request->contract_id,
        ]);

        return $payment;
    }

    //anular pago
    public function nullPayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'nulled' => 1
        ]);

        return $payment;
    }
}