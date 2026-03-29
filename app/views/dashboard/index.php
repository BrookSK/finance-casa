<?php
$mesAnterior = $mes - 1;
$anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1;
$anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<!-- Seletor de mês -->
<div class="month-selector">
    <a href="/dashboard?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/dashboard?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Status do mês -->
<div class="status-banner <?= $statusMes['tipo'] ?>">
    <i class="fas fa-<?= $statusMes['tipo'] === 'success' ? 'check-circle' : ($statusMes['tipo'] === 'danger' ? 'exclamation-triangle' : 'exclamation-circle') ?>"></i>
    <?= e($statusMes['msg']) ?>
</div>

<!-- Cards principais -->
<div class="stats-grid">
    <div class="stat-card income">
        <div class="stat-icon income"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-label">Receita do mês</div>
        <div class="stat-value positive"><?= formatMoney($totalReceitas) ?></div>
    </div>
    <div class="stat-card expense">
        <div class="stat-icon expense"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-label">Despesa do mês</div>
        <div class="stat-value negative"><?= formatMoney($totalDespesas) ?></div>
    </div>
    <div class="stat-card balance">
        <div class="stat-icon balance"><i class="fas fa-wallet"></i></div>
        <div class="stat-label">Saldo do mês</div>
        <div class="stat-value <?= $saldoMes >= 0 ? 'positive' : 'negative' ?>"><?= formatMoney($saldoMes) ?></div>
    </div>
    <div class="stat-card savings">
        <div class="stat-icon savings"><i class="fas fa-piggy-bank"></i></div>
        <div class="stat-label">Cofrinhos</div>
        <div class="stat-value"><?= formatMoney($totalCofrinhos) ?></div>
    </div>
    <div class="stat-card bills">
        <div class="stat-icon bills"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-label">Faturas abertas</div>
        <div class="stat-value"><?= formatMoney($totalFaturasAbertas) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon balance"><i class="fas fa-hand-holding-usd"></i></div>
        <div class="stat-label">Saldo disponível</div>
        <div class="stat-value <?= $saldoDisponivel >= 0 ? 'positive' : 'negative' ?>"><?= formatMoney($saldoDisponivel) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon expense"><i class="fas fa-clock"></i></div>
        <div class="stat-label">Falta pagar</div>
        <div class="stat-value"><?= formatMoney($faltaPagar) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon income"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-label">Falta receber</div>
        <div class="stat-value"><?= formatMoney($faltaReceber) ?></div>
    </div>
</div>

<!-- Atalhos rápidos -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-bolt"></i> Atalhos rápidos</span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
        <a href="/despesas/criar" class="btn btn-outline btn-sm btn-block" style="justify-content:flex-start;">
            <i class="fas fa-plus" style="color:var(--danger);"></i> Nova despesa
        </a>
        <a href="/receitas/criar" class="btn btn-outline btn-sm btn-block" style="justify-content:flex-start;">
            <i class="fas fa-plus" style="color:var(--success);"></i> Nova receita
        </a>
        <a href="/listas/criar" class="btn btn-outline btn-sm btn-block" style="justify-content:flex-start;">
            <i class="fas fa-shopping-cart" style="color:var(--info);"></i> Lista compras
        </a>
        <a href="/cofrinhos" class="btn btn-outline btn-sm btn-block" style="justify-content:flex-start;">
            <i class="fas fa-piggy-bank" style="color:var(--warning);"></i> Cofrinhos
        </a>
    </div>
</div>

<!-- Orçamentos -->
<?php if (!empty($orcamentosComGasto)): ?>
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-bar"></i> Orçamentos</span>
    </div>
    <?php foreach ($orcamentosComGasto as $orc): ?>
    <div style="margin-bottom: 14px;">
        <div class="flex-between mb-1">
            <span style="font-size:13px;font-weight:600;"><?= e($orc['categoria_nome'] ?? 'Sem categoria') ?></span>
            <span style="font-size:12px;color:var(--text-secondary);">
                <?= formatMoney($orc['gasto']) ?> / <?= formatMoney($orc['valor_limite']) ?>
            </span>
        </div>
        <div class="progress">
            <div class="progress-bar <?= statusColor($orc['percentual']) ?>" style="width:<?= $orc['percentual'] ?>%"></div>
        </div>
        <div style="font-size:11px;color:var(--text-light);margin-top:2px;">
            Restante: <?= formatMoney($orc['restante']) ?> (<?= $orc['percentual'] ?>% usado)
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Próximos vencimentos -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-exclamation-circle"></i> Próximos vencimentos</span>
        <a href="/despesas" style="font-size:13px;">Ver todos</a>
    </div>
    <?php if (empty($proximosVencimentos)): ?>
        <div class="empty-state"><p>Nenhum vencimento próximo</p></div>
    <?php else: ?>
        <?php foreach ($proximosVencimentos as $v): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:var(--danger-light);color:var(--danger);">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($v['nome']) ?></div>
                <div class="list-item-subtitle"><?= e($v['usuario_nome'] ?? '') ?> · <?= e($v['categoria_nome'] ?? '') ?></div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount text-danger"><?= formatMoney($v['valor']) ?></div>
                <div class="list-item-date"><?= formatDate($v['data_vencimento']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Próximos recebimentos -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-arrow-up"></i> Próximos recebimentos</span>
        <a href="/receitas" style="font-size:13px;">Ver todos</a>
    </div>
    <?php if (empty($proximosRecebimentos)): ?>
        <div class="empty-state"><p>Nenhum recebimento previsto</p></div>
    <?php else: ?>
        <?php foreach ($proximosRecebimentos as $r): ?>
        <div class="list-item">
            <div class="list-item-icon" style="background:var(--success-light);color:var(--success);">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="list-item-content">
                <div class="list-item-title"><?= e($r['titulo']) ?></div>
                <div class="list-item-subtitle"><?= e($r['usuario_nome'] ?? '') ?></div>
            </div>
            <div class="list-item-value">
                <div class="list-item-amount text-success"><?= formatMoney($r['valor']) ?></div>
                <div class="list-item-date"><?= $r['data_prevista'] ? formatDate($r['data_prevista']) : 'Dia ' . $r['dia_recebimento_inicio'] . '-' . $r['dia_recebimento_fim'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Cofrinhos incompletos -->
<?php if (!empty($cofrinhosIncompletos)): ?>
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-piggy-bank"></i> Cofrinhos incompletos</span>
        <a href="/cofrinhos" style="font-size:13px;">Ver todos</a>
    </div>
    <?php foreach ($cofrinhosIncompletos as $c): ?>
    <?php $pct = percentual($c['valor_atual'], $c['meta_mensal']); ?>
    <div style="margin-bottom:12px;">
        <div class="flex-between">
            <span style="font-size:13px;font-weight:600;"><?= e($c['nome']) ?></span>
            <span style="font-size:12px;color:var(--text-secondary);"><?= formatMoney($c['valor_atual']) ?> / <?= formatMoney($c['meta_mensal']) ?></span>
        </div>
        <div class="progress mt-1">
            <div class="progress-bar <?= statusColor($pct) ?>" style="width:<?= $pct ?>%"></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
