<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recibo de Agua - Desagüe</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      width: 700px;
      margin: auto;
      border: 1px solid #ccc;
      padding: 15px;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #00aaff;
      padding-bottom: 10px;
    }

    .header-info {
      flex-grow: 1;
      text-align: center;
    }

    .header-info h2 {
      margin: 0;
      color: #0078c2;
    }

    .client-info {
      margin-top: 15px;
      line-height: 1.5;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .table th, .table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
    }

    .sub-total {
      text-align: right;
      margin-top: 10px;
      font-weight: bold;
      font-size: 18px;
    }

    .total {
      text-align: right;
      margin-top: 30px;
      font-weight: bold;
      font-size: 18px;
    }

    .message {
      background: #e8faff;
      padding: 10px;
      margin-top: 15px;
      text-align: center;
      border-radius: 10px;
      color: #0078c2;
      font-weight: bold;
    }

    .cut-line {
      border-top: 2px dashed #000;
      margin-top: 20px;
      text-align: center;
      position: relative;
    }

    .cut-line::before {
      position: absolute;
      top: -12px;
      left: 10px;
      background: #fff;
      padding: 0 5px;
    }
  </style>
</head>
<body>
  <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
    <tr>
      <!-- Logo -->
      <td width="20%" align="left" style="vertical-align: middle;">
        <img src="{{ asset('dist/img/jass-logo.png') }}" style="width: 100px;">
      </td>
      <!-- Info -->
      <td width="80%" align="left" style="vertical-align: middle;">
        <h2 style="margin: 0;">Institución Comunal JASS-Apata</h2>
        <p style="margin: 0;">Recibo de Agua - Desagüe</p>
      </td>
    </tr>
</table>

  <div class="client-info">
    {{-- {{$contract_debts['contract_info']}} --}}
    <strong>Nombres:</strong> {{ $contractData['contract']->direction->client->currentTitular->names_lastnames }}<br>
    <strong>Dirección:</strong> {{ $contractData['contract']->direction->name }}<br>
    <strong>Servicio:</strong> {{ $contractData['contract']->service->name }}<br>
    <strong>N° Contrato:</strong> {{ $contractData['contract']->code }}<br>
    <strong>Fecha:</strong> {{ now()->format('d-m-Y') }}
  </div>
  <br>

  @if(isset($debts['debtsData']) && count($debts['debtsData']) > 0)
  <strong>DEUDAS DE SERVICIO</strong>
  <table class="table">
    <thead>
      <tr>
        <th>ITEM</th>
        {{-- <th>CONCEPTO</th> --}}
        <th>PERIODO</th>
        {{-- <th>FECHA DE EMISIÓN</th> --}}
        {{-- <th>FECHA DE VENCIMIENTO</th> --}}
        <th>MONTO</th>
        <th>INTERÉS</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach ( $debts['debtsData'] as $debt )
      <tr>
        <td>{{ $loop->iteration }}</td>
        {{-- <td>{{ $debt['service'] }}</td> --}}
        <td>{{ $debt->period }}</td>
        {{-- <td>{{ $debt['issue_date'] }}</td> --}}
        {{-- <td>{{ $debt['due_date'] }}</td> --}}
        <td>{{ $debt->amount }}</td>
        <td>{{ $debt->interest_amount ?? '--' }}</td>
        <td>{{ $debt->subTotal }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="sub-total">
    SUB-TOTAL: S/ {{$debts['totalDebts']}}
  </div>
  @endif

  @if(isset($additionalDebts['addDebtsData']) && count($additionalDebts['addDebtsData']) > 0)
  <strong>DEUDAS ADICIONALES</strong>
  <table class="table">
    <thead>
      <tr>
        <th>ITEM</th>
        <th>CONCEPTO</th>
        <th>MONTO</th>
        <th>A CUENTA</th>
        <th>RESTA</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($additionalDebts['addDebtsData'] as $addDebt)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $addDebt->concept }}</td>
        <td>{{ $addDebt->original_amount }}</td>
        <td>{{ $addDebt->amount_payed }}</td>
        <td>{{ $addDebt->subTotal }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="sub-total">
    SUB-TOTAL: S/ {{$additionalDebts['totalAddDebts']}}
  </div>
  @endif

  <div class="total">
    TOTAL A PAGAR: S/ {{$debts['totalDebts'] + $additionalDebts['totalAddDebts']}}
  </div>

  <div class="message">
    ¡Ahorremos cada gota de agua!<br>Está en nuestras manos cuidarla...
  </div>

  <div class="cut-line"></div>

</body>
</html>
