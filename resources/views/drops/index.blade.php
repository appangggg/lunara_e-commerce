@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto min-h-screen">
    
    <div class="text-center mb-16">
        <h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg font-bold uppercase tracking-tight text-on-background">Exclusive Drops</h1>
        <p class="font-body-md text-body-lg text-on-surface-variant mt-4 max-w-2xl mx-auto">Extremely limited runs. Prototype techwear pieces. When they are gone, they are archived forever.</p>
    </div>

    <!-- Featured Drop -->
    <div class="max-w-4xl mx-auto mb-16">
        @forelse($drops as $drop)
            @if($drop->status === 'live')
            <!-- Active Drop -->
            <div class="bg-surface-variant border border-primary/50 flex flex-col md:flex-row shadow-[0_0_30px_rgba(0,255,163,0.1)] mb-8">
                <div class="p-8 md:w-1/2 flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
                    <span class="font-label-bold text-label-sm uppercase tracking-widest text-primary mb-4 flex items-center">
                        <span class="w-2 h-2 bg-primary animate-pulse mr-2 rounded-full"></span> {{ $drop->label ?? 'LIVE NOW' }}
                    </span>
                    <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background mb-4">{{ $drop->name }}</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-8">{{ $drop->description }}</p>
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('cart.add-drop') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="drop_id" value="{{ $drop->id }}">
                            <button type="submit" class="bg-primary text-on-primary font-label-bold text-label-sm uppercase tracking-widest px-6 py-3 hover:bg-primary/90 transition-colors">
                                Acquire Now — {{ $drop->price_label }}
                            </button>
                        </form>
                        <span class="font-label-bold text-label-sm uppercase tracking-widest text-error">{{ $drop->units_left }} Units Left</span>
                    </div>
                </div>
                <div class="md:w-1/2 bg-outline-variant/20 min-h-[300px]">
                    @if($drop->image_url)
                        <img src="{{ $drop->image_url }}" alt="{{ $drop->name }}" class="w-full h-full object-cover mix-blend-multiply">
                    @endif
                </div>
            </div>
            @endif
        @empty
        @endforelse
    </div>

    <!-- Upcoming Logistics -->
    <div class="max-w-4xl mx-auto">
        <h3 class="font-display-lg text-title-lg font-bold uppercase tracking-tight text-on-background mb-6 flex items-center">
            <span class="material-symbols-outlined mr-2">calendar_month</span> Upcoming Logistics
        </h3>
        <div class="space-y-4">
            @forelse($drops as $drop)
                @if($drop->status === 'upcoming')
                <div class="bg-surface border border-outline-variant/30 p-6 flex items-center justify-between hover:border-primary/50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-24 font-mono font-bold text-lg text-on-surface-variant">{{ $drop->date_label }}</div>
                        <div>
                            <h4 class="font-label-bold text-label-md uppercase tracking-widest text-on-background">{{ $drop->name }}</h4>
                            <p class="text-sm text-on-surface-variant">{{ $drop->description }}</p>
                        </div>
                    </div>
                    <button class="border border-outline-variant px-4 py-2 text-[10px] font-label-bold uppercase tracking-widest hover:bg-surface-variant transition-colors" onclick="alert('Notification set for {{ addslashes($drop->name) }}. You will be alerted before it drops.')">
                        Remind Me
                    </button>
                </div>
                @endif
            @empty
                <div class="p-8 text-center text-on-surface-variant italic">No upcoming drops scheduled.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
