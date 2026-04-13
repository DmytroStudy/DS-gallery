<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Admin — Edit {{ $artwork->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">
    <aside>
        <nav>
            <a class="side-link" href="{{ route('home') }}">Home</a>
            <a class="side-link active" href="{{ route('admin.artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="p-4 overflow-y-auto flex-grow-1">

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success py-2 mb-3" style="font-size:13px">
                {{ session('success') }}
            </div>
        @endif

        <!-- UPDATE FORM -->
        <form method="POST" action="{{ route('admin.update', $artwork) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4 align-items-start">

                <!-- Image -->
                <div class="col-12 col-md-8">
                    <label class="img-upload w-100" style="cursor:pointer;border:none;background:none">
                        <img id="imagePreview"
                             src="{{ asset($artwork->image) }}"
                             alt="{{ $artwork->title }}"
                             style="width:100%;height:505px;object-fit:contain"/>
                        <input type="file" name="image" accept="image/*" style="display:none"
                               onchange="previewImg(this)"/>
                    </label>
                    <p class="text-muted text-center mt-1" style="font-size:12px">
                        Click image to replace it
                    </p>
                </div>

                <!-- Fields -->
                <div class="col-12 col-md-4 d-flex flex-column gap-4">

                    <div>
                        <div class="muted-label mb-1">TITLE</div>
                        <input class="edit-input" type="text" name="title"
                               value="{{ old('title', $artwork->title) }}" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">ARTIST</div>
                        <input class="edit-input" type="text" name="artist"
                               value="{{ old('artist', $artwork->artist) }}" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">DATE</div>
                        <input class="edit-input" type="number" name="year"
                               value="{{ old('year', $artwork->year) }}"
                               min="1000" max="2100" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">GENRE</div>
                        <input class="edit-input" type="text" name="genre"
                               list="genreList"
                               value="{{ old('genre', $artwork->genre) }}" required/>
                        <datalist id="genreList">
                            @foreach ($genres as $g)
                                <option value="{{ $g }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <div class="muted-label mb-1">PRICE (€)</div>
                        <input class="edit-input" type="number" name="price" step="0.01"
                               value="{{ old('price', $artwork->price) }}" min="0" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">DESCRIPTION</div>
                        <textarea class="edit-input" name="description" rows="4" style="resize:vertical">
{{ old('description', $artwork->description) }}
                        </textarea>
                    </div>

                    <div class="border-top pt-3 d-flex gap-2">
                        <a class="mid-btn" href="{{ route('admin.artworks') }}">Cancel</a>
                        <button type="submit" class="btn btn-dark btn-sm flex-grow-1">
                            Save changes
                        </button>
                    </div>

                </div>
            </div>
        </form>

        <!-- DELETE FORM -->
        <div class="border-top pt-3 mt-3">
            <form method="POST" action="{{ route('admin.destroy', $artwork) }}"
                  onsubmit="return confirm('Delete ' + @json($artwork->title) + '?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    Delete artwork
                </button>
            </form>
        </div>

    </main>
</div>

@include('footer')

<script>
    function previewImg(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

</body>
</html>
