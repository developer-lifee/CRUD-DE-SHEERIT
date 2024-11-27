<?php
include_once 'conexion.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'] ?? null;
    $clave = $_POST['clave'] ?? null;
    $streaming = $_POST['streaming'] ?? null;

    if (!$correo || !$clave || !$streaming) {
        $response['success'] = false;
        $response['message'] = 'Correo, clave y streaming son requeridos.';
    } else {
        try {
            $conn->beginTransaction();

            // Identificar id_streaming y precio basado en el nombre de streaming
            $sqlIdStreaming = "SELECT id_streaming, precio FROM lista_maestra WHERE nombre_cuenta = ?";
            $stmtIdStreaming = $conn->prepare($sqlIdStreaming);
            $stmtIdStreaming->execute([$streaming]);
            $streamingData = $stmtIdStreaming->fetch(PDO::FETCH_ASSOC);

            if (!$streamingData) {
                throw new Exception("No se encontró el servicio de streaming proporcionado: " . htmlspecialchars($streaming));
            }

            $id_streaming = $streamingData['id_streaming'];
            $precioUnitario = $streamingData['precio'];

            // Insertar en datosCuenta
            $sql = "INSERT INTO datosCuenta (correo, clave, fechaCuenta, id_streaming) VALUES (?, ?, NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$correo, $clave, $id_streaming]);
            $idCuenta = $conn->lastInsertId();

            // Definir la cantidad de perfiles según el servicio de streaming
            $perfilesPorServicio = [
                'Netflix' => 5,
                'Amazon' => 6,
                'Crunchyroll' => 5,
                'Disney' => 7,
                'Star +' => 7,
                'HBO' => 5,
                'Spotify' => 1,
                'Youtube' => 5,
                'Xbox' => 3,
                'IPTV' => 5,
                'GPT' => 7,
                'Paramount' => 6
            ];

            $cantidadPerfiles = $perfilesPorServicio[$streaming] ?? 0;

            // Insertar perfiles según la cantidad definida para el servicio
            if ($cantidadPerfiles > 0) {
                $sqlPerfil = "INSERT INTO perfil (idCuenta, precioUnitario, id_streaming) VALUES (?, ?, ?)";
                $stmtPerfil = $conn->prepare($sqlPerfil);
                for ($i = 0; $i < $cantidadPerfiles; $i++) {
                    $stmtPerfil->execute([$idCuenta, $precioUnitario, $id_streaming]);
                }
            }

            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Cuenta de {$streaming} y {$cantidadPerfiles} perfiles creados exitosamente.";
        } catch (Exception $e) {
            $conn->rollBack();
            $response['success'] = false;
            $response['message'] = "Error de base de datos: " . $e->getMessage();
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
