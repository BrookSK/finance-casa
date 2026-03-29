<?php
class Receita extends Model
{
    protected string $table = 'receitas';

    public function getByMonth(int $mes, int $ano, ?int $userId = null): array
    {
        $sql = "SELECT r.*, u.nome as usuario_nome, c.nome as categoria_nome, c.cor as categoria_cor
                FROM receitas r
                LEFT JOIN usuarios u ON r.usuario_id = u.id
                LEFT JOIN categorias c ON r.categoria_id = c.id
                WHERE r.mes_referencia = :mes AND r.ano_referencia = :ano";
        $params = ['mes' => $mes, 'ano' => $ano];
        if ($userId) {
            $sql .= " AND r.usuario_id = :uid";
            $params['uid'] = $userId;
        }
        $sql .= " ORDER BY r.data_prevista ASC, r.titulo ASC";
        return $this->query($sql, $params);
    }

    public function getTotalByMonth(int $mes, int $ano, bool $apenasOrcamento = true): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM receitas WHERE mes_referencia = :mes AND ano_referencia = :ano";
        $params = ['mes' => $mes, 'ano' => $ano];
        if ($apenasOrcamento) {
            $sql .= " AND entra_no_orcamento = 1";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (float) $stmt->fetchColumn();
    }

    public function getTotalRecebido(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM receitas WHERE mes_referencia = :mes AND ano_referencia = :ano AND status = 'recebida' AND entra_no_orcamento = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getProximosRecebimentos(int $limit = 5): array
    {
        $sql = "SELECT r.*, u.nome as usuario_nome FROM receitas r
                LEFT JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.status = 'prevista' AND r.entra_no_orcamento = 1
                ORDER BY r.data_prevista ASC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
