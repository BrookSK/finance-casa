<div class="page-header">
    <span class="page-header-title">Configurações</span>
</div>

<!-- Usuários -->
<div class="card">
    <div class="card-title mb-2"><i class="fas fa-users"></i> Usuários</div>
    <?php foreach ($usuarios as $u): ?>
    <form method="POST" action="/configuracoes/usuario/<?= $u['id'] ?>" style="margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <?= csrfField() ?>
        <div style="font-size:14px;font-weight:700;margin-bottom:8px;">
            <?= e($u['nome']) ?>
            <span class="badge badge-<?= $u['papel'] === 'admin' ? 'info' : 'neutral' ?>"><?= ucfirst($u['papel']) ?></span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-input" value="<?= e($u['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-input" value="<?= e($u['email']) ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Nova senha (deixe vazio para manter)</label>
            <input type="password" name="senha" class="form-input" placeholder="Nova senha">
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Salvar</button>
    </form>
    <?php endforeach; ?>
</div>

<!-- Categorias -->
<div class="card">
    <div class="card-title mb-2"><i class="fas fa-tags"></i> Categorias</div>

    <?php foreach ($categorias as $cat): ?>
    <div class="list-item">
        <div class="list-item-icon" style="background:<?= e($cat['cor']) ?>22;color:<?= e($cat['cor']) ?>;">
            <i class="fas fa-circle" style="font-size:10px;"></i>
        </div>
        <div class="list-item-content">
            <div class="list-item-title"><?= e($cat['nome']) ?></div>
            <div class="list-item-subtitle"><?= ucfirst($cat['tipo']) ?></div>
        </div>
        <form method="POST" action="/configuracoes/categoria/excluir/<?= $cat['id'] ?>">
            <?= csrfField() ?>
            <button type="submit" class="btn btn-sm" style="padding:4px 8px;color:var(--text-light);background:none;border:none;"
                    onclick="return confirm('Desativar categoria?')">
                <i class="fas fa-trash" style="font-size:12px;"></i>
            </button>
        </form>
    </div>
    <?php endforeach; ?>

    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
        <div style="font-size:14px;font-weight:600;margin-bottom:8px;">Nova categoria</div>
        <form method="POST" action="/configuracoes/categoria">
            <?= csrfField() ?>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="nome" class="form-input" placeholder="Nome" required>
                </div>
                <div class="form-group">
                    <select name="tipo" class="form-select">
                        <option value="ambos">Ambos</option>
                        <option value="receita">Receita</option>
                        <option value="despesa">Despesa</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="color" name="cor" class="form-input" value="#6366f1" style="height:40px;padding:4px;">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-plus"></i> Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Info do sistema -->
<div class="card">
    <div class="card-title mb-1"><i class="fas fa-info-circle"></i> Sistema</div>
    <div style="font-size:13px;color:var(--text-secondary);">
        <p><?= APP_NAME ?> v<?= APP_VERSION ?></p>
        <p>PHP <?= phpversion() ?></p>
    </div>
</div>
