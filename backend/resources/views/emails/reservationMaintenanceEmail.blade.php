{{-- resources/views/emails/reservationMaintenanceEmail.blade.php --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Nueva Reservación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="color: #333;">¡Felicidades, tienes una nueva reservación de mantenimiento!</h2>
    <p style="color: #555;">Hola, {{ $mechanicName }}. Has recibido una nueva reservación para un servicio de mantenimiento.</p>

    <div style="margin: 20px 0;">
        <p><strong>Fecha:</strong> {{ $reservationDate }}</p>
        <p><strong>Hora:</strong> {{ $reservationTime }}</p>
    </div>

    <h3>Detalles del Cliente</h3>
    <p><strong>Nombre:</strong> {{ $clientName }}</p>
    <p><strong>Teléfono:</strong> {{ $clientPhone }}</p>
    <p><strong>Correo:</strong> {{ $clientEmail }}</p>

    <a href="https://tu-app.com/reservations/{{ $reservation->id }}" class="button">Ver Reservación</a>

    <div class="footer">
        <p>Gracias por usar nuestro sistema de gestión de reservas.</p>
        <p>&copy; {{ date('Y') }} Tu Taller Mecánico. Todos los derechos reservados.</p>
    </div>
</div>
</body>
</html>
