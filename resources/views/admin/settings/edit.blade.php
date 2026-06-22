@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background">
            Edit Site Variables
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-label-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors flex items-center">
            <span class="material-symbols-outlined mr-2">arrow_back</span> Return to Dashboard
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg space-y-6">
            <div>
                <label for="manifesto_title" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Manifesto Title</label>
                <input type="text" id="manifesto_title" name="manifesto_title" required value="{{ old('manifesto_title', $settings['manifesto_title'] ?? 'UNCOMPROMISING UTILITY') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                @error('manifesto_title')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="manifesto_desc" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Manifesto Description</label>
                <textarea id="manifesto_desc" name="manifesto_desc" rows="4" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">{{ old('manifesto_desc', $settings['manifesto_desc'] ?? 'Every piece is engineered with precision...') }}</textarea>
                @error('manifesto_desc')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="border-t border-outline-variant/30 my-6 pt-6">
                <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-primary mb-4">Hero Banner Settings</h2>
                
                <div class="space-y-6">
                    <div>
                        <label for="hero_badge" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Hero Badge</label>
                        <input type="text" id="hero_badge" name="hero_badge" required value="{{ old('hero_badge', $settings['hero_badge'] ?? 'Protocol Active') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        @error('hero_badge')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="hero_title" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Hero Title</label>
                        <input type="text" id="hero_title" name="hero_title" required value="{{ old('hero_title', $settings['hero_title'] ?? 'LATEST DROP') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        @error('hero_title')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="hero_desc" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Hero Description</label>
                        <textarea id="hero_desc" name="hero_desc" rows="3" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">{{ old('hero_desc', $settings['hero_desc'] ?? 'Engineered for the nocturnal. Technical garments built with uncompromising precision. Limited quantities available.') }}</textarea>
                        @error('hero_desc')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="hero_bg_image" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Background Image URL</label>
                        <input type="url" id="hero_bg_image" name="hero_bg_image" required value="{{ old('hero_bg_image', $settings['hero_bg_image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzbucB7MEK_ZRwIMMcIi-r2TEJH7u69T3cNVqSKbd1Tr192UKAySJojSMh-JKCIfNNKD5u80RrdBVqiccBNPBjw6RMvjme4WGQguxMmXMflmx_xbj-5XmdoRkMwsia5R3xzcO8HbnTMqtP6P4uniaZz6xzVp0NmCKWOI0SZN7Bqmv6DKs4qwiFSrs9CdXpsiMp2f--q41BwwmBGSdUvDwEQn3b-z6mSUD1eFVP9ey8Wy61wQtOR1SrVsSDv_GO_66Y1l-zY148EK4') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        @error('hero_bg_image')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                        <p class="text-xs text-on-surface-variant mt-2">Provide a direct URL to the image.</p>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-t border-outline-variant/30">
                <button type="submit" class="w-full py-4 bg-primary text-on-primary font-label-bold text-label-md uppercase tracking-widest hover:bg-primary/90 transition-colors rounded">
                    Update Variables
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
