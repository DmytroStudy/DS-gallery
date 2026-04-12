<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DSgallery: home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body class="d-flex flex-column" style="min-height:100vh">



<div class="d-flex flex-grow-1">
    <aside>
        <nav>
            <a class="side-link active" href="{{ route('home') }}">Home</a>
            <a class="side-link" href="{{ route('artworks') }}">Artworks</a>
        </nav>
    </aside>

    <main class="p-4 overflow-y-auto flex-grow-1">

        <section class="mb-5">
            <h1>News from the world of art</h1>
            <article class="news-card border rounded-1 p-4">
                <div>
                    <h2 style="font-size:20px;font-weight:500;margin-bottom:14px;line-height:1.4">
                        Jeff Koons's Giant Play-Doh Sculpture Could Fetch 20€ Million at Christie's.
                    </h2>
                    <p style="font-size:16px;line-height:1.75">
                        When the Whitney Museum of American Art staged its blockbuster retrospective of Jeff Koons in 2014, the most photographed work was undeniably Play-Doh (1994–2014), an 11-foot-tall aluminum sculpture that looked as if an artistic young giant had created a multicolored pile of clay and then left it behind.<br><br>
                        Now, a version of that candy-hued work is heading to auction. Christie's will offer an edition of Play-Doh at its evening sale of postwar and contemporary art in New York on May 17. The work, consigned from a European collection, is expected to sell for a price "in the region of 20€ million."<br><br>
                        By the time Koons finally revealed Play-Doh to the public in his Whitney retrospective, it was already the stuff of legend. The work took around two decades to produce as the notoriously perfectionist Koons fiddled with the medium and refined the production process.
                    </p>
                </div>
                <div class="img-card news-img"><img class="art-image" src="{{ asset('images/home/news.jpg') }}" alt=""/></div>
            </article>
        </section>

        <section class="mb-5">
            <h1>Artwork of the day</h1>
            <a class="day-wrap" href="{{ route('artworks') }}">
                <img src="{{ asset('images/home/Girl_with_a_Pearl_Earring.jpg') }}" alt="Girl with a Pearl Earring"/>
            </a>
        </section>

        <section class="mb-5">
            <h1>New Arrivals</h1>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                @php
                    $newArrivals = \App\Models\Artwork::latest()->take(3)->get();
                @endphp
                @foreach ($newArrivals as $artwork)
                    <div class="col">
                        <figure class="card p-0">
                            <a class="img-card" style="height:300px" href="{{ route('detail', $artwork) }}">
                                <img class="art-image" src="{{ asset($artwork->image) }}" alt="{{ $artwork->title }}"/>
                                <div class="tile-btns">
                                    <form method="POST" action="{{ route('cart.add', $artwork) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1"/>
                                        <button type="submit" class="sm-icon-btn"><img src="{{ asset('icons/cart.svg') }}" alt=""/></button>
                                    </form>
                                    <button class="sm-icon-btn"><img src="{{ asset('icons/bookmark.svg') }}" alt=""/></button>
                                </div>
                            </a>
                            <div class="tile-info">
                                <figcaption class="name">{{ $artwork->title }}</figcaption>
                                <div class="price">{{ number_format($artwork->price, 0) }}€</div>
                            </div>
                        </figure>
                    </div>
                @endforeach
            </div>
        </section>

        <section>
            <h1>Our most famous artists</h1>
            <div class="d-flex flex-column">

                <article class="border-top py-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-7">
                            <p style="font-size:18px">Vincent van Gogh (1853–1890) — a Dutch Post-Impressionist painter whose work marked a dramatic shift toward emotional expression and individuality in art, developing a highly distinctive style characterized by bold, vibrant colors and thick brushstrokes.</p>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="img-card" style="height:350px">
                                <img class="art-image" src="{{ asset('images/home/Van_Gogh.jpg') }}" alt=""/>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="border-top py-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-7">
                            <p style="font-size:18px">Pablo Picasso (1881–1973) — a Spanish painter, sculptor, and one of the most influential artists of the 20th century, known for co-founding Cubism and masterpieces like "Guernica".</p>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="img-card" style="height:350px">
                                <img class="art-image" src="{{ asset('images/home/Picasso.jpg') }}" alt=""/>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="border-top py-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-7">
                            <p style="font-size:18px">Raphael (1483–1520) — an Italian painter and architect of the High Renaissance, widely regarded as one of the greatest artists in European history, known for "The School of Athens".</p>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="img-card" style="height:350px">
                                <img class="art-image" src="{{ asset('images/home/Raphael.jpg') }}" alt=""/>
                            </div>
                        </div>
                    </div>
                </article>

            </div>
        </section>

    </main>
</div>



</body>
</html>
