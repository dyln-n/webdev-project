<x-app-layout>
    <!-- Main Carousel Section -->
    <div class="py-0">
        <div class="w-full">
            <div id="main-carousel" class="carousel slide relative" data-bs-ride="carousel" data-bs-interval="5000">
                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#main-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#main-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#main-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>

                <!-- Slides -->
                <div class="carousel-inner">
                    <!-- Slide 1: Electronics -->
                    <div class="carousel-item active">
                        <img src="/images/slide1.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 1">
                        <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
                            <h1 class="text-white fw-bold display-4">Top Electronics</h1>
                            <p class="text-white fs-5">Discover cutting-edge gadgets, laptops, and smart devices.</p>
                            <a href="{{ route('category.show', 'electronics') }}" class="btn btn-light mt-3 fw-semibold">Check Details</a>
                        </div>
                    </div>

                    <!-- Slide 2: Fashion -->
                    <div class="carousel-item">
                        <img src="/images/slide2.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 2">
                        <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
                            <h1 class="text-white fw-bold display-4">Fashion Highlights</h1>
                            <p class="text-white fs-5">Step into the season with style – clothes, shoes, and more.</p>
                            <a href="{{ route('category.show', 'fashion') }}" class="btn btn-light mt-3 fw-semibold">Check Details</a>
                        </div>
                    </div>

                    <!-- Slide 3: Pet Supplies -->
                    <div class="carousel-item">
                        <img src="/images/slide3.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 3">
                        <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
                            <h1 class="text-white fw-bold display-4">Pet Essentials</h1>
                            <p class="text-white fs-5">Everything your furry friends need, from food to toys.</p>
                            <a href="{{ route('category.show', 'pet-supplies') }}" class="btn btn-light mt-3 fw-semibold">Check Details</a>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#main-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#main-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>