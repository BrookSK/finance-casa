<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= APP_NAME ?></title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="apple-touch-icon" href="/assets/img/icon-192.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <!-- Sidebar Desktop -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-wallet"></i>
                <span><?= APP_NAME ?></span>
            </div>
            <button class="sidebar-close" id="sidebarClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?= strtoupper(substr($currentUser['nome'] ?? 'U', 0, 1)) ?></div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name"><?= e($currentUser['nome'] ?? '') ?></span>
                <span class="sidebar-user-role"><?= ($currentUser['papel'] ?? '') === 'admin' ? 'Administrador' : 'Usuário' ?></span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="/dashboard" class="sidebar-link <?= isActive('/dashboard') ?: isActive('/') ?>">
                <i class="fas fa-chart-pie"></i><span>Dashboard</span>
            </a>
            <a href="/dia-pagamento" class="sidebar-link <?= isActive('/dia-pagamento') ?>">
                <i class="fas fa-hand-holding-usd"></i><span>Dia do Pagamento</span>
            </a>
            <a href="/timeline" class="sidebar-link <?= isActive('/timeline') ?>">
                <i class="fas fa-calendar-alt"></i><span>Timeline</span>
            </a>
            <a href="/receitas" class="sidebar-link <?= isActive('/receitas') ?>">
                <i class="fas fa-arrow-up"></i><span>Receitas</span>
            </a>
            <a href="/despesas" class="sidebar-link <?= isActive('/despesas') ?>">
                <i class="fas fa-arrow-down"></i><span>Despesas</span>
            </a>
            <a href="/cofrinhos" class="sidebar-link <?= isActive('/cofrinhos') ?>">
                <i class="fas fa-piggy-bank"></i><span>Cofrinhos</span>
            </a>
            <a href="/cartoes" class="sidebar-link <?= isActive('/cartoes') ?>">
                <i class="fas fa-credit-card"></i><span>Cartões</span>
            </a>
            <a href="/faturas" class="sidebar-link <?= isActive('/faturas') ?>">
                <i class="fas fa-file-invoice-dollar"></i><span>Faturas</span>
            </a>
            <div style="padding:12px 20px 4px;font-size:11px;text-transform:uppercase;color:rgba(255,255,255,0.3);font-weight:600;">Ferramentas</div>
            <a href="/listas" class="sidebar-link <?= isActive('/listas') ?>">
                <i class="fas fa-shopping-cart"></i><span>Lista de Compras</span>
            </a>
            <a href="/orcamentos" class="sidebar-link <?= isActive('/orcamentos') ?>">
                <i class="fas fa-chart-bar"></i><span>Orçamentos</span>
            </a>
            <a href="/relatorios" class="sidebar-link <?= isActive('/relatorios') ?>">
                <i class="fas fa-chart-line"></i><span>Relatórios</span>
            </a>
            <a href="/contas" class="sidebar-link <?= isActive('/contas') ?>">
                <i class="fas fa-university"></i><span>Contas Bancárias</span>
            </a>
            <a href="/notificacoes" class="sidebar-link <?= isActive('/notificacoes') ?>">
                <i class="fas fa-bell"></i><span>Notificações</span>
                <span id="notifBadgeSidebar" class="notif-badge" style="display:none;"></span>
            </a>
            <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
            <a href="/configuracoes" class="sidebar-link <?= isActive('/configuracoes') ?>">
                <i class="fas fa-cog"></i><span>Configurações</span>
            </a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-footer">
            <button class="theme-toggle sidebar-link" onclick="toggleTheme()">
                <i class="fas fa-moon" id="themeIcon"></i><span id="themeLabel">Modo escuro</span>
            </button>
            <a href="/logout" class="sidebar-link"><i class="fas fa-sign-out-alt"></i><span>Sair</span></a>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top bar -->
        <header class="topbar">
            <button class="topbar-menu" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h1 class="topbar-title" id="pageTitle">Dashboard</h1>
            <div class="topbar-actions">
                <a href="/notificacoes" class="topbar-notif" title="Notificações" style="position:relative;color:var(--text);font-size:18px;margin-right:12px;">
                    <i class="fas fa-bell"></i>
                    <span id="notifBadgeTop" class="notif-dot" style="display:none;"></span>
                </a>
                <span class="topbar-user"><?= e($currentUser['nome'] ?? '') ?></span>
            </div>
        </header>

        <!-- Flash messages -->
        <?php if (hasFlash('success')): ?>
            <div class="alert alert-success"><?= e(getFlash('success')) ?></div>
        <?php endif; ?>
        <?php if (hasFlash('error')): ?>
            <div class="alert alert-error"><?= e(getFlash('error')) ?></div>
        <?php endif; ?>

        <!-- Page content -->
        <div class="page-content">
            <?= $content ?>
        </div>
    </main>

    <!-- Bottom Navigation Mobile -->
    <nav class="bottom-nav">
        <a href="/dashboard" class="bottom-nav-item <?= isActive('/dashboard') ?>">
            <i class="fas fa-chart-pie"></i><span>Home</span>
        </a>
        <a href="/timeline" class="bottom-nav-item <?= isActive('/timeline') ?>">
            <i class="fas fa-calendar-alt"></i><span>Timeline</span>
        </a>
        <a href="/cofrinhos" class="bottom-nav-item <?= isActive('/cofrinhos') ?>">
            <i class="fas fa-piggy-bank"></i><span>Cofrinhos</span>
        </a>
        <a href="/despesas" class="bottom-nav-item <?= isActive('/despesas') ?>">
            <i class="fas fa-arrow-down"></i><span>Despesas</span>
        </a>
        <a href="/cartoes" class="bottom-nav-item <?= isActive('/cartoes') ?>">
            <i class="fas fa-credit-card"></i><span>Cartões</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <!-- Banner instalar PWA -->
    <div id="pwaInstallBanner" class="pwa-banner" style="display:none;">
        <div class="pwa-banner-content">
            <div class="pwa-banner-icon"><i class="fas fa-download"></i></div>
            <div class="pwa-banner-text">
                <span class="pwa-banner-title">Instalar FinançasCasal</span>
                <span class="pwa-banner-desc">Acesse direto da tela inicial do celular</span>
            </div>
            <button id="pwaInstallBtn" class="btn btn-primary btn-sm">Instalar</button>
            <button id="pwaCloseBtn" class="pwa-banner-close"><i class="fas fa-times"></i></button>
        </div>
    </div>
</body>
</html>
