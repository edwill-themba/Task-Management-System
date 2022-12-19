<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task Management</title>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        @vite('resources/js/app.js','resources/css/app.css')
    </head>
    <style>
        html body{
            margin: 0;
            padding: 0;
        }
    </style>
    <body class="antialiased">
        <div id="app">
            <Navbar/>
       </div>
       <div id="modal"></div>
    </body>
</html>
