<?php
class ContaBancaria extends Model
{
    protected string $table = 'contas_bancarias';

    public function getAllGrouped(): array
    {
        $sql = "SELECT cb.*, u.nome as usuario_nome
                FROM contas_bancarias cb
                LEFT JOIN usuarios u ON cb.usuario_id = u.id
                WHERE cb.ativo = 1
                ORDER BY cb.tipo ASC, cb.proprietario ASC, cb.nome ASC";
        $all = $this->query($sql);

        $grouped = ['pessoal' => [], 'empresa' => []];
        foreach ($all as $c) {
            $grouped[$c['tipo']][] = $c;
        }
        return $grouped;
    }

    public function getByUser(int $userId): array
    {
        $sql = "SELECT * FROM contas_bancarias WHERE usuario_id = :uid AND ativo = 1 ORDER BY nome ASC";
        return $this->query($sql, ['uid' => $userId]);
    }

    public function getByTipo(string $tipo): array
    {
        return $this->findWhere(['tipo' => $tipo, 'ativo' => 1], 'proprietario ASC, nome ASC');
    }
}
