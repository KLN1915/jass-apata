<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 96px;
            font-weight: bold;
            color: #dc3545;
        }
        .message {
            font-size: 24px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="message">Lo sentimos, la página que buscas no existe.</div>
        <a href="{{ route('dashboard') }}" class="btn btn-danger">← Volver al inicio</a>
    </div>
</body>
</html>