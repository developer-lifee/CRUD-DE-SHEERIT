<?php
include 'conexion.php';

try {
    $sql = "SELECT nombre_cuenta, precio FROM lista_maestra";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $precios = [];
    foreach ($lista as $item) {
        $precios[$item['nombre_cuenta']] = $item['precio'];
    }

    echo json_encode($precios);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>