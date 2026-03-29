<div class="page-header">
    <span class="page-header-title">Nova Lista de Compras</span>
    <a href="/listas" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="card">
    <form method="POST" action="/listas/salvar">
        <?= csrfField() ?>

        <div class="form-group">
            <label>Nome da lista</label>
            <input type="text" name="nome" class="form-input" required
                   placeholder="Ex: Compras da semana" autofocus>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Local da compra</label>
                <input type="text" name="local_compra" class="form-input"
                       placeholder="Ex: Atacadão">
            </div>
            <div class="form-group">
                <label>Data</label>
                <input type="date" name="data_compra" class="form-input"
                       value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Orçamento limite (R$) - opcional</label>
            <input type="text" name="orcamento_limite" class="form-input"
                   placeholder="Ex: 200,00" data-money>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-plus"></i> Criar Lista
        </button>
    </form>
</div>
