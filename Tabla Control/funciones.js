function enviarWhatsApp() {
    // Obtener los datos de la tabla
    var streaming = document.querySelector('td[data-label="Streaming"]').innerText;
    var correo = document.querySelector('td[data-label="Correo"]').innerText;
    var contraseña = document.querySelector('td[data-label="Contraseña"]').innerText;
    var perfil = document.querySelector('td[data-label="Perfil"]').innerText;
    var fechaVencimiento = document.querySelector('td[data-label="Fecha Vencimiento"]').innerText;
  
    // Crear el mensaje con los datos
    var mensaje = "*Bienvenid@* \n\n";
    mensaje += "Sheer IT quiere darte la bienvenida y te agradecemos por tu compra y esperamos disfrutes tus cuentas \n\n";
    mensaje += "*Recuerda* \n\n";
    mensaje += "*Streaming:* " + streaming + "\n";
    mensaje += "*Correo:* " + correo + "\n";
    mensaje += "*Contraseña:* " + contraseña + "\n";
    mensaje += "*Perfil:* " + perfil + "\n\n";
    mensaje += "*Fecha de Vencimiento:* " + fechaVencimiento;
  
    // Obtener el número de teléfono ingresado por el cliente (debes reemplazar "INGRESAR_NÚMERO_DE_TELEFONO" con el valor real)
    var numeroTelefono = "INGRESAR_NÚMERO_DE_TELEFONO";
  
    // Formatear el número de teléfono eliminando espacios y caracteres especiales
    numeroTelefono = numeroTelefono.replace(/[^0-9]/g, "");
  
    // Abrir la ventana de WhatsApp con el mensaje y número de teléfono predefinidos
    window.open("https://wa.me/" + numeroTelefono + "?text=" + encodeURIComponent(mensaje));
  }
  $(document).ready(function() {
  // ...

  data.forEach(function(row) {
    var newRow = "<tr>";
    newRow += "<td data-label='Streaming'>" + row.streaming + "</td>";
    newRow += "<td data-label='Nombre'>" + row.nombre + "</td>";
    newRow += "<td data-label='Apellido'>" + row.apellido + "</td>";
    newRow += "<td data-label='WhatsApp'>" + row.whatsapp + "</td>";
    newRow += "<td data-label='Correo'>" + row.correo + "</td>";
    newRow += "<td data-label='Contraseña'>" + row.contrasena + "</td>";
    newRow += "<td data-label='Perfil'>" + row.perfil + "</td>";
    newRow += "<td data-label='Deben'>" + row.deben + "</td>";
    
    // Calcular los días faltantes
    var fechaDeben = new Date(row.deben); // Supongo que row.deben contiene la fecha en formato "yyyy-MM-dd"
    var hoy = new Date();
    var diasFaltantes = Math.ceil((fechaDeben - hoy) / (1000 * 60 * 60 * 24));

    newRow += "<td data-label='Días faltantes'>" + diasFaltantes + "</td>";
    newRow += "</tr>";
    
    $("#tabla-datos").append(newRow);
  });

  // ...
});

  