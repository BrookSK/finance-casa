<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Despesas</span>
    <div class="btn-group">
        <a href="/exportar/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>" class="btn btn-outline btn-sm" title="Exportar CSV"><i class="fas fa-download"></i></a>
        <a href="/despesas/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nova</a>
    </div>
</div>

<div class="month-selector">
    <a href="/despesas?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/despesas?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="stat-card expense">
        <div class="stat-label">Total previsto</div>
        <div class="stat-value negative"><?= formatMoney($totalDespesas) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total pago</div>
        <div class="stat-value"><?= formatMoney($totalPago) ?></div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-bar">
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>" class="filter-chip <?= !$filtroStatus && !$filtroProprietario ? 'active' : '' ?>">Todas</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&status=pendente" class="filter-chip <?= $filtroStatus === 'pendente' ? 'active' : '' ?>">Pendentes</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&status=paga" class="filter-chip <?= $filtroStatus === 'paga' ? 'active' : '' ?>">Pagas</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&proprietario=lucas" class="filter-chip <?= $filtroProprietario === 'lucas' ? 'active' : '' ?>">Lucas</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&proprietario=bia" class="filter-chip <?= $filtroProprietario === 'bia' ? 'active' : '' ?>">Bia</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&proprietario=compartilhado" class="filter-chip <?= $filtroProprietario === 'compartilhado' ? 'active' : '' ?>">Casa</a>
    <a href="/despesas?mes=<?= $mes ?>&ano=<?= $ano ?>&proprietario=empresa" class="filter-chip <?= $filtroProprietario === 'empresa' ? 'active' : '' ?>">Empresa</a>
</div>

<div class="card">
    <?php if (empty($despesas)): ?>
        <div class="empty-state">
            <i class="fas fa-arrow-down"></i>
            <p>Nenhuma despesa neste mês</p>
        </div>
    <?php else: ?>
        <?php foreach ($despesas as $d): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:<?= $d['status'] === 'paga' ? 'var(--success-light)' : 'var(--danger-light)' ?>;color:<?= $d['status'] === 'paga' ? 'var(--success)' : 'var(--danger)' ?>;">
                <i class="fas fa-<?= $d['status'] === 'paga' ? 'check' : 'clock' ?>"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($d['nome']) ?></div>
                <div class="list-item-subtitle">
                    <?= e($d['usuario_nome'] ?? '') ?>
                    <?php if ($d['cartao_nome']): ?> · <i class="fas fa-credit-card"></i> <?= e($d['cartao_nome']) ?><?php endif; ?>
                    · <span class="badge badge-<?= $d['status'] === 'paga' ? 'success' : ($d['status'] === 'atrasada' ? 'danger' : 'warning') ?>"><?= ucfirst($d['status']) ?></span>
                </div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount text-danger"><?= formatMoney($d['valor']) ?></div>
                <div class="list-item-date"><?= $d['data_vencimento'] ? formatDate($d['data_vencimento']) : '' ?></div>
            </div>
        </div>
        <div style="display:flex;gap:6px;padding:0 0 8px 52px;">
            <?php if ($d['status'] !== 'paga'): ?>
            <form method="POST" action="/despesas/pagar/<?= $d['id'] ?>" style="display:inline;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-success btn-sm" style="padding:4px 10px;font-size:11px;">
                    <i class="fas fa-check"></i> Pagar
                </button>
            </form>
            <?php endif; ?>
            <a href="/despesas/editar/<?= $d['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 10px;font-size:11px;">
                <i class="fas fa-edit"></i>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
