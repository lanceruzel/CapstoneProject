<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email</title>

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <x-dialog />
    <x-notifications />
    
    <main class="w-screen h-screen">
        <div class="overflow-hidden h-full">
            <div class="relative flex items-center justify-center h-full w-full px-3 md:px-5 lg:px-10 z-60">
                <div class="flex flex-col items-center justify-center max-lg:mt-40 text-center bg-white p-10 rounded shadow">
                    <h1 class="text-xl font-semibold">Please verify your email through the email we've sent you.</h1>
                    <p class="mt-5">Didn't get the email?</p>

                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <x-button class="mt-3" label="Send Again" type="submit" />
                    </form>
                </div>

                <div class="absolute top-0 w-full h-full max-w-[1500px] z-[-10] object-auto">
                    <img src="{{ asset('assets/svg/5777076_2995664.svg') }}" alt="Traveling-cuate"/>
                </div>
            </div>
        </div>
    </main>

    <livewire:Auth.create-account-user-type-selection />
</body>
</html>