<?php
session_start();
include("conection.php");
include("libs.php");
$mysqli = bd_conection();

// Verificar que sea administrador
if (!isset($_SESSION["id_user"]) || $_SESSION["user_rol"] != 1) {
    header("Location: index.php");
    exit();
}

// Obtener todos los pedidos con información del usuario
$sql = "SELECT p.*, u.nombre_usuario, u.apellido_usuario, u.email, u.telefono as telefono_usuario
        FROM pedidos p
        INNER JOIN usuarios u ON p.id_user = u.id_user
        ORDER BY p.fecha_creacion DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Admin</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .pedidos-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .pedidos-table th,
        .pedidos-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .pedidos-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-primary { background-color: #007bff; color: #fff; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }
        .btn-map {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-map:hover {
            background-color: #0056b3;
        }
        .container-admin {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container-admin">
        <div class="header-admin">
            <h1>Gestión de Pedidos con Ubicación</h1>
            <div>
                <a href="main_admin.php" class="btn-map">Volver al Panel</a>
                <a href="log_out.php" class="btn-map" style="background-color: #dc3545;">Cerrar Sesión</a>
            </div>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
        <table class="pedidos-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Email/Tel</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Método Pago</th>
                    <th>Estado Pedido</th>
                    <th>Estado Pago</th>
                    <th>Ubicación</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $result->fetch_assoc()): 
                    $estadoClass = [
                        'Pendiente' => 'badge-warning',
                        'En Preparacion' => 'badge-info',
                        'En Camino' => 'badge-primary',
                        'Entregado' => 'badge-success',
                        'Cancelado' => 'badge-danger'
                    ][$pedido['estado_pedido']] ?? 'badge-secondary';
                    
                    $estadoPagoClass = [
                        'Pendiente' => 'badge-warning',
                        'Procesando' => 'badge-info',
                        'Completado' => 'badge-success',
                        'Fallido' => 'badge-danger'
                    ][$pedido['estado_pago']] ?? 'badge-secondary';
                ?>
                <tr>
                    <td>#<?php echo $pedido['id_pedido']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($pedido['nombre_usuario'] . ' ' . $pedido['apellido_usuario']); ?></strong>
                        <br>
                        <small><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></small>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($pedido['email']); ?><br>
                        <small><?php echo htmlspecialchars($pedido['telefono_cliente']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></td>
                    <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
                    <td>
                        <?php echo htmlspecialchars($pedido['metodo_pago']); ?>
                        <?php if ($pedido['transaccion_pago']): ?>
                            <br><small title="<?php echo htmlspecialchars($pedido['transaccion_pago']); ?>">
                                TX: <?php echo substr($pedido['transaccion_pago'], 0, 15); ?>...
                            </small>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo $estadoClass; ?>"><?php echo $pedido['estado_pedido']; ?></span></td>
                    <td><span class="badge <?php echo $estadoPagoClass; ?>"><?php echo $pedido['estado_pago']; ?></span></td>
                    <td>
                        <?php if ($pedido['latitud'] && $pedido['longitud']): ?>
                            <a href="https://www.google.com/maps?q=<?php echo $pedido['latitud']; ?>,<?php echo $pedido['longitud']; ?>" 
                               target="_blank" 
                               class="btn-map"
                               title="Lat: <?php echo $pedido['latitud']; ?>, Lng: <?php echo $pedido['longitud']; ?>">
                                <i class="fa-solid fa-map-marker-alt"></i> Ver Mapa
                            </a>
                        <?php else: ?>
                            <small>Sin ubicación</small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></td>
                    <td>
                        <a href="get_detalle_pedido.php?id=<?php echo $pedido['id_pedido']; ?>" 
                           class="btn-map" 
                           style="background-color: #28a745;">
                            Ver Detalle
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No hay pedidos registrados.</p>
        <?php endif; ?>
    </div>

    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
</body>
</html>
