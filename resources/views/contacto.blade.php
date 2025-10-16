<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="icon" type="image/jpg" href="{{ asset('img/LogoClinica.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        :root {
            --clr-primary: #7380ec;
            --clr-danger: #ff7782;
            --clr-success: #41f1b6;
            --clr-white: #fff;
            --clr-info-dark: #7d8da1;
            --clr-info-light: #dce1eb;
            --clr-dark: #363949;
            --clr-warning: #ff4e4c;
            --clr-light: rgba(132, 139, 200, 0.18);
            --clr-primary-variant: #111e88;
            --clr-dark-variant: #677483;
            --clr-color-background: #f6f6f9;
            --card-border-radius: 1rem;
            --border-radius-1: 0.4rem;
            --border-radius-2: 0.8rem;
            --border-radius-3: 1.2rem;
            --card-padding: 1.8rem;
            --padding-1: 1.2rem;
            --box-shadow: 0 2rem 3rem var(--clr-light);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Poppins", sans-serif;
            background: var(--clr-color-background);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .form-card {
            background: var(--clr-white);
            padding: 2rem;
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
        }

        .form-card h2 {
            color: var(--clr-dark);
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            color: var(--clr-dark);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border-radius: var(--border-radius-2);
            border: 1px solid var(--clr-info-light);
            outline: none;
            font-size: 0.95rem;
            transition: border 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--clr-primary);
        }

        button {
            background: var(--clr-primary);
            color: var(--clr-white);
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius-2);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background 0.3s ease;
            width: 100%;
        }

        button:hover {
            background: var(--clr-primary-variant);
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 0.6rem 1rem;
            background: var(--clr-light);
            color: var(--clr-dark);
            font-weight: 500;
            border-radius: var(--border-radius-2);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--clr-primary);
            color: var(--clr-white);
        }

        .alert-success {
            background: var(--clr-success);
            color: var(--clr-white);
            padding: 0.8rem;
            border-radius: var(--border-radius-1);
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <a href="{{ url('/') }}" class="btn-back">← Regresar</a>

        <h2>Formulario de Contacto</h2>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contacto.enviar') }}">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" name="asunto">
            </div>

            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea name="mensaje" rows="5" required></textarea>
            </div>

            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
