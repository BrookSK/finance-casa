<?php
class Usuario extends Model
{
    protected string $table = 'usuarios';

    public function findByEmail(string $email): ?array
    {
        return $this->findOneWhere(['email' => $email]);
    }

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['senha'])) {
            return $user;
        }
        return null;
    }

    public function getAllActive(): array
    {
        return $this->findWhere(['ativo' => 1], 'nome ASC');
    }
}
