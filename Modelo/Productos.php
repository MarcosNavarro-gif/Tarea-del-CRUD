<?php
declare(strict_types=1);

// Se incluye la clase DB para utilizar la conexión y las consultas a MySQL.
require_once __DIR__ . '/conexion.php';

/*
 * Clase que representa un producto y contiene las operaciones relacionadas
 * con su registro, modificación, búsqueda y listado.
 */
class Producto
{
    // Propiedades privadas que almacenan la información de cada producto.
    private string $codigo;
    private string $producto;
    private float $precio;
    private int $cantidad;

    // El constructor recibe los datos y limpia o convierte sus valores.
    public function __construct(string $codigo, string $producto, $precio, $cantidad)
    {
        $this->codigo = trim($codigo);
        $this->producto = trim($producto);
        $this->precio = (float) $precio;
        $this->cantidad = (int) $cantidad;
    }

    // Comprueba que los datos cumplan las reglas antes de guardarlos o modificarlos.
    public function validar(): array
    {
        $errores = [];

        if ($this->codigo === '' || strlen($this->codigo) > 20) {
            $errores['Codigo'] = 'El código es obligatorio y debe tener máximo 20 caracteres.';
        }

        if ($this->producto === '' || strlen($this->producto) > 100) {
            $errores['Producto'] = 'El nombre es obligatorio y debe tener máximo 100 caracteres.';
        }

        if (!is_finite($this->precio) || $this->precio < 0) {
            $errores['Precio'] = 'El precio debe ser un número mayor o igual que cero.';
        }

        if ($this->cantidad < 0) {
            $errores['Cantidad'] = 'La cantidad no puede ser negativa.';
        }

        // Devuelve un arreglo vacío si todos los datos son válidos.
        return $errores;
    }

    // Inserta un nuevo producto utilizando una consulta preparada.
    public function guardar(): int
    {
        $sql = 'INSERT INTO productos (codigo, producto, precio, cantidad)
                VALUES (:codigo, :producto, :precio, :cantidad)';

        return DB::insertSeguro($sql, [
            ':codigo' => $this->codigo,
            ':producto' => $this->producto,
            ':precio' => number_format($this->precio, 2, '.', ''),
            ':cantidad' => $this->cantidad
        ]);
    }

    // Actualiza los datos del producto que corresponde al ID recibido.
    public function editar(int $id): int
    {
        $sql = 'UPDATE productos
                SET codigo = :codigo, producto = :producto,
                    precio = :precio, cantidad = :cantidad
                WHERE id = :id';

        return DB::updateSeguro($sql, [
            ':codigo' => $this->codigo,
            ':producto' => $this->producto,
            ':precio' => number_format($this->precio, 2, '.', ''),
            ':cantidad' => $this->cantidad,
            ':id' => $id
        ]);
    }

    // Busca un producto por su código y devuelve sus datos o null si no existe.
    public static function buscarPorCodigo(string $codigo): ?array
    {
        $resultado = DB::query(
            'SELECT id, codigo, producto, precio, cantidad
             FROM productos WHERE codigo = :codigo LIMIT 1',
            [':codigo' => trim($codigo)]
        );

        return $resultado[0] ?? null;
    }

    // Obtiene todos los productos y los ordena desde el más reciente.
    public static function listar(): array
    {
        return DB::query(
            'SELECT id, codigo, producto, precio, cantidad
             FROM productos ORDER BY id DESC'
        );
    }
}
