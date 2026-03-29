<?php
$itens = $lista['itens'] ?? [];
$totalItens = count($itens);
$itensComprados = count(array_filter($itens, fn($i) => $i['comprado']));
$pctMercado = $orcamentoMercado > 0 ? percentual($gastoMercadoMes, $orcamentoMercado) : 0;
$bannerClass = $restanteMercado > 100 ? 'ok' : ($restanteMercado > 0 ? 'alerta' : 'estourado');
?>

<div class="page-header">
    <span class="page-header-title"><?= e($lista['nome']) ?></span>
    <a href="/listas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
</div>

<!-- Banner orçamento -->
<div class="mercado-banner <?= $bannerClass ?>">
    <div>
        <div style="font-weight:600;font-size:12px;">Orçamento Mercado</div>
        <div style="font-size:11px;"><?= formatMoney($gastoMercadoMes) ?> / <?= formatMoney($orcamentoMercado) ?></div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:18px;font-weight:700;"><?= formatMoney($restanteMercado) ?></div>
        <div style="font-size:11px;">disponível</div>
    </div>
</div>

<!-- Resumo da lista -->
<div class="stats-grid" style="grid-template-columns: 1fr 1fr 1fr;">
    <div class="stat-card">
        <div class="stat-label">Itens</div>
        <div class="stat-value"><?= $itensComprados ?>/<?= $totalItens ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Estimado</div>
        <div class="stat-value"><?= formatMoney($lista['total_estimado']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Real</div>
        <div class="stat-value" style="color:var(--danger);"><?= formatMoney($lista['total_real']) ?></div>
    </div>
</div>

<?php if ($lista['orcamento_limite'] && $lista['total_real'] > $lista['orcamento_limite']): ?>
<div class="alert alert-error" style="margin:0 0 16px;">
    <i class="fas fa-exclamation-triangle"></i>
    Essa compra ultrapassou o orçamento da lista em <?= formatMoney($lista['total_real'] - $lista['orcamento_limite']) ?>
</div>
<?php elseif ($lista['orcamento_limite']): ?>
<div class="alert alert-info" style="margin:0 0 16px;">
    Orçamento da lista: <?= formatMoney($lista['orcamento_limite']) ?> · Restante: <?= formatMoney($lista['orcamento_limite'] - $lista['total_real']) ?>
</div>
<?php endif; ?>

<!-- Adicionar item -->
<?php if ($lista['status'] === 'ativa'): ?>
<div class="card">
    <div class="card-title mb-1" style="font-size:14px;"><i class="fas fa-plus"></i> Adicionar item</div>
    <form method="POST" action="/listas/item/<?= $lista['id'] ?>">
        <?= csrfField() ?>
        <div class="add-item-row">
            <input type="text" name="nome" class="form-input" placeholder="Nome do item" required style="flex:2;">
            <input type="number" name="quantidade" class="form-input" placeholder="Qtd" value="1" min="0.1" step="0.1" style="flex:0.7;">
        </div>
        <div class="add-item-row">
            <select name="unidade" class="form-select" style="flex:1;font-size:13px;padding:10px;">
                <option value="un">un</option>
                <option value="kg">kg</option>
                <option value="g">g</option>
                <option value="litro">litro</option>
                <option value="ml">ml</option>
                <option value="pacote">pacote</option>
                <option value="caixa">caixa</option>
                <option value="dúzia">dúzia</option>
            </select>
            <input type="text" name="preco_estimado" class="form-input" placeholder="Preço est." data-money style="flex:1;">
            <select name="prioridade" class="form-select" style="flex:1;font-size:13px;padding:10px;">
                <option value="alta">Alta</option>
                <option value="media" selected>Média</option>
                <option value="baixa">Baixa</option>
            </select>
        </div>
        <div class="add-item-row">
            <input type="text" name="categoria" class="form-input" placeholder="Categoria (ex: Frios, Limpeza)" style="flex:1;">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Itens -->
<div class="card">
    <div class="card-header">
        <span class="card-title">Itens (<?= $totalItens ?>)</span>
    </div>

    <?php if (empty($itens)): ?>
        <div class="empty-state"><p>Nenhum item na lista</p></div>
    <?php else: ?>
        <?php
        // Agrupar por categoria
        $porCategoria = [];
        foreach ($itens as $item) {
            $cat = $item['categoria'] ?: 'Sem categoria';
            $porCategoria[$cat][] = $item;
        }
        ?>
        <?php foreach ($porCategoria as $cat => $catItens): ?>
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;padding:8px 0 4px;border-bottom:1px solid var(--border);">
            <?= e($cat) ?> (<?= count($catItens) ?>)
        </div>
        <?php foreach ($catItens as $item): ?>
        <div class="item-compra <?= $item['comprado'] ? 'comprado' : '' ?>">
            <?php if (!$item['comprado'] && $lista['status'] === 'ativa'): ?>
            <!-- Form para marcar como comprado -->
            <form method="POST" action="/listas/<?= $lista['id'] ?>/comprar/<?= $item['id'] ?>" style="display:flex;align-items:center;gap:8px;flex:1;">
                <?= csrfField() ?>
                <div class="item-check" onclick="this.closest('form').submit()">
                    <i class="fas fa-check" style="font-size:10px;opacity:0;"></i>
                </div>
                <div class="item-info">
                    <div class="item-nome"><?= e($item['nome']) ?></div>
                    <div class="item-detalhe">
                        <?= $item['quantidade'] ?> <?= e($item['unidade']) ?>
                        <?php if ($item['observacao']): ?> · <?= e($item['observacao']) ?><?php endif; ?>
                    </div>
                </div>
                <div class="item-preco">
                    <input type="text" name="preco_real" class="form-input" placeholder="R$"
                           data-money style="width:80px;padding:6px 8px;font-size:13px;text-align:right;"
                           value="<?= $item['preco_estimado'] ? number_format($item['preco_estimado'], 2, ',', '.') : '' ?>">
                    <?php if ($item['preco_estimado']): ?>
                    <div class="item-preco-est">Est: <?= formatMoney($item['preco_estimado']) ?></div>
                    <?php endif; ?>
                </div>
            </form>
            <?php else: ?>
            <!-- Item já comprado -->
            <form method="POST" action="/listas/<?= $lista['id'] ?>/desmarcar/<?= $item['id'] ?>" style="display:contents;">
                <?= csrfField() ?>
                <div class="item-check checked" onclick="this.closest('form').submit()">
                    <i class="fas fa-check" style="font-size:10px;"></i>
                </div>
            </form>
            <div class="item-info">
                <div class="item-nome"><?= e($item['nome']) ?></div>
                <div class="item-detalhe"><?= $item['quantidade'] ?> <?= e($item['unidade']) ?></div>
            </div>
            <div class="item-preco">
                <?php if ($item['preco_real']): ?>
                <div class="item-preco-valor"><?= formatMoney($item['preco_real']) ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($lista['status'] === 'ativa'): ?>
            <form method="POST" action="/listas/<?= $lista['id'] ?>/remover/<?= $item['id'] ?>" style="flex-shrink:0;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-sm" style="padding:4px 8px;color:var(--text-light);background:none;border:none;"
                        onclick="return confirm('Remover item?')">
                    <i class="fas fa-trash" style="font-size:12px;"></i>
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Ações -->
<?php if ($lista['status'] === 'ativa'): ?>
<div style="display:flex;gap:8px;">
    <form method="POST" action="/listas/concluir/<?= $lista['id'] ?>" style="flex:1;">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-success btn-block"><i class="fas fa-check"></i> Concluir Lista</button>
    </form>
    <form method="POST" action="/listas/excluir/<?= $lista['id'] ?>">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancelar esta lista?')">
            <i class="fas fa-times"></i>
        </button>
    </form>
</div>
<?php endif; ?>
