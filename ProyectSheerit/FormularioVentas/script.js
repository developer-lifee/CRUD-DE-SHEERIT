var phoneNumber;

function agregarCuenta() {
    const cuentaItem = document.querySelector('.cuentaItem');
    const cuentasContainer = document.getElementById('cuentasContainer');
    const newCuentaItem = cuentaItem.cloneNode(true);
    cuentasContainer.appendChild(newCuentaItem);
    newCuentaItem.querySelector('.cuenta-select').addEventListener('change', calcularTotal);
}

function agregarCuentaAlt() {
    const cuentaItemAlt = document.querySelector('.cuentaItemAlt');
    const cuentasContainerAlt = document.getElementById('cuentasContainerAlt');
    const newCuentaItemAlt = cuentaItemAlt.cloneNode(true);
    cuentasContainerAlt.appendChild(newCuentaItemAlt);
    newCuentaItemAlt.querySelector('.cuenta-select').addEventListener('change', calcularTotalAlt);
}

function eliminarCuenta() {
    const cuentasContainer = document.getElementById('cuentasContainer');
    if (cuentasContainer.children.length > 1) {
        cuentasContainer.removeChild(cuentasContainer.lastElementChild);
        calcularTotal();
    }
}

function eliminarCuentaAlt() {
    const cuentasContainerAlt = document.getElementById('cuentasContainerAlt');
    if (cuentasContainerAlt.children.length > 1) {
        cuentasContainerAlt.removeChild(cuentasContainerAlt.lastElementChild);
        calcularTotalAlt();
    }
}

function calcularTotal() {
    const meses = parseInt(document.getElementById('meses').value);
    const cuentas = document.querySelectorAll('#cuentasContainer .cuenta-select');
    let total = 0;

    cuentas.forEach((selectElement, index) => {
        const precio = parseInt(selectElement.selectedOptions[0].getAttribute('data-precio'));
        if (!isNaN(precio)) {
            if (index > 0) {
                total += precio - 1000; // Descuento por cuenta adicional
            } else {
                total += precio;
            }
        }
    });

    if (meses === 6) {
        total *= 0.93; // 7% de descuento
    } else if (meses === 12) {
        total *= 0.85; // 15% de descuento
    }

    total *= meses;
    total = Math.floor(total / 1000) * 1000; // Redondear hacia abajo a miles

    document.getElementById('total').innerText = `Total: $${total} COP`;
}

function calcularTotalAlt() {
    const meses = parseInt(document.getElementById('mesesAlt').value);
    const cuentas = document.querySelectorAll('#cuentasContainerAlt .cuenta-select');
    let total = 0;

    cuentas.forEach((selectElement, index) => {
        const precio = parseInt(selectElement.selectedOptions[0].getAttribute('data-precio'));
        if (!isNaN(precio)) {
            if (index > 0) {
                total += precio - 1000; // Descuento por cuenta adicional
            } else {
                total += precio;
            }
        }
    });

    if (meses === 6) {
        total *= 0.93; // 7% de descuento
    } else if (meses === 12) {
        total *= 0.85; // 15% de descuento
    }

    total *= meses;
    total = Math.floor(total / 1000) * 1000; // Redondear hacia abajo a miles

    document.getElementById('totalAlt').innerText = `Total: $${total} COP`;
}

function savePhoneNumber() {
    var telefono = document.getElementsByName('telefono')[0].value; // Obtener el valor del número de teléfono del formulario

    // Crear un objeto XMLHttpRequest para enviar la solicitud al servidor
    var xhr = new XMLHttpRequest();

    // Configurar la solicitud
    xhr.open('POST', 'telefono.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Definir la función que se ejecutará cuando la solicitud se complete
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Si la solicitud se completó exitosamente
            var response = JSON.parse(xhr.responseText); // Analizar la respuesta JSON del servidor
            if (response.registrado) {
                // Si el número está registrado
                document.getElementById('standardForm').style.display = 'block'; // Mostrar el formulario registrado
                document.getElementById('alternativeForm').style.display = 'none'; // Ocultar el formulario alternativo
                document.getElementById('telefonoHidden').value = telefono; // Asignar el número de teléfono al campo oculto
            } else {
                // Si el número no está registrado
                document.getElementById('alternativeForm').style.display = 'block'; // Mostrar el formulario alternativo
                document.getElementById('standardForm').style.display = 'none'; // Ocultar el formulario registrado
            }
        } else {
            // Si hubo un error al enviar la solicitud
            console.error('Error al enviar la solicitud.');
        }
    };

    // Manejar errores de red
    xhr.onerror = function () {
        console.error('Error de red al enviar la solicitud.');
    };

    // Enviar la solicitud con el número de teléfono
    xhr.send('telefono=' + telefono);
}

document.getElementById('phoneForm').addEventListener('submit', function(event) {
    event.preventDefault();
    savePhoneNumber(); // Llama a savePhoneNumber cuando se envía el formulario de teléfono
});

// Añade un event listener para calcular el total cuando se cambia la selección de cuenta o meses
document.getElementById('meses').addEventListener('change', calcularTotal);
document.getElementById('mesesAlt').addEventListener('change', calcularTotalAlt);
document.querySelectorAll('.cuenta-select').forEach(select => {
    select.addEventListener('change', calcularTotal);
    select.addEventListener('change', calcularTotalAlt);
});
