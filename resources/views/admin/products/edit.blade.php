@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-2xl mx-auto min-h-screen">
    
    <div class="mb-8 border-b border-outline-variant/30 pb-4 flex items-center justify-between">
        <div>
            <h1 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">Modify Item Metrics</h1>
            <p class="font-body-md text-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Arsenal Inventory Management</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors flex items-center">
            <span class="material-symbols-outlined mr-2">arrow_back</span>
            <span class="font-label-bold text-[12px] uppercase tracking-widest">Abort</span>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg space-y-6">
            
            <!-- Basic Info -->
            <div>
                <label for="name" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Item Designation (Name)</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $product->name) }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                @error('name')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Price (Rp)</label>
                    <input type="number" id="price" name="price" required min="0" value="{{ old('price', $product->price) }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                    @error('price')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="stock" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Current Stock</label>
                    <input type="number" id="stock" name="stock" required min="0" value="{{ old('stock', $product->stock) }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                    @error('stock')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label for="image" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Internal Image Upload (Optional)</label>
                @if($product->image_url)
                    <div class="mb-4">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-widest mb-2">Current Asset:</p>
                        <img src="{{ $product->image_url }}" alt="Current Image" class="w-24 h-24 object-cover rounded border border-outline-variant/30 mix-blend-multiply bg-surface-variant">
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="w-full bg-surface border border-outline-variant rounded p-2 text-on-background focus:ring-primary focus:border-primary transition-colors file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-label-bold file:bg-surface-variant file:text-on-surface hover:file:bg-primary/20">
                <p class="text-[10px] text-on-surface-variant mt-1 uppercase tracking-widest">Max upload size: 2MB. Leave blank to keep current image.</p>
                @error('image')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="category" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Product Category</label>
                <select id="category" name="category" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                    <option value="clothing" {{ old('category', $product->category) === 'clothing' ? 'selected' : '' }}>Pakaian / Clothing (Sizes: S, M, L, XL)</option>
                    <option value="shoes" {{ old('category', $product->category) === 'shoes' ? 'selected' : '' }}>Sepatu / Shoes (Sizes: 32–44 EU)</option>
                    <option value="accessories" {{ old('category', $product->category) === 'accessories' ? 'selected' : '' }}>Aksesoris / Accessories (No size selector)</option>
                </select>
                @error('category')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="flex items-center space-x-3 p-4 bg-surface border border-outline-variant rounded-lg">
                <input type="checkbox" id="is_limited" name="is_limited" class="w-5 h-5 accent-primary bg-surface border-outline-variant rounded focus:ring-primary transition-colors" {{ old('is_limited', $product->is_limited) ? 'checked' : '' }}>
                <div>
                    <label for="is_limited" class="font-label-bold text-label-sm uppercase tracking-widest text-on-background cursor-pointer">Limited Edition Status</label>
                    <p class="text-[10px] text-on-surface-variant mt-1 uppercase tracking-widest">Check this box to flag the item as a limited edition drop.</p>
                </div>
            </div>

            <div>
                <label for="description" class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Technical Description</label>
                <textarea id="description" name="description" required rows="5" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">{{ old('description', $product->description) }}</textarea>
                @error('description')<span class="text-error text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>
            
        </div>

        <button type="submit" class="w-full py-4 bg-primary text-on-primary font-label-bold text-label-bold uppercase tracking-widest hover:bg-opacity-90 transition-colors glow-effect rounded">
            Overwrite Metrics
        </button>

    </form>
</div>
@endsection
