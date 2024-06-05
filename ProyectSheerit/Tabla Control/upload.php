<?php

include 'conexion.php';

function procesarDatosCSV($handle) {
    global $conn;
    // Ignorar el encabezado del archivo CSV
    fgetcsv($handle);
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Ignorar las filas vacías
        if (empty(array_filter($row, 'trim'))) {
            continue;
        }
        
        if (count($row) == 11) {
            // Pasa los datos de la fila a la función insertarDatos
            insertarDatos($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
        } else {
            echo "Error: La fila no contiene 11 elementos. Se encontraron " . count($row) . " elementos.\n";
        }
    }
}

if (isset($_FILES["csv_file"])) {
    $archivoCSV = $_FILES["csv_file"];
    // Verificar si no hubo errores al subir el archivo
    if ($archivoCSV["error"] === UPLOAD_ERR_OK) {
        // Obtener la extensión del archivo
        $extension = pathinfo($archivoCSV["name"], PATHINFO_EXTENSION);
        // Comprobar si la extensión es "csv"
        if (strtolower($extension) === "csv") {
            // Procesar el archivo CSV subido
            $archivoCSV = $_FILES['csv_file']['tmp_name'];
            if (($handle = fopen($archivoCSV, "r")) !== FALSE) {
                procesarDatosCSV($handle);
                // Cerrar el archivo CSV
                fclose($handle);
            } else {
                echo "Error al abrir el archivo CSV.";
            }
        } else {
            echo "El archivo no tiene la extensión .csv. Por favor, seleccione un archivo CSV válido.";
        }
    } else {
        echo "Error al subir el archivo CSV. Código de error: " . $archivoCSV["error"];
    }
} else {
    echo "No se seleccionó ningún archivo CSV para subir.";
}

?>
