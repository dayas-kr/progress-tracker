<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>YouTube Playlist Organizer</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18]">
    <nav class="sticky top-0 z-50 bg-white border-b border-zinc-200/75 dark:bg-zinc-950 dark:border-zinc-800">
        <div class="px-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-end h-16">
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-[calc(100vh-65px)] overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 -z-10">
            <!-- Gradient Background -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-white via-red-50 to-orange-50 opacity-70 dark:opacity-5 dark:from-zinc-900 dark:via-red-950 dark:to-orange-950">
            </div>

            <!-- Abstract Shapes -->
            <div class="absolute w-64 h-64 bg-red-300 rounded-full top-20 right-10 opacity-10 dark:opacity-5 blur-3xl">
            </div>
            <div
                class="absolute bg-orange-300 rounded-full bottom-20 left-10 w-72 h-72 opacity-10 dark:opacity-5 blur-3xl">
            </div>

            <!-- Grid Pattern -->
            <div
                class="absolute inset-0 bg-[linear-gradient(rgba(27,27,24,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(27,27,24,0.05)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(237,237,236,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(237,237,236,0.05)_1px,transparent_1px)] bg-[size:50px_50px]">
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col items-center justify-center h-full px-6 py-16 mx-auto max-w-7xl">
            <div class="flex flex-col items-center text-center">
                <!-- Brand Badge -->
                <div
                    class="inline-flex items-center px-4 py-1.5 mb-8 text-sm font-medium rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 backdrop-blur-sm shadow-sm">
                    <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                        </path>
                    </svg>
                    <span>YouTube Integration</span>
                </div>

                <!-- Main Heading with Animated Gradient Text -->
                <h1 class="text-5xl font-bold tracking-tight sm:text-6xl lg:text-7xl">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 via-red-500 to-orange-500 dark:from-red-500 dark:via-red-400 dark:to-orange-400">
                        YouTube Playlist
                    </span>
                    <br class="sm:hidden">
                    <span class="dark:text-white">Organizer</span>
                </h1>

                <!-- Description with improved typography -->
                <p class="max-w-2xl mt-8 text-lg text-zinc-600 sm:text-xl dark:text-zinc-300">
                    Effortlessly organize, track, and watch your YouTube content.
                    <span class="block mt-2 font-light text-zinc-500 dark:text-zinc-400">Never lose track of your
                        favorite videos again.</span>
                </p>

                <!-- Improved CTA Buttons -->
                <div class="flex flex-col w-full gap-5 mt-12 sm:flex-row sm:w-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="relative inline-flex items-center justify-center w-full overflow-hidden rounded-lg group sm:w-auto">
                            <span
                                class="relative flex items-center justify-center px-8 py-3.5 w-full font-medium text-white transition-all duration-300 ease-out bg-red-600 rounded-lg shadow-md hover:shadow-lg hover:bg-red-700 dark:shadow-red-900/30">
                                <span class="mr-2">Go to Dashboard</span>
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="relative inline-flex items-center justify-center w-full overflow-hidden rounded-lg group sm:w-auto">
                            <span class="absolute inset-0 bg-gradient-to-r from-red-600 via-red-500 to-orange-500"></span>
                            <span
                                class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-r from-red-700 via-red-600 to-orange-600 group-hover:opacity-100"></span>
                            <span
                                class="relative flex items-center justify-center px-8 py-3.5 w-full font-medium text-white rounded-lg shadow-md shadow-red-600/20 dark:shadow-red-900/30">
                                <span class="mr-2">Get Started Now</span>
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="{{ route('login') }}"
                            class="relative inline-flex items-center justify-center w-full overflow-hidden rounded-lg group sm:w-auto">
                            <span class="absolute inset-0 border rounded-lg border-zinc-200 dark:border-zinc-700"></span>
                            <span
                                class="relative flex items-center justify-center px-8 py-3.5 w-full font-medium text-zinc-700 dark:text-zinc-200 transition-all duration-300 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <span>Log in</span>
                            </span>
                        </a>
                    @endauth
                </div>

                <!-- Features Preview -->
                <div class="grid grid-cols-1 gap-6 mt-20 sm:grid-cols-3">
                    <div
                        class="flex flex-col h-full p-6 transition-all duration-300 border border-zinc-100 backdrop-blur-sm rounded-xl bg-white/70 dark:bg-zinc-900/50 hover:shadow-lg dark:hover:bg-zinc-800/60 dark:border-zinc-800 group hover:border-red-100 dark:hover:border-red-900/30">
                        <div
                            class="flex items-center justify-center w-12 h-12 mb-4 text-red-600 transition-transform bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium dark:text-white">Organize</h3>
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Categorize and manage all your
                            playlists in one place</p>
                    </div>

                    <div
                        class="flex flex-col h-full p-6 transition-all duration-300 border border-zinc-100 backdrop-blur-sm rounded-xl bg-white/70 dark:bg-zinc-900/50 hover:shadow-lg dark:hover:bg-zinc-800/60 dark:border-zinc-800 group hover:border-orange-100 dark:hover:border-orange-900/30">
                        <div
                            class="flex items-center justify-center w-12 h-12 mb-4 text-orange-600 transition-transform bg-orange-100 rounded-lg dark:bg-orange-900/30 dark:text-orange-400 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium dark:text-white">Track</h3>
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Monitor your viewing progress and
                            remember where you left off</p>
                    </div>

                    <div
                        class="flex flex-col h-full p-6 transition-all duration-300 border border-zinc-100 backdrop-blur-sm rounded-xl bg-white/70 dark:bg-zinc-900/50 hover:shadow-lg dark:hover:bg-zinc-800/60 dark:border-zinc-800 group hover:border-amber-100 dark:hover:border-amber-900/30">
                        <div
                            class="flex items-center justify-center w-12 h-12 mb-4 transition-transform rounded-lg text-amber-600 bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium dark:text-white">Watch</h3>
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Enjoy your videos seamlessly without
                            distractions</p>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="flex flex-wrap justify-center gap-6 mt-16">
                    <div class="flex flex-col items-center p-4">
                        <span class="text-3xl font-bold text-red-600 dark:text-red-400">100K+</span>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Active Users</span>
                    </div>
                    <div class="flex flex-col items-center p-4">
                        <span class="text-3xl font-bold text-orange-600 dark:text-orange-400">5M+</span>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Videos Organized</span>
                    </div>
                    <div class="flex flex-col items-center p-4">
                        <span class="text-3xl font-bold text-amber-600 dark:text-amber-400">4.9</span>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">User Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
