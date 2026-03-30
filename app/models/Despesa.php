<?php
class Despesa extends Model
{
    protected string $table = 'despesas';

    public function getByMonth(int $mes, int $ano, ?int $userId = null): array
    {
        $sql = "SELECT d.*, u.nome as usuario_nome, c.nome as categoria_nome, c.cor as categoria_cor,
                       ca.nome as cartao_nome
                FROM despesas d
                LEFT JOIN usuarios u ON d.usuario_id = u.id
                LEFT JOIN categorias c ON d.categoria_id = c.id
                LEFT JOIN cartoes ca ON d.cartao_id = ca.id
                WHERE d.mes_referencia = :mes AND d.ano_referencia = :ano";
        $params = ['mes' => $mes, 'ano' => $ano];
        if ($userId) {
            $sql .= " AND d.usuario_id = :uid";
            $params['uid'] = $userId;
        }
        $sql .= " ORDER BY d.data_vencimento ASC, d.nome ASC";
        return $this->query($sql, $params);
    }

    public function getTotalByMonth(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE mes_referencia = :mes AND ano_referencia = :ano";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getTotalPago(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE mes_referencia = :mes AND ano_referencia = :ano AND status = 'paga'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getProximosVencimentos(int $limit = 5): array
    {
        $sql = "SELECT d.*, u.nome as usuario_nome, c.nome as categoria_nome
                FROM despesas d
                LEFT JOIN usuarios u ON d.usuario_id = u.id
                LEFT JOIN categorias c ON d.categoria_id = c.id
                WHERE d.status = 'pendente' AND d.data_vencimento >= CURDATE()
                ORDER BY d.data_vencimento ASC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCategory(int $mes, int $ano): array
    {
        $sql = "SELECT c.nome, c.cor, COALESCE(SUM(d.valor), 0) as total
                FROM despesas d
                LEFT JOIN categorias c ON d.categoria_id = c.id
                WHERE d.mes_referencia = :mes AND d.ano_referencia = :ano
                GROUP BY d.categoria_id, c.nome, c.cor
                ORDER BY total DESC";
        return $this->query($sql, ['mes' => $mes, 'ano' => $ano]);
    }

    public function getGastoCategoria(int $categoriaId, int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE categoria_id = :cid AND mes_referencia = :mes AND ano_referencia = :ano AND status = 'paga'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $categoriaId, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
}

    public function getGastoOrcamentoCartao(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas
                WHERE cartao_id IS NOT NULL AND entra_orcamento_cartao = 1
                AND mes_referencia = :mes AND ano_referencia = :ano";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
