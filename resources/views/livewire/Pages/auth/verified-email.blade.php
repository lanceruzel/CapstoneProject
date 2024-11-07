<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Congratulations</title>

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <x-dialog />
    <x-notifications />
    
    <main class="w-screen h-screen">
        <div class="overflow-hidden h-full">
            <div class="relative flex items-center justify-center h-full w-full px-3 md:px-5 lg:px-10 z-60">
                <div class="flex max-w-[500px] flex-col items-center justify-center max-lg:mt-40 text-center bg-gray-50 p-12 rounded shadow">
                    <h2 class="text-xl font-bold text-green-600 mb-4">Congratulations! 🎉</h2>
                <p class="text-gray-700 mb-4">
                    Your email verification was successful, and your account is now fully activated. You’re all set to explore all the features and benefits we have in store for you!
                </p>

                <p class="text-gray-700 mb-6">
                    To proceed, simply click the button below:
                </p>
                
                <x-button class="mt-3 w-full" label="Proceed" href="{{ route('home') }}" />

                <div class="absolute top-0 w-full h-full max-w-[1500px] z-[-10] object-auto">
                    <img src="{{ asset('assets/svg/13124789_5156365.svg') }}" alt="Traveling-cuate"/>
                </div>
            </div>
        </div>
    </main>

    <livewire:Auth.create-account-user-type-selection />
</body>
</html>