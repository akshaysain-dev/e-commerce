
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('styles')
    <head>
    {{-- ... other head elements ... --}}
    @vite(['resources/js/app.js'])
</head>

</head>
<body>
    @include('frontend.include.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('frontend.include.footer')
    @yield('scripts')
    @stack('scripts')
</body>
</html>
