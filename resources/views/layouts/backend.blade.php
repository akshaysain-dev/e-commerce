
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com"/>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @yield('styles')
</head>
<body>
    @include('admin.include.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('admin.include.footer')
    @yield('scripts')
    @stack('scripts')
</body>
</html>
