<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "edit") {
        if (isset($_POST["idCuenta"], $_POST["correo"], $_POST["clave"])) {
            // Conexión a la base de datos
            $host = "localhost";
            $db_name = "estavi0_sheerit";
            $username = "estavi0_sheerit";
            $password = "26o6ssCOA^";

            try {
                $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Preparar la consulta SQL
                $sql = "UPDATE datosCuenta SET correo = :correo, clave = :clave WHERE idCuenta = :idCuenta";
                $stmt = $conn->prepare($sql);

                // Asignar valores a los marcadores de posición y ejecutar la consulta
                $stmt->bindParam(':correo', $_POST["correo"]);
                $stmt->bindParam(':clave', $_POST["clave"]);
                $stmt->bindParam(':idCuenta', $_POST["idCuenta"]);
                $stmt->execute();

                // Enviar una respuesta JSON de éxito
                echo json_encode(array("success" => true, "message" => "Cuenta editada correctamente"));
            } catch (PDOException $exception) {
                // Enviar una respuesta JSON de error si hay un problema con la consulta SQL
                echo json_encode(array("error" => "Error en la consulta SQL: " . $exception->getMessage()));
            }
        } else {
            echo json_encode(array("error" => "Parámetros incompletos"));
        }
    } elseif (isset($_POST["action"]) && $_POST["action"] === "delete") {
        if (isset($_POST["idCuenta"])) {
            // Conexión a la base de datos
            $host = "localhost";
            $db_name = "estavi0_sheerit";
            $username = "estavi0_sheerit";
            $password = "26o6ssCOA^";

            try {
                $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Preparar la consulta SQL para eliminar la cuenta
                $sql = "DELETE FROM datosCuenta WHERE idCuenta = :idCuenta";
                $stmt = $conn->prepare($sql);

                // Asignar valor al marcador de posición y ejecutar la consulta
                $stmt->bindParam(':idCuenta', $_POST["idCuenta"]);
                $stmt->execute();

                // Verificar si se eliminó correctamente la cuenta
                if ($stmt->rowCount() > 0) {
                    // Enviar una respuesta JSON de éxito
                    echo json_encode(array("success" => true, "message" => "Cuenta eliminada correctamente"));
                } else {
                    echo json_encode(array("error" => "No se encontró la cuenta para eliminar"));
                }
            } catch (PDOException $exception) {
                // Enviar una respuesta JSON de error si hay un problema con la consulta SQL
                echo json_encode(array("error" => "Error al eliminar la cuenta: " . $exception->getMessage()));
            }
        } else {
            echo json_encode(array("error" => "Parámetro 'idCuenta' no recibido"));
        }
    } else {
        echo json_encode(array("error" => "Acción no válida"));
    }
} else {
    echo json_encode(array("error" => "Solicitud no válida"));
}
?>