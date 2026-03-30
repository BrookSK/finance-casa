<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
$salarioRecebido = $receita && $receita['status'] === 'recebida';
$salarioValor = $receita ? $receita['valor'] : 0;
?>

<div class="page-header">
    <span class="page-header-title">💰 Dia do Pagamento</span>
</div>

<div class="month-selector">
    <a href="/dia-pagamento?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/dia-pagamento?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Status do salário -->
<div class="status-banner <?= $salarioRecebido ? 'success' : 'warning' ?>">
    <i class="fas fa-<?= $salarioRecebido ? 'check-circle' : 'clock' ?>"></i>
    <?php if ($salarioRecebido): ?>
        Salário de <?= formatMoney($salarioValor) ?> recebido em <?= formatDate($receita['data_recebida']) ?>
    <?php else: ?>
        Salário ainda não recebido (previsto: dia <?= $receita['dia_recebimento_inicio'] ?? '?' ?>-<?= $receita['dia_recebimento_fim'] ?? '?' ?>)
    <?php endif; ?>
</div>

<!-- Resumo -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="stat-card savings">
        <div class="stat-label">Guardado nos cofrinhos</div>
        <div class="stat-value"><?= formatMoney($totalGuardado) ?></div>
        <div style="font-size:11px;color:var(--text-light);">Meta: <?= formatMoney($totalMeta) ?></div>
    </div>
    <div class="stat-card expense">
        <div class="stat-label">Contas pendentes</div>
        <div class="stat-value negative"><?= formatMoney($totalPendente) ?></div>
        <div style="font-size:11px;color:var(--text-light);">Pago: <?= formatMoney($totalPago) ?></div>
    </div>
</div>

<!-- Ordem de prioridade dos cofrinhos -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-list-ol"></i> Cofrinhos — <?= monthName($mes) ?></span>
    </div>
    <p style="font-size:12px;color:var(--text-secondary);margin-bottom:12px;">
        Ao receber o salário, deposite nos cofrinhos nesta ordem. As contas vinculadas são deste mesmo mês.
    </p>

    <?php foreach ($cofrinhos as $i => $c): ?>
    <?php
        $pct = percentual($c['valor_atual'], $c['meta_mensal']);
        $falta = max($c['meta_mensal'] - $c['valor_atual'], 0);
        $completo = $c['valor_atual'] >= $c['meta_mensal'] && $c['meta_mensal'] > 0;
        $prioridade = $c['prioridade_num'] ?? ($i + 1);
        $despesasVinculadas = $cofrinhosDespesas[$c['id']] ?? [];
    ?>
    <div class="cofrinho-card" style="border-left-color:<?= e($c['cor']) ?>;margin-bottom:12px;">
        <!-- Cabeçalho -->
        <div class="cofrinho-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="background:<?= e($c['cor']) ?>;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">
                    <?= $prioridade < 99 ? $prioridade : ($i + 1) ?>
                </span>
                <span class="cofrinho-name"><?= e($c['nome']) ?></span>
            </div>
            <span class="badge <?= $completo ? 'badge-success' : ($pct > 0 ? 'badge-warning' : 'badge-neutral') ?>">
                <?= $completo ? '✅ Completo' : ($pct > 0 ? $pct . '%' : 'Vazio') ?>
            </span>
        </div>

        <!-- Valores -->
        <div class="cofrinho-values">
            <span class="cofrinho-current"><?= formatMoney($c['valor_atual']) ?></span>
            <span class="cofrinho-meta">Meta: <?= formatMoney($c['meta_mensal']) ?></span>
        </div>
        <div class="progress" style="margin:6px 0;">
            <div class="progress-bar <?= $completo ? 'success' : statusColor($pct) ?>" style="width:<?= $pct ?>%"></div>
        </div>

        <?php if ($falta > 0): ?>
        <div style="font-size:12px;color:var(--danger);margin-bottom:6px;">
            Falta depositar: <strong><?= formatMoney($falta) ?></strong>
        </div>
        <?php endif; ?>

        <!-- Observação -->
        <?php if ($c['observacao']): ?>
        <div style="font-size:11px;color:var(--text-light);margin-bottom:8px;white-space:pre-line;">
            <?= nl2br(e($c['observacao'])) ?>
        </div>
        <?php endif; ?>

        <!-- Sub-itens do cofrinho -->
        <?php if (!empty($c['itens'])): ?>
        <div style="border-top:1px solid var(--border);padding-top:6px;margin-bottom:8px;">
            <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:4px;">COMPOSIÇÃO:</div>
            <?php foreach ($c['itens'] as $item): ?>
            <div style="display:flex;justify-content:space-between;font-size:12px;padding:2px 0;color:var(--text-secondary);">
                <span><?= e($item['nome']) ?></span>
                <span style="font-weight:600;"><?= formatMoney($item['valor']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Contas vinculadas a este cofrinho -->
        <?php if (!empty($despesasVinculadas)): ?>
        <div style="border-top:1px solid var(--border);padding-top:8px;margin-top:4px;">
            <div style="font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px;">
                CONTAS A PAGAR:
            </div>
            <?php foreach ($despesasVinculadas as $d): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:13px;">
                <div>
                    <span style="font-weight:600;"><?= e($d['nome']) ?></span>
                    <?php if ($d['data_vencimento']): ?>
                    <span style="font-size:11px;color:var(--text-light);"> · vence <?= formatDate($d['data_vencimento']) ?></span>
                    <?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="font-weight:700;color:var(--danger);"><?= formatMoney($d['valor']) ?></span>
                    <button onclick="toggleModal('modal-pagar-<?= $d['id'] ?>')" class="btn btn-success btn-sm" style="padding:4px 8px;font-size:11px;">
                        Pagar
                    </button>
                </div>
            </div>

            <!-- Modal pagar -->
            <div id="modal-pagar-<?= $d['id'] ?>" class="modal-overlay">
                <div class="modal">
                    <div class="modal-header">
                        <span class="modal-title">Pagar: <?= e($d['nome']) ?></span>
                        <button class="modal-close" onclick="toggleModal('modal-pagar-<?= $d['id'] ?>')"><i class="fas fa-times"></i></button>
                    </div>
                    <form method="POST" action="/dia-pagamento/pagar/<?= $d['id'] ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="cofrinho_id" value="<?= $c['id'] ?>">

                        <div class="form-group">
                            <label>Valor previsto</label>
                            <div style="font-size:18px;font-weight:700;"><?= formatMoney($d['valor']) ?></div>
                        </div>

                        <div class="form-group">
                            <label>Valor real pago (R$)</label>
                            <input type="text" name="valor_real" class="form-input" data-money
                                   value="<?= number_format($d['valor'], 2, ',', '.') ?>">
                        </div>

                        <div style="font-size:12px;color:var(--text-secondary);margin-bottom:12px;padding:8px;background:var(--bg);border-radius:8px;">
                            <i class="fas fa-piggy-bank"></i>
                            Cofrinho: <strong><?= e($c['nome']) ?></strong> — Saldo: <strong><?= formatMoney($c['valor_atual']) ?></strong>
                            <?php if ($d['valor'] > $c['valor_atual']): ?>
                            <br><span style="color:var(--danger);">⚠️ Valor maior que o cofrinho! Diferença de <?= formatMoney($d['valor'] - $c['valor_atual']) ?> sairá da Reserva.</span>
                            <?php else: ?>
                            <br><span style="color:var(--success);">✅ Cofrinho cobre esta conta.</span>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check"></i> Confirmar Pagamento
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Depositar rápido -->
        <?php if (!$completo): ?>
        <form method="POST" action="/dia-pagamento/depositar/<?= $c['id'] ?>" style="display:flex;gap:4px;margin-top:8px;">
            <?= csrfField() ?>
            <input type="text" name="valor" placeholder="Depositar R$" class="form-input" style="padding:8px;font-size:12px;flex:1;" data-money>
            <button type="submit" class="btn btn-primary btn-sm" style="padding:8px 12px;">
                <i class="fas fa-plus"></i>
            </button>
        </form>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<!-- Contas já pagas neste mês -->
<?php if (!empty($despesasPagas)): ?>
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-check-circle" style="color:var(--success);"></i> Já pagas em <?= monthName($mes) ?></span>
    </div>
    <?php foreach ($despesasPagas as $d): ?>
    <div class="list-item" style="opacity:0.6;">
        <div class="list-item-icon" style="background:var(--success-light);color:var(--success);">
            <i class="fas fa-check"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title" style="text-decoration:line-through;"><?= e($d['nome']) ?></div>
            <div class="list-item-subtitle"><?= $d['data_pagamento'] ? 'Pago em ' . formatDate($d['data_pagamento']) : '' ?></div>
        </div>
        <div class="list-item-value">
            <div class="list-item-amount"><?= formatMoney($d['valor']) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
