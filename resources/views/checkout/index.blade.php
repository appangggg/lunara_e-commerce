@extends('layouts.app')

@section('content')
<div class="px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto">
    <h1 class="font-display-lg text-headline-lg-mobile md:text-headline-lg font-bold uppercase tracking-tight text-on-background mb-8">Checkout Protocol</h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
        
        <!-- Left Column: Forms -->
        <div class="lg:col-span-7 space-y-12">
            
            <form id="checkout-form" onsubmit="processCheckout(event)">
                @csrf
                <!-- Step 1: Contact -->
                <section class="border border-outline-variant/30 p-6 bg-surface-container-low/30 rounded-lg mb-12">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary font-label-bold">1</div>
                        <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">Contact Information</h2>
                    </div>
                    
                    @guest
                    <p class="font-body-md text-body-md text-on-surface-variant mb-6">Already have an account? <a href="{{ route('login') }}" class="text-primary hover:underline underline-offset-4">Log in</a> for faster checkout.</p>
                    @endguest

                    <div class="space-y-4">
                        <div>
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required value="{{ auth()->check() ? auth()->user()->email : '' }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors" placeholder="ENTER YOUR EMAIL">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="newsletter" class="rounded border-outline-variant text-primary focus:ring-primary bg-surface">
                            <label for="newsletter" class="font-body-md text-label-sm text-on-surface-variant">Email me with news and exclusive drops</label>
                        </div>
                    </div>
                </section>

                <!-- Step 2: Shipping -->
                <section class="border border-outline-variant/30 p-6 bg-surface-container-low/30 rounded-lg mb-12">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-bold">2</div>
                        <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">Shipping Protocol</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" required value="{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : '' }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required value="{{ auth()->check() && count(explode(' ', auth()->user()->name)) > 1 ? explode(' ', auth()->user()->name)[1] : '' }}" class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        </div>
                        <div class="col-span-2">
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Address</label>
                            <input type="text" id="address" name="address" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">City</label>
                            <input type="text" id="city" name="city" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block font-label-bold text-label-sm uppercase tracking-widest text-on-background mb-2">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" required class="w-full bg-surface border border-outline-variant rounded p-3 text-on-background focus:ring-primary focus:border-primary transition-colors">
                        </div>
                    </div>
                </section>

                <!-- Step 3: Payment -->
                <section class="border border-outline-variant/30 p-6 bg-surface-container-low/30 rounded-lg">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-bold">3</div>
                        <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background">Payment Gateway</h2>
                    </div>
                    
                    <p class="font-body-md text-body-md text-on-surface-variant mb-6">Secure payment processing via Midtrans.</p>
                    
                    <div class="bg-surface p-4 border border-outline-variant/50 rounded flex flex-col items-center justify-center space-y-4 py-8">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant" data-icon="lock">lock</span>
                        <p class="font-body-md text-label-sm text-center text-on-surface-variant">You will be redirected to Midtrans Secure Checkout upon confirming the order.</p>
                    </div>
                    
                    <button type="submit" id="pay-button" class="w-full mt-8 py-4 bg-primary text-on-primary font-label-bold text-label-bold uppercase tracking-widest hover:bg-opacity-90 transition-colors glow-effect rounded flex justify-center items-center">
                        <span id="pay-text">Authorize & Complete</span>
                        <svg id="pay-loader" class="animate-spin ml-3 h-5 w-5 text-on-primary hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <div id="error-message" class="text-error font-body-md text-label-sm mt-4 text-center hidden"></div>
                </section>
            </form>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-5">
            <div class="sticky top-28 border border-outline-variant/30 p-6 bg-surface-container-low/30 rounded-lg">
                <h2 class="font-display-lg text-headline-md font-bold uppercase tracking-tight text-on-background mb-6">Order Manifest</h2>
                
                @php
                    $totalPrice = 0;
                @endphp
                <div class="space-y-4 mb-6 border-b border-outline-variant/30 pb-6">
                    @foreach($cart as $item)
                        @php
                            $totalPrice += $item['price'] * $item['quantity'];
                        @endphp
                        <!-- Session Item -->
                        <div class="flex gap-4">
                            <div class="w-20 h-24 bg-surface-variant rounded flex items-center justify-center text-on-surface-variant relative overflow-hidden">
                                <span class="absolute top-1 right-1 w-5 h-5 bg-background text-on-background border border-outline-variant/50 rounded-full flex items-center justify-center text-[10px] font-bold z-10">{{ $item['quantity'] }}</span>
                                @if($item['image_url'])
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover mix-blend-multiply opacity-80">
                                @else
                                    <span class="material-symbols-outlined text-sm">image</span>
                                @endif
                            </div>
                            <div class="flex-grow flex flex-col justify-between py-1">
                                <div>
                                    <h3 class="font-label-bold text-label-bold text-on-background uppercase">{{ $item['name'] }}</h3>
                                    <p class="font-body-md text-label-sm text-on-surface-variant mt-1">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <span class="font-label-bold text-label-bold text-primary">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4 border-b border-outline-variant/30 pb-6 mb-6">
                    <div class="flex justify-between items-center text-on-surface-variant font-body-md text-body-md">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-on-surface-variant font-body-md text-body-md">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                </div>

                <div class="flex justify-between items-end text-on-background">
                    <span class="font-display-lg text-headline-md font-bold uppercase">Total</span>
                    <span class="font-display-lg text-headline-lg text-primary font-bold tracking-tight">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    async function processCheckout(event) {
        event.preventDefault();
        
        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('pay-button');
        const btnText = document.getElementById('pay-text');
        const btnLoader = document.getElementById('pay-loader');
        const errorMsg = document.getElementById('error-message');

        // Reset UI
        submitBtn.disabled = true;
        btnText.innerText = 'Processing...';
        btnLoader.classList.remove('hidden');
        errorMsg.classList.add('hidden');

        try {
            const response = await fetch("{{ route('checkout.process') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Failed to process checkout');
            }

            // Trigger Midtrans Snap
            window.snap.pay(data.snap_token, {
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
                            order_id: result.order_id
                        })
                    }).then(() => {
                        window.location.href = "{{ route('user.orders') }}";
                    });
                },
                onPending: function(result){
                    window.location.href = "{{ route('user.orders') }}";
                },
                onError: function(result){
                    alert("Payment failed!");
                },
                onClose: function(){
                    // Restore button if they close popup without paying
                    submitBtn.disabled = false;
                    btnText.innerText = 'Authorize & Complete';
                    btnLoader.classList.add('hidden');
                }
            });

        } catch (error) {
            errorMsg.innerText = error.message;
            errorMsg.classList.remove('hidden');
            submitBtn.disabled = false;
            btnText.innerText = 'Authorize & Complete';
            btnLoader.classList.add('hidden');
        }
    }
</script>
@endsection
