<!-- Mobile Menu Drawer Overlay -->
<div id="mobile-menu" class="fixed inset-0 z-[100] transform translate-x-full transition-transform duration-500 ease-in-out md:hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm cursor-pointer" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')"></div>
    
    <!-- Drawer Panel -->
    <div class="absolute right-0 top-0 h-full w-full max-w-sm bg-surface-container border-l border-outline-variant/30 flex flex-col shadow-2xl">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background flex items-center">
                <span class="material-symbols-outlined mr-2">menu</span>
                Navigation
            </h2>
            <button class="text-on-surface-variant hover:text-primary transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="flex-grow flex flex-col p-8 space-y-8 overflow-y-auto">
            <a href="{{ route('products.index') }}" class="font-display-lg text-headline-md uppercase font-bold tracking-tight hover:text-primary transition-colors {{ request()->routeIs('products.*') ? 'text-primary' : 'text-on-background' }}">
                Shop
            </a>
            <a href="{{ route('collections.index') }}" class="font-display-lg text-headline-md uppercase font-bold tracking-tight hover:text-primary transition-colors {{ request()->routeIs('collections.*') ? 'text-primary' : 'text-on-background' }}">
                Collections
            </a>
            <a href="{{ route('drops.index') }}" class="font-display-lg text-headline-md uppercase font-bold tracking-tight hover:text-primary transition-colors {{ request()->routeIs('drops.*') ? 'text-primary' : 'text-on-background' }}">
                Drops
            </a>
            
            <div class="pt-8 border-t border-outline-variant/30 mt-auto">
                @auth
                    <a href="{{ route('user.orders') }}" class="flex items-center space-x-4 mb-6 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">local_shipping</span>
                        <span class="font-label-bold text-label-lg uppercase tracking-widest">My Orders</span>
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-4 mb-6 text-primary hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined">admin_panel_settings</span>
                            <span class="font-label-bold text-label-lg uppercase tracking-widest">Admin Dashboard</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center space-x-4 text-error hover:text-error/80 transition-colors">
                            <span class="material-symbols-outlined">logout</span>
                            <span class="font-label-bold text-label-lg uppercase tracking-widest">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center space-x-4 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">login</span>
                        <span class="font-label-bold text-label-lg uppercase tracking-widest">Login / Register</span>
                    </a>
                @endauth
            </div>
        </nav>
    </div>
</div>
