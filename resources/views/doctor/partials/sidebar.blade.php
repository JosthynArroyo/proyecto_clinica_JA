<aside>
    <div class="top">
        <div class="logo">
            <h2>Doctor <span class="primary">Los Ángeles</span></h2>
        </div>
        <div class="close">
            <span class="material-symbols-outlined">close</span>
        </div>
    </div>
    <div class="sidebar">
        @php($current = $active ?? '')
        <a href="{{ route('doctor.dashboard') }}" class="{{ $current === 'dashboard' ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <h3>Inicio</h3>
        </a>
        <a href="{{ route('doctor.citas') }}" class="{{ $current === 'citas' ? 'active' : '' }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <h3>Mis Citas</h3>
        </a>
        <a href="{{ route('doctor.perfil.edit') }}" class="{{ $current === 'perfil' ? 'active' : '' }}">
            <span class="material-symbols-outlined">account_circle</span>
            <h3>Perfil</h3>
        </a>
        <form id="doctor-logout-form" action="{{ route('salir') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('doctor-logout-form').submit();">
            <span class="material-symbols-outlined">logout</span>
            <h3>Cerrar Sesión</h3>
        </a>
    </div>
</aside>