<!-- Cart Drawer Overlay -->
<div id="cart-drawer" class="fixed inset-0 z-[100] transform translate-x-full transition-transform duration-500 ease-in-out">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm cursor-pointer" onclick="document.getElementById('cart-drawer').classList.add('translate-x-full')"></div>
    
    <!-- Drawer Panel -->
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-surface-container border-l border-outline-variant/30 flex flex-col shadow-2xl">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background flex items-center">
                <span class="material-symbols-outlined mr-2">shopping_cart</span>
                Arsenal Cart
            </h2>
            <button class="text-on-surface-variant hover:text-primary transition-colors" onclick="document.getElementById('cart-drawer').classList.add('translate-x-full')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        @php
            $cart = session('cart', []);
            $totalPrice = 0;
            foreach($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
        @endphp

        <!-- Cart Items List -->
        <div class="flex-grow overflow-y-auto p-6 space-y-6">
            @if(count($cart) > 0)
                @foreach($cart as $id => $item)
                    <!-- Cart Item -->
                    <div class="flex gap-4 group">
                        <div class="w-24 h-32 bg-surface-variant rounded overflow-hidden">
                            @if($item['image_url'])
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover mix-blend-multiply opacity-80 group-hover:opacity-100 transition-opacity">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-on-surface-variant">image</span></div>
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between py-1">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="font-label-bold text-label-bold text-on-background uppercase pr-4">{{ $item['name'] }}</h3>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="submit" class="text-on-surface-variant hover:text-error transition-colors"><span class="material-symbols-outlined text-sm">delete</span></button>
                                    </form>
                                </div>
                                <p class="font-body-md text-[12px] text-on-surface-variant mt-1 uppercase tracking-wider">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="flex justify-between items-end">
                                <span class="font-mono text-sm text-primary font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty Cart State -->
                <div class="h-full flex flex-col items-center justify-center text-center space-y-4 opacity-50">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant">shopping_cart</span>
                    <p class="font-label-bold text-label-bold uppercase tracking-widest text-on-surface-variant">Your cart is empty</p>
                </div>
            @endif
        </div>

        <!-- Cart Footer -->
        <div class="border-t border-outline-variant/30 bg-surface-container-low p-6 space-y-6">
            <div class="flex justify-between items-center text-on-background">
                <span class="font-label-bold text-label-bold uppercase tracking-widest">Subtotal</span>
                <span class="font-display-lg text-headline-sm font-bold text-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
            <p class="font-body-md text-[10px] text-on-surface-variant uppercase tracking-wider text-center">Shipping and taxes calculated at checkout.</p>
            
            <form action="{{ route('checkout.init') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_checkout" value="1">
                <button type="submit" class="w-full py-4 {{ count($cart) > 0 ? 'bg-primary text-on-primary hover:bg-opacity-90 glow-effect' : 'bg-surface-variant text-on-surface-variant cursor-not-allowed' }} font-label-bold text-label-bold uppercase tracking-widest transition-colors rounded" {{ count($cart) == 0 ? 'disabled' : '' }}>
                    Proceed to Checkout
                </button>
            </form>
        </div>
    </div>
</div>
