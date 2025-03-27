<nav
    {{ $attributes->merge(['class' => 'bg-white border-b border-zinc-200/75 dark:bg-zinc-950 dark:border-zinc-800']) }}>
    <!-- Primary Navigation Menu -->
    <div class="px-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Navigation Links -->
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block w-auto fill-current text-zinc-800 h-9 dark:text-zinc-200" />
                    </a>
                </div>

                @php
                    $_nav_links = [
                        [
                            'text' => 'Dashboard',
                            'href' => route('dashboard'),
                            'active' => 'dashboard',
                            'mobileOnly' => false,
                        ],
                        [
                            'text' => 'Playlists',
                            'href' => route('playlists.index'),
                            'active' => 'playlists.index',
                            'mobileOnly' => false,
                        ],
                        [
                            'text' => 'Account Settings',
                            'href' => route('profile.edit'),
                            'active' => 'profile.edit',
                            'mobileOnly' => true,
                        ],
                    ];
                @endphp

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @foreach ($_nav_links as $nav_link)
                        @if (!$nav_link['mobileOnly'])
                            <x-nav-link :href="$nav_link['href']" :active="request()->routeIs($nav_link['active'])">
                                {{ __($nav_link['text']) }}
                            </x-nav-link>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 -mb-1.5">
                <x-dropdown align="right" :gap="1">
                    <x-slot name="trigger">
                        <button data-dropdown-trigger type="button"
                            class="focus:outline-none size-8 cursor-pointer rounded-full overflow-hidden">
                            <img class="w-full object-cover" src="https://vercel.com/api/www/avatar?s=64"
                                alt="">
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div
                            class="text-xs border-b dark:border-zinc-800 mb-1.5 px-3 py-2 text-zinc-700 dark:text-zinc-300">
                            Manage Account
                        </div>
                        <x-dropdown-item id="account-settings" href="/profile" title="Account Settings" />

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-item type="submit" title="Log Out" />
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button id="hamburger-btn"
                    class="inline-flex items-center justify-center p-2 transition duration-150 ease-in-out rounded-md text-zinc-400 dark:text-zinc-500 hover:text-zinc-500 dark:hover:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 focus:outline-hidden focus:bg-zinc-100 dark:focus:bg-zinc-900 focus:text-zinc-500 dark:focus:text-zinc-400">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path id="icon-hamburger" class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div id="responsive-menu" class="hidden fixed inset-0 z-50 bg-white dark:bg-zinc-950 top-16 sm:hidden">
        <div class="flex flex-col h-full">
            <div class="px-4 pb-2 pt-4 space-y-1">
                <div class="px-4">
                    <div class="text-base font-semibold text-zinc-800 dark:text-zinc-200">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-base font-medium text-zinc-700 dark:text-zinc-300 tracking-wide">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
            <div class="px-4 pt-2 pb-3 space-y-1">
                @foreach ($_nav_links as $item)
                    <a href="{{ $item['href'] }}"
                        class="block px-4 py-2.5 text-base rounded-md hover:text-zinc-900 dark:hover:text-zinc-100 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 hover:dark:bg-zinc-800">
                        {{ $item['text'] }}
                    </a>
                @endforeach
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="px-4 cursor-pointer w-full block text-left py-2.5 text-base rounded-md hover:text-zinc-900 dark:hover:text-zinc-100 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 hover:dark:bg-zinc-800"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </button>
                </form>
                <hr class="dark:border-zinc-800 my-2">
                @foreach (['Resources', 'Channelog', 'Help', 'Documentation'] as $item)
                    <div
                        class="px-4 cursor-pointer py-2.5 text-base rounded-md hover:text-zinc-900 dark:hover:text-zinc-100 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 hover:dark:bg-zinc-800">
                        {{ $item }}
                    </div>
                @endforeach
                <hr class="dark:border-zinc-800 my-2">
            </div>
            <div class="flex-1 px-4 pb-4 flex flex-col items-end">
                <div class="w-full mt-auto space-y-2.5">
                    <button
                        class="px-8 w-full py-2 h-10 bg-black text-white text-sm rounded-md font-medium dark:bg-zinc-100 dark:text-zinc-900">
                        Upgrade to Pro
                    </button>
                    <button
                        class="px-8 text-zinc-700 border dark:border-zinc-700/75 dark:text-zinc-100 w-full py-2 h-10 text-sm rounded-md font-medium">
                        Contact
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
