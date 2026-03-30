<?php
class DashboardController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());

        $receitaModel = new Receita();
        $despesaModel = new Despesa();
        $cofrinhoModel = new Cofrinho();
        $faturaModel = new Fatura();
        $orcamentoModel = new Orcamento();

        // Totais do mês
        $totalReceitas = $receitaModel->getTotalByMonth($mes, $ano);
        $totalRecebido = $receitaModel->getTotalRecebido($mes, $ano);
        $totalDespesas = $despesaModel->getTotalByMonth($mes, $ano);
        $totalPago = $despesaModel->getTotalPago($mes, $ano);
        $saldoMes = $totalReceitas - $totalDespesas;
        $saldoDisponivel = $totalRecebido - $totalPago;
        $faltaPagar = $totalDespesas - $totalPago;
        $faltaReceber = $totalReceitas - $totalRecebido;

        // Cofrinhos
        $totalCofrinhos = $cofrinhoModel->getTotalGuardado($mes, $ano);
        $totalMetaCofrinhos = $cofrinhoModel->getTotalMeta($mes, $ano);
        $cofrinhosIncompletos = $cofrinhoModel->getIncompletos($mes, $ano);

        // Faturas
        $totalFaturasAbertas = $faturaModel->getTotalAberto($mes, $ano);

        // Próximos vencimentos e recebimentos
        $proximosVencimentos = $despesaModel->getProximosVencimentos(5);
        $proximosRecebimentos = $receitaModel->getProximosRecebimentos(5);

        // Gastos por categoria
        $gastosPorCategoria = $despesaModel->getByCategory($mes, $ano);

        // Orçamentos
        $orcamentos = $orcamentoModel->getByMonth($mes, $ano);
        $orcamentosComGasto = [];
        foreach ($orcamentos as $orc) {
            if ($orc['categoria_id'] == 12) {
                // Gastos Livres / Cartão: usa o campo entra_orcamento_cartao
                $gasto = $despesaModel->getGastoOrcamentoCartao($mes, $ano);
            } else {
                $gasto = $despesaModel->getGastoCategoria($orc['categoria_id'], $mes, $ano);
            }
            $orc['gasto'] = $gasto;
            $orc['restante'] = $orc['valor_limite'] - $gasto;
            $orc['percentual'] = percentual($gasto, $orc['valor_limite']);
            $orcamentosComGasto[] = $orc;
        }

        // Orçamento do cartão separado (pra mostrar no dashboard)
        $gastoCartao = $despesaModel->getGastoOrcamentoCartao($mes, $ano);
        $orcamentoCartao = 500;
        $restanteCartao = $orcamentoCartao - $gastoCartao;

        // Status do mês
        if ($saldoMes >= 0 && $faltaPagar <= $faltaReceber) {
            $statusMes = ['tipo' => 'success', 'msg' => 'O mês está saudável'];
        } elseif ($saldoMes < 0) {
            $statusMes = ['tipo' => 'danger', 'msg' => 'Atenção: saldo projetado ficará negativo'];
        } else {
            $statusMes = ['tipo' => 'warning', 'msg' => 'Atenção: mês apertado'];
        }

        $this->view('dashboard/index', compact(
            'mes', 'ano', 'totalReceitas', 'totalRecebido', 'totalDespesas', 'totalPago',
            'saldoMes', 'saldoDisponivel', 'faltaPagar', 'faltaReceber',
            'totalCofrinhos', 'totalMetaCofrinhos', 'totalFaturasAbertas',
            'proximosVencimentos', 'proximosRecebimentos', 'gastosPorCategoria',
            'orcamentosComGasto', 'statusMes', 'cofrinhosIncompletos',
            'gastoCartao', 'orcamentoCartao', 'restanteCartao'
        ));
    }
}
