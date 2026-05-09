<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Admin — Add product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="p-4 overflow-y-auto flex-grow-1">

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4 align-items-start">

                <!-- Image upload -->
                <div class="col-12 col-md-8">
                    <label class="img-upload w-100" id="uploadLabel" style="cursor:pointer">
                        <img src="{{ asset('icons/img.svg') }}" id="previewPlaceholder"/>
                        <span id="uploadText">Click to upload image</span>
                        <img id="imagePreview" src="#" alt="Preview"
                             style="display:none;width:100%;height:100%;object-fit:contain"/>
                        <input type="file" name="image" accept="image/*" style="display:none"
                               onchange="previewImg(this)"/>
                    </label>
                </div>

                <!-- Fields -->
                <div class="col-12 col-md-4 d-flex flex-column gap-4">

                    <div>
                        <div class="muted-label mb-1">TITLE</div>
                        <input class="edit-input" type="text" name="title"
                               placeholder="Café Terrace at Night" value="{{ old('title') }}" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">ARTIST</div>
                        <input class="edit-input" type="text" name="artist"
                               placeholder="Vincent van Gogh" value="{{ old('artist') }}" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">DATE</div>
                        <input class="edit-input" type="number" name="year"
                               placeholder="1888" value="{{ old('year') }}" min="1000" max="2100" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">GENRE</div>
                        <input class="edit-input" type="text" name="genre"
                               list="genreList" placeholder="Impressionism" value="{{ old('genre') }}" required/>
                        <datalist id="genreList">
                            @foreach ($genres as $g)<option value="{{ $g }}">@endforeach
                        </datalist>
                    </div>

                    <div>
                        <div class="muted-label mb-1">CATEGORY</div>
                        <select class="edit-input" name="category" style="cursor:pointer">
                            <option value="product" {{ old('category', $product->category ?? 'product') === 'product' ? 'selected' : '' }}>
                                product
                            </option>
                            <option value="tool" {{ old('category', $product->category ?? '') === 'tool' ? 'selected' : '' }}>
                                Tool
                            </option>
                        </select>
                    </div>

                    <div>
                        <div class="muted-label mb-1">PRICE (€)</div>
                        <input class="edit-input" type="number" name="price" step="0.01"
                               placeholder="0" value="{{ old('price') }}" min="0" required/>
                    </div>

                    <div>
                        <div class="muted-label mb-1">DESCRIPTION</div>
                        <textarea class="edit-input" name="description" rows="4"
                                  style="resize:vertical"
                                  placeholder="Description of the product…">{{ old('description') }}</textarea>
                    </div>

                    <div class="border-top pt-3 d-flex gap-2">
                        <a class="mid-btn" href="{{ route('admin.products') }}">Cancel</a>
                        <button type="submit" class="btn btn-dark btn-sm flex-grow-1">Save product</button>
                    </div>

                </div>
            </div>
        </form>
    </main>
</div>

@include('footer')

<script>
    function previewImg(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewPlaceholder').style.display = 'none';
            document.getElementById('uploadText').style.display = 'none';
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

</body>
</html>
