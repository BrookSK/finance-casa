<?php
class CofrinhoController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $filtroUser = $_GET['usuario'] ?? '';
        $filtroTipo = $_GET['tipo'] ?? '';

        $model = new Cofrinho();
        $userId = $filtroUser ? (int) $filtroUser : null;
        $cofrinhos = $model->getByMonth($mes, $ano, $userId, $filtroTipo ?: null);

        // Carregar sub-itens de cada cofrinho
        $itemModel = new CofrinhoItem();
        foreach ($cofrinhos as &$c) {
            $c['itens'] = $itemModel->getByCofrinho($c['id']);
        }
        unset($c);

        $totalMeta = $model->getTotalMeta($mes, $ano);
        $totalGuardado = $model->getTotalGuardado($mes, $ano);
        $usuarios = (new Usuario())->getAllActive();

        $this->view('cofrinhos/index', compact('cofrinhos', 'mes', 'ano', 'totalMeta', 'totalGuardado', 'usuarios', 'filtroUser', 'filtroTipo'));
    }

    public function create(): void
    {
        $categorias = (new Categoria())->getActive();
        $usuarios = (new Usuario())->getAllActive();
        $this->view('cofrinhos/form', ['categorias' => $categorias, 'usuarios' => $usuarios, 'cofrinho' => null]);
    }

    public function store(): void
    {
        $data = $this->validateInput();
        (new Cofrinho())->create($data);
        setFlash('success', 'Cofrinho criado.');
        redirect('/cofrinhos');
    }

    public function edit(string $id): void
    {
        $cofrinho = (new Cofrinho())->findById((int) $id);
        if (!$cofrinho) { redirect('/cofrinhos'); return; }
        $categorias = (new Categoria())->getActive();
        $usuarios = (new Usuario())->getAllActive();
        $this->view('cofrinhos/form', compact('cofrinho', 'categorias', 'usuarios'));
    }

    public function update(string $id): void
    {
        $data = $this->validateInput();
        (new Cofrinho())->update((int) $id, $data);
        setFlash('success', 'Cofrinho atualizado.');
        redirect('/cofrinhos');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Cofrinho())->delete((int) $id);
        setFlash('success', 'Cofrinho excluído.');
        redirect('/cofrinhos');
    }

    public function depositar(string $id): void
    {
        $valor = (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        $descricao = trim($_POST['descricao'] ?? '');
        (new Cofrinho())->depositar((int) $id, $valor, $this->user()['id'], $descricao);
        setFlash('success', 'Depósito realizado.');
        redirect('/cofrinhos');
    }

    public function retirar(string $id): void
    {
        $valor = (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        $descricao = trim($_POST['descricao'] ?? '');
        (new Cofrinho())->retirar((int) $id, $valor, $this->user()['id'], $descricao);
        setFlash('success', 'Retirada realizada.');
        redirect('/cofrinhos');
    }

    public function historico(string $id): void
    {
        $cofrinho = (new Cofrinho())->findById((int) $id);
        if (!$cofrinho) { redirect('/cofrinhos'); return; }
        $movimentacoes = (new CofrinhoMovimentacao())->getByCofrinho((int) $id);
        $this->view('cofrinhos/historico', compact('cofrinho', 'movimentacoes'));
    }

    private function validateInput(): array
    {
        return [
            'usuario_id' => (int) ($_POST['usuario_id'] ?? $this->user()['id']),
            'nome' => trim($_POST['nome'] ?? ''),
            'categoria_id' => !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null,
            'tipo' => $_POST['tipo'] ?? 'pessoal',
            'meta_mensal' => (float) str_replace(['.', ','], ['', '.'], $_POST['meta_mensal'] ?? '0'),
            'valor_atual' => (float) str_replace(['.', ','], ['', '.'], $_POST['valor_atual'] ?? '0'),
            'prioridade' => $_POST['prioridade'] ?? 'media',
            'cor' => $_POST['cor'] ?? '#6366f1',
            'mes_referencia' => (int) ($_POST['mes_referencia'] ?? currentMonth()),
            'ano_referencia' => (int) ($_POST['ano_referencia'] ?? currentYear()),
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
    }
}
