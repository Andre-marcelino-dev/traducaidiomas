@extends('admin.layout.admin')

@section('content')

<style>
    .servico-form-page {
        padding-bottom: 34px;
    }

    .servico-form-header-page {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .servico-form-header-page h1 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .servico-form-header-page p {
        color: #64748b;
        margin: 4px 0 0;
        font-size: .92rem;
    }

    .servico-form-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
        background: #fff;
    }

    .servico-form-card-header {
        padding: 22px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .servico-form-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 10px 22px rgba(22, 61, 143, .18);
    }

    .servico-form-card-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
    }

    .servico-form-card-header span {
        display: block;
        margin-top: 2px;
        font-size: .82rem;
        color: #64748b;
    }

    .servico-form-body {
        padding: 26px;
    }

    .form-section-title {
        font-size: .78rem;
        font-weight: 800;
        color: #163d8f;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 4px 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #163d8f;
        display: inline-block;
    }

    .form-label {
        font-size: .86rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 13px;
        border: 1px solid #dbe3ef;
        min-height: 35px;
        font-size: .92rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    textarea.form-control {
        resize: vertical;
    }

    .servico-hint {
        color: #94a3b8;
        font-size: .76rem;
        margin-top: 5px;
    }

    .servico-preview-box {
        min-height: 180px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        text-align: center;
        transition: .2s ease;
    }

    .servico-preview-box:hover {
        border-color: #163d8f;
        background: #f8fbff;
    }

    .servico-preview-box img {
        max-width: 100%;
        max-height: 150px;
        display: none;
        border-radius: 14px;
        object-fit: cover;
    }

    .servico-preview-empty {
        color: #94a3b8;
        font-size: .86rem;
    }

    .servico-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #edf2f7;
    }

    .btn-servico-cancelar {
        border-radius: 12px;
        padding: 10px 18px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(239, 68, 68, .12);
        transition: .22s ease;
    }

    .btn-servico-cancelar:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(239, 68, 68, .22);
    }

    .btn-servico-salvar {
        border-radius: 12px;
        padding: 10px 22px;
        background: #10b981;
        color: #fff;
        border: 0;
        font-weight: 800;
        box-shadow: 0 10px 20px rgba(16, 185, 129, .18);
    }

    .btn-servico-salvar:hover {
        background: #059669;
        color: #fff;
    }

    .alert {
        border: 0;
        border-radius: 15px;
    }

    @media (max-width: 768px) {
        .servico-form-header-page {
            flex-direction: column;
            align-items: flex-start;
        }

        .servico-form-header-page .btn-servico-cancelar {
            width: 100%;
            text-align: center;
        }

        .servico-form-body {
            padding: 18px;
        }

        .servico-actions {
            flex-direction: column-reverse;
        }

        .servico-actions .btn,
        .servico-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid servico-form-page">

    <div class="servico-form-header-page">
        <div>
            <h1>Novo Serviço</h1>
            <p>Cadastre um novo serviço para exibição no site.</p>
        </div>

        <a href="{{ route('admin.servicos.index') }}" class="btn-servico-cancelar">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Ops! Existem erros no formulário.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card servico-form-card">
        <div class="servico-form-card-header">
            <div class="servico-form-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5V6.5A2.5 2.5 0 0 1 6.5 4H20v15H6.5A2.5 2.5 0 0 1 4 16.5"></path>
                    <path d="M8 8h8"></path>
                    <path d="M8 12h6"></path>
                    <path d="M12 16h4"></path>
                </svg>
            </div>

            <div>
                <h5>Dados do Serviço</h5>
                <span>Informações principais, CTA, conteúdo e imagem</span>
            </div>
        </div>

        <div class="servico-form-body">
            <form action="{{ route('admin.servicos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section-title">Informações principais</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Título <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="titulo_servico"
                            class="form-control @error('titulo_servico') is-invalid @enderror"
                            value="{{ old('titulo_servico') }}"
                            placeholder="Ex: Curso de Inglês"
                            required
                        >

                        @error('titulo_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Subtítulo <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="subtitulo_servico"
                            class="form-control @error('subtitulo_servico') is-invalid @enderror"
                            value="{{ old('subtitulo_servico') }}"
                            placeholder="Ex: Aprenda inglês do básico ao avançado"
                            required
                        >

                        @error('subtitulo_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Língua</label>

                        <input
                            type="text"
                            name="lingua_servico"
                            class="form-control @error('lingua_servico') is-invalid @enderror"
                            value="{{ old('lingua_servico') }}"
                            placeholder="Ex: Inglês"
                        >

                        @error('lingua_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Preço</label>

                        <input
                            type="text"
                            name="preco_servico"
                            class="form-control @error('preco_servico') is-invalid @enderror"
                            value="{{ old('preco_servico') }}"
                            placeholder="Ex: R$ 199,90"
                        >

                        @error('preco_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ordem</label>

                        <input
                            type="number"
                            name="ordenar_servico"
                            class="form-control @error('ordenar_servico') is-invalid @enderror"
                            value="{{ old('ordenar_servico') }}"
                            placeholder="Ex: 1"
                            min="0"
                        >

                        @error('ordenar_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Chamada e contato</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Link WhatsApp</label>

                        <input
                            type="text"
                            name="link_whatsapp"
                            class="form-control @error('link_whatsapp') is-invalid @enderror"
                            value="{{ old('link_whatsapp') }}"
                            placeholder="Ex: https://wa.me/5511999999999"
                        >

                        @error('link_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="servico-hint">
                            Pode ser link completo do WhatsApp ou telefone usado no botão.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Classe Estilo</label>

                        <input
                            type="text"
                            name="classe_estilo_servico"
                            class="form-control @error('classe_estilo_servico') is-invalid @enderror"
                            value="{{ old('classe_estilo_servico') }}"
                            placeholder="Ex: card-blue, destaque, premium"
                        >

                        @error('classe_estilo_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="servico-hint">
                            Use apenas se o site público utilizar classes específicas.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">CTA Título</label>

                        <input
                            type="text"
                            name="cta_titulo_servico"
                            class="form-control @error('cta_titulo_servico') is-invalid @enderror"
                            value="{{ old('cta_titulo_servico') }}"
                            placeholder="Ex: Comece hoje"
                        >

                        @error('cta_titulo_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">CTA Texto</label>

                        <input
                            type="text"
                            name="cta_texto_servico"
                            class="form-control @error('cta_texto_servico') is-invalid @enderror"
                            value="{{ old('cta_texto_servico') }}"
                            placeholder="Ex: Fale conosco e saiba mais"
                        >

                        @error('cta_texto_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Título Professor</label>

                        <input
                            type="text"
                            name="titulo_professor_servico"
                            class="form-control @error('titulo_professor_servico') is-invalid @enderror"
                            value="{{ old('titulo_professor_servico') }}"
                            placeholder="Ex: Professor especialista"
                        >

                        @error('titulo_professor_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contato Texto</label>

                        <input
                            type="text"
                            name="contato_text_servico"
                            class="form-control @error('contato_text_servico') is-invalid @enderror"
                            value="{{ old('contato_text_servico') }}"
                            placeholder="Ex: Tire suas dúvidas pelo WhatsApp"
                        >

                        @error('contato_text_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Conteúdo do serviço</div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Lista de Benefícios</label>

                        <textarea
                            name="lista_beneficios_servico"
                            class="form-control @error('lista_beneficios_servico') is-invalid @enderror"
                            rows="3"
                            placeholder="Ex: Aulas ao vivo, material incluso, certificado..."
                        >{{ old('lista_beneficios_servico') }}</textarea>

                        @error('lista_beneficios_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="servico-hint">
                            Separe os benefícios por linha ou por vírgula, conforme seu site estiver usando.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Conteúdo</label>

                        <textarea
                            name="conteudo_servico"
                            class="form-control @error('conteudo_servico') is-invalid @enderror"
                            rows="5"
                            placeholder="Descreva o serviço com mais detalhes..."
                        >{{ old('conteudo_servico') }}</textarea>

                        @error('conteudo_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Imagem do serviço</div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-6">
                        <label class="form-label">Imagem</label>

                        <input
                            type="file"
                            name="imagem_servico"
                            id="imagem_servico"
                            class="form-control @error('imagem_servico') is-invalid @enderror"
                            accept="image/*"
                            onchange="previewImagemServico(event)"
                        >

                        @error('imagem_servico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="servico-hint">
                            Formatos recomendados: JPG, PNG ou WebP.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Pré-visualização</label>

                        <div class="servico-preview-box">
                            <img
                                id="previewImagem"
                                src=""
                                alt="Pré-visualização da imagem"
                            >

                            <span id="previewTexto" class="servico-preview-empty">
                                Nenhuma imagem selecionada
                            </span>
                        </div>
                    </div>
                </div>

                <div class="servico-actions">
                    <a href="{{ route('admin.servicos.index') }}" class="btn-servico-cancelar">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-servico-salvar">
                        Salvar Serviço
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function previewImagemServico(event) {
    const input = event.target;
    const previewImagem = document.getElementById('previewImagem');
    const previewTexto = document.getElementById('previewTexto');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImagem.src = e.target.result;
            previewImagem.style.display = 'block';
            previewTexto.style.display = 'none';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        previewImagem.src = '';
        previewImagem.style.display = 'none';
        previewTexto.style.display = 'block';
    }
}
</script>

@endsection