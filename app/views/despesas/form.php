<div class="page-header">
    <span class="page-header-title"><?= $despesa ? 'Editar Despesa' : 'Nova Despesa' ?></span>
    <a href="/despesas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="<?= $despesa ? '/despesas/atualizar/' . $despesa['id'] : '/despesas/salvar' ?>">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-input" required
                   value="<?= e($despesa['nome'] ?? '') ?>" placeholder="Ex: Aluguel">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Valor (R$)</label>
                <input type="text" name="valor" class="form-input" required
                       value="<?= $despesa ? number_format($despesa['valor'], 2, ',', '.') : '' ?>"
                       placeholder="0,00" data-money>
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="fixa" <?= ($despesa['tipo'] ?? '') === 'fixa' ? 'selected' : '' ?>>Fixa</option>
                    <option value="variavel" <?= ($despesa['tipo'] ?? '') === 'variavel' ? 'selected' : '' ?>>Variável</option>
                    <option value="parcelada" <?= ($despesa['tipo'] ?? '') === 'parcelada' ? 'selected' : '' ?>>Parcelada</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($despesa['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Proprietário</label>
                <select name="proprietario" class="form-select">
                    <option value="lucas" <?= ($despesa['proprietario'] ?? '') === 'lucas' ? 'selected' : '' ?>>Lucas</option>
                    <option value="bia" <?= ($despesa['proprietario'] ?? '') === 'bia' ? 'selected' : '' ?>>Bia</option>
                    <option value="compartilhado" <?= ($despesa['proprietario'] ?? 'compartilhado') === 'compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                    <option value="empresa" <?= ($despesa['proprietario'] ?? '') === 'empresa' ? 'selected' : '' ?>>Empresa</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Forma de pagamento</label>
                <select name="forma_pagamento" class="form-select">
                    <option value="pix" <?= ($despesa['forma_pagamento'] ?? '') === 'pix' ? 'selected' : '' ?>>PIX</option>
                    <option value="boleto" <?= ($despesa['forma_pagamento'] ?? '') === 'boleto' ? 'selected' : '' ?>>Boleto</option>
                    <option value="credito" <?= ($despesa['forma_pagamento'] ?? '') === 'credito' ? 'selected' : '' ?>>Crédito</option>
                    <option value="debito" <?= ($despesa['forma_pagamento'] ?? '') === 'debito' ? 'selected' : '' ?>>Débito</option>
                    <option value="dinheiro" <?= ($despesa['forma_pagamento'] ?? '') === 'dinheiro' ? 'selected' : '' ?>>Dinheiro</option>
                    <option value="transferencia" <?= ($despesa['forma_pagamento'] ?? '') === 'transferencia' ? 'selected' : '' ?>>Transferência</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cartão</label>
                <select name="cartao_id" class="form-select">
                    <option value="">Nenhum</option>
                    <?php foreach ($cartoes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($despesa['cartao_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['nome']) ?> (<?= e($c['usuario_nome']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Responsável</label>
                <select name="usuario_id" class="form-select">
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($despesa['usuario_id'] ?? $currentUser['id']) == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Vencimento</label>
                <input type="date" name="data_vencimento" class="form-input"
                       value="<?= $despesa['data_vencimento'] ?? '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Mês referência</label>
                <select name="mes_referencia" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($despesa['mes_referencia'] ?? currentMonth()) == $m ? 'selected' : '' ?>>
                        <?= monthName($m) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Ano</label>
                <input type="number" name="ano_referencia" class="form-input"
                       value="<?= $despesa['ano_referencia'] ?? currentYear() ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="pendente" <?= ($despesa['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="paga" <?= ($despesa['status'] ?? '') === 'paga' ? 'selected' : '' ?>>Paga</option>
                <option value="atrasada" <?= ($despesa['status'] ?? '') === 'atrasada' ? 'selected' : '' ?>>Atrasada</option>
            </select>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="recorrente" id="recorrente" value="1"
                       <?= ($despesa['recorrente'] ?? 0) ? 'checked' : '' ?>>
                <label for="recorrente">Recorrente</label>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="parcelada" id="parcelada" value="1"
                       <?= ($despesa['parcelada'] ?? 0) ? 'checked' : '' ?>>
                <label for="parcelada">Parcelada</label>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Total parcelas</label>
                <input type="number" name="total_parcelas" class="form-input"
                       value="<?= $despesa['total_parcelas'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Parcela atual</label>
                <input type="number" name="parcela_atual" class="form-input"
                       value="<?= $despesa['parcela_atual'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Observação</label>
            <textarea name="observacao" class="form-input" rows="2"><?= e($despesa['observacao'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="excluir_orcamento_cartao" id="excluir_orcamento_cartao" value="1"
                       <?= ($despesa['excluir_orcamento_cartao'] ?? 0) ? 'checked' : '' ?>>
                <label for="excluir_orcamento_cartao">Não conta no orçamento do cartão (R$ 500)</label>
            </div>
            <div style="font-size:11px;color:var(--text-light);margin-top:2px;">
                Marque para assinaturas, parcelas fixas, empresa. Compras normais deixe desmarcado.
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> <?= $despesa ? 'Atualizar' : 'Salvar' ?>
        </button>
    </form>
</div>
