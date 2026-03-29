<?php
/**
 * Definição de todas as rotas do sistema
 */
function registerRoutes(Router $router): void
{
    // Auth (sem autenticação)
    $router->get('/login', 'AuthController', 'loginForm', false);
    $router->post('/login', 'AuthController', 'login', false);
    $router->get('/logout', 'AuthController', 'logout', false);

    // Dashboard
    $router->get('/', 'DashboardController', 'index');
    $router->get('/dashboard', 'DashboardController', 'index');

    // Receitas
    $router->get('/receitas', 'ReceitaController', 'index');
    $router->get('/receitas/criar', 'ReceitaController', 'create');
    $router->post('/receitas/salvar', 'ReceitaController', 'store');
    $router->get('/receitas/editar/{id}', 'ReceitaController', 'edit');
    $router->post('/receitas/atualizar/{id}', 'ReceitaController', 'update');
    $router->post('/receitas/excluir/{id}', 'ReceitaController', 'delete');
    $router->post('/receitas/recebida/{id}', 'ReceitaController', 'marcarRecebida');

    // Despesas
    $router->get('/despesas', 'DespesaController', 'index');
    $router->get('/despesas/criar', 'DespesaController', 'create');
    $router->post('/despesas/salvar', 'DespesaController', 'store');
    $router->get('/despesas/editar/{id}', 'DespesaController', 'edit');
    $router->post('/despesas/atualizar/{id}', 'DespesaController', 'update');
    $router->post('/despesas/excluir/{id}', 'DespesaController', 'delete');
    $router->post('/despesas/pagar/{id}', 'DespesaController', 'marcarPaga');

    // Cofrinhos
    $router->get('/cofrinhos', 'CofrinhoController', 'index');
    $router->get('/cofrinhos/criar', 'CofrinhoController', 'create');
    $router->post('/cofrinhos/salvar', 'CofrinhoController', 'store');
    $router->get('/cofrinhos/editar/{id}', 'CofrinhoController', 'edit');
    $router->post('/cofrinhos/atualizar/{id}', 'CofrinhoController', 'update');
    $router->post('/cofrinhos/excluir/{id}', 'CofrinhoController', 'delete');
    $router->post('/cofrinhos/depositar/{id}', 'CofrinhoController', 'depositar');
    $router->post('/cofrinhos/retirar/{id}', 'CofrinhoController', 'retirar');
    $router->get('/cofrinhos/historico/{id}', 'CofrinhoController', 'historico');

    // Cartões
    $router->get('/cartoes', 'CartaoController', 'index');
    $router->get('/cartoes/criar', 'CartaoController', 'create');
    $router->post('/cartoes/salvar', 'CartaoController', 'store');
    $router->get('/cartoes/detalhe/{id}', 'CartaoController', 'detalhe');
    $router->post('/cartoes/lancamento/{id}', 'CartaoController', 'addLancamento');
    $router->get('/cartoes/editar/{id}', 'CartaoController', 'edit');
    $router->post('/cartoes/atualizar/{id}', 'CartaoController', 'update');
    $router->post('/cartoes/excluir/{id}', 'CartaoController', 'delete');

    // Faturas
    $router->get('/faturas', 'FaturaController', 'index');
    $router->post('/faturas/pagar/{id}', 'FaturaController', 'marcarPaga');

    // Timeline
    $router->get('/timeline', 'TimelineController', 'index');

    // Dia do Pagamento
    $router->get('/dia-pagamento', 'DiaPagamentoController', 'index');
    $router->post('/dia-pagamento/pagar/{id}', 'DiaPagamentoController', 'pagar');
    $router->post('/dia-pagamento/depositar/{id}', 'DiaPagamentoController', 'depositar');

    // Listas de compras
    $router->get('/listas', 'ListaCompraController', 'index');
    $router->get('/listas/criar', 'ListaCompraController', 'create');
    $router->get('/listas/historico', 'ListaCompraController', 'historico');
    $router->post('/listas/salvar', 'ListaCompraController', 'store');
    $router->get('/listas/ver/{id}', 'ListaCompraController', 'ver');
    $router->post('/listas/item/{id}', 'ListaCompraController', 'addItem');
    $router->post('/listas/{listaId}/comprar/{itemId}', 'ListaCompraController', 'comprarItem');
    $router->post('/listas/{listaId}/desmarcar/{itemId}', 'ListaCompraController', 'desmarcarItem');
    $router->post('/listas/{listaId}/remover/{itemId}', 'ListaCompraController', 'removeItem');
    $router->post('/listas/concluir/{id}', 'ListaCompraController', 'concluir');
    $router->post('/listas/excluir/{id}', 'ListaCompraController', 'delete');

    // Orçamentos
    $router->get('/orcamentos', 'OrcamentoController', 'index');
    $router->post('/orcamentos/salvar', 'OrcamentoController', 'store');
    $router->post('/orcamentos/excluir/{id}', 'OrcamentoController', 'delete');

    // Relatórios
    $router->get('/relatorios', 'RelatorioController', 'index');

    // Notificações
    $router->get('/notificacoes', 'NotificacaoController', 'index');
    $router->post('/notificacoes/lida/{id}', 'NotificacaoController', 'marcarLida');
    $router->post('/notificacoes/ler-todas', 'NotificacaoController', 'marcarTodasLidas');

    // API JSON
    $router->get('/api/dashboard-data', 'DashboardController', 'index');
    $router->get('/api/relatorios', 'RelatorioController', 'apiDados');
    $router->get('/api/notificacoes/count', 'NotificacaoController', 'contarNaoLidas');
    $router->get('/api/preco-medio', 'ListaCompraController', 'precoMedio');

    // Exportação CSV
    $router->get('/exportar/receitas', 'ExportController', 'receitas');
    $router->get('/exportar/despesas', 'ExportController', 'despesas');
    $router->get('/exportar/cofrinhos', 'ExportController', 'cofrinhos');

    // Configurações
    $router->get('/configuracoes', 'ConfigController', 'index');
    $router->post('/configuracoes/usuario/{id}', 'ConfigController', 'updateUsuario');
    $router->post('/configuracoes/categoria', 'ConfigController', 'addCategoria');
    $router->post('/configuracoes/categoria/excluir/{id}', 'ConfigController', 'deleteCategoria');

    // Contas Bancárias
    $router->get('/contas', 'ContaBancariaController', 'index');
    $router->get('/contas/criar', 'ContaBancariaController', 'create');
    $router->post('/contas/salvar', 'ContaBancariaController', 'store');
    $router->get('/contas/editar/{id}', 'ContaBancariaController', 'edit');
    $router->post('/contas/atualizar/{id}', 'ContaBancariaController', 'update');
    $router->post('/contas/excluir/{id}', 'ContaBancariaController', 'delete');
}
