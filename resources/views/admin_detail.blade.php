<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: Admin — Edit {{ $product->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">

@include('header')

<div class="d-flex flex-grow-1">

    @include('sidebar')

    <main class="p-4 overflow-y-auto flex-grow-1">
        @include('error')

        <!-- UPDATE FORM -->
            <form method="POST" action="{{ route('admin.update', $product) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4 align-items-start">

                    <div class="col-12 col-md-8">
                        <div class="muted-label mb-2">CURRENT IMAGES (Select to remove)</div>

                        <!-- Images -->
                        <div class="row row-cols-2 row-cols-lg-3 g-3 mb-4">
                            @foreach($product->images as $image)
                                <div class="col text-center">
                                    <div class="border rounded p-2 position-relative">
                                        <img src="{{ asset($image->img_path) }}"
                                             style="width:100%; height:150px; object-fit:contain"
                                             alt="Product image">

                                        <div class="mt-2">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image->product_image_id }}" id="img_{{ $image->product_image_id }}">
                                            <label for="img_{{ $image->product_image_id }}" class="small text-danger" style="cursor:pointer">
                                                Remove
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border rounded p-4 text-center" style="border-style: dashed !important;">
                            <div class="muted-label mb-2">ADD NEW IMAGES</div>
                            <input type="file" name="new_images[]" class="form-control form-control-sm" multiple accept="image/*">
                        </div>
                    </div>


                    <!-- Fields -->
                    <div class="col-12 col-md-4 d-flex flex-column gap-4">

                        <div>
                            <div class="muted-label mb-1">TITLE</div>
                            <input class="edit-input" type="text" name="title"
                                   value="{{ old('title', $product->title) }}" />
                        </div>

                        <div>
                            <div class="muted-label mb-1">ARTIST</div>
                            <input class="edit-input" type="text" name="artist"
                                   value="{{ old('artist', $product->artist?->name) }}" />
                        </div>

                        <div>
                            <div class="muted-label mb-1">DATE</div>
                            <input class="edit-input" type="number" name="year"
                                   value="{{ old('year', $product->year) }}"
                                   min="1000" max="2100" />
                        </div>

                        <div>
                            <div class="muted-label mb-1">GENRE</div>
                            <select class="edit-input" name="genre" style="cursor:pointer">
                                @foreach ($genres as $g)
                                    <option value="{{ $g->genre }}" {{ old('genre') == $g->genre ? 'selected' : '' }}>
                                        {{ $g->genre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="muted-label mb-1">CATEGORY</div>
                            <select class="edit-input" name="category" style="cursor:pointer">
                                <option value="artwork" selected>Artwork</option>
                                <option value="tool">Tool</option>
                            </select>
                        </div>

                        <div>
                            <div class="muted-label mb-1">PRICE (€)</div>
                            <input class="edit-input" type="number" name="price" step="0.01"
                                   value="{{ old('price', $product->price) }}" min="0" max="99999999"/>
                        </div>

                        <div>
                            <div class="muted-label mb-1">DESCRIPTION</div>
                            <textarea class="edit-input" name="description" rows="4" style="resize:vertical">
                            {{ old('description', $product->description) }}
                        </textarea>
                        </div>

                        <div class="border-top pt-3 d-flex gap-2">
                            <a class="mid-btn" href="{{ route('admin.products') }}">Cancel</a>
                            <button type="submit" class="btn btn-dark btn-sm flex-grow-1">
                                Save changes
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        <!-- DELETE FORM -->
        <div class="border-top pt-3 mt-3">
            <form method="POST" action="{{ route('admin.destroy', $product) }}"
                  onsubmit="return confirm('Delete ' + @json($product->title) + '?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    Delete product
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
