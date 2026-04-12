<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Register</title>
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
            <h1 class="mb-3">Create account</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">First name</label>
                        <input class="form-control" type="text" name="first_name"
                               placeholder="Willem" value="{{ old('first_name') }}"/>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Last name</label>
                        <input class="form-control" type="text" name="last_name"
                               placeholder="Dafoe" value="{{ old('last_name') }}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input class="form-control" type="email" name="email"
                           placeholder="your@email.com" value="{{ old('email') }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input class="form-control" type="password" name="password" placeholder="••••••••"/>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Confirm password</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="••••••••"/>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 mb-3" style="font-size:13px">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="btn btn-dark w-100 mb-3">Create account</button>
            </form>

            <p class="text-center text-muted small">
                Already have an account?
                <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none">Log in</a>
            </p>
        </div>
    </main>
</div>



</body>
</html>
