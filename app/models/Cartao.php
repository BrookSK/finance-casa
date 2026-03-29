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
        // Primeiro tenta pegar da fatura (valor real)
        $sql = "SELECT valor_total FROM faturas WHERE cartao_id = :cid AND mes_referencia = :mes AND ano_referencia = :ano LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $cartaoId, 'mes' => $mes, 'ano' => $ano]);
        $fatura = $stmt->fetchColumn();

        if ($fatura !== false && $fatura > 0) {
            return (float) $fatura;
        }

        // Fallback: soma despesas vinculadas ao cartão naquele mês
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM despesas WHERE cartao_id = :cid AND mes_referencia = :mes AND ano_referencia = :ano";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $cartaoId, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
}
