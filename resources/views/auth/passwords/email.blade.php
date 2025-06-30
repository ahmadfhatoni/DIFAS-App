<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - DIFAS</title>

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

        .reset-box {
            width: 380px;
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #3066BE;
            border: 1px solid #3066BE;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1e4a8a;
            border: 1px solid #1e4a8a;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(48, 102, 190, 0.3);
        }

        .alert {
            font-size: 0.875rem;
        }

        h2 {
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="reset-box">
        <div class="text-center mb-4">
            <img src="{{ asset('logo/LOGO.png') }}" alt="Logo DIFAS" style="max-width: 200px;">
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="text-start">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success" id="flash-message">
                {{ session('status') }}
            </div>
        @endif

        <h2 class="text-center">Forgot Your Password?</h2>
        <p class="text-center">Please enter your email to receive a password reset link.</p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control mb-3" required autofocus>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Request Password Reset</button>
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
                }, 5000);
            }
        };
    </script>
</body>
</html>
