<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @include('admin.partials.head')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        @include('admin.partials.app-header')

        @include('admin.partials.app-sidebar')

      

        <main class="app-main">
            @yield('content')
        </main>

        @include('admin.partials.app-footer')

    </div>

    @include('admin.partials.script')

@stack('scripts')
</body>
</html>
