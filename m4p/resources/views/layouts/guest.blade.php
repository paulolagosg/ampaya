<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '::Ampaya::') }}</title>


        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}"  crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/multiple-select.css') }}">
        <link href="{{ asset('js/lib/main.css') }}" rel='stylesheet' />
        <link href="{{ asset('css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">


        <!-- Scripts -->
        <script src="//momentjs.com/downloads/moment.min.js"></script>
        <script src="{{ asset('js/lib/main.js') }}" defer></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}" crossorigin="anonymous">
        </script>
        <script src="{{ asset('js/popper.min.js')}}"  crossorigin="anonymous"></script>
        <script src="{{ asset('js/bootstrap.min.js')}}"  crossorigin="anonymous"></script>
        <script src="{{ asset('js/jquery-3.5.1.js')}}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js')}}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/dataTables.bootstrap4.min.js')}}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/multiple-select.js')}}"></script>
        <script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>


        <!-- date picker -->
        <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
        <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
        <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
    
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
