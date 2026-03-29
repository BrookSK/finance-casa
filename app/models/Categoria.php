<?php
class Categoria extends Model
{
    protected string $table = 'categorias';

    public function getActive(string $tipo = ''): array
    {
        if ($tipo) {
            $sql = "SELECT * FROM categorias WHERE ativo = 1 AND (tipo = :tipo OR tipo = 'ambos') ORDER BY nome ASC";
            return $this->query($sql, ['tipo' => $tipo]);
        }
        return $this->findWhere(['ativo' => 1], 'nome ASC');
    }
}
