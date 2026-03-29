<?php
$mesAnterior = $mes - 1; $anoAnterior = $ano;
if ($mesAnterior < 1) { $mesAnterior = 12; $anoAnterior--; }
$mesProximo = $mes + 1; $anoProximo = $ano;
if ($mesProximo > 12) { $mesProximo = 1; $anoProximo++; }
?>

<div class="page-header">
    <span class="page-header-title">Orçamentos</span>
</div>

<div class="month-selector">
    <a href="/orcamentos?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"><i class="fas fa-chevron-left"></i></a>
    <span class="current-month"><?= monthName($mes) ?> <?= $ano ?></span>
    <a href="/orcamentos?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"><i class="fas fa-chevron-right"></i></a>
</div>

<!-- Orçamentos existentes -->
<div class="card">
    <div class="card-header">
        <span class="card-title">Orçamentos do mês</span>
    </div>

    <?php if (empty($dados)): ?>
        <div class="empty-state"><p>Nenhum orçamento definido</p></div>
    <?php else: ?>
        <?php foreach ($dados as $orc): ?>
        <div class="orcamento-item">
            <div class="orcamento-header">
                <div class="orcamento-cat">
                    <span class="orcamento-cat-dot" style="background:<?= e($orc['categoria_cor'] ?? '#6366f1') ?>;"></span>
                    <?= e($orc['categoria_nome'] ?? 'Sem categoria') ?>
                </div>
                <div class="orcamento-values">
                    <?= formatMoney($orc['gasto']) ?> / <?= formatMoney($orc['valor_limite']) ?>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar <?= statusColor($orc['percentual']) ?>" style="width:<?= $orc['percentual'] ?>%"></div>
            </div>
            <div class="flex-between mt-1">
                <span class="orcamento-restante <?= $orc['restante'] >= 0 ? 'text-success' : 'text-danger' ?>">
                    <?= $orc['restante'] >= 0 ? 'Restante: ' . formatMoney($orc['restante']) : 'Estourado: ' . formatMoney(abs($orc['restante'])) ?>
                </span>
                <span style="font-size:12px;color:var(--text-light);"><?= $orc['percentual'] ?>%</span>
            </div>
            <?php if ($orc['percentual'] >= 85): ?>
            <div class="alert alert-warning" style="margin:8px 0 0;padding:8px 12px;font-size:12px;">
                <i class="fas fa-exclamation-triangle"></i> Orçamento quase esgotado
            </div>
            <?php endif; ?>
            <?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
            <form method="POST" action="/orcamentos/excluir/<?= $orc['id'] ?>" style="margin-top:8px;">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-sm btn-outline" style="font-size:11px;padding:4px 10px;"
                        onclick="return confirm('Remover orçamento?')">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Adicionar orçamento -->
<?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
<div class="card">
    <div class="card-title mb-2">Adicionar / Atualizar Orçamento</div>
    <form method="POST" action="/orcamentos/salvar">
        <?= csrfField() ?>
        <input type="hidden" name="mes_referencia" value="<?= $mes ?>">
        <input type="hidden" name="ano_referencia" value="<?= $ano ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= e($cat['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Limite (R$)</label>
                <input type="text" name="valor_limite" class="form-input" required placeholder="0,00" data-money>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> Salvar Orçamento
        </button>
    </form>
</div>
<?php endif; ?>
