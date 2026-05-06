<?php
/**
 * Configuração Singleton de Conexão com MySQL via PDO
 * 
 * Segue o padrão Singleton para garantir uma única instância
 * de conexão com o banco de dados durante o ciclo de vida da requisição.
 * 
 * @package Config
 * @author Engenheiro de Software
 */

declare(strict_types=1);

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private const HOST = 'localhost';
    private const DB_NAME = 'gestao_hospitalar';
    private const USER = 'root';
    private const PASS = 'nicolas9013';
    private const CHARSET = 'utf8mb4';

    /**
     * Construtor privado para impedir instanciação externa
     */
    private function __construct()
    {
    }

    /**
     * Obtém a instância única da conexão PDO
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    self::HOST,
                    self::DB_NAME,
                    self::CHARSET
                );

                self::$instance = new PDO($dsn, self::USER, self::PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('[DB ERROR] ' . $e->getMessage());
                throw new PDOException('Erro ao conectar com o banco de dados: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Impede clonagem da instância
     */
    private function __clone()
    {
    }

    /**
     * Impede deserialização da instância
     */
    public function __wakeup()
    {
        throw new \Exception('Não é possível desserializar um singleton.');
    }
}
