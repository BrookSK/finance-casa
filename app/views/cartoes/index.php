<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Cartões</span>
    <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
    <a href="/cartoes/criar" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Novo</a>
    <?php endif; ?>
</div>

<div class="month-selector">
    <a href="/cartoes?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/cartoes?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<?php if (empty($cartoes)): ?>
    <div class="empty-state">
        <i class="fas fa-credit-card"></i>
        <p>Nenhum cartão cadastrado</p>
    </div>
<?php else: ?>
    <?php foreach ($cartoes as $c): ?>
    <?php $pctUso = $c['limite_total'] > 0 ? percentual($c['gasto_atual'], $c['limite_total']) : 0; ?>
    <div class="cartao-card" style="background: linear-gradient(135deg, <?= e($c['cor']) ?>dd, <?= e($c['cor']) ?>88);">
        <div class="cartao-nome"><?= e($c['nome']) ?></div>
        <div class="cartao-bandeira"><?= e($c['bandeira']) ?> · <?= e($c['usuario_nome']) ?></div>
        <div class="cartao-info">
            <div>
                <div class="cartao-info-label">Fatura atual</div>
                <div class="cartao-info-value"><?= formatMoney($c['gasto_atual']) ?></div>
            </div>
            <div>
                <div class="cartao-info-label">Disponível</div>
                <div class="cartao-info-value"><?= formatMoney($c['limite_disponivel']) ?></div>
            </div>
            <div>
                <div class="cartao-info-label">Fecha dia</div>
                <div class="cartao-info-value"><?= $c['dia_fechamento'] ?></div>
            </div>
            <div>
                <div class="cartao-info-label">Vence dia</div>
                <div class="cartao-info-value"><?= $c['dia_vencimento'] ?></div>
            </div>
        </div>
        <div class="cartao-limite">
            <div class="progress">
                <div class="progress-bar" style="width:<?= $pctUso ?>%"></div>
            </div>
            <div class="cartao-limite-text">
                <span><?= $pctUso ?>% usado</span>
                <span>Limite: <?= formatMoney($c['limite_total']) ?></span>
            </div>
        </div>
    </div>
    <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
    <div style="display:flex;gap:8px;margin-bottom:16px;margin-top:-8px;">
        <a href="/cartoes/detalhe/<?= $c['id'] ?>?mes=<?= $mes ?>&ano=<?= $ano ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver detalhes</a>
        <a href="/cartoes/editar/<?= $c['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Editar</a>
    </div>
    <?php else: ?>
    <div style="display:flex;gap:8px;margin-bottom:16px;margin-top:-8px;">
        <a href="/cartoes/detalhe/<?= $c['id'] ?>?mes=<?= $mes ?>&ano=<?= $ano ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver detalhes</a>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
