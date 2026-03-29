<?php
class DespesaController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $model = new Despesa();
        $despesas = $model->getByMonth($mes, $ano);
        $totalDespesas = $model->getTotalByMonth($mes, $ano);
        $totalPago = $model->getTotalPago($mes, $ano);
        $this->view('despesas/index', compact('despesas', 'mes', 'ano', 'totalDespesas', 'totalPago'));
    }

    public function create(): void
    {
        $categorias = (new Categoria())->getActive('despesa');
        $usuarios = (new Usuario())->getAllActive();
        $cartoes = (new Cartao())->getAllActive();
        $this->view('despesas/form', ['categorias' => $categorias, 'usuarios' => $usuarios, 'cartoes' => $cartoes, 'despesa' => null]);
    }

    public function store(): void
    {
        $data = $this->validateInput();
        (new Despesa())->create($data);
        setFlash('success', 'Despesa cadastrada.');
        redirect('/despesas');
    }

    public function edit(string $id): void
    {
        $despesa = (new Despesa())->findById((int) $id);
        if (!$despesa) { redirect('/despesas'); return; }
        $categorias = (new Categoria())->getActive('despesa');
        $usuarios = (new Usuario())->getAllActive();
        $cartoes = (new Cartao())->getAllActive();
        $this->view('despesas/form', compact('despesa', 'categorias', 'usuarios', 'cartoes'));
    }

    public function update(string $id): void
    {
        $data = $this->validateInput();
        (new Despesa())->update((int) $id, $data);
        setFlash('success', 'Despesa atualizada.');
        redirect('/despesas');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Despesa())->delete((int) $id);
        setFlash('success', 'Despesa excluída.');
        redirect('/despesas');
    }

    public function marcarPaga(string $id): void
    {
        (new Despesa())->update((int) $id, ['status' => 'paga', 'data_pagamento' => date('Y-m-d')]);
        setFlash('success', 'Despesa marcada como paga.');
        redirect('/despesas');
    }

    private function validateInput(): array
    {
        return [
            'usuario_id' => (int) ($_POST['usuario_id'] ?? $this->user()['id']),
            'nome' => trim($_POST['nome'] ?? ''),
            'valor' => (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0'),
            'tipo' => $_POST['tipo'] ?? 'fixa',
            'categoria_id' => !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null,
            'subcategoria_id' => !empty($_POST['subcategoria_id']) ? (int) $_POST['subcategoria_id'] : null,
            'proprietario' => $_POST['proprietario'] ?? 'compartilhado',
            'forma_pagamento' => $_POST['forma_pagamento'] ?? 'pix',
            'cartao_id' => !empty($_POST['cartao_id']) ? (int) $_POST['cartao_id'] : null,
            'data_vencimento' => $_POST['data_vencimento'] ?: null,
            'data_pagamento' => $_POST['data_pagamento'] ?: null,
            'recorrente' => isset($_POST['recorrente']) ? 1 : 0,
            'parcelada' => isset($_POST['parcelada']) ? 1 : 0,
            'total_parcelas' => !empty($_POST['total_parcelas']) ? (int) $_POST['total_parcelas'] : null,
            'parcela_atual' => !empty($_POST['parcela_atual']) ? (int) $_POST['parcela_atual'] : null,
            'mes_referencia' => (int) ($_POST['mes_referencia'] ?? currentMonth()),
            'ano_referencia' => (int) ($_POST['ano_referencia'] ?? currentYear()),
            'status' => $_POST['status'] ?? 'pendente',
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
    }
}
