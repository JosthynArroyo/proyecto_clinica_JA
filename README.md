# Proyecto Clínica

Este repositorio contiene una aplicación **Laravel** para la gestión integral de una clínica médica.  
El sistema permite administrar pacientes, doctores, citas y procesos clínicos de forma sencilla y organizada.

## Funcionalidades principales

- **Gestión de usuarios y roles**
  - Administrador: crea y administra doctores y pacientes.
  - Doctor: revisa, acepta o cancela citas y gestiona su perfil.
  - Paciente: se registra, agenda, reprograma o cancela citas médicas.

- **Gestión de citas médicas**
  - Registro, modificación y cancelación de citas.
  - Validación para evitar duplicidad de citas en el mismo horario con el mismo doctor.
  - Historial de citas por paciente.

- **Control clínico**
  - Registro de especialidades médicas.
  - Asociación de doctores a sus especialidades.
  - Información clínica básica por paciente.

- **Seguridad**
  - Autenticación y autorización basada en roles.
  - Validación de datos únicos como número de cédula y correo electrónico.
  - Paneles separados para cada tipo de usuario.

## Arquitectura

El sistema está desarrollado en **Laravel** utilizando el patrón **MVC** (Modelo – Vista – Controlador).
La base de datos se diseñó en **MySQL**, y las vistas utilizan **Blade** junto con **TailwindCSS** para un diseño moderno y responsivo.
Se implementó control de roles a nivel de controladores y middleware para garantizar la seguridad y organización del flujo de trabajo.

## Roles y paneles

La plataforma cuenta con un modelo de roles flexible gestionado a través de la tabla pivote `role_user`:

| Rol             | Panel principal                     | Responsabilidades clave                             |
|-----------------|-------------------------------------|-----------------------------------------------------|
| Administrador   | `/admin/dashboard`                  | Métricas globales, gestión de usuarios y finanzas.  |
| Doctor          | `/doctor/dashboard`                 | Agenda diaria, control de citas y emisión de recetas|
| Paciente        | `/paciente/dashboard`               | Seguimiento de citas y programación de nuevas citas |

El middleware `role` (archivo `app/Http/Middleware/EnsureUserRole.php`) protege las rutas y devuelve una respuesta JSON con `403` para peticiones AJAX cuando el rol no coincide.

### Widgets principales por panel

- **Administrativo**: tarjetas con conteo de citas por estado, resumen de ingresos emitidos y tabla de últimas 10 citas.
- **Médico**: indicadores del día (citas realizadas, pendientes y actividad de las últimas dos horas) y agenda rápida.
- **Paciente**: resumen de historial personal y tabla con próximas citas, además de acceso directo para agendar.

## Puesta en marcha

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run build # o `npm run dev` durante el desarrollo
```

El seeder `Database\Seeders\UserSeeder` crea cuentas de ejemplo:

| Rol           | Correo                 | Contraseña      |
|---------------|------------------------|-----------------|
| Administrador | `admin@clinic.test`    | `admin1234`     |
| Doctor        | `doctor@clinic.test`   | `doctor1234`    |
| Paciente      | `paciente@clinic.test` | `paciente1234`  |

> **Nota:** cambia las contraseñas en producción y asigna roles adicionales con el método `assignRole()` del modelo `User`.

## Despliegue

El sistema está preparado para ejecutarse en entornos con **PHP-FPM** y servidores web como **Nginx** o **Apache**.  
También puede integrarse con servicios en la nube para mayor escalabilidad y disponibilidad.


