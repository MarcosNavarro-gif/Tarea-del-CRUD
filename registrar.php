<?php
declare(strict_types=1);

// Todas las respuestas de este archivo se envían en formato JSON.
header('Content-Type: application/json; charset=utf-8');

// Se incluye la clase Producto, que contiene las operaciones del CRUD.
require_once __DIR__ . '/Modelo/Productos.php';

/**
 * Construye y envía una respuesta JSON al navegador.
 *
 * $success indica si la operación se realizó correctamente.
 * $message contiene un mensaje para el usuario.
 * $accion identifica la operación solicitada.
 * $extra permite agregar datos adicionales, como productos o errores.
 */
function responder(bool $success, string $message, string $accion, array $extra = []): void
{
    echo json_encode(
        array_merge([
            'success' => $success,
            'message' => $message,
            'accion' => $accion,
            'errors' => []
        ], $extra),
        JSON_UNESCAPED_UNICODE
    );
    exit; // Evita que se envíe contenido adicional después del JSON.
}

try {
    // Se obtiene la acción enviada desde JavaScript mediante FormData.
    $accion = trim((string) ($_POST['Accion'] ?? ''));

    // El switch dirige la solicitud hacia la operación correspondiente.
    switch ($accion) {
        case 'Guardar':
        case 'Modificar':
            // Se reciben los datos del formulario.
            $codigo = (string) ($_POST['Codigo'] ?? '');
            $nombre = (string) ($_POST['Producto'] ?? '');
            $precio = $_POST['Precio'] ?? '';
            $cantidad = $_POST['Cantidad'] ?? '';

            // Se recopilan primero los errores de formato.
            $errores = [];

            if ($precio === '' || !is_numeric($precio)) {
                $errores['Precio'] = 'Ingresa un precio numérico.';
            }
            if ($cantidad === '' || filter_var($cantidad, FILTER_VALIDATE_INT) === false) {
                $errores['Cantidad'] = 'Ingresa una cantidad entera.';
            }

            // Se crea el objeto y se ejecutan las validaciones de la clase Producto.
            $producto = new Producto($codigo, $nombre, $precio === '' ? 0 : $precio,
                $cantidad === '' ? 0 : $cantidad);
            $errores = array_merge($errores, $producto->validar());

            // Cuando se modifica, también se verifica que el ID sea válido.
            if ($accion === 'Modificar') {
                $id = filter_var($_POST['Id'] ?? null, FILTER_VALIDATE_INT);
                if (!$id || $id < 1) {
                    $errores['Id'] = 'No se recibió un identificador válido para modificar.';
                }
            }

            // Si existen errores, se devuelven al navegador sin guardar los datos.
            if ($errores) {
                responder(false, 'Revisa los campos indicados.', $accion, ['errors' => $errores]);
            }

            // La acción Guardar inserta un producto nuevo en la base de datos.
            if ($accion === 'Guardar') {
                $producto->guardar();
                responder(true, 'Producto registrado correctamente.', $accion);
            }

            // La acción Modificar actualiza el producto identificado por su ID.
            $filas = $producto->editar((int) $id);
            if ($filas === 0) {
                // rowCount puede ser cero si los datos enviados son iguales a los existentes.
                $existe = DB::query('SELECT id FROM productos WHERE id = :id', [':id' => (int) $id]);
                if (!$existe) {
                    responder(false, 'No se encontró el producto que deseas modificar.', $accion);
                }
            }
            responder(true, 'Producto actualizado correctamente.', $accion);
            break;

        case 'Buscar':
            // Se recibe y limpia el código que se desea localizar.
            $codigo = trim((string) ($_POST['Codigo'] ?? ''));
            if ($codigo === '') {
                responder(false, 'Escribe el código del producto que deseas buscar.', $accion,
                    ['errors' => ['Codigo' => 'El código es obligatorio.']]);
            }

            // Se consulta el producto por su código.
            $encontrado = Producto::buscarPorCodigo($codigo);
            if (!$encontrado) {
                responder(false, 'No se encontró un producto con ese código.', $accion);
            }

            // Si existe, se envían sus datos al navegador.
            responder(true, 'Producto encontrado.', $accion, ['producto' => $encontrado]);
            break;

        case 'Listar':
            // Se devuelve la lista completa de productos registrados.
            responder(true, 'Listado cargado.', $accion, ['productos' => Producto::listar()]);
            break;

        default:
            // Se informa cuando llega una acción que el sistema no reconoce.
            responder(false, 'La acción solicitada no es válida.', $accion);
    }
} catch (PDOException $e) {
    // El código 23000 permite identificar, entre otros casos, códigos duplicados.
    if ($e->getCode() === '23000') {
        responder(false, 'Ese código ya está registrado. Utiliza uno diferente.', $accion,
            ['errors' => ['Codigo' => 'El código debe ser único.']]);
    }

    // Los detalles técnicos se guardan en el registro del servidor, no se muestran al usuario.
    error_log($e->getMessage());
    responder(false, 'Ocurrió un problema al comunicarse con la base de datos.', $accion);
} catch (Throwable $e) {
    // Captura otros errores inesperados para evitar mostrar información interna.
    error_log($e->getMessage());
    responder(false, 'Ocurrió un error inesperado en el servidor.', $accion);
}
