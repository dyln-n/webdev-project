<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-4">
                <a href="{{ Auth::user()?->role == 'seller' ? route('dashboard.seller') : (Auth::user()?->role == 'buyer' ? route('dashboard.buyer') : '/') }}" class="flex items-center space-x-6 no-underline text-gray-800 dark:text-gray-200">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200 hidden sm:inline">Welcome to Amazon</span>
                </a>
            </div>

            <!-- Category Links -->
            <div class="hidden sm:flex gap-16 font-medium">
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Electronics</a>
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Fashions</a>
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Pet Supplies</a>
            </div>

            <!-- Right Icons -->
           <div class="flex items-center gap-10">
                <!-- Search Icon -->
                <a href="#" title="Search" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </a>

                <!-- User Icon -->
                <!-- User Icon -->
@auth
    <a href="{{ Auth::user()->role === 'seller' ? route('dashboard.seller') : route('dashboard.buyer') }}"
       title="My Dashboard"
       class="text-gray-500 hover:text-indigo-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </a>
@else
    <a href="{{ route('register') }}" title="Login/Register" class="text-gray-500 hover:text-indigo-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </a>
@endauth


                <!-- Cart Icon -->
                @auth
                    <a href="#" title="Cart" class="text-gray-500 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" title="Login to view cart" class="text-gray-500 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div id="carouselExampleIndicators" class="carousel slide relative" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1: Electronics -->
        <div class="carousel-item active">
    <img src="/images/slide1.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 1">
    <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
        <h1 class="text-white fw-bold display-4">Top Electronics</h1>
        <p class="text-white fs-5">Discover cutting-edge gadgets, laptops, and smart devices.</p>
        <a href="#" class="btn btn-light mt-3 fw-semibold">Check Details</a>
    </div>
</div>

        <!-- Slide 2: Fashions -->
      <div class="carousel-item">
    <img src="/images/slide2.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 2">
    <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
        <h1 class="text-white fw-bold display-4">Fashion Highlights</h1>
        <p class="text-white fs-5">Step into the season with style – clothes, shoes, and more.</p>
        <a href="#" class="btn btn-light mt-3 fw-semibold">Check Details</a>
    </div>
</div>


        <!-- Slide 3: Pet Supplies -->
<div class="carousel-item">
    <img src="/images/slide3.jpg" class="d-block w-100" style="max-height: 700px; object-fit: cover;" alt="Slide 3">
    <div class="carousel-caption d-none d-md-block text-start" style="top: 50%; transform: translateY(-50%);">
        <h1 class="text-white fw-bold display-4">Pet Essentials</h1>
        <p class="text-white fs-5">Everything your furry friends need, from food to toys.</p>
        <a href="#" class="btn btn-light mt-3 fw-semibold">Check Details</a>
    </div>
</div>


    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

        </div>
    </div>
</x-app-layout>
