@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto min-h-screen">
    <div class="flex items-center justify-between mb-8 border-b border-outline-variant/30 pb-4">
        <h1 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">System.Admin // Dashboard</h1>
        <div class="px-3 py-1 bg-primary/20 text-primary border border-primary font-label-bold text-[10px] uppercase tracking-wider rounded-sm flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            <span>Live Feed</span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 p-4 bg-primary/10 border border-primary text-primary font-label-bold text-sm uppercase tracking-widest rounded-lg flex items-center">
        <span class="material-symbols-outlined mr-3">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg">
            <p class="font-label-bold text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Total Revenue</p>
            <h3 class="font-display-lg text-headline-lg font-bold text-primary">Rp {{ number_format($orders->where('status', '!=', 'expired')->sum('total_price'), 0, ',', '.') }}</h3>
        </div>
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg">
            <p class="font-label-bold text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Total Orders</p>
            <h3 class="font-display-lg text-headline-lg font-bold text-on-background">{{ $orders->count() }}</h3>
        </div>
        <div class="bg-surface-container-low border border-outline-variant/30 p-6 rounded-lg">
            <p class="font-label-bold text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Active Inventory</p>
            <h3 class="font-display-lg text-headline-lg font-bold text-on-background">{{ $products->count() }} <span class="text-label-sm font-body-md text-on-surface-variant">Items</span></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
        
        <!-- Left Column -->
        <div class="flex flex-col space-y-12">
            <!-- Order Manifest -->
            <section>
                <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background mb-6 flex items-center">
                    <span class="material-symbols-outlined mr-2">receipt_long</span> Order Manifest
                </h2>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-variant text-on-surface-variant font-label-bold text-label-sm uppercase tracking-widest">
                                    <th class="p-4 border-b border-outline-variant/30">Order ID</th>
                                    <th class="p-4 border-b border-outline-variant/30">Customer</th>
                                    <th class="p-4 border-b border-outline-variant/30">Amount</th>
                                    <th class="p-4 border-b border-outline-variant/30">Status</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                @forelse($orders as $order)
                                <tr class="border-b border-outline-variant/10 hover:bg-surface-variant/30 transition-colors">
                                    <td class="p-4 text-on-background font-mono text-sm">{{ $order->order_number }}</td>
                                    <td class="p-4 text-on-surface-variant">
                                        <p class="font-bold text-on-background">{{ $order->customer_name }}</p>
                                        <p class="text-[12px] truncate max-w-[150px]">{{ $order->customer_email }}</p>
                                    </td>
                                    <td class="p-4 text-primary font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @if($order->status === 'paid')
                                            <span class="px-2 py-1 bg-primary/20 text-primary rounded text-[10px] uppercase font-bold tracking-wider">Paid</span>
                                        @elseif($order->status === 'pending')
                                            <span class="px-2 py-1 bg-warning/20 text-warning rounded text-[10px] uppercase font-bold tracking-wider text-yellow-500">Pending</span>
                                        @else
                                            <span class="px-2 py-1 bg-error/20 text-error rounded text-[10px] uppercase font-bold tracking-wider text-red-500">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-on-surface-variant font-label-bold uppercase">No orders recorded in system.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Curated Collections -->
            <section>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
                        <h2 class="font-label-bold text-label-md uppercase tracking-widest text-primary">Curated Collections</h2>
                        <a href="{{ route('admin.collections.create') }}" class="px-4 py-2 border border-outline-variant text-on-background hover:border-primary hover:text-primary transition-colors rounded text-[10px] font-label-bold uppercase tracking-widest flex items-center">
                            <span class="material-symbols-outlined text-[14px] mr-1">add</span> Create New
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-variant text-on-surface-variant font-label-bold text-label-sm uppercase tracking-widest">
                                    <th class="p-4 border-b border-outline-variant/30">Name</th>
                                    <th class="p-4 border-b border-outline-variant/30">Label</th>
                                    <th class="p-4 border-b border-outline-variant/30 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                @forelse($collections as $collection)
                                <tr class="border-b border-outline-variant/10 hover:bg-surface-variant/20 transition-colors">
                                    <td class="p-4 font-label-bold text-on-background">{{ $collection->name }}</td>
                                    <td class="p-4 text-primary text-sm">{{ $collection->label }}</td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('admin.collections.edit', $collection->id) }}" class="p-2 text-on-surface-variant hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-on-surface-variant italic">No collections defined.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- Exclusive Drops -->
            <section>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
                        <h2 class="font-label-bold text-label-md uppercase tracking-widest text-primary">Drop Logistics</h2>
                        <a href="{{ route('admin.drops.create') }}" class="px-4 py-2 border border-outline-variant text-on-background hover:border-primary hover:text-primary transition-colors rounded text-[10px] font-label-bold uppercase tracking-widest flex items-center">
                            <span class="material-symbols-outlined text-[14px] mr-1">add</span> Schedule Drop
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-variant text-on-surface-variant font-label-bold text-label-sm uppercase tracking-widest">
                                    <th class="p-4 border-b border-outline-variant/30">Item</th>
                                    <th class="p-4 border-b border-outline-variant/30">Date</th>
                                    <th class="p-4 border-b border-outline-variant/30">Status</th>
                                    <th class="p-4 border-b border-outline-variant/30 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                @forelse($drops as $drop)
                                <tr class="border-b border-outline-variant/10 hover:bg-surface-variant/20 transition-colors">
                                    <td class="p-4 font-label-bold text-on-background">{{ $drop->name }}</td>
                                    <td class="p-4 text-primary font-mono text-sm">{{ $drop->date_label }}</td>
                                    <td class="p-4 text-sm">{{ strtoupper($drop->status) }}</td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('admin.drops.edit', $drop->id) }}" class="p-2 text-on-surface-variant hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-on-surface-variant italic">No drops scheduled.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column -->
        <div class="flex flex-col space-y-12">
            <!-- Arsenal Inventory -->
            <section>
                <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background mb-6 flex items-center">
                    <span class="material-symbols-outlined mr-2">inventory_2</span> Operations
                </h2>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
                        <h2 class="font-label-bold text-label-md uppercase tracking-widest text-primary">Arsenal Inventory</h2>
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 border border-outline-variant text-on-background hover:border-primary hover:text-primary transition-colors rounded text-[10px] font-label-bold uppercase tracking-widest flex items-center">
                            <span class="material-symbols-outlined text-[14px] mr-1">add</span> Deploy New
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-variant text-on-surface-variant font-label-bold text-label-sm uppercase tracking-widest">
                                    <th class="p-4 border-b border-outline-variant/30">Item</th>
                                    <th class="p-4 border-b border-outline-variant/30">Stock</th>
                                    <th class="p-4 border-b border-outline-variant/30">Price</th>
                                    <th class="p-4 border-b border-outline-variant/30 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                @forelse($products as $product)
                                <tr class="border-b border-outline-variant/10 hover:bg-surface-variant/20 transition-colors">
                                    <td class="p-4">
                                        <div class="font-label-bold text-on-background">{{ $product->name }}</div>
                                        <div class="text-sm text-on-surface-variant truncate w-48">{{ $product->description }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($product->stock > 0)
                                            <span class="text-primary">{{ $product->stock }} Units</span>
                                        @else
                                            <span class="text-error">Depleted</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-primary font-mono text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-on-surface-variant hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('WARNING: Are you sure you want to permanently erase this item from the arsenal?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-on-surface-variant hover:text-error transition-colors" title="Delete">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-on-surface-variant italic">No items deployed yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Site Variables -->
            <section>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
                        <h2 class="font-label-bold text-label-md uppercase tracking-widest text-primary">Site Variables (Manifesto)</h2>
                        <a href="{{ route('admin.settings.edit') }}" class="text-sm text-primary hover:text-primary/80 font-label-bold uppercase tracking-widest flex items-center">
                            <span class="material-symbols-outlined mr-1 text-sm">edit</span> Edit Values
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-[10px] text-on-surface-variant uppercase tracking-widest mb-1">Manifesto Title</h3>
                            <p class="font-label-bold text-on-background">{{ $settings['manifesto_title'] ?? 'UNCOMPROMISING UTILITY' }}</p>
                        </div>
                        <div>
                            <h3 class="text-[10px] text-on-surface-variant uppercase tracking-widest mb-1">Manifesto Description</h3>
                            <p class="text-sm text-on-surface">{{ $settings['manifesto_desc'] ?? 'Every piece is engineered with precision...' }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
@endsection
