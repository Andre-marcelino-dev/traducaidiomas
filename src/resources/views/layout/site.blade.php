<!DOCTYPE html>
<html lang="pt-br">

<head>
    @include('partials.head')
</head>

<body>
    <div class="page-wrapper">
        @include('partials.preloader')
        
        @include('partials.header')
        
        <main>
            @yield('content')
        </main>
        
        @include('partials.footer')

    </div>

    @include('partials.script')
</body>

</html>