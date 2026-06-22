@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 border-b border-outline-variant/30 pb-4 gap-4">
        <div>
            <h1 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">My Orders</h1>
            <p class="font-body-md text-label-sm text-on-surface-variant mt-1">Track your acquisition history.</p>
        </div>
        <div class="flex items-center space-x-2 text-on-surface-variant text-sm font-label-bold uppercase tracking-wider">
            <span class="material-symbols-outlined text-[18px]">account_circle</span>
            <span>{{ auth()->user()->name }}</span>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="bg-surface-container-low border border-outline-variant/30 p-12 rounded-lg flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4 opacity-50" data-icon="inventory_2">inventory_2</span>
            <h2 class="font-display-lg text-headline-sm font-bold uppercase tracking-tight text-on-background mb-2">No Active Acquisitions</h2>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-md mx-auto mb-8">You haven't made any purchases yet. Browse the catalog to start building your arsenal.</p>
            <a href="{{ route('products.index') }}" class="px-8 py-3 bg-primary text-on-primary font-label-bold text-label-bold uppercase tracking-widest hover:bg-opacity-90 transition-colors glow-effect rounded">
                Explore Arsenal
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-lg overflow-hidden group hover:border-primary/50 transition-colors">
                    
                    <!-- Order Header -->
                    <div class="bg-surface-variant/30 p-4 border-b border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex flex-col">
                            <span class="font-label-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Order Reference</span>
                            <span class="font-mono text-sm text-on-background font-bold">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex flex-col sm:items-end">
                            <span class="font-label-bold text-[10px] text-on-surface-variant uppercase tracking-widest">Date Placed</span>
                            <span class="text-sm text-on-background">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <!-- Order Body -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="md:col-span-3 space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-20 bg-surface-variant rounded flex-shrink-0 overflow-hidden">
                                        @if($item->product && $item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply opacity-80">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-sm text-on-surface-variant">image</span></div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-label-bold text-sm text-on-background uppercase">{{ $item->product ? $item->product->name : 'Unknown Item' }}</h3>
                                        <p class="font-body-md text-[12px] text-on-surface-variant mt-1">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-mono text-sm text-primary font-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Status & Actions -->
                        <div class="md:col-span-1 md:border-l border-outline-variant/30 md:pl-6 flex flex-col justify-between space-y-6">
                            <div>
                                <p class="font-label-bold text-[10px] text-on-surface-variant uppercase tracking-widest mb-2">Total Value</p>
                                <span class="font-display-lg text-xl text-on-background font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            
                            <div>
                                <p class="font-label-bold text-[10px] text-on-surface-variant uppercase tracking-widest mb-2">Status</p>
                                @if($order->status === 'paid')
                                    <div class="flex items-center space-x-2 text-primary">
                                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        <span class="font-label-bold text-sm uppercase tracking-wider">Paid / Processing</span>
                                    </div>
                                @elseif($order->status === 'pending')
                                    <div class="flex flex-col space-y-3">
                                        <div class="flex items-center space-x-2 text-yellow-500">
                                            <span class="material-symbols-outlined text-[18px] animate-pulse">pending</span>
                                            <span class="font-label-bold text-sm uppercase tracking-wider">Awaiting Payment</span>
                                        </div>
                                        <button onclick="payOrder('{{ $order->snap_token }}', '{{ $order->order_number }}')" class="px-4 py-2 bg-primary/20 border border-primary text-primary font-label-bold text-[10px] uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-colors rounded text-center">
                                            Pay Now
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 text-red-500">
                                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                                        <span class="font-label-bold text-sm uppercase tracking-wider">Expired / Canceled</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
        
        <!-- Midtrans Script (If user needs to pay pending orders from this page) -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        <script>
            function payOrder(snapToken, orderNumber) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result){
                        // Simulate webhook locally since Midtrans can't reach localhost
                        fetch("{{ route('api.webhook.midtrans') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                transaction_status: 'capture',
                                fraud_status: 'accept',
                                order_id: orderNumber
                            })
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    onPending: function(result){
                        window.location.reload();
                    }
                });
            }
        </script>
    @endif
</div>
@endsection
