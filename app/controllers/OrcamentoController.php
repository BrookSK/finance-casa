<?php
class OrcamentoController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());

        $orcModel = new Orcamento();
        $despModel = new Despesa();
        $orcamentos = $orcModel->getByMonth($mes, $ano);

        $dados = [];
        foreach ($orcamentos as $orc) {
            $gasto = $despModel->getGastoCategoria($orc['categoria_id'], $mes, $ano);
            $orc['gasto'] = $gasto;
            $orc['restante'] = $orc['valor_limite'] - $gasto;
            $orc['percentual'] = percentual($gasto, $orc['valor_limite']);
            $dados[] = $orc;
        }

        $categorias = (new Categoria())->getActive('despesa');
        $this->view('orcamentos/index', compact('dados', 'mes', 'ano', 'categorias'));
    }

    public function store(): void
    {
        $this->requireAdmin();
        $data = [
            'categoria_id' => (int) $_POST['categoria_id'],
            'valor_limite' => (float) str_replace(['.', ','], ['', '.'], $_POST['valor_limite'] ?? '0'),
            'mes_referencia' => (int) ($_POST['mes_referencia'] ?? currentMonth()),
            'ano_referencia' => (int) ($_POST['ano_referencia'] ?? currentYear()),
        ];

        // Verificar se já existe
        $existing = (new Orcamento())->findOneWhere([
            'categoria_id' => $data['categoria_id'],
            'mes_referencia' => $data['mes_referencia'],
            'ano_referencia' => $data['ano_referencia'],
        ]);

        $model = new Orcamento();
        if ($existing) {
            $model->update($existing['id'], $data);
            setFlash('success', 'Orçamento atualizado.');
        } else {
            $model->create($data);
            setFlash('success', 'Orçamento criado.');
        }
        redirect('/orcamentos');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Orcamento())->delete((int) $id);
        setFlash('success', 'Orçamento removido.');
        redirect('/orcamentos');
    }
}
