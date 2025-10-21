{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Clínica')</title>
  <link rel="icon" type="image/jpg" href="{{ asset('img/LogoClinica.jpg') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

  {{-- si usas Vite/Bootstrap, déjalo aquí --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- NECESARIO para que entren los estilos de las vistas --}}
  @stack('styles')
</head>
<body class="bg-slate-50">
  <div id="app">
    <nav class="bg-white border-b border-slate-200 shadow-sm">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-semibold text-slate-800">
              <img src="{{ asset('img/LogoClinica.jpg') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover">
              <span>Clínica Los Ángeles</span>
            </a>
            @auth
              <div class="hidden md:flex items-center gap-2 text-sm text-slate-600">
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium">
                  Rol: {{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'sin rol' }}
                </span>
              </div>
            @endauth
          </div>

          <div class="flex items-center gap-4 text-sm font-medium text-slate-700">
            @guest
              <a href="{{ route('login') }}" class="hover:text-slate-900">Iniciar sesión</a>
            @else
              @php($user = auth()->user())
              @if($user->hasRole('administrador'))
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900">Panel administrativo</a>
              @endif
              @if($user->hasRole('doctor'))
                <a href="{{ route('doctor.dashboard') }}" class="hover:text-slate-900">Panel médico</a>
              @endif
              @if($user->hasRole('paciente'))
                <a href="{{ route('paciente.dashboard') }}" class="hover:text-slate-900">Panel paciente</a>
              @endif
              <form id="logout-form-app" action="{{ route('salir') }}" method="POST" class="hidden">@csrf</form>
              <button type="button"
                      onclick="document.getElementById('logout-form-app').submit();"
                      class="rounded-md bg-rose-500 px-3 py-1.5 text-white shadow hover:bg-rose-600">
                Salir
              </button>
            @endguest
          </div>
        </div>
      </div>
    </nav>

    <main class="py-6">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @yield('content')
      </div>
    </main>
  </div>

  {{-- NECESARIO para los scripts que se pushean desde las vistas --}}
  @stack('scripts')
</body>
</html>
