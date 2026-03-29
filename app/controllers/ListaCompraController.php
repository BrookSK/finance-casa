<?php
class ListaCompraController extends Controller
{
    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $model = new ListaCompra();
        $listas = $model->getAllByUser(null, $status);

        // Orçamento de mercado
        $mes = currentMonth();
        $ano = currentYear();
        $orcModel = new Orcamento();
        $orcamentos = $orcModel->getByMonth($mes, $ano);
        $orcMercado = null;
        foreach ($orcamentos as $o) {
            if (stripos($o['categoria_nome'] ?? '', 'mercado') !== false) {
                $orcMercado = $o;
                break;
            }
        }
        $gastoMercadoMes = $model->getTotalGastoMercadoMes($mes, $ano);
        $orcamentoMercado = $orcMercado ? (float) $orcMercado['valor_limite'] : 500;
        $restanteMercado = $orcamentoMercado - $gastoMercadoMes;

        $this->view('listas/index', compact('listas', 'status', 'gastoMercadoMes', 'orcamentoMercado', 'restanteMercado'));
    }

    public function create(): void
    {
        $this->view('listas/form', ['lista' => null]);
    }

    public function store(): void
    {
        $data = [
            'usuario_id' => $this->user()['id'],
            'nome' => trim($_POST['nome'] ?? ''),
            'orcamento_limite' => !empty($_POST['orcamento_limite'])
                ? (float) str_replace(['.', ','], ['', '.'], $_POST['orcamento_limite'])
                : null,
            'local_compra' => trim($_POST['local_compra'] ?? ''),
            'data_compra' => $_POST['data_compra'] ?: date('Y-m-d'),
            'status' => 'ativa',
        ];
        $id = (new ListaCompra())->create($data);
        setFlash('success', 'Lista criada.');
        redirect('/listas/ver/' . $id);
    }

    public function ver(string $id): void
    {
        $model = new ListaCompra();
        $lista = $model->getWithItems((int) $id);
        if (!$lista) { redirect('/listas'); return; }

        // Orçamento de mercado
        $mes = currentMonth();
        $ano = currentYear();
        $gastoMercadoMes = $model->getTotalGastoMercadoMes($mes, $ano);
        $orcModel = new Orcamento();
        $orcamentos = $orcModel->getByMonth($mes, $ano);
        $orcamentoMercado = 500;
        foreach ($orcamentos as $o) {
            if (stripos($o['categoria_nome'] ?? '', 'mercado') !== false) {
                $orcamentoMercado = (float) $o['valor_limite'];
                break;
            }
        }
        $restanteMercado = $orcamentoMercado - $gastoMercadoMes;

        $this->view('listas/ver', compact('lista', 'gastoMercadoMes', 'orcamentoMercado', 'restanteMercado'));
    }

    public function addItem(string $id): void
    {
        $data = [
            'lista_id' => (int) $id,
            'nome' => trim($_POST['nome'] ?? ''),
            'quantidade' => (float) ($_POST['quantidade'] ?? 1),
            'unidade' => $_POST['unidade'] ?? 'un',
            'categoria' => trim($_POST['categoria'] ?? ''),
            'prioridade' => $_POST['prioridade'] ?? 'media',
            'preco_estimado' => !empty($_POST['preco_estimado'])
                ? (float) str_replace(['.', ','], ['', '.'], $_POST['preco_estimado'])
                : null,
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];

        (new ListaItem())->create($data);
        (new ListaCompra())->recalcularTotais((int) $id);
        setFlash('success', 'Item adicionado.');
        redirect('/listas/ver/' . $id);
    }

    public function comprarItem(string $listaId, string $itemId): void
    {
        $precoReal = (float) str_replace(['.', ','], ['', '.'], $_POST['preco_real'] ?? '0');
        (new ListaItem())->marcarComprado((int) $itemId, $precoReal);
        (new ListaCompra())->recalcularTotais((int) $listaId);
        setFlash('success', 'Item marcado como comprado.');
        redirect('/listas/ver/' . $listaId);
    }

    public function desmarcarItem(string $listaId, string $itemId): void
    {
        (new ListaItem())->desmarcarComprado((int) $itemId);
        (new ListaCompra())->recalcularTotais((int) $listaId);
        redirect('/listas/ver/' . $listaId);
    }

    public function removeItem(string $listaId, string $itemId): void
    {
        (new ListaItem())->delete((int) $itemId);
        (new ListaCompra())->recalcularTotais((int) $listaId);
        setFlash('success', 'Item removido.');
        redirect('/listas/ver/' . $listaId);
    }

    public function concluir(string $id): void
    {
        (new ListaCompra())->update((int) $id, ['status' => 'concluida']);
        setFlash('success', 'Lista concluída.');
        redirect('/listas');
    }

    public function delete(string $id): void
    {
        (new ListaCompra())->update((int) $id, ['status' => 'cancelada']);
        setFlash('success', 'Lista cancelada.');
        redirect('/listas');
    }

    public function historico(): void
    {
        $model = new ListaCompra();
        $historicoLocais = $model->getHistoricoPorLocal();
        $this->view('listas/historico', compact('historicoLocais'));
    }

    // API: buscar preço médio de item (AJAX)
    public function precoMedio(): void
    {
        $nome = trim($_GET['nome'] ?? '');
        if (!$nome) { $this->json(['preco' => null]); return; }
        $preco = (new ListaItem())->getPrecoMedio($nome);
        $historico = (new ListaItem())->getHistoricoPrecos($nome, 5);
        $this->json(['preco' => $preco, 'historico' => $historico]);
    }
}
