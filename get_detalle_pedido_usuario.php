<?php
session_start();
include('conection.php');
$mysqli = bd_conection();

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_user"])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

if (isset($_GET['id'])) {
    $id_pedido = intval($_GET['id']);
    $id_user = $_SESSION["id_user"];
    
    // Obtener información del pedido (solo del usuario actual)
    $sql_pedido = "SELECT * FROM pedidos WHERE id_pedido = ? AND id_user = ?";
    $stmt = $mysqli->prepare($sql_pedido);
    $stmt->bind_param("ii", $id_pedido, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();
    
    if (!$pedido) {
        echo json_encode(['error' => 'Pedido no encontrado']);
        exit();
    }
    
    // Obtener detalles de productos
    $sql_detalle = "SELECT dp.*, pr.nombre_producto 
                    FROM detalle_pedidos dp 
                    INNER JOIN productos pr ON dp.id_producto = pr.id_producto 
                    WHERE dp.id_pedido = ?";
    $stmt_detalle = $mysqli->prepare($sql_detalle);
    $stmt_detalle->bind_param("i", $id_pedido);
    $stmt_detalle->execute();
    $result_detalle = $stmt_detalle->get_result();
    
    $detalles = [];
    while ($detalle = $result_detalle->fetch_assoc()) {
        $detalles[] = $detalle;
    }
    
    echo json_encode([
        'pedido' => $pedido,
        'detalles' => $detalles
    ]);
} else {
    echo json_encode(['error' => 'ID de pedido no proporcionado']);
}
?>
