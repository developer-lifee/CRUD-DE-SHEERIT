var phoneNumber;

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
// Obtener la fecha actual
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

// Establecer la fecha actual como valor del campo de fecha
document.getElementsByName('fechaCompra')[0].value = today;


// Añade un event listener para el evento 'change' para restablecer las opciones originales cuando se cambia la selección
selectElement.addEventListener('change', function() {
    // Restablece el select a su estado original
    selectElement.innerHTML = '';
    originalOptions.forEach(function(option) {
        selectElement.appendChild(option.cloneNode(true));
    });
});

// Añade un event listener para el evento 'change' para restablecer la variable de control cuando se cambia la selección
selectElement.addEventListener('change', function() {
    optionSelected = false;
});



//telefono 

document.getElementById('phoneForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var telefono = phoneNumber; // Utilizar el número de teléfono guardado
    console.log('Número de teléfono enviado:', telefono);  // Mensaje de depuración

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'telefono.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log('AJAX Response:', response);  // Mensaje de depuración
                
                if (response.registrado) {
                    console.log('Usuario registrado. Mostrar standardForm.');  // Mensaje de depuración
                    
                    document.getElementById('standardForm').style.display = 'block';
                    document.getElementById('alternativeForm').style.display = 'none'; // Ocultar el formulario alternativo si se muestra
                } else {
                    console.log('Usuario no registrado. Mostrar alternativeForm.');  // Mensaje de depuración
                    
                    document.getElementById('alternativeForm').style.display = 'block';
                    document.getElementById('standardForm').style.display = 'none'; // Ocultar el formulario estándar si se muestra
                }
            } else {
                console.error('Error en la solicitud AJAX: ' + xhr.status);
            }
        }
    };
    xhr.send('telefono=' + encodeURIComponent(telefono));
});

// Resto del código sigue igual...
