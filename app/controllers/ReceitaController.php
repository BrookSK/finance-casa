<?php
class ReceitaController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $filtroStatus = $_GET['status'] ?? '';

        $model = new Receita();

        if ($filtroStatus) {
            $sql = "SELECT r.*, u.nome as usuario_nome, c.nome as categoria_nome, c.cor as categoria_cor
                    FROM receitas r
                    LEFT JOIN usuarios u ON r.usuario_id = u.id
                    LEFT JOIN categorias c ON r.categoria_id = c.id
                    WHERE r.mes_referencia = :mes AND r.ano_referencia = :ano AND r.status = :status
                    ORDER BY r.data_prevista ASC, r.titulo ASC";
            $receitas = $model->query($sql, ['mes' => $mes, 'ano' => $ano, 'status' => $filtroStatus]);
        } else {
            $receitas = $model->getByMonth($mes, $ano);
        }

        $totalPrevisto = $model->getTotalByMonth($mes, $ano);
        $totalRecebido = $model->getTotalRecebido($mes, $ano);
        $this->view('receitas/index', compact('receitas', 'mes', 'ano', 'totalPrevisto', 'totalRecebido', 'filtroStatus'));
    }

    public function create(): void
    {
        $categorias = (new Categoria())->getActive('receita');
        $usuarios = (new Usuario())->getAllActive();
        $this->view('receitas/form', ['categorias' => $categorias, 'usuarios' => $usuarios, 'receita' => null]);
    }

    public function store(): void
    {
        $data = $this->validateInput();
        $model = new Receita();
        $model->create($data);
        setFlash('success', 'Receita cadastrada.');
        redirect('/receitas');
    }

    public function edit(string $id): void
    {
        $model = new Receita();
        $receita = $model->findById((int) $id);
        if (!$receita) { redirect('/receitas'); return; }
        $categorias = (new Categoria())->getActive('receita');
        $usuarios = (new Usuario())->getAllActive();
        $this->view('receitas/form', compact('receita', 'categorias', 'usuarios'));
    }

    public function update(string $id): void
    {
        $data = $this->validateInput();
        $model = new Receita();
        $model->update((int) $id, $data);
        setFlash('success', 'Receita atualizada.');
        redirect('/receitas');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Receita())->delete((int) $id);
        setFlash('success', 'Receita excluída.');
        redirect('/receitas');
    }

    public function marcarRecebida(string $id): void
    {
        (new Receita())->update((int) $id, ['status' => 'recebida', 'data_recebida' => date('Y-m-d')]);
        setFlash('success', 'Receita marcada como recebida.');
        redirect('/receitas');
    }

    private function validateInput(): array
    {
        return [
            'usuario_id' => (int) ($_POST['usuario_id'] ?? $this->user()['id']),
            'titulo' => trim($_POST['titulo'] ?? ''),
            'valor' => (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0'),
            'tipo' => $_POST['tipo'] ?? 'fixa',
            'categoria_id' => !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null,
            'data_prevista' => $_POST['data_prevista'] ?: null,
            'data_recebida' => $_POST['data_recebida'] ?: null,
            'recorrente' => isset($_POST['recorrente']) ? 1 : 0,
            'dia_recebimento_inicio' => !empty($_POST['dia_recebimento_inicio']) ? (int) $_POST['dia_recebimento_inicio'] : null,
            'dia_recebimento_fim' => !empty($_POST['dia_recebimento_fim']) ? (int) $_POST['dia_recebimento_fim'] : null,
            'entra_no_orcamento' => isset($_POST['entra_no_orcamento']) ? 1 : 0,
            'mes_referencia' => (int) ($_POST['mes_referencia'] ?? currentMonth()),
            'ano_referencia' => (int) ($_POST['ano_referencia'] ?? currentYear()),
            'status' => $_POST['status'] ?? 'prevista',
            'observacao' => trim($_POST['observacao'] ?? ''),
        ];
    }
}
