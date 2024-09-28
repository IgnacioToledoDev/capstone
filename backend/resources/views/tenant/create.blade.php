<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Autominder - nuevo taller</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
</head>
<body>
<nav>
    <div>Logo</div>
</nav>
<h1>Agrega tu taller</h1>
<div class="container">
    <h2>Agregar Nuevo Taller Mecánico</h2>

    <!-- Muestra mensajes de error o éxito -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenants.store') }}" method="POST">
        @csrf  <!-- Token de seguridad para prevenir CSRF -->

        <div class="form-group">
            <label for="name">Nombre del Taller</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="administrator">Administrador</label>
            <select class="form-control" id="administrator" name="administrator" required>
                <option value="">Seleccione un administrador</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="size">Tamaño (m²)</label>
            <input type="number" class="form-control" id="size" name="size" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Número de Teléfono</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Taller</button>
    </form>
</div>
</body>
</html>
