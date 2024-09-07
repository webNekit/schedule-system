<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-slate-50">
        <div class="w-full h-screen flex items-center justify-center">
            <div class="space-y-4">
                <h1 class="text-center text-gray-800 spac text-8xl font-bold tracking-widest">С.У.Р</h1>
                <h2 class="text-center text-gray-700 text-xl font-medium">Система Умного Расписания</h2>
            </div>
        </div>
    </body>
</html>
