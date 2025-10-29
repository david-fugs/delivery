<?php
session_start();
include('conection.php');
include('libs.php');
$mysqli = bd_conection();

// Verificar que sea administrador
if (!isset($_SESSION["id_user"]) || $_SESSION["user_rol"] != 1) {
    header("Location: index.php");
    exit();
}

// Actualizar estado del pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_estado'])) {
    $id_pedido = intval($_POST['id_pedido']);
    $nuevo_estado = mysqli_real_escape_string($mysqli, $_POST['estado_pedido']);
    
    $sql_update = "UPDATE pedidos SET estado_pedido = ? WHERE id_pedido = ?";
    $stmt = $mysqli->prepare($sql_update);
    $stmt->bind_param("si", $nuevo_estado, $id_pedido);
    
    if ($stmt->execute()) {
        // Si se marca como entregado, registrar fecha de entrega
        if ($nuevo_estado == 'Entregado') {
            $sql_entrega = "UPDATE pedidos SET fecha_entrega = NOW() WHERE id_pedido = ?";
            $stmt_entrega = $mysqli->prepare($sql_entrega);
            $stmt_entrega->bind_param("i", $id_pedido);
            $stmt_entrega->execute();
        }
        $mensaje = "Estado del pedido actualizado";
        $tipo_mensaje = "success";
    }
}

// Obtener todos los pedidos
$sql_pedidos = "SELECT p.*, u.nombre_usuario, u.apellido_usuario, u.email 
                FROM pedidos p 
                INNER JOIN usuarios u ON p.id_user = u.id_user 
                ORDER BY p.fecha_creacion DESC";
$result_pedidos = $mysqli->query($sql_pedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - TinBurguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="info-header">
                        <nav class="d-flex justify-content-between align-items-center">
                            <div class="main-logo"></div>
                            <ul class="d-flex align-items-center">
                                <li><a href="main_admin.php" class="px-3">Panel Admin</a></li>
                                <li><a href="inventario.php" class="px-3">Inventario</a></li>
                                <li><a href="pedidos.php" class="px-3">Pedidos</a></li>
                                <li><a href="usuarios.php" class="px-3">Usuarios</a></li>
                                <li><a href="index.php" class="px-3">Ir al sitio</a></li>
                                <li><a href="log_out.php" class="px-3">Cerrar Sesión</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4"><i class="fa-solid fa-clipboard-list"></i> Gestión de Pedidos</h1>
                
                <?php if (isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <div class="table-inventory">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Total</th>
                                <th>Método Pago</th>
                                <th>Estado</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_pedidos && $result_pedidos->num_rows > 0): ?>
                                <?php while ($pedido = $result_pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $pedido['id_pedido']; ?></strong></td>
                                    <td>
                                        <?php echo htmlspecialchars($pedido['nombre_cliente']); ?><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($pedido['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($pedido['telefono_cliente']); ?></td>
                                    <td><small><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></small></td>
                                    <td><strong>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($pedido['metodo_pago']); ?></td>
                                    <td>
                                        <?php
                                        $badge_class = '';
                                        switch($pedido['estado_pedido']) {
                                            case 'Pendiente': $badge_class = 'bg-warning'; break;
                                            case 'En Preparacion': $badge_class = 'bg-info'; break;
                                            case 'En Camino': $badge_class = 'bg-primary'; break;
                                            case 'Entregado': $badge_class = 'bg-success'; break;
                                            case 'Cancelado': $badge_class = 'bg-danger'; break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>">
                                            <?php echo htmlspecialchars($pedido['estado_pedido']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($pedido['fecha_creacion'])); ?><br>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($pedido['fecha_creacion'])); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="verDetalle(<?php echo $pedido['id_pedido']; ?>)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit" onclick="cambiarEstado(<?php echo $pedido['id_pedido']; ?>, '<?php echo $pedido['estado_pedido']; ?>')">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="9" class="text-center">No hay pedidos registrados</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle del Pedido -->
    <div class="modal fade" id="modalDetalle" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalleContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Estado -->
    <div class="modal fade" id="modalEstado" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="update_estado" value="1">
                        <input type="hidden" name="id_pedido" id="estado_id_pedido">
                        <div class="mb-3">
                            <label class="form-label">Nuevo Estado</label>
                            <select class="form-control" name="estado_pedido" id="estado_select" required>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Preparacion">En Preparación</option>
                                <option value="En Camino">En Camino</option>
                                <option value="Entregado">Entregado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
    <script>
        function verDetalle(idPedido) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
            modal.show();
            
            // Cargar detalle del pedido via AJAX
            fetch('get_detalle_pedido.php?id=' + idPedido)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('detalleContent').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('detalleContent').innerHTML = 
                        '<div class="alert alert-danger">Error al cargar el detalle</div>';
                });
        }
        
        function cambiarEstado(idPedido, estadoActual) {
            document.getElementById('estado_id_pedido').value = idPedido;
            document.getElementById('estado_select').value = estadoActual;
            new bootstrap.Modal(document.getElementById('modalEstado')).show();
        }
    </script>
</body>
</html>
