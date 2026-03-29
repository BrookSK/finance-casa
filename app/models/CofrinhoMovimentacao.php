<?php
class CofrinhoMovimentacao extends Model
{
    protected string $table = 'cofrinho_movimentacoes';

    public function getByCofrinho(int $cofrinhoId): array
    {
        $sql = "SELECT cm.*, u.nome as usuario_nome FROM cofrinho_movimentacoes cm
                LEFT JOIN usuarios u ON cm.usuario_id = u.id
                WHERE cm.cofrinho_id = :cid ORDER BY cm.criado_em DESC";
        return $this->query($sql, ['cid' => $cofrinhoId]);
    }
}
