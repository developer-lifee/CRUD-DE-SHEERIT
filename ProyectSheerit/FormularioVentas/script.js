var phoneNumber;

function fetchPrices() {
    return fetch('obtener_precios.php')
        .then(response => response.json())
        .catch(error => {
            console.error('Error al obtener precios:', error);
            return {};
        });
}

// Initialize precios array
var precios = {};

// Function to populate cuenta dropdowns
function populateCuentas(preciosData) {
    precios = preciosData; // Store precios in array
    
    const cuentaSelects = document.querySelectorAll('select[name="cuenta[]"]');
    cuentaSelects.forEach(select => {
        // Clear existing options
        select.innerHTML = '<option value="" disabled selected>Selecciona una cuenta</option>';
        // Populate with fetched precios
        for (const cuenta in precios) {
            const option = document.createElement('option');
            option.value = cuenta;
            option.textContent = capitalize(cuenta);
            select.appendChild(option);
        }
    });
}

// Capitalize function
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Fetch precios on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchPrices().then(fetchedPrecios => {
        populateCuentas(fetchedPrecios);
    });
});

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
    // Adjust the function to calculate the total based on the current date and selected months
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
    // Adjust the function to calculate the total for the alternative form
    fetchPrices().then(precios => {
        const meses = parseInt(document.getElementById('mesesAlt').value) || 1;
        const cuentas = document.querySelectorAll('#cuentasContainerAlt select[name="cuenta[]"]');
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

        document.getElementById('totalAlt').innerText = `Total: $${total} COP`;
    });
}

function savePhoneNumber() {
    var telefono = document.getElementsByName('telefono')[0].value;

    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'telefono.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.registrado) {
                document.getElementById('standardForm').style.display = 'block';
                document.getElementById('alternativeForm').style.display = 'none';
                document.getElementById('telefonoHidden').value = telefono;

                // Attach event listeners
                document.getElementById('meses').addEventListener('change', calcularTotal);
                document.querySelectorAll('#cuentasContainer select[name="cuenta[]"]').forEach(select => {
                    select.addEventListener('change', calcularTotal);
                });

                calcularTotal(); // Update total price

            } else {
                document.getElementById('alternativeForm').style.display = 'block';
                document.getElementById('standardForm').style.display = 'none';

                // Attach event listeners
                document.getElementById('mesesAlt').addEventListener('change', calcularTotalAlt);
                document.querySelectorAll('#cuentasContainerAlt select[name="cuenta[]"]').forEach(select => {
                    select.addEventListener('change', calcularTotalAlt);
                });

                calcularTotalAlt(); // Update total price
            }
        } else {
            console.error('Error al enviar la solicitud.');
        }
    };

    xhr.onerror = function () {
        console.error('Error de red al enviar la solicitud.');
    };

    xhr.send('telefono=' + telefono);
}

// Remove or comment out the initial event listeners
/*
document.getElementById('meses').addEventListener('change', calcularTotal);
document.getElementById('mesesAlt').addEventListener('change', calcularTotalAlt);
document.querySelectorAll('#cuentasContainer select[name="cuenta[]"]').forEach(select => {
    select.addEventListener('change', calcularTotal);
});
document.querySelectorAll('#cuentasContainerAlt select[name="cuenta[]"]').forEach(select => {
    select.addEventListener('change', calcularTotalAlt);
});
*/

document.getElementById('phoneForm').addEventListener('submit', function(event) {
    event.preventDefault();
    savePhoneNumber(); // Llama a savePhoneNumber cuando se envía el formulario de teléfono
});
