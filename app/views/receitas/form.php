<div class="page-header">
    <span class="page-header-title"><?= $receita ? 'Editar Receita' : 'Nova Receita' ?></span>
    <a href="/receitas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="<?= $receita ? '/receitas/atualizar/' . $receita['id'] : '/receitas/salvar' ?>">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-input" required
                   value="<?= e($receita['titulo'] ?? '') ?>" placeholder="Ex: Salário">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Valor (R$)</label>
                <input type="text" name="valor" class="form-input" required
                       value="<?= $receita ? number_format($receita['valor'], 2, ',', '.') : '' ?>"
                       placeholder="0,00" data-money>
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="fixa" <?= ($receita['tipo'] ?? '') === 'fixa' ? 'selected' : '' ?>>Fixa</option>
                    <option value="variavel" <?= ($receita['tipo'] ?? '') === 'variavel' ? 'selected' : '' ?>>Variável</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($receita['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Responsável</label>
                <select name="usuario_id" class="form-select">
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($receita['usuario_id'] ?? $currentUser['id']) == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Data prevista</label>
                <input type="date" name="data_prevista" class="form-input"
                       value="<?= $receita['data_prevista'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Data recebida</label>
                <input type="date" name="data_recebida" class="form-input"
                       value="<?= $receita['data_recebida'] ?? '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Dia recebimento (de)</label>
                <input type="number" name="dia_recebimento_inicio" class="form-input" min="1" max="31"
                       value="<?= $receita['dia_recebimento_inicio'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Dia recebimento (até)</label>
                <input type="number" name="dia_recebimento_fim" class="form-input" min="1" max="31"
                       value="<?= $receita['dia_recebimento_fim'] ?? '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Mês referência</label>
                <select name="mes_referencia" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($receita['mes_referencia'] ?? currentMonth()) == $m ? 'selected' : '' ?>>
                        <?= monthName($m) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Ano</label>
                <input type="number" name="ano_referencia" class="form-input"
                       value="<?= $receita['ano_referencia'] ?? currentYear() ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="prevista" <?= ($receita['status'] ?? '') === 'prevista' ? 'selected' : '' ?>>Prevista</option>
                <option value="recebida" <?= ($receita['status'] ?? '') === 'recebida' ? 'selected' : '' ?>>Recebida</option>
                <option value="atrasada" <?= ($receita['status'] ?? '') === 'atrasada' ? 'selected' : '' ?>>Atrasada</option>
            </select>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="recorrente" id="recorrente" value="1"
                       <?= ($receita['recorrente'] ?? 0) ? 'checked' : '' ?>>
                <label for="recorrente">Recorrente</label>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="entra_no_orcamento" id="entra_no_orcamento" value="1"
                       <?= ($receita['entra_no_orcamento'] ?? 1) ? 'checked' : '' ?>>
                <label for="entra_no_orcamento">Entra no orçamento principal</label>
            </div>
        </div>

        <div class="form-group">
            <label>Observação</label>
            <textarea name="observacao" class="form-input" rows="2"><?= e($receita['observacao'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> <?= $receita ? 'Atualizar' : 'Salvar' ?>
        </button>
    </form>
</div>
