@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-label-sm font-label-bold uppercase text-on-surface-variant mb-8">
        <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Shop</a>
        <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
        <a href="#" class="hover:text-primary transition-colors">System.Arsenal</a>
        <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
        <span class="text-on-background">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24">
        
        <!-- Image Gallery -->
        <div class="space-y-4">
            <div class="relative bg-surface-container-low aspect-[4/5] rounded-lg overflow-hidden border border-outline-variant/30 group">
                @if($product->image_url)
                    <img class="w-full h-full object-cover mix-blend-multiply opacity-90 transition-transform duration-700 group-hover:scale-105" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-6xl">image</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-background/60 to-transparent pointer-events-none"></div>
                <div class="absolute top-4 left-4 z-10 flex gap-2">
                    @if($product->stock <= 0)
                        <span class="px-3 py-1 bg-error text-on-error font-label-bold text-[10px] uppercase tracking-wider rounded-sm">Sold Out</span>
                    @elseif($product->is_limited)
                        <span class="px-3 py-1 bg-primary text-on-primary font-label-bold text-[10px] uppercase tracking-wider rounded-sm">Limited Edition</span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-surface-container-low aspect-square rounded-lg border-2 border-primary overflow-hidden cursor-pointer">
                    @if($product->image_url)
                        <img class="w-full h-full object-cover mix-blend-multiply" src="{{ $product->image_url }}" alt="Thumbnail">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined">image</span></div>
                    @endif
                </div>
                <!-- Additional thumbnails can be loop rendered here -->
                @for ($i = 0; $i < 3; $i++)
                <div class="bg-surface-container-low aspect-square rounded-lg border border-outline-variant/30 overflow-hidden cursor-pointer hover:border-primary transition-colors">
                    <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined">image</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Product Details -->
        <div class="flex flex-col">
            <h1 class="font-display-lg text-headline-lg-mobile md:text-headline-lg font-bold uppercase tracking-tight text-on-background">{{ $product->name }}</h1>
            <p class="font-body-md text-body-lg text-on-surface-variant mt-4">{{ $product->description }}</p>
            
            <div class="mt-8 border-t border-b border-outline-variant/30 py-6 flex justify-between items-center">
                <span class="font-display-lg text-headline-md text-primary font-bold tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="font-body-md text-label-sm text-on-surface-variant uppercase">{{ $product->stock > 0 ? $product->stock . ' Units Available' : 'Out of Stock' }}</span>
            </div>

            @php $sizes = $product->getSizes(); @endphp

            {{-- Dynamic Size Selector --}}
            @if(count($sizes) > 0)
            <div class="mt-8" x-data="{ selected: '{{ $sizes[count($sizes) > 2 ? 1 : 0] }}' }">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-label-bold text-label-bold text-on-background uppercase tracking-widest">
                        {{ $product->category === 'shoes' ? 'Shoe Size (EU)' : 'Size' }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach($sizes as $size)
                    <button
                        type="button"
                        onclick="selectSize('{{ $size }}')"
                        id="size-btn-{{ $size }}"
                        class="size-btn py-2.5 px-4 border border-outline-variant/50 text-on-surface-variant hover:border-primary hover:text-primary font-label-bold text-label-bold uppercase transition-colors rounded">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" id="selected_size_input" value="{{ $sizes[count($sizes) > 2 ? 1 : 0] }}">
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 flex flex-col space-y-4">
                @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-primary bg-primary/10 border border-primary rounded-lg flex items-center" role="alert">
                        <span class="material-symbols-outlined mr-2">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-error bg-error-container rounded-lg" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('checkout.init') }}" method="POST" id="form-checkout">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="selected_size" id="size_checkout" value="">
                    <button type="submit" @if($product->stock <= 0) disabled @endif class="w-full py-4 {{ $product->stock <= 0 ? 'bg-surface-variant text-on-surface-variant cursor-not-allowed' : 'bg-primary text-on-primary hover:bg-opacity-90 glow-effect' }} font-label-bold text-label-bold uppercase tracking-widest transition-colors rounded">
                        {{ $product->stock <= 0 ? 'Sold Out' : 'Direct Checkout' }}
                    </button>
                </form>
                <form action="{{ route('cart.add') }}" method="POST" class="mt-2" id="form-cart">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="selected_size" id="size_cart" value="">
                    <button type="submit" @if($product->stock <= 0) disabled @endif class="w-full py-4 border border-outline-variant {{ $product->stock <= 0 ? 'text-on-surface-variant cursor-not-allowed' : 'text-on-background hover:border-primary' }} font-label-bold text-label-bold uppercase tracking-widest transition-colors rounded">
                        Add to Cart
                    </button>
                </form>
                <div class="flex items-center justify-center space-x-2 text-on-surface-variant text-label-sm font-body-md mt-4">
                    <span class="material-symbols-outlined text-[16px]" data-icon="local_shipping">local_shipping</span>
                    <span>Free global shipping on orders over Rp 2.000.000</span>
                </div>
            </div>

            <!-- Details Accordion -->
            <div class="mt-12 space-y-4">
                <details class="group border-b border-outline-variant/30 pb-4" open>
                    <summary class="flex justify-between items-center cursor-pointer list-none font-label-bold text-label-bold uppercase tracking-widest text-on-background">
                        <span>Specifications</span>
                        <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <div class="mt-4 font-body-md text-body-md text-on-surface-variant space-y-2">
                        <ul class="list-disc list-inside space-y-1">
                            <li>14oz Japanese Selvedge Denim</li>
                            <li>DWR (Durable Water Repellent) coating</li>
                            <li>Articulated knee darts for mobility</li>
                            <li>Matte black tactical hardware</li>
                            <li>Hidden utility pockets</li>
                        </ul>
                    </div>
                </details>
                <details class="group border-b border-outline-variant/30 pb-4">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-label-bold text-label-bold uppercase tracking-widest text-on-background">
                        <span>Shipping & Returns</span>
                        <span class="material-symbols-outlined transform group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <div class="mt-4 font-body-md text-body-md text-on-surface-variant">
                        <p>Orders are dispatched within 24 hours. Express shipping available. Returns accepted within 14 days of delivery provided the item is in its original condition with all tags attached.</p>
                    </div>
                </details>
            </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sync initial selected size into hidden form inputs
        const sizeInput = document.getElementById('selected_size_input');
        if (sizeInput && sizeInput.value) {
            const el = document.getElementById('size_checkout');
            const el2 = document.getElementById('size_cart');
            if (el) el.value = sizeInput.value;
            if (el2) el2.value = sizeInput.value;

            // Visually mark the default selected button
            const defaultBtn = document.getElementById('size-btn-' + sizeInput.value);
            if (defaultBtn) markActive(defaultBtn);
        }
    });

    function markActive(btn) {
        document.querySelectorAll('.size-btn').forEach(b => {
            b.style.border = '';
            b.style.color = '';
            b.style.background = '';
            b.style.fontWeight = '';
        });
        btn.style.border = '2px solid var(--color-primary, #00796B)';
        btn.style.color = 'var(--color-primary, #00796B)';
        btn.style.background = 'rgba(0, 121, 107, 0.08)';
        btn.style.fontWeight = '700';
    }

    function selectSize(size) {
        // Update hidden inputs
        const el  = document.getElementById('size_checkout');
        const el2 = document.getElementById('size_cart');
        if (el)  el.value  = size;
        if (el2) el2.value = size;

        // Update sizeInput tracker
        const sizeInput = document.getElementById('selected_size_input');
        if (sizeInput) sizeInput.value = size;

        // Highlight active button
        const activeBtn = document.getElementById('size-btn-' + size);
        if (activeBtn) markActive(activeBtn);
    }
</script>
@endpush
@endsection
