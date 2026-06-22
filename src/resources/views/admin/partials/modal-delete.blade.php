<div class="del-overlay" id="delOverlay" onclick="fecharModalExcluir()">
    <div class="del-modal" onclick="event.stopPropagation()">
        <div class="del-icon-wrap">
            <div class="del-icon-circle">
                <i class="fas fa-trash-alt"></i>
            </div>
            <div class="del-icon-ring"></div>
        </div>
        <h5 class="del-title">{{ $delTitulo ?? 'Excluir Item' }}</h5>
        <p class="del-desc">{{ $delDescricao ?? 'Você está prestes a excluir:' }}</p>
        <p class="del-item-nome" id="delItemNome"></p>
        <p class="del-warn"><i class="fas fa-exclamation-triangle me-1"></i> Essa ação não pode ser desfeita.</p>
        <div class="del-actions">
            <button type="button" class="del-btn-cancelar" onclick="fecharModalExcluir()">
                <i class="fas fa-xmark me-1"></i> Cancelar
            </button>
            <button type="button" class="del-btn-confirmar" id="delBtnConfirmar">
                <i class="fas fa-trash-alt me-1"></i> Sim, excluir
            </button>
        </div>
    </div>
</div>

<script>
    let _delForm = null;

    function abrirModalExcluir(btn) {
        var nome = btn.getAttribute('data-nome') || btn.getAttribute('data-titulo') || '';
        document.getElementById('delItemNome').textContent = nome;
        _delForm = btn.closest('.form-delete');
        document.getElementById('delOverlay').classList.add('del-active');
    }

    function fecharModalExcluir() {
        var overlay = document.getElementById('delOverlay');
        overlay.classList.remove('del-active');
        overlay.classList.add('del-closing');
        setTimeout(function() {
            overlay.classList.remove('del-closing');
            _delForm = null;
        }, 300);
    }

    document.getElementById('delBtnConfirmar').addEventListener('click', function() {
        if (_delForm) _delForm.submit();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') fecharModalExcluir();
    });
</script>
