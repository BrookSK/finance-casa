/**
 * FinançasCasal - JavaScript Principal
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initAlerts();
    initMoneyInputs();
    initPageTitle();
    initNotifBadge();
    initPWA();
});

// ===== Sidebar toggle =====
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

// ===== Auto-dismiss alerts =====
function initAlerts() {
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => el.remove(), 300);
        }, 4000);
    });
}

// ===== Money input formatting =====
function initMoneyInputs() {
    document.querySelectorAll('input[data-money]').forEach(input => {
        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (!val) { e.target.value = ''; return; }
            val = (parseInt(val) / 100).toFixed(2);
            e.target.value = val.replace('.', ',');
        });
    });
}

// ===== Page title from active nav =====
function initPageTitle() {
    const active = document.querySelector('.sidebar-link.active span, .bottom-nav-item.active span');
    const title = document.getElementById('pageTitle');
    if (active && title) title.textContent = active.textContent;
}

// ===== Notification badge =====
function initNotifBadge() {
    if (!document.querySelector('.main-content')) return;
    checkNotifications();
    setInterval(checkNotifications, 60000);
}

function checkNotifications() {
    fetch('/api/notificacoes/count')
        .then(r => r.json())
        .then(data => {
            const sidebar = document.getElementById('notifBadgeSidebar');
            const dot = document.getElementById('notifBadgeTop');
            if (data.total > 0) {
                if (sidebar) { sidebar.textContent = data.total; sidebar.style.display = 'inline'; }
                if (dot) dot.style.display = 'block';
            } else {
                if (sidebar) sidebar.style.display = 'none';
                if (dot) dot.style.display = 'none';
            }
        })
        .catch(() => {});
}

// ===== Helpers =====
function confirmDelete(form, name) {
    if (confirm(`Tem certeza que deseja excluir "${name}"?`)) form.submit();
    return false;
}

function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.toggle('show');
}

function formatMoney(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ===== Dark mode =====
function toggleTheme() {
    const html = document.documentElement;
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
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

// Aplicar tema salvo imediatamente
(function() {
    const saved = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    document.addEventListener('DOMContentLoaded', () => updateThemeUI(saved));
})();

// ===== PWA: Service Worker + Install Banner + Notificações =====
function initPWA() {
    registerServiceWorker();
    initInstallBanner();
}

function registerServiceWorker() {
    if (!('serviceWorker' in navigator)) return;

    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('SW registrado:', registration.scope);

            // Pedir permissão de notificação após login
            if (document.querySelector('.main-content')) {
                requestNotificationPermission(registration);
            }
        })
        .catch(err => console.log('SW falhou:', err));
}

function requestNotificationPermission(registration) {
    // Só pedir se ainda não decidiu
    if (Notification.permission !== 'default') return;

    // Esperar 5 segundos para não ser invasivo
    setTimeout(() => {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Notificações permitidas');
                // Mostrar notificação de boas-vindas
                registration.showNotification('FinançasCasal', {
                    body: 'Notificações ativadas! Você será avisado sobre vencimentos e alertas.',
                    icon: '/assets/img/icon-192.png',
                    badge: '/assets/img/icon-192.png',
                    tag: 'welcome'
                });
            }
        });
    }, 5000);
}

function initInstallBanner() {
    let deferredPrompt = null;
    const banner = document.getElementById('pwaInstallBanner');
    const installBtn = document.getElementById('pwaInstallBtn');
    const closeBtn = document.getElementById('pwaCloseBtn');

    if (!banner) return;

    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
    }

    function wasDismissed() {
        const dismissed = localStorage.getItem('pwa_dismiss');
        if (!dismissed) return false;
        // Reexibir após 7 dias
        if ((Date.now() - parseInt(dismissed)) > 7 * 86400000) {
            localStorage.removeItem('pwa_dismiss');
            return false;
        }
        return true;
    }

    // Se já está instalado, nunca mostrar
    if (isStandalone()) return;

    // Android/Chrome: capturar beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (!wasDismissed()) banner.style.display = 'block';
    });

    // Botão instalar
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                // Android: prompt nativo
                deferredPrompt.prompt();
                const result = await deferredPrompt.userChoice;
                if (result.outcome === 'accepted') {
                    localStorage.setItem('pwa_dismiss', Date.now().toString());
                }
                deferredPrompt = null;
                banner.style.display = 'none';
            }
        });
    }

    // Botão fechar
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            banner.style.display = 'none';
            localStorage.setItem('pwa_dismiss', Date.now().toString());
        });
    }

    // iOS Safari: não tem beforeinstallprompt, mostrar instrução manual
    const ua = navigator.userAgent.toLowerCase();
    const isIos = /iphone|ipad|ipod/.test(ua);
    const isSafari = /safari/.test(ua) && !/crios|fxios|chrome/.test(ua);

    if (isIos && isSafari && !wasDismissed()) {
        banner.style.display = 'block';
        if (installBtn) {
            installBtn.textContent = 'Como instalar';
            installBtn.onclick = () => {
                alert(
                    'Para instalar no iPhone/iPad:\n\n' +
                    '1. Toque no ícone de compartilhar (⬆️) na barra do Safari\n' +
                    '2. Role para baixo e toque em "Adicionar à Tela de Início"\n' +
                    '3. Toque em "Adicionar"'
                );
            };
        }
    }
}

// ===== Notificações locais periódicas =====
// Verificar vencimentos e mostrar notificação no navegador
function checkAndNotifyBrowser() {
    if (Notification.permission !== 'granted') return;
    if (!('serviceWorker' in navigator)) return;

    fetch('/api/notificacoes/count')
        .then(r => r.json())
        .then(data => {
            if (data.total > 0) {
                navigator.serviceWorker.ready.then(reg => {
                    reg.showNotification('FinançasCasal', {
                        body: `Você tem ${data.total} notificação(ões) pendente(s)`,
                        icon: '/assets/img/icon-192.png',
                        badge: '/assets/img/icon-192.png',
                        tag: 'pending-notifs',
                        renotify: false,
                        data: { url: '/notificacoes' }
                    });
                });
            }
        })
        .catch(() => {});
}

// Verificar a cada 5 minutos (só se a aba estiver ativa)
if (document.querySelector('.main-content')) {
    setInterval(checkAndNotifyBrowser, 5 * 60 * 1000);
}
