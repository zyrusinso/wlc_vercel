<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet"> -->

        <!-- Favicon -->
        <link rel="icon" href="{{ url('img/favicon.png') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        @if (request()->is('register'))
            <style>
                body {
                    background-image: url("{{ asset('img/reg_img.jpg') }}");
                    background-size: cover;
                    background-position: center center;
                    height: 100vh;
                    background-repeat: no-repeat;
                    min-height:100%;
                    background-attachment: fixed;
                    -moz-background-size: cover;
                }
                input::-webkit-outer-spin-button,
                input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
                }

                /* Firefox */
                input[type=number] {
                -moz-appearance: textfield;
                }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased">

        
        {{ $slot }}

        @stack('modals')

        @livewireScripts

        @stack('scripts')
    </body>
</html>