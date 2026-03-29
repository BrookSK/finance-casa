<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Faturas</span>
</div>

<div class="month-selector">
    <a href="/faturas?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/faturas?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<?php if (empty($faturas)): ?>
    <div class="empty-state">
        <i class="fas fa-file-invoice-dollar"></i>
        <p>Nenhuma fatura neste mês</p>
        <p style="font-size:12px;margin-top:8px;">As faturas são geradas automaticamente quando despesas são vinculadas a cartões.</p>
    </div>
<?php else: ?>
    <?php foreach ($faturas as $f): ?>
    <?php $faltante = max($f['valor_total'] - $f['valor_reservado'], 0); ?>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="card-title" style="color:<?= e($f['cartao_cor'] ?? 'var(--primary)') ?>;">
                    <i class="fas fa-credit-card"></i> <?= e($f['cartao_nome']) ?>
                </span>
                <div class="card-subtitle"><?= e($f['usuario_nome'] ?? '') ?></div>
            </div>
            <span class="badge badge-<?= $f['status'] === 'paga' ? 'success' : ($f['status'] === 'fechada' ? 'warning' : 'info') ?>">
                <?= ucfirst($f['status']) ?>
            </span>
        </div>

        <div class="flex-between mb-1">
            <span style="font-size:13px;">Valor total</span>
            <span style="font-size:18px;font-weight:700;"><?= formatMoney($f['valor_total']) ?></span>
        </div>
        <div class="flex-between mb-1">
            <span style="font-size:13px;">Reservado</span>
            <span style="font-size:14px;color:var(--success);"><?= formatMoney($f['valor_reservado']) ?></span>
        </div>
        <?php if ($faltante > 0): ?>
        <div class="flex-between mb-1">
            <span style="font-size:13px;color:var(--danger);">Faltante</span>
            <span style="font-size:14px;font-weight:600;color:var(--danger);"><?= formatMoney($faltante) ?></span>
        </div>
        <?php endif; ?>

        <div class="flex-between" style="font-size:12px;color:var(--text-secondary);margin-top:8px;">
            <span>Fecha: <?= $f['data_fechamento'] ? formatDate($f['data_fechamento']) : '-' ?></span>
            <span>Vence: <?= $f['data_vencimento'] ? formatDate($f['data_vencimento']) : '-' ?></span>
        </div>

        <?php if ($f['status'] !== 'paga'): ?>
        <form method="POST" action="/faturas/pagar/<?= $f['id'] ?>" style="margin-top:12px;">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-success btn-sm btn-block">
                <i class="fas fa-check"></i> Marcar como paga
            </button>
        </form>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
