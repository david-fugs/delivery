<?php
session_start();
include('conection.php');
$mysqli = bd_conection();

if (!isset($_SESSION["id_user"]) || $_SESSION["user_rol"] != 1) {
    echo '<div class="alert alert-danger">Acceso denegado</div>';
    exit();
}

if (isset($_GET['id'])) {
    $id_pedido = intval($_GET['id']);
    
    // Obtener información del pedido
    $sql_pedido = "SELECT p.*, u.nombre_usuario, u.apellido_usuario, u.email 
                   FROM pedidos p 
                   INNER JOIN usuarios u ON p.id_user = u.id_user 
                   WHERE p.id_pedido = ?";
    $stmt = $mysqli->prepare($sql_pedido);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();
    
    if (!$pedido) {
        echo '<div class="alert alert-danger">Pedido no encontrado</div>';
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
    ?>
    
    <div class="pedido-detalle">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6><strong>Información del Cliente</strong></h6>
                <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></p>
                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
                <p class="mb-1"><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono_cliente']); ?></p>
                <p class="mb-1"><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion_entrega']); ?></p>
            </div>
            <div class="col-md-6">
                <h6><strong>Información del Pedido</strong></h6>
                <p class="mb-1"><strong>ID Pedido:</strong> #<?php echo $pedido['id_pedido']; ?></p>
                <p class="mb-1"><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></p>
                <p class="mb-1"><strong>Estado:</strong> 
                    <span class="badge bg-primary"><?php echo htmlspecialchars($pedido['estado_pedido']); ?></span>
                </p>
                <p class="mb-1"><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
                <?php if ($pedido['fecha_entrega']): ?>
                <p class="mb-1"><strong>Fecha Entrega:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_entrega'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <h6><strong>Productos del Pedido</strong></h6>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = $result_detalle->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                    <td>$<?php echo number_format($detalle['precio_unitario'], 0, ',', '.'); ?></td>
                    <td><?php echo $detalle['cantidad']; ?></td>
                    <td>$<?php echo number_format($detalle['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="table-active">
                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                    <td><strong>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <?php
} else {
    echo '<div class="alert alert-danger">ID de pedido no proporcionado</div>';
}
?>
