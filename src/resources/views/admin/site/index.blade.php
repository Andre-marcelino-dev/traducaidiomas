@extends('admin.layout.admin')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Gerenciar Site</h3>
            </div>
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                <li class="breadcrumb-item active">Site</li>
            </ol>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.site.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- LOGO PAINEL --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    🏷️ Logo do Painel Administrativo
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 text-center">
                            <p class="text-muted small mb-1">Logo Atual do Painel</p>
                            @if($config['logo_painel'])
                                <img src="{{ asset('traducaidiomas/img/' . $config['logo_painel']) }}?v={{ time() }}" style="max-height:80px;" class="rounded">
                            @else
                                <span class="text-muted small">Nenhuma logo cadastrada</span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Substituir Logo do Painel</label>
                            <input type="file" name="logo_painel" class="form-control" accept="image/*">
                            <small class="text-muted">Aparece na barra lateral do admin. Recomendado: PNG transparente, mínimo 200x60px</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LOGO SITE --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    🌐 Logo do Site Público
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 text-center">
                            <p class="text-muted small mb-1">Logo Atual do Site</p>
                            @if($config['logo_site'])
                                <img src="{{ asset('traducaidiomas/img/' . $config['logo_site']) }}?v={{ time() }}" style="max-height:80px;" class="rounded">
                            @else
                                <span class="text-muted small">Nenhuma logo cadastrada</span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Substituir Logo do Site</label>
                            <input type="file" name="logo_site" class="form-control" accept="image/*">
                            <small class="text-muted">Aparece no cabeçalho do site público. Recomendado: PNG transparente, mínimo 200x60px</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BANNER 1 --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    🖼️ Banner 1
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Eyebrow (texto pequeno acima do título)</label>
                            <input type="text" name="banner1_eyebrow" class="form-control" value="{{ $config['banner1_eyebrow'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Título</label>
                            <input type="text" name="banner1_titulo" class="form-control" value="{{ $config['banner1_titulo'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Subtítulo</label>
                            <textarea name="banner1_subtitulo" class="form-control" rows="2">{{ $config['banner1_subtitulo'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagem do Banner 1</label>
                            <input type="file" name="banner1_imagem" class="form-control" accept="image/*">
                            @if($config['banner1_imagem'])
                                <img src="{{ asset('traducaidiomas/banners/' . $config['banner1_imagem']) }}" class="mt-2 rounded" style="height:80px; object-fit:cover;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- BANNER 2 --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    🖼️ Banner 2
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Eyebrow (texto pequeno acima do título)</label>
                            <input type="text" name="banner2_eyebrow" class="form-control" value="{{ $config['banner2_eyebrow'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Título</label>
                            <input type="text" name="banner2_titulo" class="form-control" value="{{ $config['banner2_titulo'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Subtítulo</label>
                            <textarea name="banner2_subtitulo" class="form-control" rows="2">{{ $config['banner2_subtitulo'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagem do Banner 2</label>
                            <input type="file" name="banner2_imagem" class="form-control" accept="image/*">
                            @if($config['banner2_imagem'])
                                <img src="{{ asset('traducaidiomas/banners/' . $config['banner2_imagem']) }}" class="mt-2 rounded" style="height:80px; object-fit:cover;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO INFORMAÇÃO --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    📝 Seção Informação
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Título</label>
                            <input type="text" name="info_titulo" class="form-control" value="{{ $config['info_titulo'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Subtítulo</label>
                            <textarea name="info_subtitulo" class="form-control" rows="2">{{ $config['info_subtitulo'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO SOBRE --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    👤 Seção Sobre/Biografia
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Título</label>
                            <input type="text" name="sobre_titulo" class="form-control" value="{{ $config['sobre_titulo'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Texto da Biografia</label>
                            <textarea name="sobre_texto" class="form-control" rows="4">{{ $config['sobre_texto'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PÁGINA SOBRE - TEXTO COMPLETO --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    📄 Página Sobre — Texto Completo da Biografia
                </div>
                <div class="card-body">
                    <textarea name="sobre_pagina_texto" class="form-control" rows="8">{{ $config['sobre_pagina_texto'] }}</textarea>
                    <small class="text-muted">Este texto aparece na página /sobre (diferente do resumo da home)</small>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Foto da Página Sobre</label>
                        <input type="file" name="sobre_foto" class="form-control" accept="image/*">
                        @if($config['sobre_foto'])
                            <img src="{{ asset('traducaidiomas/img/' . $config['sobre_foto']) }}" class="mt-2 rounded" style="height:100px; object-fit:cover;">
                        @else
                            <img src="{{ asset('traducaidiomas/img/globo2.png') }}" class="mt-2 rounded" style="height:100px; object-fit:cover;">
                        @endif
                    </div>
                </div>
            </div>

            {{-- CORES --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                    🎨 Cores do Site
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cor Primária</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="cor_primaria" class="form-control form-control-color" value="{{ $config['cor_primaria'] }}">
                                <span class="text-muted small">{{ $config['cor_primaria'] }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cor Secundária</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="cor_secundaria" class="form-control form-control-color" value="{{ $config['cor_secundaria'] }}">
                                <span class="text-muted small">{{ $config['cor_secundaria'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-5">
                <button type="submit" class="btn btn-success px-5">
                    <i class="fas fa-save me-1"></i> Salvar Alterações
                </button>
                <a href="{{ route('admin.dash') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
