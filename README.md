# Sistema de inventario – CRUD de productos

Proyecto académico desarrollado por **Dereck Moreno y Marcos Navarro**. Consiste en una aplicación web para administrar productos mediante operaciones de registro, búsqueda, modificación y consulta del inventario.

La aplicación utiliza **HTML, Bootstrap, JavaScript (Fetch API), PHP orientado a objetos y MySQL**. Las solicitudes se envían de forma asíncrona al servidor y las respuestas se procesan en formato JSON.

## 📑 Contenido
1. [Objetivo](#-objetivo)
2. [Tecnologías utilizadas](#-tecnologías-utilizadas)
3. [Funciones del sistema](#-funciones-del-sistema)
4. [Estructura del proyecto](#-estructura-del-proyecto)
5. [Requisitos previos](#-requisitos-previos)
6. [Instalación en WampServer](#-instalación-en-wampserver)
7. [Base de datos](#-base-de-datos)
8. [Descripción de archivos](#-descripción-de-archivos)
9. [Validaciones y respuestas](#-validaciones-y-respuestas)
10. [Evidencias](#-evidencias)
11. [Autores](#-autores)

---

## 🎯 Objetivo
Desarrollar un CRUD de productos que permita registrar información en MySQL, buscar productos por su código, modificar sus datos y visualizar los registros existentes. El ejercicio aplica Fetch API y FormData para la comunicación entre JavaScript y PHP, programación orientada a objetos y consultas preparadas mediante PDO.

## 🧰 Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| HTML5 | Estructura del formulario y la tabla. |
| Bootstrap | Diseño y presentación de los componentes. |
| JavaScript | Eventos, validaciones y selección de acciones. |
| Fetch API y FormData | Envío asíncrono de datos al servidor. |
| PHP orientado a objetos | Procesamiento y organización de la lógica. |
| PDO | Conexión con MySQL y consultas preparadas. |
| MySQL | Almacenamiento de los productos. |
| SweetAlert2 | Mensajes visuales de éxito y error. |

## ⚙️ Funciones del sistema
- **Registrar productos:** guarda código, nombre, precio y cantidad.
- **Buscar por código:** recupera un producto y carga sus datos en el formulario.
- **Modificar productos:** actualiza la información del producto seleccionado.
- **Actualizar lista:** consulta los productos registrados y refresca la tabla.
- **Limpiar formulario:** deja los campos listos para otra operación.
- **Validar datos:** comprueba campos obligatorios y valores permitidos en el navegador y en el servidor.
- **Notificar resultados:** muestra mensajes de confirmación o error mediante SweetAlert2.

## 📁 Estructura del proyecto

```text
Tarea-del-CRUD/
├── Modelo/
│   ├── conexion.php
│   └── Productos.php
├── capturas/
│   ├── captura_01.png
│   ├── captura_02.png
│   └── ...
├── index.html
├── registrar.php
├── script.js
├── database.sql
└── README.md
```

La carpeta `capturas/` contiene las imágenes extraídas del documento de evidencias adjunto.

## 💻 Requisitos previos
- Windows con WampServer (o XAMPP).
- Apache y MySQL/MariaDB activos.
- PHP con la extensión PDO MySQL habilitada.
- Navegador web actualizado.
- Conexión a Internet para cargar Bootstrap y SweetAlert2 desde sus CDN.

## 🚀 Instalación en WampServer
1. Descarga o clona este repositorio.
2. Copia la carpeta del proyecto dentro de `C:\wamp64\www\`.
3. Inicia Apache y MySQL desde WampServer y verifica que estén activos.
4. Abre phpMyAdmin: `http://localhost/phpmyadmin`.
5. Importa el archivo `database.sql`.
6. Revisa las credenciales en `Modelo/conexion.php`. La configuración local utilizada es:
   - Host: `localhost`
   - Base de datos: `productosdb`
   - Usuario: `root`
   - Contraseña: vacía
7. Si tu MySQL utiliza otras credenciales, actualiza esos valores en `Modelo/conexion.php`.
8. Abre el sistema en el navegador: `http://localhost/Tarea-del-CRUD/`.

> Si colocas el proyecto en una carpeta con otro nombre, sustituye `Tarea-del-CRUD` en la dirección por el nombre correspondiente.

## 🗄️ Base de datos
El sistema utiliza la base de datos **`productosdb`** y una tabla de productos con estos campos:

| Campo | Descripción |
|---|---|
| `id` | Identificador del registro. |
| `codigo` | Código del producto. |
| `producto` | Nombre o descripción del producto. |
| `precio` | Precio del producto. |
| `cantidad` | Unidades disponibles. |

La estructura SQL se encuentra en `database.sql`. Importa ese archivo antes de utilizar la aplicación.

## 🧩 Descripción de archivos

| Archivo | Responsabilidad |
|---|---|
| `index.html` | Interfaz: formulario, botones y tabla de productos. |
| `script.js` | Eventos, FormData, Fetch API, `switch` y alertas. |
| `registrar.php` | Recibe solicitudes POST, procesa la acción con `switch` y responde JSON. |
| `Modelo/conexion.php` | Clase `DB`: conexión PDO y consultas preparadas. |
| `Modelo/Productos.php` | Clase `Producto`: validaciones y operaciones de guardar, editar, buscar y listar. |
| `database.sql` | Creación de la base de datos y tabla requerida. |

## 🛡️ Validaciones y respuestas
El sistema combina validaciones del lado del cliente y del servidor:
- Los campos del formulario son obligatorios.
- El precio debe ser numérico y no negativo.
- La cantidad debe ser un número entero y no negativo.
- El código del producto debe ser único.
- PHP vuelve a validar los datos antes de ejecutar las operaciones.
- Las consultas a MySQL utilizan PDO y parámetros preparados.

El servidor devuelve respuestas JSON con propiedades como:
- `success`: indica si la operación se completó.
- `message`: describe el resultado.
- `accion`: identifica la operación procesada.
- `errors`: contiene los errores de validación, si existen.

JavaScript interpreta la respuesta y muestra el resultado mediante SweetAlert2.

## 🖼️ Evidencias
Las capturas están organizadas en la carpeta [`capturas/`](capturas/). El documento de evidencias muestra:

- **Página 1:** interfaz principal y registro exitoso.
- **Página 2:** búsqueda de un producto.
- **Página 3:** actualización de un producto.
- **Página 4:** código PHP que procesa las acciones.
- **Páginas 5 y 6:** lógica JavaScript y conexión PDO.
- **Páginas 6 y 7:** clase Producto y operaciones con la base de datos.
- **Página 8:** formulario HTML y respuesta JSON.
- **Página 9:** validación de código duplicado.

## 👥 Autores
**Dereck Moreno**  
**Marcos Navarro**

Proyecto académico de desarrollo web.
