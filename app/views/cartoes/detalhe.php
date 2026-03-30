<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
$pctUso = $cartao['limite_total'] > 0 ? percentual($gastoAtual, $cartao['limite_total']) : 0;
?>

<div class="page-header">
    <span class="page-header-title"><?= e($cartao['nome']) ?></span>
    <a href="/cartoes" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
</div>

<!-- Card visual do cartão -->
<div class="cartao-card" style="background: linear-gradient(135deg, <?= e($cartao['cor']) ?>dd, <?= e($cartao['cor']) ?>88);">
    <div class="cartao-nome"><?= e($cartao['nome']) ?></div>
    <div class="cartao-bandeira"><?= e($cartao['bandeira']) ?> · <?= e($cartao['usuario_nome']) ?></div>
    <div class="cartao-info">
        <div>
            <div class="cartao-info-label">Fatura</div>
            <div class="cartao-info-value"><?= formatMoney($gastoAtual) ?></div>
        </div>
        <div>
            <div class="cartao-info-label">Disponível</div>
            <div class="cartao-info-value"><?= formatMoney($limiteDisponivel) ?></div>
        </div>
        <div>
            <div class="cartao-info-label">Fecha dia</div>
            <div class="cartao-info-value"><?= $cartao['dia_fechamento'] ?></div>
        </div>
        <div>
            <div class="cartao-info-label">Vence dia</div>
            <div class="cartao-info-value"><?= $cartao['dia_vencimento'] ?></div>
        </div>
    </div>
    <div class="cartao-limite">
        <div class="progress"><div class="progress-bar" style="width:<?= $pctUso ?>%"></div></div>
        <div class="cartao-limite-text">
            <span><?= $pctUso ?>% usado</span>
            <span>Limite: <?= formatMoney($cartao['limite_total']) ?></span>
        </div>
    </div>
</div>

<!-- Seletor de mês -->
<div class="month-selector">
    <a href="/cartoes/detalhe/<?= $cartao['id'] ?>?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/cartoes/detalhe/<?= $cartao['id'] ?>?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Orçamento do cartão (R$ 500 compartilhado entre todos os cartões) -->
<?php
$gastoOrcCartao = (new Despesa())->getGastoOrcamentoCartao($mes, $ano);
$orcCartao = 500;
$restOrcCartao = $orcCartao - $gastoOrcCartao;
$pctOrcCartao = percentual($gastoOrcCartao, $orcCartao);
?>
<div class="card" style="border-left:4px solid #7c3aed;padding:12px 16px;">
    <div class="flex-between" style="font-size:13px;">
        <span><i class="fas fa-credit-card" style="color:#7c3aed;"></i> Orçamento Cartão (todos)</span>
        <span style="font-weight:700;color:<?= $restOrcCartao >= 0 ? 'var(--success)' : 'var(--danger)' ?>;">
            <?= $restOrcCartao >= 0 ? formatMoney($restOrcCartao) . ' disponível' : 'Estourou ' . formatMoney(abs($restOrcCartao)) ?>
        </span>
    </div>
    <div class="progress" style="margin-top:6px;">
        <div class="progress-bar <?= statusColor($pctOrcCartao) ?>" style="width:<?= min($pctOrcCartao, 100) ?>%"></div>
    </div>
    <div style="font-size:11px;color:var(--text-light);margin-top:2px;">
        <?= formatMoney($gastoOrcCartao) ?> / <?= formatMoney($orcCartao) ?> (<?= $pctOrcCartao ?>%)
    </div>
</div>

<!-- Status da fatura -->
<?php if ($fatura): ?>
<div class="card">
    <div class="card-header">
        <span class="card-title">Fatura <?= monthName($mes) ?></span>
        <span class="badge badge-<?= $fatura['status'] === 'paga' ? 'success' : ($fatura['status'] === 'fechada' ? 'warning' : 'info') ?>">
            <?= ucfirst($fatura['status']) ?>
        </span>
    </div>
    <div class="flex-between mb-1">
        <span style="font-size:13px;">Total</span>
        <span style="font-size:20px;font-weight:700;"><?= formatMoney($fatura['valor_total']) ?></span>
    </div>
    <?php if ($fatura['valor_reservado'] > 0): ?>
    <div class="flex-between mb-1">
        <span style="font-size:13px;">Reservado no cofrinho</span>
        <span style="font-size:14px;color:var(--success);"><?= formatMoney($fatura['valor_reservado']) ?></span>
    </div>
    <?php endif; ?>
    <div class="flex-between" style="font-size:12px;color:var(--text-secondary);">
        <span>Fecha: <?= $fatura['data_fechamento'] ? formatDate($fatura['data_fechamento']) : '-' ?></span>
        <span>Vence: <?= $fatura['data_vencimento'] ? formatDate($fatura['data_vencimento']) : '-' ?></span>
    </div>
    <?php if ($fatura['status'] !== 'paga'): ?>
    <form method="POST" action="/faturas/pagar/<?= $fatura['id'] ?>" style="margin-top:12px;">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fas fa-check"></i> Marcar fatura como paga</button>
    </form>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="empty-state" style="padding:20px;">
        <p>Nenhuma fatura para <?= monthName($mes) ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Gastos por categoria -->
<?php if (!empty($porCategoria)): ?>
<div class="card">
    <div class="card-title mb-2">Gastos por Categoria</div>
    <?php foreach ($porCategoria as $cat): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:<?= e($cat['cor']) ?>22;color:<?= e($cat['cor']) ?>;">
            <i class="fas fa-circle" style="font-size:10px;"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($cat['nome']) ?></div>
            <div class="list-item-subtitle"><?= $cat['qtd'] ?> lançamento(s)</div>
        </div>
        <div class="list-item-value">
            <div class="list-item-amount"><?= formatMoney($cat['total']) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Lançamentos da fatura -->
<?php if (!empty($lancamentos)): ?>
<div class="card">
    <div class="card-title mb-2">Lançamentos (<?= count($lancamentos) ?>)</div>
    <?php foreach ($lancamentos as $l): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:<?= e($l['categoria_cor'] ?? '#6366f1') ?>22;color:<?= e($l['categoria_cor'] ?? '#6366f1') ?>;">
            <i class="fas fa-receipt" style="font-size:12px;"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($l['descricao']) ?></div>
            <div class="list-item-subtitle">
                <?= e($l['categoria_nome'] ?? '') ?>
                <?php if ($l['parcela_atual']): ?> · Parcela <?= $l['parcela_atual'] ?>/<?= $l['total_parcelas'] ?><?php endif; ?>
                <?php if ($l['data_compra']): ?> · <?= formatDate($l['data_compra']) ?><?php endif; ?>
            </div>
        </div>
        <div class="list-item-value">
            <div class="list-item-amount"><?= formatMoney($l['valor']) ?></div>
        </div>
        <div style="display:flex;gap:4px;flex-shrink:0;margin-left:8px;">
            <a href="/cartoes/<?= $cartao['id'] ?>/lancamento/<?= $l['id'] ?>/editar" class="btn btn-outline btn-sm" style="padding:4px 6px;"><i class="fas fa-edit" style="font-size:11px;"></i></a>
            <form method="POST" action="/cartoes/<?= $cartao['id'] ?>/lancamento/<?= $l['id'] ?>/excluir" style="display:inline;" onsubmit="return confirm('Excluir lançamento?')">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-sm" style="padding:4px 6px;color:var(--danger);background:none;border:1px solid var(--border);"><i class="fas fa-trash" style="font-size:11px;"></i></button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Parcelas ativas neste cartão (resumo) -->
<?php if (!empty($parcelas)): ?>
<div class="card">
    <div class="card-title mb-2">Parcelas Ativas</div>
    <?php foreach ($parcelas as $p): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:var(--warning-light);color:var(--warning);">
            <i class="fas fa-sync-alt" style="font-size:12px;"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($p['nome_base']) ?></div>
            <div class="list-item-subtitle">
                Parcela <?= $p['proxima_parcela'] ?> a <?= $p['ultima_parcela'] ?> de <?= $p['total_parcelas'] ?> · até <?= $p['ultimo_mes'] ?>
            </div>
        </div>
        <div class="list-item-value">
            <div class="list-item-amount"><?= formatMoney($p['valor']) ?>/mês</div>
            <div class="list-item-date">Total: <?= formatMoney($p['valor'] * $p['parcelas_restantes']) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Adicionar lançamento -->
<?php if (($currentUser['papel'] ?? '') === 'admin' || $cartao['usuario_id'] == $currentUser['id']): ?>
<div class="card">
    <div class="card-title mb-2"><i class="fas fa-plus"></i> Novo Lançamento</div>
    <form method="POST" action="/cartoes/lancamento/<?= $cartao['id'] ?>">
        <?= csrfField() ?>
        <input type="hidden" name="mes_referencia" value="<?= $mes ?>">
        <input type="hidden" name="ano_referencia" value="<?= $ano ?>">

        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-input" required placeholder="Ex: Supermercado Antunes">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Valor (R$)</label>
                <input type="text" name="valor" class="form-input" required placeholder="0,00" data-money>
            </div>
            <div class="form-group">
                <label>Data da compra</label>
                <input type="date" name="data_compra" class="form-input" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= e($cat['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Proprietário</label>
                <select name="proprietario" class="form-select">
                    <option value="compartilhado">Casa</option>
                    <option value="lucas">Lucas</option>
                    <option value="bia">Bia</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="parcelada" id="parcelada" value="1">
                <label for="parcelada">Parcelada</label>
            </div>
        </div>

        <div class="form-group">
            <label>Número de parcelas</label>
            <input type="number" name="total_parcelas" class="form-input" min="2" max="48" placeholder="Ex: 3">
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="excluir_orcamento_cartao" id="excluir_orc_cartao" value="1">
                <label for="excluir_orc_cartao">Não conta no orçamento do cartão (R$ 500)</label>
            </div>
            <div style="font-size:11px;color:var(--text-light);margin-top:2px;">
                Marque para assinaturas, parcelas fixas, empresa. Compras normais deixe desmarcado.
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-plus"></i> Adicionar Lançamento
        </button>
    </form>
</div>
<?php endif; ?>

<!-- Regras do cartão -->
<?php if ($cartao['observacao']): ?>
<div class="card" style="border-left:4px solid <?= e($cartao['cor']) ?>;">
    <div class="card-title mb-1"><i class="fas fa-info-circle"></i> Regras deste cartão</div>
    <div style="font-size:13px;color:var(--text-secondary);white-space:pre-line;">
        <?= nl2br(e($cartao['observacao'])) ?>
    </div>
</div>
<?php endif; ?>
