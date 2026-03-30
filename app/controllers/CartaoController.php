<?php
class CartaoController extends Controller
{
    public function index(): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $cartoes = (new Cartao())->getAllActive();
        $cartaoModel = new Cartao();
        $faturaModel = new Fatura();

        foreach ($cartoes as &$c) {
            $c['gasto_atual'] = $cartaoModel->getGastoAtual($c['id'], $mes, $ano);
            $c['limite_disponivel'] = $c['limite_total'] - $c['gasto_atual'];
            // Buscar status da fatura do mês
            $fatura = $faturaModel->findOneWhere([
                'cartao_id' => $c['id'],
                'mes_referencia' => $mes,
                'ano_referencia' => $ano,
            ]);
            $c['fatura_status'] = $fatura['status'] ?? null;
            $c['fatura_vencimento'] = $fatura['data_vencimento'] ?? null;
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

    public function detalhe(string $id): void
    {
        $mes = (int) ($_GET['mes'] ?? currentMonth());
        $ano = (int) ($_GET['ano'] ?? currentYear());
        $cartao = (new Cartao())->findById((int) $id);
        if (!$cartao) { redirect('/cartoes'); return; }

        $cartao['usuario_nome'] = (new Usuario())->findById($cartao['usuario_id'])['nome'] ?? '';

        // Fatura do mês
        $faturaModel = new Fatura();
        $fatura = $faturaModel->findOneWhere([
            'cartao_id' => (int) $id,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
        ]);

        // Lançamentos da fatura
        $lancamentos = [];
        if ($fatura) {
            $lancamentos = $faturaModel->query(
                "SELECT fl.*, c.nome as categoria_nome, c.cor as categoria_cor
                 FROM fatura_lancamentos fl
                 LEFT JOIN categorias c ON fl.categoria_id = c.id
                 WHERE fl.fatura_id = :fid ORDER BY fl.data_compra ASC",
                ['fid' => $fatura['id']]
            );
        }

        // Despesas vinculadas ao cartão neste mês
        $despesas = (new Despesa())->query(
            "SELECT d.*, c.nome as categoria_nome
             FROM despesas d
             LEFT JOIN categorias c ON d.categoria_id = c.id
             WHERE d.cartao_id = :cid AND d.mes_referencia = :mes AND d.ano_referencia = :ano
             ORDER BY d.data_vencimento ASC, d.nome ASC",
            ['cid' => (int) $id, 'mes' => $mes, 'ano' => $ano]
        );

        // Totais por categoria
        $porCategoria = [];
        foreach ($lancamentos as $l) {
            $cat = $l['categoria_nome'] ?? 'Sem categoria';
            if (!isset($porCategoria[$cat])) {
                $porCategoria[$cat] = ['nome' => $cat, 'cor' => $l['categoria_cor'] ?? '#6366f1', 'total' => 0, 'qtd' => 0];
            }
            $porCategoria[$cat]['total'] += $l['valor'];
            $porCategoria[$cat]['qtd']++;
        }
        usort($porCategoria, fn($a, $b) => $b['total'] <=> $a['total']);

        // Resumo de parcelas ativas neste cartão (agrupado)
        $parcelas = (new Despesa())->query(
            "SELECT
                SUBSTRING_INDEX(d.nome, ' (parcela', 1) as nome_base,
                d.valor,
                d.total_parcelas,
                COUNT(*) as parcelas_restantes,
                MIN(d.parcela_atual) as proxima_parcela,
                MAX(d.parcela_atual) as ultima_parcela,
                MAX(CONCAT(d.ano_referencia, '-', LPAD(d.mes_referencia, 2, '0'))) as ultimo_mes
             FROM despesas d
             WHERE d.cartao_id = :cid AND d.parcelada = 1 AND d.status = 'pendente'
             GROUP BY SUBSTRING_INDEX(d.nome, ' (parcela', 1), d.valor, d.total_parcelas
             ORDER BY ultimo_mes ASC",
            ['cid' => (int) $id]
        );

        // Gasto atual (do model)
        $gastoAtual = (new Cartao())->getGastoAtual((int) $id, $mes, $ano);
        $limiteDisponivel = $cartao['limite_total'] - $gastoAtual;

        $categorias = (new Categoria())->getActive('despesa');

        $this->view('cartoes/detalhe', compact(
            'cartao', 'fatura', 'lancamentos', 'despesas', 'porCategoria',
            'parcelas', 'gastoAtual', 'limiteDisponivel', 'mes', 'ano', 'categorias'
        ));
    }

    public function addLancamento(string $id): void
    {
        $mes = (int) ($_POST['mes_referencia'] ?? currentMonth());
        $ano = (int) ($_POST['ano_referencia'] ?? currentYear());
        $cartao = (new Cartao())->findById((int) $id);
        if (!$cartao) { redirect('/cartoes'); return; }

        $valor = (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoriaId = !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
        $dataCompra = $_POST['data_compra'] ?: date('Y-m-d');
        $proprietario = $_POST['proprietario'] ?? 'compartilhado';
        $parcelada = isset($_POST['parcelada']) ? 1 : 0;
        $totalParcelas = !empty($_POST['total_parcelas']) ? (int) $_POST['total_parcelas'] : null;
        $entraOrcCartao = isset($_POST['excluir_orcamento_cartao']) ? 1 : 0;

        // Determinar em qual fatura cai (baseado na data de compra vs fechamento)
        $diaCompra = (int) date('d', strtotime($dataCompra));
        $mesCompra = (int) date('m', strtotime($dataCompra));
        $anoCompra = (int) date('Y', strtotime($dataCompra));

        if ($diaCompra > $cartao['dia_fechamento']) {
            // Cai na fatura do mês seguinte
            $mesFatura = $mesCompra + 1;
            $anoFatura = $anoCompra;
            if ($mesFatura > 12) { $mesFatura = 1; $anoFatura++; }
        } else {
            $mesFatura = $mesCompra;
            $anoFatura = $anoCompra;
        }

        // Criar ou pegar fatura
        $faturaModel = new Fatura();
        $fatura = $faturaModel->getOrCreate((int) $id, $mesFatura, $anoFatura);

        if ($parcelada && $totalParcelas > 1) {
            // Criar lançamento e despesa para cada parcela
            $valorParcela = round($valor / $totalParcelas, 2);
            for ($p = 1; $p <= $totalParcelas; $p++) {
                $mf = $mesFatura + ($p - 1);
                $af = $anoFatura;
                while ($mf > 12) { $mf -= 12; $af++; }

                $fat = $faturaModel->getOrCreate((int) $id, $mf, $af);

                // Lançamento na fatura
                $faturaModel->execute(
                    "INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, parcela_atual, total_parcelas, data_compra)
                     VALUES (:fid, :desc, :val, :cat, :pa, :tp, :dc)",
                    ['fid' => $fat['id'], 'desc' => $descricao . " ({$p}/{$totalParcelas})", 'val' => $valorParcela,
                     'cat' => $categoriaId, 'pa' => $p, 'tp' => $totalParcelas, 'dc' => $dataCompra]
                );

                // Atualizar total da fatura
                $faturaModel->execute(
                    "UPDATE faturas SET valor_total = valor_total + :val WHERE id = :fid",
                    ['val' => $valorParcela, 'fid' => $fat['id']]
                );

                // Despesa
                $dataVenc = date('Y-m-d', mktime(0, 0, 0, $mf, $cartao['dia_vencimento'], $af));
                (new Despesa())->create([
                    'usuario_id' => $this->user()['id'],
                    'nome' => $descricao . " ({$p}/{$totalParcelas})",
                    'valor' => $valorParcela,
                    'tipo' => 'parcelada',
                    'categoria_id' => $categoriaId,
                    'proprietario' => $proprietario,
                    'forma_pagamento' => 'credito',
                    'cartao_id' => (int) $id,
                    'data_vencimento' => $dataVenc,
                    'parcelada' => 1,
                    'total_parcelas' => $totalParcelas,
                    'parcela_atual' => $p,
                    'mes_referencia' => $mf,
                    'ano_referencia' => $af,
                    'status' => 'pendente',
                    'excluir_orcamento_cartao' => $entraOrcCartao,
                ]);
            }
        } else {
            // Lançamento único
            $faturaModel->execute(
                "INSERT INTO fatura_lancamentos (fatura_id, descricao, valor, categoria_id, data_compra)
                 VALUES (:fid, :desc, :val, :cat, :dc)",
                ['fid' => $fatura['id'], 'desc' => $descricao, 'val' => $valor,
                 'cat' => $categoriaId, 'dc' => $dataCompra]
            );

            $faturaModel->execute(
                "UPDATE faturas SET valor_total = valor_total + :val WHERE id = :fid",
                ['val' => $valor, 'fid' => $fatura['id']]
            );

            $dataVenc = date('Y-m-d', mktime(0, 0, 0, $mesFatura, $cartao['dia_vencimento'], $anoFatura));
            (new Despesa())->create([
                'usuario_id' => $this->user()['id'],
                'nome' => $descricao,
                'valor' => $valor,
                'tipo' => 'variavel',
                'categoria_id' => $categoriaId,
                'proprietario' => $proprietario,
                'forma_pagamento' => 'credito',
                'cartao_id' => (int) $id,
                'data_vencimento' => $dataVenc,
                'mes_referencia' => $mesFatura,
                'ano_referencia' => $anoFatura,
                'status' => 'pendente',
                'excluir_orcamento_cartao' => $entraOrcCartao,
            ]);
        }

        setFlash('success', 'Lançamento adicionado ao cartão.');
        redirect('/cartoes/detalhe/' . $id . '?mes=' . $mesFatura . '&ano=' . $anoFatura);
    }

    public function editLancamento(string $cartaoId, string $lancamentoId): void
    {
        $lancamento = (new Fatura())->query(
            "SELECT fl.*, f.cartao_id, f.mes_referencia, f.ano_referencia
             FROM fatura_lancamentos fl
             LEFT JOIN faturas f ON fl.fatura_id = f.id
             WHERE fl.id = :id",
            ['id' => (int) $lancamentoId]
        );
        if (empty($lancamento)) { redirect('/cartoes/detalhe/' . $cartaoId); return; }
        $lancamento = $lancamento[0];

        $categorias = (new Categoria())->getActive('despesa');
        $cartao = (new Cartao())->findById((int) $cartaoId);
        $this->view('cartoes/edit-lancamento', compact('lancamento', 'categorias', 'cartao', 'cartaoId'));
    }

    public function updateLancamento(string $cartaoId, string $lancamentoId): void
    {
        $valor = (float) str_replace(['.', ','], ['', '.'], $_POST['valor'] ?? '0');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoriaId = !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
        $dataCompra = $_POST['data_compra'] ?: null;

        $faturaModel = new Fatura();

        // Pegar lançamento atual para calcular diferença
        $atual = $faturaModel->query("SELECT * FROM fatura_lancamentos WHERE id = :id", ['id' => (int) $lancamentoId]);
        if (empty($atual)) { redirect('/cartoes/detalhe/' . $cartaoId); return; }
        $atual = $atual[0];
        $diferenca = $valor - $atual['valor'];

        // Atualizar lançamento
        $faturaModel->execute(
            "UPDATE fatura_lancamentos SET descricao = :desc, valor = :val, categoria_id = :cat, data_compra = :dc WHERE id = :id",
            ['desc' => $descricao, 'val' => $valor, 'cat' => $categoriaId, 'dc' => $dataCompra, 'id' => (int) $lancamentoId]
        );

        // Atualizar total da fatura
        if ($diferenca != 0) {
            $faturaModel->execute(
                "UPDATE faturas SET valor_total = valor_total + :diff WHERE id = :fid",
                ['diff' => $diferenca, 'fid' => $atual['fatura_id']]
            );
        }

        // Atualizar despesa vinculada se existir
        if ($atual['despesa_id']) {
            (new Despesa())->update($atual['despesa_id'], [
                'nome' => $descricao,
                'valor' => $valor,
                'categoria_id' => $categoriaId,
                'excluir_orcamento_cartao' => isset($_POST['excluir_orcamento_cartao']) ? 1 : 0,
            ]);
        }

        $fatura = $faturaModel->findById($atual['fatura_id']);
        setFlash('success', 'Lançamento atualizado.');
        redirect('/cartoes/detalhe/' . $cartaoId . '?mes=' . $fatura['mes_referencia'] . '&ano=' . $fatura['ano_referencia']);
    }

    public function deleteLancamento(string $cartaoId, string $lancamentoId): void
    {
        $this->requireAdmin();
        $faturaModel = new Fatura();

        $lancamento = $faturaModel->query("SELECT * FROM fatura_lancamentos WHERE id = :id", ['id' => (int) $lancamentoId]);
        if (empty($lancamento)) { redirect('/cartoes/detalhe/' . $cartaoId); return; }
        $lancamento = $lancamento[0];

        // Subtrair do total da fatura
        $faturaModel->execute(
            "UPDATE faturas SET valor_total = GREATEST(valor_total - :val, 0) WHERE id = :fid",
            ['val' => $lancamento['valor'], 'fid' => $lancamento['fatura_id']]
        );

        // Deletar despesa vinculada
        if ($lancamento['despesa_id']) {
            (new Despesa())->delete($lancamento['despesa_id']);
        }

        // Deletar lançamento
        $faturaModel->execute("DELETE FROM fatura_lancamentos WHERE id = :id", ['id' => (int) $lancamentoId]);

        $fatura = $faturaModel->findById($lancamento['fatura_id']);
        setFlash('success', 'Lançamento excluído.');
        redirect('/cartoes/detalhe/' . $cartaoId . '?mes=' . ($fatura['mes_referencia'] ?? currentMonth()) . '&ano=' . ($fatura['ano_referencia'] ?? currentYear()));
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        (new Cartao())->delete((int) $id);
        setFlash('success', 'Cartão excluído.');
        redirect('/cartoes');
    }
}
