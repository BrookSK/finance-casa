<?php
class ListaCompra extends Model
{
    protected string $table = 'listas_compras';

    public function getAllByUser(?int $userId = null, string $status = ''): array
    {
        $sql = "SELECT lc.*, u.nome as usuario_nome,
                       (SELECT COUNT(*) FROM lista_itens li WHERE li.lista_id = lc.id) as total_itens,
                       (SELECT COUNT(*) FROM lista_itens li WHERE li.lista_id = lc.id AND li.comprado = 1) as itens_comprados
                FROM listas_compras lc
                LEFT JOIN usuarios u ON lc.usuario_id = u.id
                WHERE 1=1";
        $params = [];
        if ($userId) {
            $sql .= " AND lc.usuario_id = :uid";
            $params['uid'] = $userId;
        }
        if ($status) {
            $sql .= " AND lc.status = :status";
            $params['status'] = $status;
        }
        $sql .= " ORDER BY lc.criado_em DESC";
        return $this->query($sql, $params);
    }

    public function getWithItems(int $id): ?array
    {
        $lista = $this->findById($id);
        if (!$lista) return null;

        $sql = "SELECT * FROM lista_itens WHERE lista_id = :lid ORDER BY comprado ASC, prioridade ASC, nome ASC";
        $lista['itens'] = $this->query($sql, ['lid' => $id]);
        return $lista;
    }

    public function recalcularTotais(int $id): void
    {
        $sql = "UPDATE listas_compras SET
                total_estimado = COALESCE((SELECT SUM(preco_estimado * quantidade) FROM lista_itens WHERE lista_id = :id1 AND preco_estimado IS NOT NULL), 0),
                total_real = COALESCE((SELECT SUM(preco_real * quantidade) FROM lista_itens WHERE lista_id = :id2 AND comprado = 1 AND preco_real IS NOT NULL), 0)
                WHERE id = :id3";
        $this->execute($sql, ['id1' => $id, 'id2' => $id, 'id3' => $id]);
    }

    public function getTotalGastoMercadoMes(int $mes, int $ano): float
    {
        $sql = "SELECT COALESCE(SUM(lc.total_real), 0) FROM listas_compras lc
                WHERE MONTH(lc.data_compra) = :mes AND YEAR(lc.data_compra) = :ano AND lc.status != 'cancelada'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    public function getHistoricoPorLocal(): array
    {
        $sql = "SELECT local_compra, COUNT(*) as total_compras, SUM(total_real) as total_gasto,
                       AVG(total_real) as media_gasto
                FROM listas_compras
                WHERE local_compra IS NOT NULL AND local_compra != '' AND status != 'cancelada'
                GROUP BY local_compra ORDER BY total_compras DESC";
        return $this->query($sql);
    }
}
