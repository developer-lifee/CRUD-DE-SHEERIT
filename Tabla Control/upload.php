<?php
// Comprueba si se ha subido un archivo
if(isset($_FILES['fileToUpload'])) {
    // Obtiene el nombre del archivo
    $filename = $_FILES['fileToUpload']['name'];

    // Comprueba si el archivo es un CSV
    if(pathinfo($filename, PATHINFO_EXTENSION) == 'csv') {
        // Abre el archivo CSV
        $file = fopen($_FILES['fileToUpload']['tmp_name'], 'r');

        // Conéctate a tu base de datos aquí
        // $conexion = new mysqli('localhost', 'username', 'password', 'database');

        // Recorre cada fila del archivo CSV
        while(($row = fgetcsv($file)) !== FALSE) {
            // Prepara la consulta SQL para insertar los datos en la base de datos
            $sql = "INSERT INTO elneflis (streaming, nombre, apellido, whatsapp, correo, contrasena, perfil, deben) VALUES ('$row[0]', '$row[1]', '$row[2]', '$row[3]', '$row[4]', '$row[5]', '$row[6]', '$row[7]')";

            // Ejecuta la consulta SQL
            // $conexion->query($sql);
        }

        // Cierra el archivo CSV
        fclose($file);

        echo "Archivo CSV importado con éxito.";
    } else {
        echo "Por favor, sube un archivo CSV.";
    }
} else {
    echo "No se ha subido ningún archivo.";
}
?>
