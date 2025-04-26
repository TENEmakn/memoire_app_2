<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Motors') }}</title>
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite('resources')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .vehicle-image-container {
            transition: transform 0.3s ease;
        }
        .vehicle-image-container:hover {
            transform: scale(1.05);
        }
        .vehicle-details {
            background-color: white;
            border-radius: 0 0 8px 8px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- <div class="row">
        <div class="col-lg-3"> -->
            @include('layouts.sidebar')
        <!-- </div> -->
        <!-- <div class="col-lg-9"> -->
            <main class="py-1 content-wrapper">
                @yield('content')
            </main>
        <!-- </div>
    </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
</body>
</html> 