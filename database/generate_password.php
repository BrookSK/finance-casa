<?php
/**
 * Script para gerar hash de senha bcrypt
 * Uso: php database/generate_password.php suasenha
 * Copie o hash gerado e substitua no seed.sql
 */

$password = $argv[1] ?? 'password';
echo "Senha: {$password}\n";
echo "Hash: " . password_hash($password, PASSWORD_DEFAULT) . "\n";
