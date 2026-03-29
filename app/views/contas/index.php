<div class="page-header">
    <span class="page-header-title">Contas Bancárias</span>
    <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
    <a href="/contas/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nova</a>
    <?php endif; ?>
</div>

<!-- Resumo -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr 1fr;">
    <div class="stat-card">
        <div class="stat-icon balance"><i class="fas fa-user"></i></div>
        <div class="stat-label">Pessoais Lucas</div>
        <div class="stat-value"><?= count(array_filter($contas['pessoal'], fn($c) => $c['usuario_id'] == 1)) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#ec4899;"><i class="fas fa-user"></i></div>
        <div class="stat-label">Pessoais Bia</div>
        <div class="stat-value"><?= count(array_filter($contas['pessoal'], fn($c) => $c['usuario_id'] == 2)) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(139,92,246,0.1);color:#8b5cf6;"><i class="fas fa-building"></i></div>
        <div class="stat-label">Empresa</div>
        <div class="stat-value"><?= count($contas['empresa']) ?></div>
    </div>
</div>

<!-- Contas Pessoais Lucas -->
<?php
$contasLucas = array_filter($contas['pessoal'], fn($c) => $c['usuario_id'] == 1);
$contasBia = array_filter($contas['pessoal'], fn($c) => $c['usuario_id'] == 2);
?>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user"></i> Lucas - Pessoais</span>
    </div>
    <?php foreach ($contasLucas as $c): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:rgba(99,102,241,0.1);color:#6366f1;">
            <i class="fas fa-university"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($c['nome']) ?></div>
            <div class="list-item-subtitle"><?= e($c['banco']) ?><?= $c['observacao'] ? ' · ' . e($c['observacao']) : '' ?></div>
        </div>
        <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
        <div style="display:flex;gap:4px;">
            <a href="/contas/editar/<?= $c['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 8px;"><i class="fas fa-edit" style="font-size:12px;"></i></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if (empty($contasLucas)): ?>
    <div class="empty-state"><p>Nenhuma conta</p></div>
    <?php endif; ?>
</div>

<!-- Contas Pessoais Bia -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user"></i> Bia - Pessoais</span>
    </div>
    <?php foreach ($contasBia as $c): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:#fce7f3;color:#ec4899;">
            <i class="fas fa-university"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($c['nome']) ?></div>
            <div class="list-item-subtitle"><?= e($c['banco']) ?><?= $c['observacao'] ? ' · ' . e($c['observacao']) : '' ?></div>
        </div>
        <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
        <div style="display:flex;gap:4px;">
            <a href="/contas/editar/<?= $c['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 8px;"><i class="fas fa-edit" style="font-size:12px;"></i></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if (empty($contasBia)): ?>
    <div class="empty-state"><p>Nenhuma conta</p></div>
    <?php endif; ?>
</div>

<!-- Contas Empresa -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-building"></i> LRV Web - Empresa</span>
    </div>
    <?php foreach ($contas['empresa'] as $c): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:rgba(139,92,246,0.1);color:#8b5cf6;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($c['nome']) ?></div>
            <div class="list-item-subtitle"><?= e($c['banco']) ?><?= $c['observacao'] ? ' · ' . e($c['observacao']) : '' ?></div>
        </div>
        <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
        <div style="display:flex;gap:4px;">
            <a href="/contas/editar/<?= $c['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 8px;"><i class="fas fa-edit" style="font-size:12px;"></i></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if (empty($contas['empresa'])): ?>
    <div class="empty-state"><p>Nenhuma conta</p></div>
    <?php endif; ?>
</div>
