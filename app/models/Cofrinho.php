<?php
class Cofrinho extends Model
{
    protected string $table = 'cofrinhos';

    public function getByMonth(int $mes, int $ano, ?int $userId = null, ?string $tipo = null): array
    {
        $sql = "SELECT co.*, u.nome as usuario_nome, c.nome as categoria_nome
                FROM cofrinhos co
                LEFT JOIN usuarios u ON co.usuario_id = u.id
                LEFT JOIN categorias c ON co.categoria_id = c.id
                WHERE co.mes_referencia = :mes AND co.ano_referencia = :ano AND co.ativo = 1";
        $params = ['mes' => $mes, 'ano' => $ano];
        if ($userId) {
            $sql .= " AND co.usuario_id = :uid";
            $params['uid'] = $userId;
        }
        if ($tipo) {
            $sql .= " AND co.tipo = :tipo";
            $params['tipo'] = $tipo;
        }
        $sql .= " ORDER BY co.prioridade ASC, co.nome ASC";
        return $this->query($sql, $params);
    }

    public function getTotalMeta(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(meta_mensal), 0) FROM cofrinhos WHERE mes_referencia = :mes AND ano_referencia = :ano AND ativo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getTotalGuardado(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(valor_atual), 0) FROM cofrinhos WHERE mes_referencia = :mes AND ano_referencia = :ano AND ativo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getIncompletos(int $mes, int $ano, int $limit = 5): array
    {
        $sql = "SELECT co.*, u.nome as usuario_nome FROM cofrinhos co
                LEFT JOIN usuarios u ON co.usuario_id = u.id
                WHERE co.mes_referencia = :mes AND co.ano_referencia = :ano
                AND co.ativo = 1 AND co.valor_atual < co.meta_mensal AND co.meta_mensal > 0
                ORDER BY co.prioridade ASC, (co.meta_mensal - co.valor_atual) DESC
                LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue('ano', $ano, PDO::PARAM_INT);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function depositar(int $id, float $valor, int $userId, string $descricao = ''): bool
    {
        $this->db->beginTransaction();
        try {
            $this->execute("UPDATE cofrinhos SET valor_atual = valor_atual + :val WHERE id = :id", ['val' => $valor, 'id' => $id]);
            $movModel = new CofrinhoMovimentacao();
            $movModel->create([
                'cofrinho_id' => $id,
                'usuario_id' => $userId,
                'tipo' => 'deposito',
                'valor' => $valor,
                'descricao' => $descricao ?: 'Depósito',
            ]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function retirar(int $id, float $valor, int $userId, string $descricao = ''): bool
    {
        $this->db->beginTransaction();
        try {
            $this->execute("UPDATE cofrinhos SET valor_atual = GREATEST(valor_atual - :val, 0) WHERE id = :id", ['val' => $valor, 'id' => $id]);
            $movModel = new CofrinhoMovimentacao();
            $movModel->create([
                'cofrinho_id' => $id,
                'usuario_id' => $userId,
                'tipo' => 'retirada',
                'valor' => $valor,
                'descricao' => $descricao ?: 'Retirada',
            ]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
