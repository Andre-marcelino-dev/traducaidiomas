{{-- resources/views/aluno/partials/modal-reagendamento.blade.php --}}

<div class="modal fade" id="modalReagendamento" tabindex="-1" aria-labelledby="modalReagendamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-radius:16px 16px 0 0; padding:1.5rem 1.5rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-calendar-alt text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" id="modalReagendamentoLabel" style="color:#92400e;">
                            Solicitar Reagendamento
                        </h5>
                        <small style="color:#b45309;">Envie sua solicitação ao professor</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            {{-- Body --}}
            <form action="{{ route('aluno.reagendamento.solicitar') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">

                    {{-- Alerta de sucesso/erro dentro do modal via flash --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Seleção de Aula --}}
                    <div class="mb-3">
                        <label for="aula_id" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-book-open me-1 text-warning"></i> Aula
                        </label>
                        <select name="aula_id" id="aula_id"
                            class="form-select @error('aula_id') is-invalid @enderror"
                            style="border-radius:10px;border-color:#e5e7eb;">
                            <option value="">Selecione a aula...</option>
                            {{-- Passe $aulas da controller ou use a variável disponível no contexto --}}
                            @isset($aulas)
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->titulo }} — {{ optional($aula->data_hora)->format('d/m/Y H:i') ?? 'Sem data' }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('aula_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Data sugerida (opcional) --}}
                    <div class="mb-3">
                        <label for="data_sugerida" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-clock me-1 text-warning"></i> Data/Hora Sugerida
                            <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="datetime-local" name="data_sugerida" id="data_sugerida"
                            class="form-control @error('data_sugerida') is-invalid @enderror"
                            style="border-radius:10px;border-color:#e5e7eb;"
                            value="{{ old('data_sugerida') }}"
                            min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}">
                        @error('data_sugerida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Motivo --}}
                    <div class="mb-2">
                        <label for="motivo" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-comment-alt me-1 text-warning"></i> Motivo
                        </label>
                        <textarea name="motivo" id="motivo" rows="3"
                            class="form-control @error('motivo') is-invalid @enderror"
                            style="border-radius:10px;border-color:#e5e7eb;resize:none;"
                            placeholder="Explique o motivo do reagendamento..."
                            maxlength="500">{{ old('motivo') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            @error('motivo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <small class="text-muted">Mínimo 10 caracteres</small>
                            @enderror
                            <small class="text-muted" id="motivoCount">0/500</small>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                        style="border-radius:10px;border:1px solid #e5e7eb;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn px-4 text-white fw-semibold"
                        style="background:#d97706;border-radius:10px;border:none;">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Solicitação
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Script: contador de caracteres --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const motivo = document.getElementById('motivo');
        const count  = document.getElementById('motivoCount');
        if (motivo && count) {
            const update = () => count.textContent = motivo.value.length + '/500';
            motivo.addEventListener('input', update);
            update();
        }
    });
</script>

@if($errors->any() || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('modalReagendamento'));
        modal.show();
    });
</script>
@endif
