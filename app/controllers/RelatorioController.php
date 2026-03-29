<?php
class RelatorioController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());

        $receitaModel = new Receita();
        $despesaModel = new Despesa();
        $cofrinhoModel = new Cofrinho();

        // Dados do mês atual
        $totalReceitas = $receitaModel->getTotalByMonth($mes, $ano);
        $totalDespesas = $despesaModel->getTotalByMonth($mes, $ano);
        $gastosPorCategoria = $despesaModel->getByCategory($mes, $ano);

        // Gastos por usuário
        $gastosPorUsuario = $despesaModel->query(
            "SELECT u.nome, COALESCE(SUM(d.valor), 0) as total
             FROM despesas d LEFT JOIN usuarios u ON d.usuario_id = u.id
             WHERE d.mes_referencia = :mes AND d.ano_referencia = :ano
             GROUP BY d.usuario_id, u.nome ORDER BY total DESC",
            ['mes' => $mes, 'ano' => $ano]
        );

        // Comparativo últimos 6 meses
        $comparativo = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $mes - $i;
            $a = $ano;
            while ($m < 1) { $m += 12; $a--; }
            $comparativo[] = [
                'mes' => monthName($m),
                'ano' => $a,
                'receitas' => $receitaModel->getTotalByMonth($m, $a),
                'despesas' => $despesaModel->getTotalByMonth($m, $a),
            ];
        }

        // Despesas fixas vs variáveis
        $fixasVsVariaveis = $despesaModel->query(
            "SELECT tipo, COALESCE(SUM(valor), 0) as total
             FROM despesas WHERE mes_referencia = :mes AND ano_referencia = :ano
             GROUP BY tipo",
            ['mes' => $mes, 'ano' => $ano]
        );

        // Cofrinhos
        $cofrinhos = $cofrinhoModel->getByMonth($mes, $ano);

        $this->view('relatorios/index', compact(
            'mes', 'ano', 'totalReceitas', 'totalDespesas',
            'gastosPorCategoria', 'gastosPorUsuario', 'comparativo',
            'fixasVsVariaveis', 'cofrinhos'
        ));
    }

    // API para dados de gráficos (JSON)
    public function apiDados(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());

        $receitaModel = new Receita();
        $despesaModel = new Despesa();

        $gastosPorCategoria = $despesaModel->getByCategory($mes, $ano);

        $comparativo = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $mes - $i;
            $a = $ano;
            while ($m < 1) { $m += 12; $a--; }
            $comparativo[] = [
                'label' => monthName($m) . '/' . substr($a, 2),
                'receitas' => $receitaModel->getTotalByMonth($m, $a),
                'despesas' => $despesaModel->getTotalByMonth($m, $a),
            ];
        }

        $this->json([
            'categorias' => $gastosPorCategoria,
            'comparativo' => $comparativo,
        ]);
    }
}
