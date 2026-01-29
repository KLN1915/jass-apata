<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\DetailPaymentService;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private DetailPaymentService $detailPaymentService,
    ){}

    // public function getPayments(Request $request)
    // {
    //     $data = $this->paymentService->getPayments($request);

    //     return response()->json($data);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return $this->paymentService->getPaymentsData();
        }
        return view('payments.index');
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
    public function store(StorePaymentRequest $request)
    {
        try{
            DB::beginTransaction();
            // dd($request->all());
            $payment = $this->paymentService->createPayment($request);
            $this->detailPaymentService->createDetail($request, $payment->id);

            DB::commit();

            return response()->json([
                'message' => 'Pago realizado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al registrar pago',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->paymentService->getPaymentData($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function nullPayment(string $id)
    {
        try{
            DB::beginTransaction();
            // Actualizar nulled en pago
            $payment = $this->paymentService->nullPayment($id);
            //Actualizar cambios en deudas, deudas adicionales
            $this->detailPaymentService->returnDebtsToPrevious($payment->id);

            DB::commit();

            return response()->json([
                'message' => 'Pago anulado exitosamente',
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'error' => 'Error al anular pago',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //generar pdf
    public function generatePdfReceipt($id)
    {
        $payment = $this->paymentService->getPaymentData($id);
        $paymentDetails = $this->detailPaymentService->getPaymentDetails($payment->id);

        $html = view('payments.partials.pdf-receipt', [
            'paymentData' => $payment,
            'paymentDetails' => $paymentDetails,
        ])->render();

        $mpdf = new Mpdf([
            'format' => [80, 200], // o [80, 200] para tickets
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);

        $mpdf->WriteHTML($html);

        // Retorna el PDF al navegador
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}