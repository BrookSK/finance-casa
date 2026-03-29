<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Receitas</span>
    <div class="btn-group">
        <a href="/exportar/receitas?mes=<?= $mes ?>&ano=<?= $ano ?>" class="btn btn-outline btn-sm" title="Exportar CSV"><i class="fas fa-download"></i></a>
        <a href="/receitas/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nova</a>
    </div>
</div>

<div class="month-selector">
    <a href="/receitas?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/receitas?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Resumo -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="stat-card income">
        <div class="stat-label">Previsto</div>
        <div class="stat-value positive"><?= formatMoney($totalPrevisto) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Recebido</div>
        <div class="stat-value"><?= formatMoney($totalRecebido) ?></div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <a href="/receitas?mes=<?= $mes ?>&ano=<?= $ano ?>" class="filter-chip <?= !$filtroStatus ? 'active' : '' ?>">Todas</a>
    <a href="/receitas?mes=<?= $mes ?>&ano=<?= $ano ?>&status=prevista" class="filter-chip <?= $filtroStatus === 'prevista' ? 'active' : '' ?>">Previstas</a>
    <a href="/receitas?mes=<?= $mes ?>&ano=<?= $ano ?>&status=recebida" class="filter-chip <?= $filtroStatus === 'recebida' ? 'active' : '' ?>">Recebidas</a>
</div>

<!-- Lista -->
<div class="card">
    <?php if (empty($receitas)): ?>
        <div class="empty-state">
            <i class="fas fa-arrow-up"></i>
            <p>Nenhuma receita neste mês</p>
        </div>
    <?php else: ?>
        <?php foreach ($receitas as $r): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:<?= $r['status'] === 'recebida' ? 'var(--success-light)' : '#f1f5f9' ?>;color:<?= $r['status'] === 'recebida' ? 'var(--success)' : 'var(--text-secondary)' ?>;">
                <i class="fas fa-<?= $r['status'] === 'recebida' ? 'check' : 'clock' ?>"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($r['titulo']) ?></div>
                <div class="list-item-subtitle">
                    <?= e($r['usuario_nome'] ?? '') ?>
                    <?php if (!$r['entra_no_orcamento']): ?>
                        · <span class="badge badge-neutral">Fora do orçamento</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount text-success"><?= formatMoney($r['valor']) ?></div>
                <div class="list-item-date">
                    <?php if ($r['status'] !== 'recebida'): ?>
                        <?php if ($r['dia_recebimento_inicio']): ?>
                            Dia <?= $r['dia_recebimento_inicio'] ?>-<?= $r['dia_recebimento_fim'] ?>
                        <?php elseif ($r['data_prevista']): ?>
                            <?= formatDate($r['data_prevista']) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-success">Recebida</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:6px;padding:0 0 8px 52px;">
            <?php if ($r['status'] !== 'recebida'): ?>
            <form method="POST" action="/receitas/recebida/<?= $r['id'] ?>" style="display:inline;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-success btn-sm" style="padding:4px 10px;font-size:11px;">
                    <i class="fas fa-check"></i> Recebida
                </button>
            </form>
            <?php endif; ?>
            <a href="/receitas/editar/<?= $r['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 10px;font-size:11px;">
                <i class="fas fa-edit"></i>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
