<?php
session_start();
header('Content-Type: application/json');
include("conection.php");
$mysqli = bd_conection();

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_user"])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_user = $_SESSION["id_user"];

// Obtener pedidos del usuario
$sql = "SELECT * FROM pedidos WHERE id_user = ? ORDER BY fecha_creacion DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode(['pedidos' => $pedidos]);
?>
