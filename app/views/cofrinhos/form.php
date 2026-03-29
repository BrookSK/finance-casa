<div class="page-header">
    <span class="page-header-title"><?= $cofrinho ? 'Editar Cofrinho' : 'Novo Cofrinho' ?></span>
    <a href="/cofrinhos" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="<?= $cofrinho ? '/cofrinhos/atualizar/' . $cofrinho['id'] : '/cofrinhos/salvar' ?>">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Nome do cofrinho</label>
            <input type="text" name="nome" class="form-input" required
                   value="<?= e($cofrinho['nome'] ?? '') ?>" placeholder="Ex: Aluguel">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Meta mensal (R$)</label>
                <input type="text" name="meta_mensal" class="form-input" required
                       value="<?= $cofrinho ? number_format($cofrinho['meta_mensal'], 2, ',', '.') : '' ?>"
                       placeholder="0,00" data-money>
            </div>
            <div class="form-group">
                <label>Valor atual (R$)</label>
                <input type="text" name="valor_atual" class="form-input"
                       value="<?= $cofrinho ? number_format($cofrinho['valor_atual'], 2, ',', '.') : '0,00' ?>"
                       placeholder="0,00" data-money>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="pessoal" <?= ($cofrinho['tipo'] ?? '') === 'pessoal' ? 'selected' : '' ?>>Pessoal</option>
                    <option value="compartilhado" <?= ($cofrinho['tipo'] ?? '') === 'compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Prioridade</label>
                <select name="prioridade" class="form-select">
                    <option value="alta" <?= ($cofrinho['prioridade'] ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                    <option value="media" <?= ($cofrinho['prioridade'] ?? 'media') === 'media' ? 'selected' : '' ?>>Média</option>
                    <option value="baixa" <?= ($cofrinho['prioridade'] ?? '') === 'baixa' ? 'selected' : '' ?>>Baixa</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cofrinho['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Responsável</label>
                <select name="usuario_id" class="form-select">
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($cofrinho['usuario_id'] ?? $currentUser['id']) == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Mês referência</label>
                <select name="mes_referencia" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($cofrinho['mes_referencia'] ?? currentMonth()) == $m ? 'selected' : '' ?>>
                        <?= monthName($m) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Ano</label>
                <input type="number" name="ano_referencia" class="form-input"
                       value="<?= $cofrinho['ano_referencia'] ?? currentYear() ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Cor</label>
            <input type="color" name="cor" class="form-input" style="height:44px;padding:4px;"
                   value="<?= $cofrinho['cor'] ?? '#6366f1' ?>">
        </div>

        <div class="form-group">
            <label>Observação</label>
            <textarea name="observacao" class="form-input" rows="2"><?= e($cofrinho['observacao'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> <?= $cofrinho ? 'Atualizar' : 'Salvar' ?>
        </button>
    </form>
</div>
