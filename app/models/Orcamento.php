<?php
class Orcamento extends Model
{
    protected string $table = 'orcamentos';

    public function getByMonth(int $mes, int $ano): array
    {
        $sql = "SELECT o.*, c.nome as categoria_nome, c.cor as categoria_cor
                FROM orcamentos o
                LEFT JOIN categorias c ON o.categoria_id = c.id
                WHERE o.mes_referencia = :mes AND o.ano_referencia = :ano
                ORDER BY c.nome ASC";
        return $this->query($sql, ['mes' => $mes, 'ano' => $ano]);
    }
}
