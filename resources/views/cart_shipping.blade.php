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



<div class="d-flex flex-grow-1">
    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="d-flex justify-content-center p-4 flex-grow-1 overflow-y-auto">
        <div style="width:100%;max-width:500px">
            <h1 class="mb-3">Shipping information</h1>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label small fw-semibold">First Name</label>
                    <input class="form-control" type="text" placeholder="Willem"/>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-semibold">Last Name</label>
                    <input class="form-control" type="text" placeholder="Dafoe"/>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input class="form-control" type="email"
                       value="{{ Auth::user()?->email }}" placeholder="email@example.com"/>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Phone</label>
                <input class="form-control" type="tel" placeholder="+421 900 000 000"/>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Country</label>
                <select class="form-select">
                    <option>Slovakia</option><option>Czech Republic</option>
                    <option>Austria</option><option>Germany</option><option>Hungary</option>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-8">
                    <label class="form-label small fw-semibold">City</label>
                    <input class="form-control" type="text" placeholder="Bratislava"/>
                </div>
                <div class="col-4">
                    <label class="form-label small fw-semibold">Postal code</label>
                    <input class="form-control" type="text" placeholder="81101"/>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Street address</label>
                <input class="form-control mb-2" type="text" placeholder="Street and house number"/>
                <input class="form-control" type="text" placeholder="Apartment, floor (optional)"/>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('cart') }}" style="font-size:13px;color:var(--muted);text-decoration:none">Back to cart</a>
                <a class="btn btn-dark px-4" href="{{ route('cart.payment') }}">Continue to payment</a>
            </div>
        </div>
    </main>
</div>



</body>
</html>
