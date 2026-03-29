<div class="page-header">
    <span class="page-header-title">Histórico: <?= e($cofrinho['nome']) ?></span>
    <a href="/cofrinhos" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<!-- Info do cofrinho -->
<div class="card">
    <div class="flex-between mb-1">
        <span style="font-size:14px;font-weight:600;">Valor atual</span>
        <span style="font-size:18px;font-weight:700;color:var(--primary);"><?= formatMoney($cofrinho['valor_atual']) ?></span>
    </div>
    <div class="flex-between mb-1">
        <span style="font-size:14px;">Meta mensal</span>
        <span style="font-size:14px;"><?= formatMoney($cofrinho['meta_mensal']) ?></span>
    </div>
    <?php $pct = percentual($cofrinho['valor_atual'], $cofrinho['meta_mensal']); ?>
    <div class="progress mt-1">
        <div class="progress-bar <?= statusColor($pct) ?>" style="width:<?= $pct ?>%"></div>
    </div>
</div>

<!-- Retirar -->
<div class="card">
    <div class="card-title mb-1">Retirar valor</div>
    <form method="POST" action="/cofrinhos/retirar/<?= $cofrinho['id'] ?>" style="display:flex;gap:8px;">
        <?= csrfField() ?>
        <input type="text" name="valor" placeholder="Valor" class="form-input" style="flex:1;" data-money required>
        <input type="hidden" name="descricao" value="Retirada manual">
        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-minus"></i> Retirar</button>
    </form>
</div>

<!-- Movimentações -->
<div class="card">
    <div class="card-title mb-2">Movimentações</div>
    <?php if (empty($movimentacoes)): ?>
        <div class="empty-state"><p>Nenhuma movimentação</p></div>
    <?php else: ?>
        <?php foreach ($movimentacoes as $m): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:<?= $m['tipo'] === 'deposito' ? 'var(--success-light)' : 'var(--danger-light)' ?>;color:<?= $m['tipo'] === 'deposito' ? 'var(--success)' : 'var(--danger)' ?>;">
                <i class="fas fa-<?= $m['tipo'] === 'deposito' ? 'plus' : 'minus' ?>"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($m['descricao'] ?: ucfirst($m['tipo'])) ?></div>
                <div class="list-item-subtitle"><?= e($m['usuario_nome'] ?? '') ?> · <?= formatDateTime($m['criado_em']) ?></div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount <?= $m['tipo'] === 'deposito' ? 'text-success' : 'text-danger' ?>">
                    <?= $m['tipo'] === 'deposito' ? '+' : '-' ?><?= formatMoney($m['valor']) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
