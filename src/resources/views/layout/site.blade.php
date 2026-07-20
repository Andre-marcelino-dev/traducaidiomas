<!DOCTYPE html>
<html lang="pt-br">

<head>
    @include('partials.head')
</head>

<body>

    @include('partials.header')

    <main>
        @yield('content')

    
    </main>

    @include('partials.footer')

    @include('partials.chatbot')

    @include('partials.script')
</body>

</html>
