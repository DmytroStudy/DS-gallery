<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="d-flex justify-content-center p-4 flex-grow-1">
        <div style="width:100%;max-width:500px">
            <h1>Payment details</h1>

            {{-- Guest sign-in nudge on payment step --}}
            @guest
            <div class="border rounded-1 p-3 mb-4" style="background:#f9f9f9;font-size:13px">
                <span class="text-muted">Checking out as guest.</span>
                <a href="{{ route('login') }}?redirect=orders.payment" class="fw-semibold text-dark ms-1">Log in</a>
            </div>
            @endguest

            <form method="POST" action="{{ route('orders.store') }}" novalidate>
                @csrf

                {{-- Payment method tabs --}}
                <div class="pay-tabs mb-4">
                    <label class="pay-tab">
                        <input type="radio" name="payment_method" value="card" checked style="display:none"/>
                        Credit card
                    </label>
                    <label class="pay-tab">
                        <input type="radio" name="payment_method" value="paypal" style="display:none"/>
                        PayPal
                    </label>
                    <label class="pay-tab">
                        <input type="radio" name="payment_method" value="bank" style="display:none"/>
                        Bank transfer
                    </label>
                </div>

                @include('error')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Cardholder name</label>
                    <input class="form-control" type="text" name="card_name"
                           value="{{ old('card_name', Auth::user()?->name) }}"placeholder="Willem Dafoe"/>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Card number</label>
                    <input class="form-control" type="text" name="card_number"
                           value="{{ old('card_number') }}" placeholder="1234 1234 1234 1234" maxlength="19" />
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Expiry date</label>
                        <input class="form-control" type="text" name="mmyy"
                               value="{{ old('mmyy') }}" placeholder="MM/YY" maxlength="5" />
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">CVV</label>
                        <input class="form-control" type="password" name="cvv" placeholder="•••" maxlength="3" />
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100 py-2" style="font-size:15px">
                    Confirm payment — {{ number_format($total, 0) }}€
                </button>

                <div class="mt-3">
                    <a href="{{ route('cart.shipping') }}" style="font-size:13px;color:var(--muted);text-decoration:none">← Back to shipping</a>
                </div>

            </form>
        </div>
    </main>
</div>

@include('footer')

<script>
    // Highlight active payment tab
    document.querySelectorAll('.pay-tab input').forEach(radio => {
        radio.closest('.pay-tab').classList.toggle('active', radio.checked);
        radio.addEventListener('change', () => {
            document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
            radio.closest('.pay-tab').classList.add('active');
        });
    });
</script>

</body>
</html>
