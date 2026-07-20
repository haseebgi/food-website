<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>

    @include('partials.navbar')

    <div id="layoutSidenav">

        @include('partials.sidebar')

        <div id="layoutSidenav_content">

            <main class="container-fluid px-4 py-4">
                @yield('content')
            </main>

        </div>

    </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/scripts.js') }}"></script>

@yield('scripts')

</body>

</html>