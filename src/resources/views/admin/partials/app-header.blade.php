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
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="bi bi-search"></i>
                </a>
            </li>
            <!--end::Navbar Search-->

            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-chat-text"></i>
                    @if ($mensagensNaoLidasCount > 0)
                        <span class="navbar-badge badge text-bg-danger">{{ $mensagensNaoLidasCount }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    @forelse ($mensagensRecentes as $msg)
                        <a href="{{ route('admin.mensagens.show', $msg) }}" class="dropdown-item">
                            <!--begin::Message-->
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white me-3"
                                        style="width:50px;height:50px;">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="dropdown-item-title {{ !$msg->lida ? 'fw-bold' : '' }}">
                                        {{ $msg->nome }}
                                        @if (!$msg->lida)
                                            <span class="float-end fs-7 text-danger"><i class="bi bi-circle-fill" style="font-size:8px;"></i></span>
                                        @endif
                                    </h3>
                                    <p class="fs-7">{{ $msg->assunto }}</p>
                                    <p class="fs-7 text-secondary">
                                        <i class="bi bi-clock-fill me-1"></i> {{ $msg->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <!--end::Message-->
                        </a>
                        <div class="dropdown-divider"></div>
                    @empty
                        <span class="dropdown-item text-secondary">Nenhuma mensagem recebida</span>
                        <div class="dropdown-divider"></div>
                    @endforelse
                    <a href="{{ route('admin.mensagens.index') }}" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-warning">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

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
                        <a href="#" class="btn btn-outline-secondary">Profile</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger float-end">Sair</button>
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