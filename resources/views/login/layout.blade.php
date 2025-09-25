<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel Auth' }}</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(90deg, #1e40af, #3b82f6);">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="w-100" style="max-width: 420px;">
            @yield('content')
        </div>
    </div>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="w-100" style="max-width: 420px;">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
