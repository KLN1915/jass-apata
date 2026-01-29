<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Ticket de Pago - Agua</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            max-width: 350px;
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .logo {
            width: 60px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border-bottom: 1px solid #ccc;
            padding: 4px;
            text-align: center;
        }

        .total {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }

        .datos {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="center">
        {{-- <img src="logo.png" alt="Logo" class="logo" /> --}}
        <h3>INSTITUCIÓN COMUNAL<br>JASS APATA<br>2024-2026</h3>
        <!--<p>RUC Nº 20173231987<br>AV. CANADA 218 - TELF. 252188</p>-->
    </div>

    <div class="datos">
        {{-- <p><strong>NRO. COMPROBANTE:</strong> F-00{{$datos_factura->id}}</p> --}}
        <p><strong>NOMBRES:</strong> {{$paymentData->titular}}</p>
        <p><strong>DIRECCIÓN:</strong> {{$paymentData->contract->direction->name}}</p>
        <p><strong>N° CONTRATO:</strong> {{$paymentData->contract->code}}</p>
        <p><strong>FECHA DE PAGO:</strong> {{$paymentData->created_at->format('d-m-Y H:i')}}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>ITEM</th>
            <th>Concepto</th>
            <th>Periodo</th>
            <th>Monto</th>
        </tr>
        </thead>
        <tbody>
        @php $contador = 1; @endphp

        {{-- Servicios --}}
        @foreach ($paymentDetails['debts'] as $debtDetail)
        <tr>
            <td>{{ $contador++ }}</td>
            <td>{{ $debtDetail['concept']}}</td>
            <td>{{ $debtDetail['period'] }}</td>
            <td>{{ $debtDetail['amount'] }}</td>
        </tr>
        @endforeach

        {{-- Deudas adicionales --}}
        @foreach ($paymentDetails['additional_debts'] as $addDebtDetail)
        <tr>
            <td>{{ $contador++ }}</td>
            <td>{{ $addDebtDetail['concept']}}</td>
            <td></td>
            <td>{{ $addDebtDetail['amount'] }}</td>
        </tr>
        @endforeach

        {{-- Servicios adicionales --}}
        @foreach ($paymentDetails['additional_services'] as $addServiceDetail)
        <tr>
            <td>{{ $contador++ }}</td>
            <td>{{ $addServiceDetail['concept']}}</td>
            <td></td>
            <td>{{ $addServiceDetail['amount'] }}</td>
        </tr>
        @endforeach

        </tbody>
    </table>

    <p class="total">TOTAL: {{$paymentData->total}}</p>
</body>
</html>