<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use App\Services\ContractService;
use App\Services\DebtService;
use App\Services\AdditionalDebtService;
use Mpdf\Mpdf;

class DebtController extends Controller
{
    public function __construct(
        private ContractService $contractService,
        private DebtService $debtService,
        private AdditionalDebtService $additionalDebtService,
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Debt $debt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt)
    {
        //
    }

    //obtener deudas
    public function getAllDebts($id)
    {
        return [
            'debts' => $this->debtService->getAllDebts($id),
            'additional_debts' => $this->additionalDebtService->getAllAdditionalDebts($id),
        ];
    }

    //generar pdf
    public function generatePdfBill($id)
    {
        $contract = $this->contractService->getContractData($id);
        $debts = $this->debtService->getAllDebts($id);
        $additionalDebts = $this->additionalDebtService->getAllAdditionalDebts($id);

        $html = view('debts.partials.pdf-bill', [
            'contractData' => $contract,
            'debts' => $debts,
            'additionalDebts' => $additionalDebts,
        ])->render();

        $mpdf = new Mpdf([
            'format' => 'A4', // o [80, 200] para tickets
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        // Retorna el PDF al navegador
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
