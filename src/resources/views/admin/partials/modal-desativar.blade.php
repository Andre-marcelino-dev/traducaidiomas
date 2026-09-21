<div class="del-overlay tone-amber" id="desativarOverlay" onclick="fecharModalDesativar()">
    <div class="del-modal" onclick="event.stopPropagation()">
        <div class="del-icon-wrap">
            <div class="del-icon-circle">
                <i class="fas fa-ban"></i>
            </div>
            <div class="del-icon-ring"></div>
        </div>
        <h5 class="del-title">{{ $desTitulo ?? 'Desativar Item' }}</h5>
        <p class="del-desc">{{ $desDescricao ?? 'Você está prestes a desativar:' }}</p>
        <p class="del-item-nome" id="desativarItemNome"></p>
        <p class="del-warn"><i class="fas fa-circle-info me-1"></i> Ela deixará de aparecer nas listas, mas pode ser reativada quando quiser.</p>
        <div class="del-actions">
            <button type="button" class="del-btn-cancelar" onclick="fecharModalDesativar()">
                <i class="fas fa-xmark me-1"></i> Cancelar
            </button>
            <button type="button" class="del-btn-confirmar" id="desativarBtnConfirmar">
                <i class="fas fa-ban me-1"></i> Sim, desativar
            </button>
        </div>
    </div>
</div>

<script>
    let _desForm = null;

    function abrirModalDesativar(btn) {
        var nome = btn.getAttribute('data-nome') || btn.getAttribute('data-titulo') || '';
        document.getElementById('desativarItemNome').textContent = nome;
        _desForm = btn.closest('.form-desativar');
        document.getElementById('desativarOverlay').classList.add('del-active');
    }

    function fecharModalDesativar() {
        var overlay = document.getElementById('desativarOverlay');
        overlay.classList.remove('del-active');
        overlay.classList.add('del-closing');
        setTimeout(function() {
            overlay.classList.remove('del-closing');
            _desForm = null;
        }, 300);
    }

    document.getElementById('desativarBtnConfirmar').addEventListener('click', function() {
        if (_desForm) _desForm.submit();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') fecharModalDesativar();
    });
</script>
