<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Debt;
use App\Models\Service;
use App\Models\Contract;
use App\Models\DetailPayments;
use App\Services\DebtService;
use App\Services\AdditionalDebtService;
use Carbon\Carbon;

class DetailPaymentService
{
    public function __construct(
        private DebtService $debtService,
        private AdditionalDebtService $additionalDebtService,
    ) {}

    // public function getPaymentDetails($paymentId)
    // {
    //     $paymentDetails = DetailPayments::with('debt.contract.service', 'additionalDebt', 'additionalService')->where('payment_id', $paymentId)->get();

    //     $debtsDetails = $paymentDetails
    //         ->where('type', 'debt')
    //         ->map(function($detail){
    //             return [
    //                 'concept' => $detail->debt->contract->service->name,
    //                 'period' => $detail->debt->period,
    //                 'amount' => $detail->debt->amount,
    //             ];
    //         });

    //     $additionalDebtsDetails = $paymentDetails
    //         ->where('type', 'additional_debt')
    //         ->map(function($detail){
    //             return [
    //                 'concept' => $detail->additionalDebt->concept,
    //                 'amount' => $detail->amount_payed,
    //             ];
    //         });

    //     $additionalServicesDetails = $paymentDetails
    //         ->where('type', 'additional_service')
    //         ->map(function($detail){
    //             return [
    //                 'concept' => $detail->additionalService->name,
    //                 'amount' => $detail->amount_payed,
    //             ];
    //         });

    //     return [
    //         $debtsDetails,
    //         $additionalDebtsDetails,
    //         $additionalServicesDetails
    //     ];
    // }
public function getPaymentDetails($paymentId)
{
    $paymentDetails = DetailPayments::with(
        'debt.contract.service',
        'additionalDebt',
        'additionalService'
    )->where('payment_id', $paymentId)->get();

    return [
        'debts' => $paymentDetails
            ->where('type', 'debt')
            ->map(fn ($detail) => [
                'concept' => $detail->debt?->contract?->service?->name,
                'period'  => $detail->debt?->period,
                'amount'  => $detail->debt?->amount,
            ]),

        'additional_debts' => $paymentDetails
            ->where('type', 'additional_debt')
            ->map(fn ($detail) => [
                'concept' => $detail->additionalDebt?->concept,
                'amount'  => $detail->amount_payed,
            ]),

        'additional_services' => $paymentDetails
            ->where('type', 'additional_service')
            ->map(fn ($detail) => [
                'concept' => $detail->additionalService?->name,
                'amount'  => $detail->amount_payed,
            ]),
    ];
}

    public function createDetail($request, $paymentId)
    {
        if($request->debtIds){
            $this->registerDebts($request->debtIds, $paymentId);
            $this->debtService->payDebts($request->debtIds);
        }
        if($request->additionalDebt){
            $this->registerAdditionalDebts($request->additionalDebt, $paymentId);
            $this->additionalDebtService->payAdditionalDebts($request->additionalDebt);
        }
        if(!empty(array_filter($request->additionalService))){
            $this->registerAdditionalServices($request->additionalService, $paymentId);
        }
    }

    private function registerDebts($debtIds, $paymentId)
    {
        sort($debtIds);
        $data = [];

        foreach($debtIds as $id){
            $data[] = [
                'type' => 'debt',
                'amount_payed' => null,
                'payment_id' => $paymentId,
                'debt_id' => $id,
                'additional_debt_id' => null,
                'additional_service_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('detail_payments')->insert($data);
    }

    private function registerAdditionalDebts($additionalDebts, $paymentId)
    {
        $data = [];

        foreach($additionalDebts as $addDebtId => $amountPayed){
            $data[] = [
                'type' => 'additional_debt',
                'amount_payed' => $amountPayed,
                'payment_id' => $paymentId,
                'debt_id' => null,
                'additional_debt_id' => $addDebtId,
                'additional_service_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('detail_payments')->insert($data);
    }

    private function registerAdditionalServices($additionalServices, $paymentId)
    {
        // Filtrar valores nulos o vacíos
        $additionalServices = array_filter(
            $additionalServices,
            fn ($value) => $value !== null && $value !== ''
        );

        if (empty($additionalServices)) {
            return;
        }

        $data = [];

        foreach($additionalServices as $addServiceId => $amountPayed){
            $data[] = [
                'type' => 'additional_service',
                'amount_payed' => $amountPayed,
                'payment_id' => $paymentId,
                'debt_id' => null,
                'additional_debt_id' => null,
                'additional_service_id' => $addServiceId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('detail_payments')->insert($data);
    }

    //regresar deudas y deudas adicionales a estado anterior
    public function returnDebtsToPrevious($paymentId)
    {
        $details = DetailPayments::where('payment_id', $paymentId)->get();

        $debtsIds = $details
            ->where('type', 'debt')
            ->pluck('debt_id');

        if($debtsIds){
            $this->debtService->changePayedToFalse($debtsIds);
        }

        $addDebts = $details
            ->where('type', 'additional_debt')
            ->pluck('amount_payed', 'additional_debt_id');

        if($addDebts){
            $this->additionalDebtService->changeAmountPayed($addDebts);
        }
    }
}