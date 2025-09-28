<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Área de Paciente')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    @vite('resources/css/dashboards/paciente.css')
    @stack('head')
</head>
<body class="paciente-body @yield('body-class')">
<div class="container paciente-container">
    @include('paciente.partials.sidebar')
    <main>
        @yield('main')
    </main>
    @hasSection('right')
        <div class="right">
            @yield('right')
        </div>
    @endif
</div>
@stack('scripts')
</body>
</html>