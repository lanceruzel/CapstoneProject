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
<body class="font-inter text-gray-700 antialiased p-0 m-0 overflow-x-hidden">
    <main class="w-screen h-screen grid grid-cols-12">
        <div class="max-lg:hidden lg:col-span-7 xl:col-span-6 overflow-hidden flex items-start justify-center">
            <img src="{{ asset('assets/svg/7010830_3326653.svg') }}" alt="Traveling-cuate"/>
        </div>

        <div class="col-span-12 lg:col-span-5 xl:col-span-6 relative">
            <div class="flex items-center justify-center h-full w-full px-3 md:px-5 lg:px-10 z-60 max-md:py-5">
                @livewire('Auth.signup-form')
            </div>

            <div class="absolute top-0 lg:hidden blur-xs w-full h-full z-[-10] object-auto">
                <img src="{{ asset('assets/svg/7010830_3326653.svg') }}" alt="7010830_3326653"/>
            </div>
        </div>
    </main>
</body>
</html>