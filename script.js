// Se obtienen los elementos principales del formulario y de la tabla.
const formulario = document.getElementById('formProducto');
const tabla = document.getElementById('tablaProductos');
const botonGuardar = document.getElementById('btnGuardar');

// Muestra mensajes visuales utilizando la biblioteca SweetAlert2.
function mostrarAlerta(icono, titulo, texto) {
    Swal.fire({ icon: icono, title: titulo, text: texto });
}

// Envía los datos a PHP mediante Fetch y devuelve la respuesta en formato JSON.
async function enviarDatos(datos) {
    const respuesta = await fetch('registrar.php', {
        method: 'POST',
        body: datos
    });

    // Si la respuesta HTTP no fue correcta, se genera un error para manejarlo.
    if (!respuesta.ok) {
        throw new Error('El servidor respondió con estado HTTP ' + respuesta.status);
    }

    return await respuesta.json();
}

// Convierte los errores recibidos desde PHP en un mensaje para el usuario.
function mostrarErrores(errores) {
    const mensajes = Object.values(errores || {});
    if (mensajes.length > 0) {
        mostrarAlerta('warning', 'Verifica los datos', mensajes.join('\n'));
    }
}

// Restablece el formulario y su estado inicial después de una operación.
function limpiarFormulario() {
    formulario.reset();
    document.getElementById('Id').value = '';
    botonGuardar.textContent = 'Registrar';
    document.getElementById('Codigo').readOnly = false;
}

// Solicita a PHP todos los productos y construye las filas de la tabla.
async function cargarProductos() {
    try {
        const datos = new FormData();
        datos.append('Accion', 'Listar');
        const resultado = await enviarDatos(datos);

        if (!resultado.success) {
            mostrarAlerta('error', 'No se pudo cargar', resultado.message);
            return;
        }

        // Se limpia el contenido anterior antes de mostrar los resultados nuevos.
        tabla.innerHTML = '';
        if (resultado.productos.length === 0) {
            tabla.innerHTML = '<tr><td colspan="6" class="text-center">Todavía no hay productos registrados.</td></tr>';
            return;
        }

        // Se crea una fila por cada producto recibido desde el servidor.
        resultado.productos.forEach(producto => {
            const fila = document.createElement('tr');

            // textContent muestra los valores como texto y evita interpretar HTML ingresado.
            const valores = [
                producto.id,
                producto.codigo,
                producto.producto,
                Number(producto.precio).toFixed(2),
                producto.cantidad
            ];

            valores.forEach(valor => {
                const celda = document.createElement('td');
                celda.textContent = valor;
                fila.appendChild(celda);
            });

            // Se agrega un botón que carga los datos del producto en el formulario.
            const celdaAccion = document.createElement('td');
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.className = 'btn btn-sm btn-outline-warning';
            boton.textContent = 'Editar';
            boton.addEventListener('click', () => cargarEnFormulario(producto));
            celdaAccion.appendChild(boton);
            fila.appendChild(celdaAccion);
            tabla.appendChild(fila);
        });
    } catch (error) {
        // Se informa si ocurre un problema al comunicarse con el servidor.
        mostrarAlerta('error', 'Error de conexión', error.message);
    }
}

// Coloca los datos de un producto seleccionado dentro de los campos del formulario.
function cargarEnFormulario(producto) {
    document.getElementById('Id').value = producto.id;
    document.getElementById('Codigo').value = producto.codigo;
    document.getElementById('Producto').value = producto.producto;
    document.getElementById('Precio').value = producto.precio;
    document.getElementById('Cantidad').value = producto.cantidad;

    // El código queda bloqueado durante la edición y cambia el texto del botón.
    document.getElementById('Codigo').readOnly = true;
    botonGuardar.textContent = 'Actualizar';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Evento que procesa el envío del formulario para guardar o modificar.
formulario.addEventListener('submit', async function (evento) {
    evento.preventDefault(); // Evita que el navegador recargue la página.

    const datos = new FormData(formulario);

    // El switch decide la acción según exista o no un ID en el formulario.
    let accion;
    switch (document.getElementById('Id').value.trim() === '') {
        case true:
            accion = 'Guardar';
            break;
        case false:
            accion = 'Modificar';
            break;
    }

    // Se envía a PHP el nombre de la acción que debe ejecutar.
    datos.append('Accion', accion);

    try {
        const resultado = await enviarDatos(datos);
        if (resultado.success) {
            // Si PHP confirma el éxito, se muestra el mensaje y se actualiza la tabla.
            mostrarAlerta('success', 'Operación completada', resultado.message);
            limpiarFormulario();
            cargarProductos();
        } else {
            // Si PHP devuelve errores de validación, se muestran al usuario.
            mostrarErrores(resultado.errors);
            if (!resultado.errors || Object.keys(resultado.errors).length === 0) {
                mostrarAlerta('error', 'No se pudo completar', resultado.message);
            }
        }
    } catch (error) {
        // Captura errores de comunicación o respuestas inesperadas.
        mostrarAlerta('error', 'Error', error.message);
    }
});

// Evento del botón Buscar: consulta el producto usando el código escrito.
document.getElementById('btnBuscar').addEventListener('click', async function () {
    const codigo = document.getElementById('Codigo').value.trim();
    const datos = new FormData();
    datos.append('Accion', 'Buscar');
    datos.append('Codigo', codigo);

    try {
        const resultado = await enviarDatos(datos);
        if (resultado.success) {
            cargarEnFormulario(resultado.producto);
            mostrarAlerta('success', 'Producto encontrado', resultado.message);
        } else {
            mostrarAlerta('info', 'Búsqueda', resultado.message);
        }
    } catch (error) {
        mostrarAlerta('error', 'Error de búsqueda', error.message);
    }
});

// Eventos de los botones auxiliares del formulario y de la lista.
document.getElementById('btnLimpiar').addEventListener('click', limpiarFormulario);
document.getElementById('btnActualizarLista').addEventListener('click', cargarProductos);

// Al abrir la página, se carga automáticamente la lista de productos.
cargarProductos();
