<?php

class Database
{
    private static ?PDO $instance = null;

    // Configuración de conexión (idealmente deberías usar variables de entorno)
    private const HOST = 'localhost';
    private const DB   = 'sistema_articulos';
    private const USER = 'root';
    private const PASS = '';
    private const CHARSET = 'utf8mb4';

    private function __construct() {} // Evita instanciación directa

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB . ";charset=" . self::CHARSET;
                
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones ante errores
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos por defecto
                    PDO::ATTR_EMULATE_PREPARES   => false,                  // Seguridad contra inyección SQL
                ];

                self::$instance = new PDO($dsn, self::USER, self::PASS, $options);
            } catch (PDOException $e) {
                // En producción, registra esto en un log en lugar de mostrarlo al usuario
                throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
