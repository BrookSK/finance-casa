<?php
class Cartao extends Model
{
    protected string $table = 'cartoes';

    public function getByUser(int $userId): array
    {
        return $this->findWhere(['usuario_id' => $userId, 'ativo' => 1], 'nome ASC');
    }

    public function getAllActive(): array
    {
        $sql = "SELECT ca.*, u.nome as usuario_nome FROM cartoes ca
                LEFT JOIN usuarios u ON ca.usuario_id = u.id
                WHERE ca.ativo = 1 ORDER BY ca.nome ASC";
        return $this->query($sql);
    }

    public function getGastoAtual(int $cartaoId, int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE cartao_id = :cid AND mes_referencia = :mes AND ano_referencia = :ano";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $cartaoId, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
}
