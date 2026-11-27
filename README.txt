CRUD DE PRODUCTOS - FETCH API + PHP OOP + MYSQL

1. Requisitos
- WampServer o XAMPP con Apache, PHP y MySQL/MariaDB.
- PHP con la extensión PDO MySQL habilitada.
- Navegador web.
- Conexión a Internet para cargar Bootstrap y SweetAlert2 desde sus CDN.

2. Instalación en WampServer
- Copia la carpeta CRUD_Fetch_PHP_MySQL dentro de C:\wamp64\www\
- Inicia los servicios de Apache y MySQL.
- Abre phpMyAdmin: http://localhost/phpmyadmin
- Importa el archivo database.sql.
- Si tu usuario/contraseña de MySQL no es root/sin contraseña, modifica Modelo/conexion.php.
- Abre: http://localhost/CRUD_Fetch_PHP_MySQL/

3. Archivos
- index.html: formulario Bootstrap y tabla de productos.
- script.js: eventos, FormData, Fetch API, switch y SweetAlert2.
- registrar.php: controlador que recibe POST, usa switch y responde JSON.
- Modelo/conexion.php: clase DB con PDO y consultas preparadas.
- Modelo/Productos.php: clase Producto, validación y operaciones.
- database.sql: creación de la base de datos y tabla.

4. Pruebas sugeridas
A. Registrar: código P001, producto Teclado, precio 15.50, cantidad 10.
B. Buscar: escribe P001 y pulsa Buscar por código.
C. Modificar: cambia el precio o la cantidad y pulsa Actualizar.
D. Prueba cantidad 0 para representar un producto agotado.
E. Intenta registrar de nuevo P001: debe mostrar un mensaje de código duplicado.
F. Intenta enviar campos vacíos o valores inválidos: deben aparecer validaciones.

5. Correspondencia con la guía
- Formulario HTML y Bootstrap.
- FormData y Fetch API asíncrono.
- PHP orientado a objetos.
- PDO y consultas preparadas.
- switch en PHP y JavaScript.
- Validación en cliente y servidor.
- Respuestas JSON con success, message, accion y errors.
- SweetAlert2 para mensajes.
- Operaciones de guardar, editar y buscar, además de listar para refrescar la tabla.

Nota: este proyecto es una base funcional de laboratorio. La guía no exige eliminar registros; DELETE queda como reto extra.
