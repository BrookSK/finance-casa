<?php
class CofrinhoItem extends Model
{
    protected string $table = 'cofrinho_itens';

    public function getByCofrinho(int $cofrinhoId): array
    {
        return $this->findWhere(['cofrinho_id' => $cofrinhoId], 'nome ASC');
    }

    public function getByMonth(int $mes, int $ano): array
    {
        $sql = "SELECT ci.*, c.id as cofrinho_id, c.nome as cofrinho_nome
                FROM cofrinho_itens ci
                INNER JOIN cofrinhos c ON ci.cofrinho_id = c.id
                WHERE c.mes_referencia = :mes AND c.ano_referencia = :ano
                ORDER BY c.id ASC, ci.nome ASC";
        return $this->query($sql, ['mes' => $mes, 'ano' => $ano]);
    }
}
