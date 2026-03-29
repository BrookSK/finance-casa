<?php
class Fatura extends Model
{
    protected string $table = 'faturas';

    public function getByMonth(int $mes, int $ano): array
    {
        $sql = "SELECT f.*, ca.nome as cartao_nome, ca.cor as cartao_cor, u.nome as usuario_nome
                FROM faturas f
                LEFT JOIN cartoes ca ON f.cartao_id = ca.id
                LEFT JOIN usuarios u ON ca.usuario_id = u.id
                WHERE f.mes_referencia = :mes AND f.ano_referencia = :ano
                ORDER BY f.data_vencimento ASC";
        return $this->query($sql, ['mes' => $mes, 'ano' => $ano]);
    }

    public function getOrCreate(int $cartaoId, int $mes, int $ano): array
    {
        $existing = $this->findOneWhere([
            'cartao_id' => $cartaoId,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
        ]);
        if ($existing) return $existing;

        $cartao = (new Cartao())->findById($cartaoId);
        $id = $this->create([
            'cartao_id' => $cartaoId,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
            'valor_total' => 0,
            'data_fechamento' => date('Y-m-d', mktime(0, 0, 0, $mes, $cartao['dia_fechamento'], $ano)),
            'data_vencimento' => date('Y-m-d', mktime(0, 0, 0, $mes, $cartao['dia_vencimento'], $ano)),
            'status' => 'aberta',
        ]);
        return $this->findById($id);
    }

    public function getTotalAberto(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor_total - valor_reservado), 0) FROM faturas WHERE mes_referencia = :mes AND ano_referencia = :ano AND status != 'paga'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
}
