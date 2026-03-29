<div class="page-header">
    <span class="page-header-title"><?= $conta ? 'Editar Conta' : 'Nova Conta Bancária' ?></span>
    <a href="/contas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="<?= $conta ? '/contas/atualizar/' . $conta['id'] : '/contas/salvar' ?>">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Nome da conta</label>
            <input type="text" name="nome" class="form-input" required
                   value="<?= e($conta['nome'] ?? '') ?>" placeholder="Ex: PicPay Lucas">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Banco / Instituição</label>
                <input type="text" name="banco" class="form-input" required
                       value="<?= e($conta['banco'] ?? '') ?>" placeholder="Ex: PicPay">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="pessoal" <?= ($conta['tipo'] ?? '') === 'pessoal' ? 'selected' : '' ?>>Pessoal</option>
                    <option value="empresa" <?= ($conta['tipo'] ?? '') === 'empresa' ? 'selected' : '' ?>>Empresa</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Proprietário</label>
                <input type="text" name="proprietario" class="form-input" required
                       value="<?= e($conta['proprietario'] ?? '') ?>" placeholder="Ex: Lucas, Bia, LRV Web">
            </div>
            <div class="form-group">
                <label>Usuário vinculado</label>
                <select name="usuario_id" class="form-select">
                    <option value="">Nenhum</option>
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($conta['usuario_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                        <?= e($u['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Observação</label>
            <textarea name="observacao" class="form-input" rows="2"><?= e($conta['observacao'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-save"></i> <?= $conta ? 'Atualizar' : 'Salvar' ?>
        </button>

        <?php if ($conta && ($currentUser['papel'] ?? '') === 'admin'): ?>
        <form method="POST" action="/contas/excluir/<?= $conta['id'] ?>" style="margin-top:12px;">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-danger btn-block btn-sm" onclick="return confirm('Desativar esta conta?')">
                <i class="fas fa-trash"></i> Desativar conta
            </button>
        </form>
        <?php endif; ?>
    </form>
</div>
