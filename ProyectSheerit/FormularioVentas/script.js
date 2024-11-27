var phoneNumber;

function fetchPrices() {
    return fetch('obtener_precios.php')
        .then(response => response.json())
        .catch(error => {
            console.error('Error al obtener precios:', error);
            return {};
        });
}

function agregarCuenta() {
    const cuentaItem = document.querySelector('.cuentaItem');
    const cuentasContainer = document.getElementById('cuentasContainer');
    const newCuentaItem = cuentaItem.cloneNode(true);
    cuentasContainer.appendChild(newCuentaItem);
    newCuentaItem.querySelector('select[name="cuenta[]"]').addEventListener('change', calcularTotal);
    newCuentaItem.querySelector('input[name="fechaCompra[]"]').value = new Date().toISOString().split('T')[0]; // Set current date
    calcularTotal();
}

function agregarCuentaAlt() {
    const cuentaItemAlt = document.querySelector('.cuentaItemAlt');
    const cuentasContainerAlt = document.getElementById('cuentasContainerAlt');
    const newCuentaItemAlt = cuentaItemAlt.cloneNode(true);
    cuentasContainerAlt.appendChild(newCuentaItemAlt);
    newCuentaItemAlt.querySelector('select[name="cuenta[]"]').addEventListener('change', calcularTotalAlt);
    newCuentaItemAlt.querySelector('input[name="fechaCompra[]"]').value = new Date().toISOString().split('T')[0]; // Set current date
    calcularTotalAlt();
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
    fetchPrices().then(precios => {
        const meses = parseInt(document.getElementById('meses').value) || 1;
        const cuentas = document.querySelectorAll('#cuentasContainer select[name="cuenta[]"]');
        let total = 0;

        cuentas.forEach((selectElement, index) => {
            const cuenta = selectElement.value;
            const precioCuenta = parseInt(precios[cuenta]) || 0;
            total += precioCuenta;
        });

        // Aplicar descuentos por cantidad de cuentas
        const descuento = (cuentas.length - 1) * 1000;
        total -= descuento;

        // Aplicar descuento por cantidad de meses
        if (meses === 6) {
            total *= 0.93;
        } else if (meses === 12) {
            total *= 0.85;
        }

        total *= meses;
        total = Math.floor(total / 1000) * 1000;

        document.getElementById('total').innerText = `Total: $${total} COP`;
    });
}

function calcularTotalAlt() {
    fetchPrices().then(precios => {
        const meses = parseInt(document.getElementById('mesesAlt').value) || 1;
        const cuentas = document.querySelectorAll('#cuentasContainerAlt select[name="cuenta[]"]');
        let total = 0;

        cuentas.forEach((selectElement, index) => {
            const cuenta = selectElement.value;
            const precioCuenta = parseInt(precios[cuenta]) || 0;
            total += precioCuenta;
        });

        const descuento = (cuentas.length - 1) * 1000;
        total -= descuento;

        if (meses === 6) {
            total *= 0.93;
        } else if (meses === 12) {
            total *= 0.85;
        }

        total *= meses;
        total = Math.floor(total / 1000) * 1000;

        document.getElementById('totalAlt').innerText = `Total: $${total} COP`;
    });
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
document.querySelectorAll('#cuentasContainer select[name="cuenta[]"]').forEach(select => {
    select.addEventListener('change', calcularTotal);
});
document.querySelectorAll('#cuentasContainerAlt select[name="cuenta[]"]').forEach(select => {
    select.addEventListener('change', calcularTotalAlt);
});
