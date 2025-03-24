@if (request()->routeIs('home'))
    @include('layouts.nav-home')
@else
    @include('layouts.nav-dashboard')
@endif
