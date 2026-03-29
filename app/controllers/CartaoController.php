<?php
class CartaoController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $cartoes = (new Cartao())->getAllActive();
        $cartaoModel = new Cartao();

        // Adicionar gasto atual a cada cartão
        foreach ($cartoes as &$c) {
            $c['gasto_atual'] = $cartaoModel->getGastoAtual($c['id'], $mes, $ano);
            $c['limite_disponivel'] = $c['limite_total'] - $c['gasto_atual'];
        }

        $this->view('cartoes/index', compact('cartoes', 'mes', 'ano'));
    }

    public function create(): void
    {
        $this->requireAdmin();
        $usuarios = (new Usuario())->getAllActive();
        $this->view('cartoes/form', ['usuarios' => $usuarios, 'cartao' => null]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $data = [
            'usuario_id' => (int) $_POST['usuario_id'],
            'nome' => trim($_POST['nome'] ?? ''),
            'bandeira' => trim($_POST['bandeira'] ?? ''),
            'limite_total' => (float) str_replace(['.', ','], ['', '.'], $_POST['limite_total'] ?? '0'),
            'dia_fechamento' => (int) $_POST['dia_fechamento'],
            'dia_vencimento' => (int) $_POST['dia_vencimento'],
            'cor' => $_POST['cor'] ?? '#6366f1',
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
        (new Cartao())->create($data);
        setFlash('success', 'Cartão cadastrado.');
        redirect('/cartoes');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $cartao = (new Cartao())->findById((int) $id);
        if (!$cartao) { redirect('/cartoes'); return; }
        $usuarios = (new Usuario())->getAllActive();
        $this->view('cartoes/form', compact('cartao', 'usuarios'));
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $data = [
            'usuario_id' => (int) $_POST['usuario_id'],
            'nome' => trim($_POST['nome'] ?? ''),
            'bandeira' => trim($_POST['bandeira'] ?? ''),
            'limite_total' => (float) str_replace(['.', ','], ['', '.'], $_POST['limite_total'] ?? '0'),
            'dia_fechamento' => (int) $_POST['dia_fechamento'],
            'dia_vencimento' => (int) $_POST['dia_vencimento'],
            'cor' => $_POST['cor'] ?? '#6366f1',
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
        (new Cartao())->update((int) $id, $data);
        setFlash('success', 'Cartão atualizado.');
        redirect('/cartoes');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Cartao())->delete((int) $id);
        setFlash('success', 'Cartão excluído.');
        redirect('/cartoes');
    }
}
