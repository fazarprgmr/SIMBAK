<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBAK</title>
    <link rel="shortcut icon" href="{{ asset('template/images/logo-simbak1.png') }}" type="image/png">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #141E30 0%, #243B55 100%);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #fff;
            margin: 0;
        }

        .login-card {
            background: #1e1e2f;
            border-radius: 20px;
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .login-card img {
            height: 110px;
            margin-bottom: 15px;
        }

        .login-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-card p {
            color: #bbb;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-control {
            background: #2c2c3c;
            border: 1px solid #444;
            border-radius: 12px;
            color: #fff;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            background: #2c2c3c;
        }

        .btn-login {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            border-radius: 12px;
            padding: 12px;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .form-check-label {
            color: #ccc;
        }

        a {
            color: #4facfe;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="{{ asset('template/images/logo-simbaknobg.png') }}" alt="Logo SIMBAK">
        <h4>Login ke SIMBAK</h4>
        <p>Masukkan email dan password Anda</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-control">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required class="form-control">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-login w-100">Login</button>
        </form>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
