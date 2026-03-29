<?php
class Notificacao extends Model
{
    protected string $table = 'notificacoes';

    public function getUnread(int $userId, int $limit = 10): array
    {
        $sql = "SELECT * FROM notificacoes WHERE usuario_id = :uid AND lida = 0 ORDER BY criado_em DESC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countUnread(int $userId): int
    {
        return $this->count(['usuario_id' => $userId, 'lida' => 0]);
    }

    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['lida' => 1]);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->execute("UPDATE notificacoes SET lida = 1 WHERE usuario_id = :uid", ['uid' => $userId]);
    }
}
