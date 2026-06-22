<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LUNARA') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased overflow-x-hidden min-h-screen flex flex-col">

    <!-- Header / TopNavBar -->
    <header class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md border-b border-outline-variant/30">
        <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop h-20 max-w-container-max mx-auto">
            <!-- Brand -->
            <a href="{{ route('products.index') }}" class="flex items-center space-x-4">
                <img alt="LUNARA Logo" class="h-10 w-10 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwYITsd5-91oY-_3dRCrAzTDelvu9ajA9LpzH5dFdDPjrAZAVI0lVzXFyfmz_53fZr9b7wWL49IcWlywffzlmHzDLQmoSfqlN2b8enR0JzJ5R9dhngv5A7zL0l5zYtNm-eFtRvLicaPbkiStx_ziBZaFX6WAGm6jAXBRoOi6Uf1Wgl8FQQpfxkg_Eg2rj1xO-aaR8SFQ_Vj-A4kvxuWYqg_mmB9UOc8HmHowpa9G1hIvFYzUXDcoeE8jnP5tTELTIavDJJhyipouM"/>
                <span class="font-display-lg text-headline-md font-bold tracking-tighter text-on-background uppercase">LUNARA</span>
            </a>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex space-x-8">
                <a class="{{ request()->routeIs('products.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-on-surface hover:text-primary transition-all duration-300' }} font-label-bold text-label-bold" href="{{ route('products.index') }}">Shop</a>
                <a class="{{ request()->routeIs('collections.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-on-surface hover:text-primary transition-all duration-300' }} font-label-bold text-label-bold" href="{{ route('collections.index') }}">Collections</a>
                <a class="{{ request()->routeIs('drops.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-on-surface hover:text-primary transition-all duration-300' }} font-label-bold text-label-bold" href="{{ route('drops.index') }}">Drops</a>
            </nav>

            <!-- Trailing Icons -->
            <div class="flex items-center space-x-6 text-primary">
                <button aria-label="Search" class="hover:text-primary transition-all duration-300" onclick="alert('Search module is currently offline for maintenance. Browse Collections or Drops instead.')">
                    <span class="material-symbols-outlined" data-icon="search">search</span>
                </button>
                <button aria-label="shopping_cart" class="hover:text-primary transition-all duration-300" onclick="document.getElementById('cart-drawer').classList.toggle('translate-x-full')">
                    <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                </button>
                
                @auth
                    <div class="relative group">
                        <button aria-label="person" class="hover:text-primary transition-all duration-300 flex items-center h-full py-2">
                            <span class="material-symbols-outlined" data-icon="person">person</span>
                        </button>
                        <!-- Dropdown Wrapper with top padding to bridge the hover gap -->
                        <div class="absolute right-0 top-full w-48 hidden group-hover:block z-50 pt-2">
                            <div class="bg-surface border border-outline-variant/50 rounded-md shadow-lg py-1">
                                <div class="px-4 py-2 border-b border-outline-variant/30">
                                    <p class="text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                                </div>
                                <a href="{{ route('user.orders') }}" class="block px-4 py-2 text-sm text-on-surface-variant hover:bg-surface-variant hover:text-on-surface border-b border-outline-variant/30">My Orders</a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-primary hover:bg-surface-variant hover:text-primary">Admin Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-error hover:bg-error-container hover:text-on-error-container">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" aria-label="login" class="hover:text-primary transition-all duration-300">
                        <span class="material-symbols-outlined" data-icon="login">login</span>
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button class="md:hidden text-on-background hover:text-primary transition-colors">
                    <span class="material-symbols-outlined" data-icon="menu">menu</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-surface-container border-t border-outline-variant/30 mt-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-mobile md:px-margin-desktop py-16 max-w-container-max mx-auto">
            <!-- Brand / Legal -->
            <div class="flex flex-col space-y-4 md:col-span-2 lg:col-span-1">
                <span class="font-display-lg text-headline-md text-on-background font-bold uppercase">LUNARA</span>
                <p class="font-body-md text-label-sm text-on-surface-variant max-w-xs">Engineered for the nocturnal. Precision streetwear.</p>
                <p class="font-body-md text-label-sm text-on-surface-variant mt-auto pt-8">
                    &copy; {{ date('Y') }} LUNARA. ALL RIGHTS RESERVED. NOCTURNAL PRECISION&trade;
                </p>
            </div>
            <!-- Empty space for layout balance on large screens -->
            <div class="hidden lg:block lg:col-span-1"></div>
            <!-- Links -->
            <div class="flex flex-col space-y-4">
                <h4 class="font-label-bold text-label-bold text-on-background uppercase tracking-wider mb-2">Protocol</h4>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Terms & Conditions document is being finalized.')">Terms</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Privacy Policy is being updated.')">Privacy</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Sustainability Report is currently offline.')">Sustainability</a>
            </div>
            <div class="flex flex-col space-y-4">
                <h4 class="font-label-bold text-label-bold text-on-background uppercase tracking-wider mb-2">Support</h4>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Shipping Policy document is being finalized.')">Shipping</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Returns Policy is being finalized.')">Returns</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors w-max" href="#" onclick="alert('Support channel is currently offline.')">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Include Cart Drawer -->
    @include('components.cart-drawer')

</body>
</html>
