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
  