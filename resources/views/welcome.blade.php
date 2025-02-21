<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Jornadas25</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-gray-800 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <h1 class="text-4xl font-bold text-center text-black dark:text-white">
                            Bienvenido a Jornadas25
                        </h1>
                    </div>
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black dark:text-white">
                                    Inicio
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black dark:text-white">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black dark:text-white">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <!-- Lista de eventos -->
                <section class="mt-8">
                    <h2 class="text-2xl font-semibold text-center text-black dark:text-white">Eventos</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach($eventos as $evento)
                            <li class="bg-white dark:bg-white p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold text-black dark:text-black">{{ $evento->nombre }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-800">
                                    Fecha: {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }} |
                                    Hora: {{ $evento->hora_inicio }} - {{ $evento->hora_fin }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-800">
                                    Ponente: {{ $evento->ponente->nombre ?? 'Por definir' }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </footer>
            </div>
        </div>
    </div>
</body>
</html>
