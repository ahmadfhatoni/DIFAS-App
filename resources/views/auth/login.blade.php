<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login DIFAS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom right, #f0f4ff, #cbd5e1);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-box {
            width: 380px;
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #090C9B;
            border: 1px solid #090C9B;
        }

        .btn-primary:hover {
            background-color: #06097a;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .forgot-password {
            font-size: 0.9rem;
        }

        .alert {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="text-center mb-4">
            <img src="{{ asset('logo/LOGO.png') }}" alt="Logo DIFAS" style="max-width: 200px;">
        </div>

        @if (session('success'))
            <div class="alert alert-success" id="flash-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="text-start">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" value="{{ old('email') }}" required autofocus>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="remember">Remember Me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password text-decoration-none fw-semibold">Forgot Password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide flash message after 5 seconds
        window.onload = function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(function() {
                    flashMessage.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        };
    </script>
</body>
</html>
