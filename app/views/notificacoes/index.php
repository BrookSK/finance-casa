<div class="page-header">
    <span class="page-header-title">Notificações</span>
    <?php if ($totalNaoLidas > 0): ?>
    <form method="POST" action="/notificacoes/ler-todas" style="display:inline;">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-check-double"></i> Ler todas</button>
    </form>
    <?php endif; ?>
</div>

<?php if ($totalNaoLidas > 0): ?>
<div class="alert alert-info" style="margin:0 0 16px;">
    Você tem <?= $totalNaoLidas ?> notificação(ões) não lida(s)
</div>
<?php endif; ?>

<div class="card">
    <?php if (empty($notificacoes)): ?>
        <div class="empty-state">
            <i class="fas fa-bell"></i>
            <p>Nenhuma notificação</p>
        </div>
    <?php else: ?>
        <?php foreach ($notificacoes as $n): ?>
        <div class="notif-item <?= !$n['lida'] ? 'unread' : '' ?>">
            <div class="notif-icon <?= e($n['tipo']) ?>">
                <i class="fas fa-<?= $n['tipo'] === 'urgente' ? 'exclamation-triangle' : ($n['tipo'] === 'alerta' ? 'exclamation-circle' : ($n['tipo'] === 'sucesso' ? 'check-circle' : 'info-circle')) ?>"></i>
            </div>
            <div class="notif-content">
                <div class="notif-title"><?= e($n['titulo']) ?></div>
                <div class="notif-msg"><?= e($n['mensagem']) ?></div>
                <div class="notif-time"><?= formatDateTime($n['criado_em']) ?></div>
            </div>
            <div style="display:flex;gap:4px;flex-shrink:0;">
                <?php if ($n['link']): ?>
                <a href="<?= e($n['link']) ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;font-size:11px;">
                    <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
                <?php if (!$n['lida']): ?>
                <form method="POST" action="/notificacoes/lida/<?= $n['id'] ?>" style="display:inline;">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-sm" style="padding:4px 8px;font-size:11px;background:var(--bg);border:1px solid var(--border);">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
