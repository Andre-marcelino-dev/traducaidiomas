<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>

        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
                
            </li>
          
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('traducaidiomas/professor/' . auth('admin')->user()->foto_professor) }}"
                        style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle shadow"
                        alt="User Image" style="width: 40px; height: 40px; object-fit: cover;"
                        class="d-none d-md-inline">{{ auth('admin')->user()->nome_professor }}
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <img src="{{ asset('traducaidiomas/professor/' . auth('admin')->user()->foto_professor) }}"
                            style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle shadow"
                            alt="User Image" />
                        <p>
                            {{ auth('admin')->user()->nome_professor }} Graduado em Letras & Idiomas
                            <small>Desde
                                {{ date('d/m/Y', strtotime(auth('admin')->user()->criado_em_professor)) }}</small>
                            <i class="fas fa-briefcase me-1"></i>
                            {{-- {{ $professor->experiencia_professor }} --}}
                            {{ auth('admin')->user()->experiencia_professor }}
                            </small>
                        </p>
                    </li>
                    <!--end::User Image-->


                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                          <button type="submit" class="btn btn-outline-danger d-block mx-auto" style="width: 200px; padding: 10px 30px; font-size: 1.1rem;">Sair</button>
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->