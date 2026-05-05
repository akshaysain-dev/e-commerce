<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Vendor Panel')</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- jQuery -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Chart JS -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('styles')

    <style>

        body{
            min-height:100vh;
            display:flex;
            flex-direction:column;
            background:#f5f5f5;
        }

        .vendor-sidebar{
            width:250px;
            background:#212529;
            min-height:100vh;
            color:#fff;
            position:fixed;
            top:0;
            left:0;
            padding-top:20px;
        }

        .vendor-sidebar a{
            color:#fff;
            text-decoration:none;
            display:block;
            padding:12px 20px;
            transition:0.3s;
        }

        .vendor-sidebar a:hover{
            background:#343a40;
        }

        .vendor-content{
            margin-left:250px;
            padding:20px;
            flex:1;
        }

        .vendor-header{
            background:#fff;
            padding:15px 20px;
            border-radius:10px;
            margin-bottom:20px;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

    </style>

</head>

<body>

    <!-- Sidebar -->

    @include('vendor.include.sidebar')

    <!-- Main Content -->

    <div class="vendor-content">

        <!-- Header -->

        @include('vendor.include.header')

        <!-- Page Content -->

        <main>

            @yield('content')

        </main>

    </div>

    <!-- Footer -->

    @include('vendor.include.footer')

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

    @stack('scripts')

</body>

</html>