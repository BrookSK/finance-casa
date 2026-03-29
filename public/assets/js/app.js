/**
 * FinançasCasal - JavaScript Principal
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initAlerts();
    initMoneyInputs();
    initPageTitle();
});

// Sidebar toggle
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');
    const close = document.getElementById('sidebarClose');

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        });
    }

    const closeSidebar = () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    };

    if (close) close.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
}

// Auto-dismiss alerts
function initAlerts() {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });
}

// Money input formatting
function initMoneyInputs() {
    document.querySelectorAll('input[data-money]').forEach(input => {
        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            val = (parseInt(val) / 100).toFixed(2);
            e.target.value = val.replace('.', ',');
        });
    });
}

// Set page title from active nav
function initPageTitle() {
    const active = document.querySelector('.sidebar-link.active span, .bottom-nav-item.active span');
    const title = document.getElementById('pageTitle');
    if (active && title) {
        title.textContent = active.textContent;
    }
}

// Confirm delete
function confirmDelete(form, name) {
    if (confirm(`Tem certeza que deseja excluir "${name}"?`)) {
        form.submit();
    }
    return false;
}

// Toggle modal
function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.toggle('show');
    }
}

// Format money display
function formatMoney(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}


// PWA Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}


// Verificar notificações não lidas (badge)
function checkNotifications() {
    fetch('/api/notificacoes/count')
        .then(r => r.json())
        .then(data => {
            const badges = document.querySelectorAll('#notifBadgeSidebar, #notifBadgeTop');
            const dot = document.getElementById('notifBadgeTop');
            if (data.total > 0) {
                const sidebar = document.getElementById('notifBadgeSidebar');
                if (sidebar) { sidebar.textContent = data.total; sidebar.style.display = 'inline'; }
                if (dot) dot.style.display = 'block';
            }
        })
        .catch(() => {});
}

// Verificar a cada 60 segundos
if (document.querySelector('.main-content')) {
    checkNotifications();
    setInterval(checkNotifications, 60000);
}


// Dark mode toggle
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeUI(next);
}

function updateThemeUI(theme) {
    const icon = document.getElementById('themeIcon');
    const label = document.getElementById('themeLabel');
    if (icon) icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    if (label) label.textContent = theme === 'dark' ? 'Modo claro' : 'Modo escuro';
}

// Aplicar tema salvo
(function() {
    const saved = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    document.addEventListener('DOMContentLoaded', () => updateThemeUI(saved));
})();


// ===== PWA Install Banner =====
(function() {
    let deferredPrompt = null;
    const banner = document.getElementById('pwaInstallBanner');
    const installBtn = document.getElementById('pwaInstallBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');

    if (!banner) return;

    // Não mostrar se já instalado (standalone) ou se o usuário já dispensou
    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
    }

    function wasDismissed() {
        return localStorage.getItem('pwa_banner_dismissed') === '1';
    }

    // Capturar o evento beforeinstallprompt (Chrome/Edge/Samsung)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        if (!isStandalone() && !wasDismissed()) {
            banner.style.display = 'block';
        }
    });

    // Botão instalar
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const result = await deferredPrompt.userChoice;
            if (result.outcome === 'accepted') {
                banner.style.display = 'none';
                localStorage.setItem('pwa_banner_dismissed', '1');
            }
            deferredPrompt = null;
        });
    }

    // Botão fechar — não mostra mais por 7 dias
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            banner.style.display = 'none';
            localStorage.setItem('pwa_banner_dismissed', '1');
            localStorage.setItem('pwa_banner_dismissed_at', Date.now().toString());
        });
    }

    // Reexibir após 7 dias se dispensou
    const dismissedAt = localStorage.getItem('pwa_banner_dismissed_at');
    if (dismissedAt && (Date.now() - parseInt(dismissedAt)) > 7 * 24 * 60 * 60 * 1000) {
        localStorage.removeItem('pwa_banner_dismissed');
        localStorage.removeItem('pwa_banner_dismissed_at');
    }

    // Fallback para iOS (Safari não tem beforeinstallprompt)
    const isIos = /iphone|ipad|ipod/.test(navigator.userAgent.toLowerCase());
    const isSafari = /safari/.test(navigator.userAgent.toLowerCase()) && !/chrome/.test(navigator.userAgent.toLowerCase());

    if (isIos && isSafari && !isStandalone() && !wasDismissed()) {
        // Mostrar banner com instrução manual
        if (installBtn) {
            installBtn.textContent = 'Como instalar';
            installBtn.addEventListener('click', () => {
                alert('Toque no botão de compartilhar (ícone ⬆) e depois em "Adicionar à Tela de Início".');
            }, { once: true });
        }
        banner.style.display = 'block';
    }

    // Esconder se já está em modo standalone
    if (isStandalone()) {
        banner.style.display = 'none';
    }
})();
