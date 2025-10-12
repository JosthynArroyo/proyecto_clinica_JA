<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clínica Los Ángeles</title>
    @vite(['resources/css/welcome.css','resources/js/welcome-login-modal.js'])
</head>
<body>
    <header class="header">
        <div class="menu container">
            <a href="{{ url('/') }}" class="logo">Clinica Los Angeles</a>
            <input type="checkbox" id="menu">
            <label for="menu" aria-label="Abrir menú">
                <img src="{{ asset('img/menu.png') }}" class="menu-icono" alt="Menú">
            </label>
            <nav class="navbar" aria-label="Navegación principal">
                <ul>
                    @if (Route::has('login'))
                        @auth
                            <li><a href="{{ route('home') }}">Mi Panel</a></li>
                        @else
                            <li>
                                <a href="#" data-login-trigger class="font-semibold">Iniciar Sesión</a>
                                <noscript><a href="{{ route('login') }}">Iniciar Sesión</a></noscript>
                            </li>
                        @endauth
                    @endif
                    <li><a href="{{ route('contacto.guest') }}">Contacto</a></li>
                </ul>
            </nav>
        </div>

        <div class="header-content container">
            <div class="header-txt">
                <h1>Clinica</h1>
                <span>Los Ángeles</span>
                <p>Sistema de gestión médica para agendar citas fácilmente y recibir atención especializada.</p>
                @auth
                    <a href="{{ route('paciente.dashboard') }}" class="btn-1">Agendar Cita</a>
                @else
                    <a href="#" data-login-trigger class="btn-1">Agendar Cita</a>
                    <noscript><a href="{{ route('login') }}" class="btn-1">Agendar Cita</a></noscript>
                @endauth
            </div>
            <div class="header-dir">
                <div class="dir">
                    <h3>Dirección</h3>
                    <p>Quito, Av. Colón y 6 de Diciembre</p>
                </div>
                <div class="dir">
                    <h3>Teléfono</h3>
                    <p>0998742410</p>
                </div>
                <div class="dir">
                    <h3>Horario</h3>
                    <p>Lunes a Viernes, 08:00 - 18:00</p>
                </div>
            </div>
        </div>
    </header>

    <section class="welcome">
        <div class="welcome-1"></div>
        <div class="welcome-2">
            <h2>Bienvenidos al Sistema de Gestión de Citas Médicas</h2>
            <p class="b1">Accede a consultas con médicos especializados desde cualquier lugar.</p>
            <p>Nuestro sistema te permite agendar, modificar o cancelar tus citas de forma rápida y segura.</p>
        </div>
    </section>

    <main class="services container">
        <div class="services-txt">
            <h2>Nuestros Servicios</h2>
            <hr>
            <p>Contamos con atención médica en múltiples especialidades para cuidar de tu salud y bienestar.</p>
        </div>
        <div class="services-group">
            <div class="services-1">
                <svg class="svc-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="5" cy="5" r="1.6" fill="currentColor"/>
                    <circle cx="19" cy="5" r="1.6" fill="currentColor"/>
                    <path d="M6 3v5a4 4 0 0 0 8 0V3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 13v3a5 5 0 0 0 5 5h1" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>Medicina General</h3>
                <p>Evaluaciones integrales, diagnósticos iniciales y derivaciones oportunas para tu cuidado primario.</p>
            </div>
            <div class="services-1">
                <svg class="svc-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20.5 9.5c0 5.4-8.5 10-8.5 10S3.5 14.9 3.5 9.5A4.9 4.9 0 0 1 12 8a4.9 4.9 0 0 1 8.5 1.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 12h3l1-2 2 6 1-2h3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>Cardiología</h3>
                <p>Prevención, diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular.</p>
            </div>
            <div class="services-1">
                <svg class="svc-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M5 19a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M12 5.5c.5-.7 1-.9 1.6-1" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <h3>Pediatría</h3>
                <p>Atención especializada para bebés, niños y adolescentes con enfoque integral y preventivo.</p>
            </div>
            <div class="services-1">
                <svg class="svc-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 3c2.6 2.7 4 5 4 7a4 4 0 1 1-8 0c0-2 1.4-4.3 4-7Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="14" r=".9" fill="currentColor"/>
                    <circle cx="12" cy="16" r=".9" fill="currentColor"/>
                    <circle cx="15" cy="14" r=".9" fill="currentColor"/>
                </svg>
                <h3>Dermatología</h3>
                <p>Diagnóstico y tratamiento de condiciones de la piel, cabello y uñas con tecnología avanzada.</p>
            </div>
        </div>
    </main>

    <section class="prices">
        <div class="prices-1">
            <h2>Tarifario de Servicios</h2>
            <p>Consulta los precios aproximados de nuestros servicios médicos. Pregunta por paquetes y promociones.</p>
            <table>
                <tbody>
                    <tr><th>Medicina General</th><td>$25</td></tr>
                    <tr><th>Cardiología</th><td>$40</td></tr>
                    <tr><th>Pediatría</th><td>$35</td></tr>
                    <tr><th>Dermatología</th><td>$30</td></tr>
                </tbody>
            </table>
            @auth
                <a href="{{ route('paciente.dashboard') }}" class="btn-1">Agendar Cita</a>
            @else
                <a href="#" data-login-trigger class="btn-1">Agendar Cita</a>
                <noscript><a href="{{ route('login') }}" class="btn-1">Agendar Cita</a></noscript>
            @endauth
        </div>
        <div class="prices-2"></div>
    </section>

    <section class="personal container">
        <div class="personal-txt">
            <h2>Nuestros Doctores</h2>
            <p>Contamos con un equipo médico calificado y comprometido con tu salud y la de tu familia.</p>
        </div>
        <div class="personal-group">
            <div class="personal-1">
                <img src="{{ asset('img/doctora1.jpg') }}" alt="Doctora Dermatóloga">
                <p>Dra. Ana Martínez — Dermatología. Enfoque en dermatosis crónicas y estética clínica.</p>
            </div>
            <div class="personal-1">
                <img src="{{ asset('img/doctor1.jpg') }}" alt="Doctor Pediatra">
                <p>Dr. Carlos Pérez — Pediatría. Acompañamiento del desarrollo y control de crecimiento.</p>
            </div>
            <div class="personal-1">
                <img src="{{ asset('img/doctor2.jpg') }}" alt="Doctor Cardiólogo">
                <p>Dr. Juan Torres — Cardiología. Prevención y manejo integral de riesgo cardiovascular.</p>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-txt">
            <p>Clínica Los Ángeles © {{ date('Y') }} - Todos los derechos reservados.</p>
        </div>
    </footer>

    <div id="loginModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="loginTitle" aria-hidden="true">
        <div class="modal-backdrop"></div>
        <div class="modal-dialog" role="document">
            <div class="panel">
                <div class="panel-header">
                    <h3 id="loginTitle">Iniciar sesión</h3>
                    <button id="closeLoginModal" class="close-btn" aria-label="Cerrar modal">✕</button>
                </div>
                <div class="panel-body">
                    @if(session('status'))
                        <div class="alert ok">{{ session('status') }}</div>
                    @endif
                    @if($errors->any())
                        <span data-open-login-onload hidden></span>
                        <div class="alert err">Revisa tus datos e inténtalo nuevamente.</div>
                    @endif
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="field">
                            <label>Correo</label>
                            <input type="email" name="email" autocomplete="email" required value="{{ old('email') }}" placeholder="correo@ejemplo.com" inputmode="email">
                            @error('email') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label>Contraseña</label>
                            <input type="password" name="password" autocomplete="current-password" required placeholder="••••••••">
                            @error('password') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div class="row">
                            <label class="remember"><input type="checkbox" name="remember"> Recuérdame</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>
                        <button type="submit" class="submit">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
