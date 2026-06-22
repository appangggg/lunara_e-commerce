@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background">
            Create Collection
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-label-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors flex items-center">
            <span class="material-symbols-outlined mr-2">arrow_back</span> Return to Dashboard
        </a>
    </div>

    <form method="POST" action="{{ route('admin.collections.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg space-y-6">
            <div>
                <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Collection Name</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background">
            </div>
            <div>
                <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Label (e.g. ACTIVE SERIES)</label>
                <input type="text" name="label" required value="{{ old('label') }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background">
            </div>
            <div>
                <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Description</label>
                <textarea name="description" rows="3" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Cover Image (Local)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-surface border border-outline-variant rounded p-2 text-on-background file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-label-bold file:bg-surface-variant file:text-on-surface hover:file:bg-primary/20">
            </div>
            <div class="pt-4 border-t border-outline-variant/30">
                <button type="submit" class="w-full py-4 bg-primary text-on-primary font-label-bold text-label-md uppercase tracking-widest rounded">
                    Execute
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
