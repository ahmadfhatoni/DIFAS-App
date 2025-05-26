<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - DIFAS</title>
  <link rel="icon" href="{{ asset('logo/LOGO.png') }}" type="image/png">

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
        background-color: #090C9B;
        border: 1px solid #090C9B;
    }

    .btn-primary:hover {
        background-color: #06097a;
    }

    .alert {
        font-size: 0.875rem;
    }

    label {
        margin-top: 0.5rem;
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

    @if (session()->has('status'))
      <div class="alert alert-success" id="flash-message">
        {{ session()->get('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ request()->token }}">
        <input type="hidden" name="email" value="{{ request()->email }}">

        <input type="password" class="form-control mb-3" name="password" placeholder="New Password" required>
        <input type="password" class="form-control mb-3" name="password_confirmation" placeholder="Confirm Password" required>
        
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Update Password</button>
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
