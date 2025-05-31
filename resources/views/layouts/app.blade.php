<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DIFAS App')</title>

    <link rel="icon" type="image/png" href="{{ asset('logo/ICON.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom right, #f0f4ff, #cbd5e1);
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            margin: 0;
        }

        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .bg-primary {
            background-color: #3066BE !important;
            color: #ffffff; /* Opsional: teks putih agar kontras */
        }
        
        .bg-secondary {
            background: linear-gradient(to bottom right, #f0f4ff, #cbd5e1) !important;
        }

        .btn-primary {
            background-color: #090C9B;
            color: white;
            border: 1px solid #090C9B;
        }

        .btn-primary:hover {
            background-color: white;
            color: #090C9B;
            border: 1px solid #06097a;
        }

        .btn-secondary {
            background-color: white;
            color: #090C9B;
            border: 0px solid #090C9B;
        }

        .btn-secondary:hover {
            background-color: #06097a;
            color: white;
        }

        .btn-sidebar {
            background-color: white;
            color: #090C9B;
        }

        .btn-sidebar:hover {
            background-color: #06097a;
            color: white;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-primary {
            background-color: #e6f0ff;
        }

        th, td {
            vertical-align: middle !important;
        }

        .main-wrapper {
            margin-left: 250px;
            padding-top: 100px; 
        }

        #sidebar {
            margin-top: 90px; 
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Konten utama --}}
    <div id="mainContent" class="container py-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
