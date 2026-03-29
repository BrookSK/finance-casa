<?php
/**
 * Classe de conexão com banco de dados MySQL via PDO
 * Singleton para reutilizar conexão
 */
class Database
{
    private static ?PDO $instance = null;

    // Configuração direta (sem .env)
    private static string $host = 'localhost';
    private static string $dbname = 'financas_casal';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$instance = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die('Erro de conexão: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    /**
     * Atualiza configurações de conexão (usado pela tela de configurações)
     */
    public static function updateConfig(string $host, string $dbname, string $username, string $password): void
    {
        self::$host = $host;
        self::$dbname = $dbname;
        self::$username = $username;
        self::$password = $password;
        self::$instance = null; // Forçar reconexão
    }

    // Prevenir clonagem e instanciação
    private function __construct() {}
    private function __clone() {}
}
