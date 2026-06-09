@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="app-content">
    <div class="container-fluid">
        <div class="row">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gerenciamento de Serviços</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalNovoServico">
                            <i class="bi bi-plus-circle"></i>
                            Novo Serviço
                        </button>
                    </div>
                </div>
            
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Foto</th>
                                <th>Título</th>
                                <th>Subtitulo</th>
                                <th>Benefícios</th>
                                <th>Preço</th>
                                <th>Conteúdo</th>
                                <th>Ordem</th>
                                <th>Status</th>
                                <th style="width: 200px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicos as $linha)
                            <tr class="align-middle">
                                <td>
                                    <a href="{{ asset('davilla/images/'. $linha->imagem_servico) }}" data-lightbox="galeria">
                                        <img src="{{ asset('davilla/images/'. $linha->imagem_servico) }}" class="img-thumbnail" alt="{{ $linha->titulo_servico }}" data-lightbox="roadtrip">
                                    </a>
                                </td>
                                <td>
                                    <div class="tblservico">
                                        <div class="tituloservico">
                                            {{ $linha->titulo_servico }}
                                        </div>
                                        <div class="descservico">
                                            {{ $linha->subtitulo_servico }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $linha->lista_beneficios_servico }}</td>
                                <td>{{ $linha->conteudo_servico }}</td>
                                <td>R${{ $linha->preco_servico }}</td>
                                <td>{{ $linha->conteudo_servico }}</td>
                                <td>{{ $linha->ordenar_servico }}</td>
                                <td>
                                    @if($linha->status_servico === 'ATIVO')
                                    <span class="badge text-bg-success">Ativo</span>
                                    @else
                                    <span class="badge text-bg-danger">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarservico{{ $linha->id_servico }}">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </td>

                                <td>
                                    {{-- Ajustado para 'servico' no singular para combinar com o final do arquivo --}}
                                    @include('admin.servicos.modal.edit', ['servico' => $linha])

                                    @if ($linha->status_servico === 'ATIVO')
                                    <form action="{{ route('admin.servicos.destroy', $linha->id_servico) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja deletar este serviço?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                    @else
                                    {{-- Seu código antigo comentado --}}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Nenhum serviço cadastrado</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>

        </div>
    </div>
</div>

@include('admin.servicos.modal.create')

@endsection