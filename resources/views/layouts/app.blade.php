<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials.head')
</head>

<body>

    @include('layouts.partials.header')

    @include('layouts.partials.side-bar')

    <main id="main" class="main">
        @yield('front-content')
    </main>

    @include('layouts.partials.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    @include('layouts.partials.scripts')

</body>

</html>
