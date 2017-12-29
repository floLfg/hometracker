<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
        <link rel="icon" href="{{ url('img/favicon.ico') }}">
        
        <title>Hometracker</title>
    </head>
    <body>
        <div id="app">
            <vue-toastr ref="toastr"></vue-toastr>
            <div class="page-content">
                <router-view></router-view>
            </div>
            <router-nav></router-nav>
        </div>
    </body>

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</html>
