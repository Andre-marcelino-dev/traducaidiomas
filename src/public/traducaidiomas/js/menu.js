// Menu hambúrguer do site (header.blade.php + menu.css).
// No celular/tablet o <nav class="menu"> vira uma gaveta lateral.
(function () {
    const html = document.documentElement;
    const topo = document.querySelector('.topo');
    const botao = document.querySelector('.menu-toggle');
    const menu = document.getElementById('menu-principal');
    const overlay = document.querySelector('.menu-overlay');
    if (!topo || !botao || !menu) return;

    // Mesmo valor do @media de menu.css
    const mobile = window.matchMedia('(max-width: 1199.98px)');

    function abrir() {
        html.classList.add('menu-aberto');
        botao.setAttribute('aria-expanded', 'true');
        botao.setAttribute('aria-label', 'Fechar menu');
        const primeiro = menu.querySelector('a');
        if (primeiro) setTimeout(() => primeiro.focus({ preventScroll: true }), 300);
    }

    function fechar(devolverFoco) {
        if (!html.classList.contains('menu-aberto')) return;
        html.classList.remove('menu-aberto');
        botao.setAttribute('aria-expanded', 'false');
        botao.setAttribute('aria-label', 'Abrir menu');
        if (devolverFoco) botao.focus();
    }

    botao.addEventListener('click', () => {
        html.classList.contains('menu-aberto') ? fechar() : abrir();
    });

    if (overlay) overlay.addEventListener('click', () => fechar());

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') fechar(true);
    });

    // Clicar num link fecha a gaveta (útil para âncoras na mesma página)
    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => fechar());
    });

    // Submenu de Serviços em acordeão no celular
    menu.querySelectorAll('.menu-sub-toggle').forEach((sub) => {
        sub.addEventListener('click', () => {
            const item = sub.closest('.dropdown');
            const aberto = item.classList.toggle('sub-aberto');
            sub.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        });
    });

    // Voltou para o layout de desktop (girou o tablet, redimensionou): fecha a gaveta
    mobile.addEventListener('change', (e) => {
        if (!e.matches) fechar();
    });

    // Sombra no topo depois de rolar a página
    const marcarRolagem = () => topo.classList.toggle('rolou', window.scrollY > 10);
    window.addEventListener('scroll', marcarRolagem, { passive: true });
    marcarRolagem();
})();
