<?php
class NotificacaoController extends Controller
{
    public function index(): void
    {
        $model = new Notificacao();
        $userId = $this->user()['id'];

        // Gerar notificações automáticas
        $this->gerarNotificacoesAutomaticas($userId);

        $notificacoes = $model->query(
            "SELECT * FROM notificacoes WHERE usuario_id = :uid ORDER BY lida ASC, criado_em DESC LIMIT 50",
            ['uid' => $userId]
        );
        $totalNaoLidas = $model->countUnread($userId);

        $this->view('notificacoes/index', compact('notificacoes', 'totalNaoLidas'));
    }

    public function marcarLida(string $id): void
    {
        (new Notificacao())->markAsRead((int) $id);
        redirect('/notificacoes');
    }

    public function marcarTodasLidas(): void
    {
        (new Notificacao())->markAllAsRead($this->user()['id']);
        setFlash('success', 'Todas as notificações foram marcadas como lidas.');
        redirect('/notificacoes');
    }

    // API: contar não lidas (para badge no menu)
    public function contarNaoLidas(): void
    {
        $total = (new Notificacao())->countUnread($this->user()['id']);
        $this->json(['total' => $total]);
    }

    private function gerarNotificacoesAutomaticas(int $userId): void
    {
        $model = new Notificacao();
        $hoje = date('Y-m-d');
        $amanha = date('Y-m-d', strtotime('+1 day'));
        $mes = currentMonth();
        $ano = currentYear();

        // Vencimentos próximos (amanhã)
        $despesas = (new Despesa())->query(
            "SELECT * FROM despesas WHERE data_vencimento = :amanha AND status = 'pendente'
             AND (usuario_id = :uid OR proprietario = 'compartilhado')",
            ['amanha' => $amanha, 'uid' => $userId]
        );
        foreach ($despesas as $d) {
            $existe = $model->findOneWhere([
                'usuario_id' => $userId,
                'titulo' => 'Vencimento amanhã: ' . $d['nome'],
            ]);
            if (!$existe) {
                $model->create([
                    'usuario_id' => $userId,
                    'titulo' => 'Vencimento amanhã: ' . $d['nome'],
                    'mensagem' => 'A despesa "' . $d['nome'] . '" no valor de R$ ' . number_format($d['valor'], 2, ',', '.') . ' vence amanhã.',
                    'tipo' => 'alerta',
                    'link' => '/despesas',
                ]);
            }
        }

        // Contas atrasadas
        $atrasadas = (new Despesa())->query(
            "SELECT * FROM despesas WHERE data_vencimento < :hoje AND status = 'pendente'
             AND (usuario_id = :uid OR proprietario = 'compartilhado')",
            ['hoje' => $hoje, 'uid' => $userId]
        );
        foreach ($atrasadas as $d) {
            $existe = $model->findOneWhere([
                'usuario_id' => $userId,
                'titulo' => 'Conta atrasada: ' . $d['nome'],
            ]);
            if (!$existe) {
                $model->create([
                    'usuario_id' => $userId,
                    'titulo' => 'Conta atrasada: ' . $d['nome'],
                    'mensagem' => 'A despesa "' . $d['nome'] . '" venceu em ' . date('d/m', strtotime($d['data_vencimento'])) . ' e ainda não foi paga.',
                    'tipo' => 'urgente',
                    'link' => '/despesas',
                ]);
            }
        }

        // Cofrinhos incompletos (alerta no dia 20+)
        if ((int) date('d') >= 20) {
            $cofrinhos = (new Cofrinho())->getIncompletos($mes, $ano, 20);
            foreach ($cofrinhos as $c) {
                if ($c['usuario_id'] != $userId && $c['tipo'] !== 'compartilhado') continue;
                $existe = $model->findOneWhere([
                    'usuario_id' => $userId,
                    'titulo' => 'Cofrinho incompleto: ' . $c['nome'],
                ]);
                if (!$existe) {
                    $falta = $c['meta_mensal'] - $c['valor_atual'];
                    $model->create([
                        'usuario_id' => $userId,
                        'titulo' => 'Cofrinho incompleto: ' . $c['nome'],
                        'mensagem' => 'Faltam R$ ' . number_format($falta, 2, ',', '.') . ' para completar o cofrinho "' . $c['nome'] . '".',
                        'tipo' => 'alerta',
                        'link' => '/cofrinhos',
                    ]);
                }
            }
        }

        // Orçamento de categoria quase acabando (>85%)
        $orcamentos = (new Orcamento())->getByMonth($mes, $ano);
        $despModel = new Despesa();
        foreach ($orcamentos as $orc) {
            $gasto = $despModel->getGastoCategoria($orc['categoria_id'], $mes, $ano);
            $pct = percentual($gasto, $orc['valor_limite']);
            if ($pct >= 85) {
                $catNome = $orc['categoria_nome'] ?? 'Categoria';
                $existe = $model->findOneWhere([
                    'usuario_id' => $userId,
                    'titulo' => 'Orçamento quase esgotado: ' . $catNome,
                ]);
                if (!$existe) {
                    $model->create([
                        'usuario_id' => $userId,
                        'titulo' => 'Orçamento quase esgotado: ' . $catNome,
                        'mensagem' => 'O orçamento de "' . $catNome . '" está em ' . $pct . '%. Restam R$ ' . number_format($orc['valor_limite'] - $gasto, 2, ',', '.') . '.',
                        'tipo' => 'alerta',
                        'link' => '/orcamentos',
                    ]);
                }
            }
        }
    }
}
