<!-- Search Drawer Overlay -->
<div id="search-drawer" class="fixed inset-0 z-[100] transform translate-x-full transition-transform duration-500 ease-in-out">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm cursor-pointer" onclick="document.getElementById('search-drawer').classList.add('translate-x-full')"></div>
    
    <!-- Drawer Panel -->
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-surface-container border-l border-outline-variant/30 flex flex-col shadow-2xl">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background flex items-center">
                <span class="material-symbols-outlined mr-2">search</span>
                Search System
            </h2>
            <button class="text-on-surface-variant hover:text-primary transition-colors" onclick="document.getElementById('search-drawer').classList.add('translate-x-full')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="flex-grow overflow-y-auto p-6">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col space-y-6">
                <div>
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" placeholder="Enter query..." class="w-full bg-surface-variant text-on-surface-variant border border-outline-variant rounded p-4 font-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" value="{{ request('search') }}">
                    </div>
                </div>
                
                <p class="font-body-md text-label-sm text-on-surface-variant">Search through our complete arsenal of technical garments, footwear, and accessories.</p>
                
                <button type="submit" class="w-full py-4 bg-primary text-on-primary font-label-bold text-label-bold uppercase tracking-widest hover:bg-opacity-90 glow-effect transition-colors rounded">
                    Execute Search
                </button>
            </form>
        </div>
    </div>
</div>
