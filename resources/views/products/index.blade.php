@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative w-full h-[80vh] min-h-[600px] flex items-center justify-center overflow-hidden border-b border-outline-variant/30">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <div class="bg-cover bg-center w-full h-full opacity-30 mix-blend-multiply" style="background-image: url('{{ $settings['hero_bg_image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzbucB7MEK_ZRwIMMcIi-r2TEJH7u69T3cNVqSKbd1Tr192UKAySJojSMh-JKCIfNNKD5u80RrdBVqiccBNPBjw6RMvjme4WGQguxMmXMflmx_xbj-5XmdoRkMwsia5R3xzcO8HbnTMqtP6P4uniaZz6xzVp0NmCKWOI0SZN7Bqmv6DKs4qwiFSrs9CdXpsiMp2f--q41BwwmBGSdUvDwEQn3b-z6mSUD1eFVP9ey8Wy61wQtOR1SrVsSDv_GO_66Y1l-zY148EK4' }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
    </div>
    <div class="relative z-10 flex flex-col items-center text-center px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto space-y-8">
        <div class="space-y-4">
            <span class="inline-block px-3 py-1 bg-primary-container text-on-primary-container font-label-bold text-label-bold uppercase tracking-widest border border-primary">{{ $settings['hero_badge'] ?? 'Protocol Active' }}</span>
            <h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg font-bold tracking-tighter text-on-background uppercase drop-shadow-sm">{{ $settings['hero_title'] ?? 'LATEST DROP' }}</h1>
            <p class="font-body-md text-body-lg text-on-surface-variant max-w-2xl mx-auto">{{ $settings['hero_desc'] ?? 'Engineered for the nocturnal. Technical garments built with uncompromising precision. Limited quantities available.' }}</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 mt-12 w-full sm:w-auto">
            <button class="px-8 py-4 bg-primary text-on-primary font-label-bold text-label-bold uppercase tracking-wider hover:bg-opacity-90 transition-colors glow-effect w-full sm:w-auto rounded" onclick="document.getElementById('products-grid').scrollIntoView({behavior: 'smooth'})">
                Explore Collection
            </button>
        </div>
    </div>
</section>

<!-- Product Grid Section -->
<section id="products-grid" class="py-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
    <div class="flex justify-between items-end mb-12 border-b border-outline-variant/30 pb-4">
        <div>
            <h2 class="font-display-lg text-headline-md text-on-background uppercase font-bold tracking-tight">System.Arsenal</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2">Core technical pieces. Restocked for immediate deployment.</p>
        </div>
    </div>

    <!-- Bento Grid Layout for Products -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
        @forelse($products as $index => $product)
            @php
                // Make the first product span 8 columns, rest span 4
                $colSpan = ($index === 0) ? 'md:col-span-8' : 'md:col-span-4';
                $imageHeight = ($index === 0) ? 'h-[400px] md:h-[600px]' : 'h-[400px] aspect-square md:aspect-auto md:h-[600px]';
            @endphp
            <div class="{{ $colSpan }} group cursor-pointer relative bg-surface-container-low overflow-hidden {{ $imageHeight }} rounded-lg border border-outline-variant/20 shadow-sm" onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                @if($product->image_url)
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100 mix-blend-multiply" src="{{ $product->image_url }}" alt="{{ $product->name }}"/>
                @else
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-surface-variant text-on-surface-variant">
                        <span class="material-symbols-outlined text-6xl">image</span>
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-gradient-to-t from-background/95 via-background/40 to-transparent"></div>
                
                <div class="absolute top-4 left-4 z-10 flex gap-2">
                    @if($product->stock <= 0)
                        <span class="px-2 py-1 bg-error text-on-error font-label-bold text-[10px] uppercase tracking-wider rounded-sm">Sold Out</span>
                    @elseif($index === 0)
                        <span class="px-2 py-1 bg-primary text-on-primary font-label-bold text-[10px] uppercase tracking-wider rounded-sm">Limited Edition</span>
                    @else
                        <span class="px-2 py-1 bg-surface-variant text-on-surface-variant font-label-bold text-[10px] uppercase tracking-wider border border-outline-variant rounded-sm">Core</span>
                    @endif
                </div>

                <div class="absolute bottom-6 left-6 right-6 z-10 {{ $index === 0 ? 'flex justify-between items-end' : '' }}">
                    <div>
                        <h3 class="font-display-lg {{ $index === 0 ? 'text-headline-md' : 'text-body-lg' }} text-on-background font-bold uppercase tracking-tight {{ $product->stock <= 0 ? 'line-through decoration-error text-on-surface-variant' : '' }}">{{ $product->name }}</h3>
                        @if($index === 0)
                            <p class="font-body-md text-body-md text-on-surface-variant mt-1">{{ Str::limit($product->description, 50) }}</p>
                        @endif
                    </div>
                    
                    <div class="{{ $index === 0 ? 'text-right' : 'flex justify-between items-center mt-2' }}">
                        @if($index !== 0)
                            <p class="font-body-md text-label-sm text-on-surface-variant">{{ Str::limit($product->description, 20) }}</p>
                        @endif
                        <span class="block font-label-bold text-label-bold {{ $product->stock <= 0 ? 'text-on-surface-variant' : ($index === 0 ? 'text-primary' : 'text-on-background') }}">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Hover Overlay -->
                <div class="absolute inset-0 {{ $index === 0 ? 'bg-primary/5' : 'border-2 border-transparent group-hover:border-primary' }} opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none rounded-lg"></div>
            </div>
        @empty
            <div class="col-span-12 py-20 text-center border border-outline-variant border-dashed rounded-lg">
                <span class="material-symbols-outlined text-4xl text-outline-variant mb-4" data-icon="inventory_2">inventory_2</span>
                <h3 class="font-display-lg text-headline-md text-on-background uppercase font-bold tracking-tight">Systems Offline</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">No products available at the moment. Stand by for deployment.</p>
            </div>
        @endforelse

        <!-- Information Panel -->
        <div class="md:col-span-4 bg-surface-container-low border border-outline-variant/30 p-8 flex flex-col justify-center h-[400px] md:h-auto rounded-lg shadow-sm">
            <div class="max-w-md">
            <span class="material-symbols-outlined text-primary mb-6 text-3xl">deployed_code</span>
            <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background mb-4">
                {{ $settings['manifesto_title'] ?? 'UNCOMPROMISING UTILITY' }}
            </h2>
            <p class="font-body-md text-body-lg text-on-surface-variant mb-8 leading-relaxed">
                {{ $settings['manifesto_desc'] ?? 'Every piece is engineered with precision. We source military-grade textiles and combine them with brutalist silhouettes for a uniform built for the modern urban landscape.' }}
            </p>
            <a href="#" class="inline-flex items-center group border-2 border-on-background px-6 py-3 font-label-bold text-label-md uppercase tracking-widest text-primary hover:bg-on-background hover:text-surface transition-all" onclick="alert('The full LUNARA manifesto is currently classified and will be unsealed in the next phase.')">
                Read Manifesto 
                <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
        </div>
    </div>
</section>
@endsection
