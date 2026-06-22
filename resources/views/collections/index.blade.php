@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto min-h-screen">
    
    <div class="text-center mb-16">
        <h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg font-bold uppercase tracking-tight text-on-background">Curated Collections</h1>
        <p class="font-body-md text-body-lg text-on-surface-variant mt-4 max-w-2xl mx-auto">Explore our themed drops and seasonal archives. Each collection is engineered with precision for specific urban environments.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
        @forelse($collections as $collection)
        <a href="{{ route('products.index') }}" class="group relative block overflow-hidden rounded-xl {{ $loop->iteration % 3 == 0 ? 'md:col-span-2 h-[300px] md:h-[400px]' : 'h-[400px] md:h-[500px] lg:h-[600px]' }} border border-outline-variant/30 hover:border-primary transition-colors duration-500">
            <!-- Background Image -->
            <img src="{{ $collection->image_url ?? 'https://images.unsplash.com/photo-1618331835717-801e976710b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $collection->name }}" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-80 group-hover:scale-105 transition-transform duration-700 ease-out">
            
            <div class="absolute inset-0 {{ $loop->iteration % 3 == 0 ? 'bg-gradient-to-r from-background/90 via-background/60 to-transparent' : 'bg-gradient-to-t from-background via-background/20 to-transparent' }}"></div>
            
            <div class="absolute {{ $loop->iteration % 3 == 0 ? 'top-0 left-0 h-full justify-center' : 'bottom-0 left-0 w-full justify-end' }} p-8 md:p-12 flex flex-col">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="font-label-bold text-label-sm {{ $collection->color_theme === 'error' ? 'text-error border-error/50' : 'text-primary border-primary/50' }} border px-3 py-1 rounded-sm uppercase tracking-widest">{{ $collection->label }}</span>
                </div>
                <h2 class="font-display-lg text-headline-lg font-bold text-on-background uppercase tracking-tight mb-2">{{ $collection->name }}</h2>
                
                @if($loop->iteration % 3 == 0)
                <p class="font-body-md text-on-surface-variant max-w-lg mt-2">
                    {{ $collection->description }}
                </p>
                <div class="mt-8">
                    <button class="px-6 py-3 border border-outline-variant text-on-background font-label-bold text-label-sm uppercase tracking-widest hover:border-primary transition-colors rounded">
                        Explore Collection
                    </button>
                </div>
                @else
                <p class="font-body-md text-on-surface-variant max-w-sm mt-2 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500">
                    {{ $collection->description }}
                </p>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-24 border border-outline-variant/30 rounded-lg bg-surface-variant/20">
            <p class="text-on-surface-variant font-label-bold uppercase tracking-widest">No collections currently active.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
