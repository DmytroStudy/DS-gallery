<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Shipping</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="d-flex justify-content-center p-4 flex-grow-1 overflow-y-auto">
        <div style="width:100%;max-width:500px">
            <h1 class="mb-3">Shipping information</h1>

            {{-- Guest login --}}
            @guest
            <div class="border rounded-1 p-3 mb-4" style="background:#f9f9f9;font-size:13px">
                Already have an account?
                <a href="{{ route('login') }}?redirect=cart.shipping" class="fw-semibold text-dark">Log in</a>
            </div>
            @endguest

            <form method="POST" action="{{ route('cart.shipping.process') }}" novalidate>
                @csrf
                @include('error')

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">First Name</label>
                        <input class="form-control" type="text" name="first_name"
                               placeholder="Willem" value="{{ old('first_name', Auth::user()?->first_name) }}" />
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Last Name</label>
                        <input class="form-control" type="text" name="last_name"
                               placeholder="Dafoe" value="{{ old('last_name', Auth::user()?->last_name) }}" />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input class="form-control" type="email" name="email"
                           value="{{ old('email', Auth::user()?->email) }}"
                           placeholder="email@example.com" />
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Phone</label>
                    <input class="form-control" type="tel" name="phone"
                           placeholder="+421 900 000 000" value="{{ old('phone') }}"/>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Country</label>
                    <select class="form-select" name="country" >
                        @foreach (['Slovakia','Czech Republic','Austria','Germany','Hungary'] as $c)
                            <option {{ old('country') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-8">
                        <label class="form-label small fw-semibold">City</label>
                        <input class="form-control" type="text" name="city"
                               placeholder="Bratislava" value="{{ old('city') }}" />
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-semibold">Postal code</label>
                        <input class="form-control" type="text" name="postal_code"
                               placeholder="81101" value="{{ old('postal_code') }}" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Street address</label>
                    <input class="form-control mb-2" type="text" name="address"
                           placeholder="Street and house number" value="{{ old('address') }}" />
                    <input class="form-control" type="text" name="address2"
                           placeholder="Apartment, floor (optional)" value="{{ old('address2') }}"/>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('cart') }}"
                       style="font-size:13px;color:var(--muted);text-decoration:none">Back to cart</a>
                    <button type="submit" class="btn btn-dark px-4">Continue to payment</button>
                </div>

            </form>
        </div>
    </main>
</div>

@include('footer')

</body>
</html>
