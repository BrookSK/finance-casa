<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }

$diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
?>

<div class="page-header">
    <span class="page-header-title">Linha do Tempo</span>
</div>

<div class="month-selector">
    <a href="/timeline?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/timeline?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Saldo projetado -->
<div class="status-banner <?= $saldoAcumulado >= 0 ? 'success' : 'danger' ?>">
    <i class="fas fa-<?= $saldoAcumulado >= 0 ? 'check-circle' : 'exclamation-triangle' ?>"></i>
    Saldo projetado do mês: <?= formatMoney($saldoAcumulado) ?>
</div>

<!-- Timeline -->
<div class="card">
    <div class="timeline">
        <?php foreach ($timelineAtiva as $dia => $item): ?>
        <?php
            $temReceita = !empty($item['receitas']);
            $temDespesa = !empty($item['despesas']);
            $dotClass = $temReceita && $temDespesa ? 'mixed' : ($temReceita ? 'income' : 'expense');
            if ($item['negativo']) $dotClass = 'negative';
            $dataObj = new DateTime($item['data']);
            $diaSemana = $diasSemana[(int)$dataObj->format('w')];
        ?>
        <div class="timeline-day">
            <div class="timeline-dot <?= $dotClass ?>"></div>
            <div class="timeline-date">
                <span>Dia <?= $dia ?></span>
                <span class="day-name"><?= $diaSemana ?></span>
            </div>
            <div class="timeline-items">
                <?php foreach ($item['receitas'] as $r): ?>
                <div class="timeline-item income">
                    <span class="timeline-item-name">
                        <i class="fas fa-arrow-up" style="color:var(--success);margin-right:4px;font-size:11px;"></i>
                        <?= e($r['titulo']) ?>
                    </span>
                    <span class="timeline-item-value positive">+<?= formatMoney($r['valor']) ?></span>
                </div>
                <?php endforeach; ?>

                <?php foreach ($item['despesas'] as $d): ?>
                <div class="timeline-item expense">
                    <span class="timeline-item-name">
                        <i class="fas fa-arrow-down" style="color:var(--danger);margin-right:4px;font-size:11px;"></i>
                        <?= e($d['nome']) ?>
                        <?php if ($d['status'] === 'paga'): ?>
                            <span class="badge badge-success" style="margin-left:4px;">Paga</span>
                        <?php endif; ?>
                    </span>
                    <span class="timeline-item-value negative">-<?= formatMoney($d['valor']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="timeline-saldo <?= $item['saldo_acumulado'] >= 0 ? 'positive' : 'negative' ?>">
                Saldo acumulado: <?= formatMoney($item['saldo_acumulado']) ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($timelineAtiva)): ?>
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <p>Nenhum evento financeiro neste mês</p>
        </div>
        <?php endif; ?>
    </div>
</div>
