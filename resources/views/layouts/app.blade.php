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
            color: #ffffff;
        }
        
        .bg-secondary {
            background: linear-gradient(to bottom right, #f0f4ff, #cbd5e1) !important;
        }

        .btn-primary {
            background-color: #3066BE;
            color: white;
            border: 1px solid #3066BE;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1e4a8a;
            color: white;
            border: 1px solid #1e4a8a;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(48, 102, 190, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
            border: 1px solid #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: 1px solid #28a745;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            color: white;
            border: 1px solid #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: 1px solid #dc3545;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            color: white;
            border: 1px solid #c82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-sidebar {
            background-color: white;
            color: #3066BE;
            border: 1px solid white;
            transition: all 0.3s ease;
        }

        .btn-sidebar:hover {
            background-color: #1e4a8a !important;
            color: white !important;
            border: 1px solid #1e4a8a !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(48, 102, 190, 0.3) !important;
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

        /* Theme color classes */
        .text-theme-blue {
            color: #3066BE !important;
        }

        .bg-theme-blue {
            background-color: #3066BE !important;
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
    @include('layouts.navbar')
    @include('layouts.sidebar')
    <div id="mainContent" class="container py-5">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
