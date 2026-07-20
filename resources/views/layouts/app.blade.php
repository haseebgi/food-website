<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- SweetAlert2 CSS & JS CDNs -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{-- Breeze/Component style ke liye --}}
                @isset($slot)
                    {{ $slot }}
                @endisset

                {{-- Hamare Manual CRUD (Expenses Module) ke liye --}}
                @yield('content')
            </main>
        </div>

        <!-- ========================================== -->
        <!-- JAVASCRIPT DEPENDENCIES & TOASTR SETUP -->
        <!-- ========================================== -->
        
        <!-- 1. jQuery (Toastr ke chalne ke liye zaroori hai) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- 2. Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        
        <!-- 3. Bootstrap 5 Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- 4. Toastr Configuration & Session Flash Interceptor -->
        <script>
            $(document).ready(function() {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "4000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                // Catch standard controller redirect sessions globally
                @if(Session::has('success'))
                    toastr.success("{{ Session::get('success') }}");
                @endif

                @if(Session::has('error'))
                    toastr.error("{{ Session::get('error') }}");
                @endif

                @if(Session::has('info'))
                    toastr.info("{{ Session::get('info') }}");
                @endif

                @if(Session::has('warning'))
                    toastr.warning("{{ Session::get('warning') }}");
                @endif
            });
        </script>
    </body>
</html>