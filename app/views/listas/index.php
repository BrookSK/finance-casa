<?php
$pctMercado = $orcamentoMercado > 0 ? percentual($gastoMercadoMes, $orcamentoMercado) : 0;
$bannerClass = $restanteMercado > 100 ? 'ok' : ($restanteMercado > 0 ? 'alerta' : 'estourado');
?>

<div class="page-header">
    <span class="page-header-title">Lista de Compras</span>
    <a href="/listas/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nova Lista</a>
</div>

<!-- Banner orçamento mercado -->
<div class="mercado-banner <?= $bannerClass ?>">
    <div>
        <div style="font-weight:700;">Orçamento Mercado</div>
        <div>Gasto: <?= formatMoney($gastoMercadoMes) ?> / <?= formatMoney($orcamentoMercado) ?></div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:20px;font-weight:700;"><?= formatMoney($restanteMercado) ?></div>
        <div>disponível</div>
    </div>
</div>

<div class="progress mb-2">
    <div class="progress-bar <?= statusColor($pctMercado) ?>" style="width:<?= $pctMercado ?>%"></div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <a href="/listas" class="filter-chip <?= !$status ? 'active' : '' ?>">Todas</a>
    <a href="/listas?status=ativa" class="filter-chip <?= $status === 'ativa' ? 'active' : '' ?>">Ativas</a>
    <a href="/listas?status=concluida" class="filter-chip <?= $status === 'concluida' ? 'active' : '' ?>">Concluídas</a>
    <a href="/listas/historico" class="filter-chip">Histórico</a>
</div>

<!-- Listas -->
<?php if (empty($listas)): ?>
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <p>Nenhuma lista de compras</p>
    </div>
<?php else: ?>
    <?php foreach ($listas as $l): ?>
    <a href="/listas/ver/<?= $l['id'] ?>" class="lista-card" style="display:block;text-decoration:none;color:inherit;">
        <div class="lista-header">
            <div>
                <div class="lista-nome"><?= e($l['nome']) ?></div>
                <div class="lista-meta">
                    <?= e($l['usuario_nome'] ?? '') ?>
                    · <?= $l['data_compra'] ? formatDate($l['data_compra']) : '' ?>
                    <?php if ($l['local_compra']): ?> · <?= e($l['local_compra']) ?><?php endif; ?>
                </div>
            </div>
            <span class="badge badge-<?= $l['status'] === 'ativa' ? 'info' : ($l['status'] === 'concluida' ? 'success' : 'neutral') ?>">
                <?= ucfirst($l['status']) ?>
            </span>
        </div>
        <div class="lista-totais">
            <div>
                <div class="lista-total-label">Itens</div>
                <div class="lista-total-value"><?= $l['itens_comprados'] ?>/<?= $l['total_itens'] ?></div>
            </div>
            <div>
                <div class="lista-total-label">Total real</div>
                <div class="lista-total-value"><?= formatMoney($l['total_real']) ?></div>
            </div>
        </div>
        <?php if ($l['total_itens'] > 0): ?>
        <div class="progress mt-1">
            <div class="progress-bar success" style="width:<?= percentual($l['itens_comprados'], $l['total_itens']) ?>%"></div>
        </div>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
<?php endif; ?>
