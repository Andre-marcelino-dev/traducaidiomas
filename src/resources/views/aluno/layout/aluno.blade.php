<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @include('aluno.partials.head')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        @include('aluno.partials.app-header')

        @include('aluno.partials.app-sidebar')

        <main class="app-main">
            @yield('content')
        </main>

        @include('aluno.partials.app-footer')

    </div>

    @include('aluno.partials.script')

    @stack('scripts')
</body>
</html>