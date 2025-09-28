<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel del Doctor')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
    @stack('head')
    @vite(['resources/css/dashboards/doctor.css'])
    @stack('styles')
</head>
<body class="@yield('body-class')">
    @php($hasRight = $__env->hasSection('right'))
    <div class="container @if($hasRight) has-right @else has-no-right @endif">
        @php($activeSidebar = trim($__env->yieldContent('activeSidebar')))
        @include('doctor.partials.sidebar', ['active' => $activeSidebar])
        <main>
            @yield('content')
        </main>
        @if($hasRight)
            <div class="right">
                @yield('right')
            </div>
        @endif
    </div>
    @stack('scripts')
</body>
</html>