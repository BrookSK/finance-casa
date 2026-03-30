<?php
/**
 * Tela "Dia do Pagamento"
 * Mostra a ordem de prioridade dos cofrinhos ao receber salário
 * Permite pagar contas com desconto automático do cofrinho
 */
class DiaPagamentoController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $userId = $this->user()['id'];

        $cofrinhoModel = new Cofrinho();
        $despesaModel = new Despesa();
        $receitaModel = new Receita();

        // Cofrinhos do usuário com ordem de prioridade
        $cofrinhos = $cofrinhoModel->getByMonth($mes, $ano, $userId);

        // Ordenar por prioridade
        usort($cofrinhos, function ($a, $b) {
            $pa = $this->extractPriority($a['observacao'] ?? '');
            $pb = $this->extractPriority($b['observacao'] ?? '');
            return $pa - $pb;
        });

        foreach ($cofrinhos as &$c) {
            $c['prioridade_num'] = $this->extractPriority($c['observacao'] ?? '');
            $c['itens'] = (new CofrinhoItem())->getByCofrinho($c['id']);
        }
        unset($c);

        // Despesas pendentes DESTE mês
        $despesas = $despesaModel->query(
            "SELECT d.*, c.nome as categoria_nome, ca.nome as cartao_nome
             FROM despesas d
             LEFT JOIN categorias c ON d.categoria_id = c.id
             LEFT JOIN cartoes ca ON d.cartao_id = ca.id
             WHERE d.mes_referencia = :mes AND d.ano_referencia = :ano
             AND d.status = 'pendente'
             AND (d.usuario_id = :uid OR d.proprietario = 'compartilhado')
             ORDER BY d.data_vencimento ASC, d.nome ASC",
            ['mes' => $mes, 'ano' => $ano, 'uid' => $userId]
        );

        // Despesas já pagas deste mês
        $despesasPagas = $despesaModel->query(
            "SELECT d.*, c.nome as categoria_nome
             FROM despesas d
             LEFT JOIN categorias c ON d.categoria_id = c.id
             WHERE d.mes_referencia = :mes AND d.ano_referencia = :ano
             AND d.status = 'paga'
             AND (d.usuario_id = :uid OR d.proprietario = 'compartilhado')
             ORDER BY d.data_pagamento ASC",
            ['mes' => $mes, 'ano' => $ano, 'uid' => $userId]
        );

        // Receita do usuário
        $receita = $receitaModel->findOneWhere([
            'usuario_id' => $userId,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
            'entra_no_orcamento' => 1,
        ]);

        // Calcular totais
        $totalMeta = 0;
        $totalGuardado = 0;
        $totalFaltante = 0;
        foreach ($cofrinhos as $c) {
            $totalMeta += $c['meta_mensal'];
            $totalGuardado += $c['valor_atual'];
            $totalFaltante += max($c['meta_mensal'] - $c['valor_atual'], 0);
        }

        $totalPendente = array_sum(array_column($despesas, 'valor'));
        $totalPago = array_sum(array_column($despesasPagas, 'valor'));

        // Mapeamento cofrinho → despesas vinculadas
        $cofrinhosDespesas = $this->mapCofrinhosDespesas($cofrinhos, $despesas);

        $this->view('dia-pagamento/index', compact(
            'cofrinhos', 'despesas', 'despesasPagas', 'receita', 'mes', 'ano',
            'totalMeta', 'totalGuardado', 'totalFaltante',
            'totalPendente', 'totalPago', 'cofrinhosDespesas'
        ));
    }

    /**
     * Pagar despesa com desconto automático do cofrinho
     */
    public function pagar(string $despesaId): void
    {
        $despesa = (new Despesa())->findById((int) $despesaId);
        if (!$despesa) { redirect('/dia-pagamento'); return; }

        $valorReal = (float) str_replace(['.', ','], ['', '.'], $_POST['valor_real'] ?? $despesa['valor']);
        $cofrinhoId = !empty($_POST['cofrinho_id']) ? (int) $_POST['cofrinho_id'] : null;

        $mes = $despesa['mes_referencia'];
        $ano = $despesa['ano_referencia'];

        // Marcar despesa como paga
        (new Despesa())->update((int) $despesaId, [
            'status' => 'paga',
            'valor' => $valorReal,
            'data_pagamento' => date('Y-m-d'),
        ]);

        // Descontar do cofrinho automaticamente
        if ($cofrinhoId) {
            $cofrinho = (new Cofrinho())->findById($cofrinhoId);
            if ($cofrinho) {
                $valorNoCofrinho = (float) $cofrinho['valor_atual'];
                $desconto = min($valorReal, $valorNoCofrinho);
                $diferenca = $valorReal - $valorNoCofrinho;

                (new Cofrinho())->retirar($cofrinhoId, $desconto, $this->user()['id'],
                    'Pagamento: ' . $despesa['nome'] . ' (R$ ' . number_format($valorReal, 2, ',', '.') . ')');

                if ($diferenca > 0) {
                    // Valor maior que o cofrinho — avisar
                    setFlash('warning', 'Conta paga! O valor real (R$ ' . number_format($valorReal, 2, ',', '.') .
                        ') foi R$ ' . number_format($diferenca, 2, ',', '.') .
                        ' maior que o cofrinho. Diferença de R$ ' . number_format($diferenca, 2, ',', '.') .
                        ' precisa sair da Reserva ou de outro cofrinho.');
                } else {
                    setFlash('success', 'Conta paga e R$ ' . number_format($desconto, 2, ',', '.') . ' descontado do cofrinho "' . $cofrinho['nome'] . '".');
                }
            } else {
                setFlash('success', 'Conta paga.');
            }
        } else {
            setFlash('success', 'Conta paga.');
        }

        redirect('/dia-pagamento?mes=' . $mes . '&ano=' . $ano);
    }

    /**
     * Depositar no cofrinho (ação rápida da tela)
     */
    public function depositar(string $cofrinhoId): void
    {
        $valor = (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        if ($valor > 0) {
            (new Cofrinho())->depositar((int) $cofrinhoId, $valor, $this->user()['id'], 'Depósito dia do pagamento');
            setFlash('success', 'R$ ' . number_format($valor, 2, ',', '.') . ' depositado.');
        }
        redirect('/dia-pagamento');
    }

    private function extractPriority(string $obs): int
    {
        if (preg_match('/Prioridade\s*(\d+)/i', $obs, $m)) {
            return (int) $m[1];
        }
        return 99;
    }

    /**
     * Mapeia quais despesas pertencem a qual cofrinho
     */
    private function mapCofrinhosDespesas(array $cofrinhos, array $despesas): array
    {
        $map = [];
        $keywords = [
            'Aluguel' => ['Aluguel'],
            'Água + Energia' => ['Água', 'Energia'],
            'Faculdade + MEI' => ['Faculdade', 'MEI Lucas'],
            'Unimed' => ['Unimed'],
            'Consórcio + Gasolina' => ['Consórcio', 'Gasolina'],
            'Investimento' => ['Investimento'],
            'Cartões' => ['Fatura', 'Anuidade Sicredi'],
            'Internet + Celular + TV' => ['Internet', 'Celular Lucas', 'TV /'],
            'Reserva / Ajustes' => ['Reserva'],
            'Mercado' => ['Mercado'],
            'Faculdade' => ['Faculdade Bia'],
            'MEI + Celular + Canva' => ['MEI Bia', 'Celular Bia', 'Canva'],
            'Centro + Ônibus' => ['Centro', 'Ônibus'],
            'Unha' => ['Unha'],
            'Hidratação + Sobrancelha' => ['Hidratação', 'Sobrancelha'],
            'Progressiva' => ['Progressiva'],
            'Assinaturas' => ['Spotify Bia', 'Google Fotos', 'ChatGPT Bia'],
        ];

        foreach ($cofrinhos as $c) {
            $cofName = $c['nome'];
            $map[$c['id']] = [];
            if (isset($keywords[$cofName])) {
                foreach ($despesas as $d) {
                    foreach ($keywords[$cofName] as $kw) {
                        if (stripos($d['nome'], $kw) !== false) {
                            $map[$c['id']][] = $d;
                            break;
                        }
                    }
                }
            }
        }
        return $map;
    }
}
