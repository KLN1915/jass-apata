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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #00aaff;
      padding-bottom: 10px;
    }

    .header img {
      height: 60px;
    }

    .header-info {
      flex-grow: 1;
      text-align: center;
    }

    .header-info h2 {
      margin: 0;
      color: #0078c2;
    }

    .qr-box {
      text-align: right;
    }

    .qr-box small {
      display: block;
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

  <div class="header">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Drop_of_water.svg/1024px-Drop_of_water.svg.png"/>
    <div class="header-info">
      <h2>Institución Comunal JASS-Apata</h2>
      <p>Recibo de Agua - Desagüe
      </p>
    </div>
    <!--<div class="qr-box">
      <small>RUC: 1076777777</small>
      <small>Rec. Nº 1</small>
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=1076777777" alt="QR">
    </div>-->
  </div>

  <div class="client-info">
    {{-- {{$contract_debts['contract_info']}} --}}
    <strong>Nombres:</strong> {{ $contractData['contract']->direction->client->currentTitular->names_lastnames }}<br>
    <strong>Dirección:</strong> {{ $contractData['contract']->direction->name }}<br>
    <strong>Servicio:</strong> {{ $contractData['contract']->service->name }}<br>
    <strong>N° Contrato:</strong> {{ $contractData['contract']->code }}<br>
    <strong>Fecha:</strong> {{ now()->format('d-m-Y') }}
  </div>

  @if(!empty($debts))
  <table class="table">
    <strong>Deudas</strong>
    <thead>
      <tr>
        <th>ITEM</th>
        {{-- <th>CONCEPTO</th> --}}
        <th>PERIODO</th>
        {{-- <th>FECHA DE EMISIÓN</th> --}}
        {{-- <th>FECHA DE VENCIMIENTO</th> --}}
        <th>MONTO</th>
        {{-- <th>INTERÉS</th> --}}
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
        {{-- <td>{{ $debt['interest_percentage'] }}</td> --}}
        <td>{{ $debt->subTotal }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="sub-total">
    SUB-TOTAL: S/ {{$debts['totalDebts']}}
  </div>
  @endif

  @if(!empty($additionalDebts))
  <table class="table">
    <strong>Deudas adicionales</strong>
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
