<div class="page-header">
    <span class="page-header-title">Histórico de Compras</span>
    <a href="/listas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <div class="card-title mb-2">Gastos por Local</div>
    <?php if (empty($historicoLocais)): ?>
        <div class="empty-state"><p>Nenhum histórico ainda</p></div>
    <?php else: ?>
        <?php foreach ($historicoLocais as $h): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:var(--info-light);color:var(--info);">
                <i class="fas fa-store"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($h['local_compra']) ?></div>
                <div class="list-item-subtitle"><?= $h['total_compras'] ?> compras · Média: <?= formatMoney($h['media_gasto']) ?></div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount"><?= formatMoney($h['total_gasto']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
