<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <main class="w-screen h-screen grid grid-cols-12">

        <div class="max-lg:hidden col-span-7 bg-login-pattern bg-cover bg-no-repeat"></div>

        <div class="col-span-12 lg:col-span-5 relative">

            <div class="flex items-center justify-center h-full w-full px-3 md:px-5 lg:px-10 z-60">
                @livewire('Auth.signup-form')
            </div>

            <div class="absolute top-0 lg:hidden bg-login-pattern bg-cover bg-no-repeat blur-sm w-full h-full z-[-10]"></div>
        </div>
    </main>
</body>
</html>