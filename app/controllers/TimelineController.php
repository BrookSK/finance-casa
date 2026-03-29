<?php
class TimelineController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());

        $receitaModel = new Receita();
        $despesaModel = new Despesa();

        $receitas = $receitaModel->getByMonth($mes, $ano);
        $despesas = $despesaModel->getByMonth($mes, $ano);

        // Montar timeline por dia
        $timeline = [];
        $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        for ($dia = 1; $dia <= $diasNoMes; $dia++) {
            $dataStr = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
            $timeline[$dia] = [
                'data' => $dataStr,
                'receitas' => [],
                'despesas' => [],
                'total_entrada' => 0,
                'total_saida' => 0,
            ];
        }

        // Distribuir receitas
        foreach ($receitas as $r) {
            $diaInicio = $r['dia_recebimento_inicio'] ?? ($r['data_prevista'] ? (int) date('d', strtotime($r['data_prevista'])) : 1);
            if ($diaInicio > $diasNoMes) $diaInicio = $diasNoMes;
            if (isset($timeline[$diaInicio])) {
                $timeline[$diaInicio]['receitas'][] = $r;
                $timeline[$diaInicio]['total_entrada'] += $r['valor'];
            }
        }

        // Distribuir despesas
        foreach ($despesas as $d) {
            $dia = $d['data_vencimento'] ? (int) date('d', strtotime($d['data_vencimento'])) : 1;
            if ($dia > $diasNoMes) $dia = $diasNoMes;
            if (isset($timeline[$dia])) {
                $timeline[$dia]['despesas'][] = $d;
                $timeline[$dia]['total_saida'] += $d['valor'];
            }
        }

        // Calcular saldo acumulado
        $saldoAcumulado = 0;
        foreach ($timeline as $dia => &$item) {
            $saldoAcumulado += $item['total_entrada'] - $item['total_saida'];
            $item['saldo_acumulado'] = $saldoAcumulado;
            $item['negativo'] = $saldoAcumulado < 0;
        }

        // Filtrar dias vazios para visão limpa
        $timelineAtiva = array_filter($timeline, function ($item) {
            return !empty($item['receitas']) || !empty($item['despesas']);
        });

        $this->view('timeline/index', compact('timeline', 'timelineAtiva', 'mes', 'ano', 'saldoAcumulado'));
    }
}
