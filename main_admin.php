<?php
    session_start();
    include("conection.php");
    include("libs.php");
    $mysqli = bd_conection();
    
    if (!isset($_SESSION["id_user"]) || $_SESSION["user_rol"] != 1) {
        header("Location: index.php");
        exit();
    }
    
    // Obtener estadísticas
    $sql_total_pedidos = "SELECT COUNT(*) as total FROM pedidos";
    $total_pedidos = $mysqli->query($sql_total_pedidos)->fetch_assoc()['total'];
    
    $sql_pedidos_hoy = "SELECT COUNT(*) as total FROM pedidos WHERE DATE(fecha_creacion) = CURDATE()";
    $pedidos_hoy = $mysqli->query($sql_pedidos_hoy)->fetch_assoc()['total'];
    
    $sql_total_usuarios = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 2";
    $total_usuarios = $mysqli->query($sql_total_usuarios)->fetch_assoc()['total'];
    
    $sql_total_productos = "SELECT COUNT(*) as total FROM productos WHERE estado = 1";
    $total_productos = $mysqli->query($sql_total_productos)->fetch_assoc()['total'];
    
    $sql_ingresos_mes = "SELECT SUM(total) as ingresos FROM pedidos WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())";
    $ingresos_mes = $mysqli->query($sql_ingresos_mes)->fetch_assoc()['ingresos'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - TinBurguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .stat-icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container-fluid">
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

    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fa-solid fa-gauge"></i> Panel de Administración
                </h2>
                <h4 class="color-second-title text-center lead mb-5">¡ Hola de nuevo ! <?php echo htmlspecialchars($_SESSION["user_name"])?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="dashboard-card position-relative">
                                <i class="fa-solid fa-shopping-cart stat-icon"></i>
                                <div class="stat-number color-main-title"><?php echo $total_pedidos; ?></div>
                                <div class="stat-label">Total Pedidos</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="dashboard-card position-relative">
                                <i class="fa-solid fa-calendar-day stat-icon"></i>
                                <div class="stat-number color-main-title"><?php echo $pedidos_hoy; ?></div>
                                <div class="stat-label">Pedidos Hoy</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="dashboard-card position-relative">
                                <i class="fa-solid fa-users stat-icon"></i>
                                <div class="stat-number color-main-title"><?php echo $total_usuarios; ?></div>
                                <div class="stat-label">Usuarios Registrados</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="dashboard-card position-relative">
                                <i class="fa-solid fa-box stat-icon"></i>
                                <div class="stat-number color-main-title"><?php echo $total_productos; ?></div>
                                <div class="stat-label">Productos Activos</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="dashboard-card d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="color-main-title mb-3"><i class="fa-solid fa-dollar-sign"></i> Ingresos Acumulados</h4>
                                    <div class="stat-number color-second-title" id="totalAmount">$<?php echo number_format($ingresos_mes, 0, ',', '.'); ?></div>
                                    <p class="color-main-title" id="currentDate"></p>
                                </div>
                                <div class="circle-container">
                                    <div class="circle-progress">
                                        <span class="progress-value"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dashboard-card">
                                <h5 class="color-main-title mb-4"><i class="fa-solid fa-bell"></i> Últimos Pedidos</h5>
                                <?php
                                $sql_ultimos = "SELECT p.id_pedido, p.nombre_cliente, p.total, p.estado_pedido, p.fecha_creacion 
                                                FROM pedidos p 
                                                ORDER BY p.fecha_creacion DESC 
                                                LIMIT 5";
                                $result_ultimos = $mysqli->query($sql_ultimos);
                                ?>
                                <div class="list-group">
                                    <?php if ($result_ultimos && $result_ultimos->num_rows > 0): ?>
                                        <?php while ($pedido = $result_ultimos->fetch_assoc()): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <strong class="color-main-title">#<?php echo $pedido['id_pedido']; ?> - <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></strong>
                                                    <span class="badge bg-primary"><?php echo $pedido['estado_pedido']; ?></span>
                                                </div>
                                                <small class="text-muted">
                                                    <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?> - 
                                                    $<?php echo number_format($pedido['total'], 0, ',', '.'); ?>
                                                </small>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-muted">No hay pedidos recientes</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dashboard-card">
                                <h5 class="color-main-title mb-4"><i class="fa-solid fa-clock"></i> Accesos Rápidos</h5>
                                <div class="d-grid gap-2">
                                    <a href="inventario.php" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-boxes-stacked"></i> Gestionar Inventario
                                    </a>
                                    <a href="pedidos.php" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-clipboard-list"></i> Ver Pedidos
                                    </a>
                                    <a href="usuarios.php" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-users-gear"></i> Control de Usuarios
                                    </a>
                                </div>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
</body>
</html>