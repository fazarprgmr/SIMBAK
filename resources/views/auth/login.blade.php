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
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #141E30 0%, #243B55 100%);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-card {
            background: #1e1e2f;
            border-radius: 20px;
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
            text-align: center;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            padding-right: 40px;
        }

        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-login {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            border-radius: 12px;
            padding: 12px;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 242, 254, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 242, 254, 0.4);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 1.2rem;
            line-height: 1;
        }

        .toggle-password:hover {
            color: #4facfe;
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

        .alert-custom {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-custom i {
            font-size: 18px;
            color: #ff4d4d;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="{{ asset('template/images/logo-simbaknobg.png') }}" alt="Logo SIMBAK">
        <h4>Login ke SIMBAK</h4>
        <p>Masukkan email dan password Anda</p>

        {{-- Error umum (login gagal) --}}
        {{-- Error umum (login gagal) --}}
        @if ($errors->has('email'))
            <div class="alert-custom">
                <i class="bi bi-exclamation-circle"></i>
                {{ $errors->first('email') }}
            </div>
        @endif


        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <div class="position-relative d-flex align-items-center">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="form-control" placeholder="Masukkan email">
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3 text-start password-wrapper">
                <label for="password" class="form-label">Password</label>
                <div class="position-relative d-flex align-items-center">
                    <input id="password" type="password" name="password" required class="form-control pe-5"
                        placeholder="Masukkan password">
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>
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
    <script>
        // Toggle Password
        // Toggle Password
        // Toggle Password Sesuai Status
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Cek tipe password
            if (password.type === 'password') {
                password.type = 'text'; // Buka password
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye'); // Mata nyala
            } else {
                password.type = 'password'; // Tutup password
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash'); // Mata mati
            }
        });
    </script>
</body>

</html>
