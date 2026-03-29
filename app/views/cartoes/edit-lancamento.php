<div class="page-header">
    <span class="page-header-title">Editar Lançamento</span>
    <a href="/cartoes/detalhe/<?= $cartaoId ?>?mes=<?= $lancamento['mes_referencia'] ?>&ano=<?= $lancamento['ano_referencia'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="/cartoes/<?= $cartaoId ?>/lancamento/<?= $lancamento['id'] ?>/atualizar">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-input" required value="<?= e($lancamento['descricao']) ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Valor (R$)</label>
                <input type="text" name="valor" class="form-input" required data-money
                       value="<?= number_format($lancamento['valor'], 2, ',', '.') ?>">
            </div>
            <div class="form-group">
                <label>Data da compra</label>
                <input type="date" name="data_compra" class="form-input" value="<?= $lancamento['data_compra'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Categoria</label>
            <select name="categoria_id" class="form-select">
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($lancamento['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['nome']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($lancamento['parcela_atual']): ?>
        <div class="alert alert-info" style="margin:0 0 16px;">
            Parcela <?= $lancamento['parcela_atual'] ?>/<?= $lancamento['total_parcelas'] ?>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> Salvar
        </button>
    </form>
</div>

<!-- Excluir -->
<?php if (($currentUser['papel'] ?? '') === 'admin'): ?>
<div class="card" style="border-left:4px solid var(--danger);">
    <form method="POST" action="/cartoes/<?= $cartaoId ?>/lancamento/<?= $lancamento['id'] ?>/excluir"
          onsubmit="return confirm('Excluir este lançamento? O valor será subtraído da fatura.')">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-danger btn-block">
            <i class="fas fa-trash"></i> Excluir Lançamento
        </button>
    </form>
</div>
<?php endif; ?>
