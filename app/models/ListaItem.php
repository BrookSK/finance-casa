<?php
class ListaItem extends Model
{
    protected string $table = 'lista_itens';

    public function getByLista(int $listaId): array
    {
        return $this->findWhere(['lista_id' => $listaId], 'comprado ASC, prioridade ASC, nome ASC');
    }

    public function marcarComprado(int $id, float $precoReal): bool
    {
        return $this->update($id, [
            'comprado' => 1,
            'preco_real' => $precoReal,
        ]);
    }

    public function desmarcarComprado(int $id): bool
    {
        return $this->update($id, ['comprado' => 0]);
    }

    public function getPrecoMedio(string $nomeItem): ?float
    {
        $sql = "SELECT AVG(preco_real) FROM lista_itens WHERE LOWER(nome) = LOWER(:nome) AND comprado = 1 AND preco_real > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nome' => $nomeItem]);
        $val = $stmt->fetchColumn();
        return $val ? (float) $val : null;
    }

    public function getHistoricoPrecos(string $nomeItem, int $limit = 10): array
    {
        $sql = "SELECT li.preco_real, li.quantidade, lc.local_compra, lc.data_compra
                FROM lista_itens li
                LEFT JOIN listas_compras lc ON li.lista_id = lc.id
                WHERE LOWER(li.nome) = LOWER(:nome) AND li.comprado = 1 AND li.preco_real > 0
                ORDER BY lc.data_compra DESC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('nome', $nomeItem);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
