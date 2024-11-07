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
                <div class="flex flex-col items-center justify-center max-lg:mt-40 text-center bg-gray-50 p-12 rounded-lg shadow-lg space-y-6">
                    <h1 class="text-2xl font-bold text-gray-800">Verify Your Email</h1>
                    <p class="text-gray-600">Please check your inbox for a verification email and click the link to complete the process.</p>
                    
                    <p class="text-gray-500">Didn’t receive an email?</p>
                    
                    <form action="{{ route('verification.send') }}" method="POST" class="w-full max-w-xs">
                        @csrf
                        <x-button class="w-full py-2 mt-2" label="Resend Email" type="submit" />
                    </form>
                    
                    <x-link label="Back to Login" class="text-blue-500 mt-2 hover:underline text-sm" href="{{ route('signout') }}" />
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