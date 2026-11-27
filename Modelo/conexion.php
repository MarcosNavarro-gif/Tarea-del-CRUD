<?php
declare(strict_types=1);

/*
 * Clase encargada de administrar la conexión con la base de datos MySQL.
 * En una instalación local de WampServer puede utilizarse root sin contraseña.
 * Estos datos deben ajustarse si la instalación utiliza otras credenciales.
 */
class DB
{
    // Guarda la conexión para reutilizarla durante la ejecución del programa.
    private static ?PDO $conexion = null;

    // Crea la conexión PDO solo cuando todavía no existe.
    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            $host = 'localhost';
            $bd = 'productosdb';
            $usuario = 'root';
            $contrasena = '';

            // DSN: indica el servidor, la base de datos y el juego de caracteres.
            $dsn = "mysql:host={$host};dbname={$bd};charset=utf8mb4";

            // PDO permite ejecutar consultas y controlar los errores de la conexión.
            self::$conexion = new PDO($dsn, $usuario, $contrasena, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        }

        return self::$conexion;
    }

    // Ejecuta una consulta INSERT utilizando parámetros preparados.
    public static function insertSeguro(string $sql, array $datos): int
    {
        $consulta = self::conectar()->prepare($sql);
        $consulta->execute($datos);

        // Devuelve el ID generado por la última inserción.
        return (int) self::conectar()->lastInsertId();
    }

    // Ejecuta una consulta UPDATE y devuelve la cantidad de filas afectadas.
    public static function updateSeguro(string $sql, array $datos): int
    {
        $consulta = self::conectar()->prepare($sql);
        $consulta->execute($datos);
        return $consulta->rowCount();
    }

    // Ejecuta consultas preparadas y devuelve los resultados como un arreglo.
    public static function query(string $sql, array $datos = []): array
    {
        $consulta = self::conectar()->prepare($sql);
        $consulta->execute($datos);
        return $consulta->fetchAll();
    }
}
