<div class="page-header">
    <span class="page-header-title"><?= $cartao ? 'Editar Cartão' : 'Novo Cartão' ?></span>
    <a href="/cartoes" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="<?= $cartao ? '/cartoes/atualizar/' . $cartao['id'] : '/cartoes/salvar' ?>">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Nome do cartão</label>
            <input type="text" name="nome" class="form-input" required
                   value="<?= e($cartao['nome'] ?? '') ?>" placeholder="Ex: XP">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Bandeira</label>
                <select name="bandeira" class="form-select">
                    <option value="Visa" <?= ($cartao['bandeira'] ?? '') === 'Visa' ? 'selected' : '' ?>>Visa</option>
                    <option value="Mastercard" <?= ($cartao['bandeira'] ?? '') === 'Mastercard' ? 'selected' : '' ?>>Mastercard</option>
                    <option value="Elo" <?= ($cartao['bandeira'] ?? '') === 'Elo' ? 'selected' : '' ?>>Elo</option>
                    <option value="Outro" <?= ($cartao['bandeira'] ?? '') === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            <div class="form-group">
                <label>Responsável</label>
                <select name="usuario_id" class="form-select">
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($cartao['usuario_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Limite total (R$)</label>
            <input type="text" name="limite_total" class="form-input" required
                   value="<?= $cartao ? number_format($cartao['limite_total'], 2, ',', '.') : '' ?>"
                   placeholder="0,00" data-money>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Dia fechamento</label>
                <input type="number" name="dia_fechamento" class="form-input" min="1" max="31" required
                       value="<?= $cartao['dia_fechamento'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Dia vencimento</label>
                <input type="number" name="dia_vencimento" class="form-input" min="1" max="31" required
                       value="<?= $cartao['dia_vencimento'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Cor</label>
            <input type="color" name="cor" class="form-input" style="height:44px;padding:4px;"
                   value="<?= $cartao['cor'] ?? '#6366f1' ?>">
        </div>

        <div class="form-group">
            <label>Observação</label>
            <textarea name="observacao" class="form-input" rows="2"><?= e($cartao['observacao'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> <?= $cartao ? 'Atualizar' : 'Salvar' ?>
        </button>
    </form>
</div>
