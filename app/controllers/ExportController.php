<?php
class ExportController extends Controller
{
    public function receitas(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $receitas = (new Receita())->getByMonth($mes, $ano);

        $this->exportCsv("receitas_{$mes}_{$ano}.csv", [
            ['Título', 'Valor', 'Tipo', 'Categoria', 'Responsável', 'Status', 'Data Prevista', 'Data Recebida', 'Orçamento Principal']
        ], $receitas, function ($r) {
            return [
                $r['titulo'], number_format($r['valor'], 2, ',', '.'), $r['tipo'],
                $r['categoria_nome'] ?? '', $r['usuario_nome'] ?? '', $r['status'],
                $r['data_prevista'] ? date('d/m/Y', strtotime($r['data_prevista'])) : '',
                $r['data_recebida'] ? date('d/m/Y', strtotime($r['data_recebida'])) : '',
                $r['entra_no_orcamento'] ? 'Sim' : 'Não',
            ];
        });
    }

    public function despesas(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $despesas = (new Despesa())->getByMonth($mes, $ano);

        $this->exportCsv("despesas_{$mes}_{$ano}.csv", [
            ['Nome', 'Valor', 'Tipo', 'Categoria', 'Proprietário', 'Forma Pgto', 'Cartão', 'Vencimento', 'Status']
        ], $despesas, function ($d) {
            return [
                $d['nome'], number_format($d['valor'], 2, ',', '.'), $d['tipo'],
                $d['categoria_nome'] ?? '', $d['proprietario'], $d['forma_pagamento'],
                $d['cartao_nome'] ?? '', $d['data_vencimento'] ? date('d/m/Y', strtotime($d['data_vencimento'])) : '',
                $d['status'],
            ];
        });
    }

    public function cofrinhos(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $cofrinhos = (new Cofrinho())->getByMonth($mes, $ano);

        $this->exportCsv("cofrinhos_{$mes}_{$ano}.csv", [
            ['Nome', 'Tipo', 'Meta', 'Guardado', 'Faltante', '%', 'Prioridade', 'Responsável']
        ], $cofrinhos, function ($c) {
            $falta = max($c['meta_mensal'] - $c['valor_atual'], 0);
            $pct = $c['meta_mensal'] > 0 ? round(($c['valor_atual'] / $c['meta_mensal']) * 100, 1) : 0;
            return [
                $c['nome'], $c['tipo'], number_format($c['meta_mensal'], 2, ',', '.'),
                number_format($c['valor_atual'], 2, ',', '.'), number_format($falta, 2, ',', '.'),
                $pct . '%', $c['prioridade'], $c['usuario_nome'] ?? '',
            ];
        });
    }

    private function exportCsv(string $filename, array $headers, array $data, callable $mapper): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        // BOM para Excel reconhecer UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        foreach ($headers as $h) {
            fputcsv($output, $h, ';');
        }
        foreach ($data as $row) {
            fputcsv($output, $mapper($row), ';');
        }
        fclose($output);
        exit;
    }
}
