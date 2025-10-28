<?php
session_start();
include('conection.php');
include('libs.php');
$mysqli = bd_conection();

if (!isset($_SESSION["id_user"]) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_pedido = intval($_GET['id']);
$id_user = $_SESSION["id_user"];

// Obtener información del pedido
$sql = "SELECT * FROM pedidos WHERE id_pedido = ? AND id_user = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $id_pedido, $id_user);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - TinBurguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .success-animation {
            text-align: center;
            padding: 50px 0;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            animation: scaleIn 0.5s ease-in-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
    </style>
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
                                <li><a href="index.php" class="px-3">Volver al inicio</a></li>
                                <li><a href="log_out.php" class="px-3">Cerrar Sesión</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="success-animation">
                    <i class="fa-solid fa-circle-check success-icon"></i>
                    <h1 class="mt-4 mb-3">¡Pedido Confirmado!</h1>
                    <p class="lead">Tu pedido ha sido recibido y está siendo procesado</p>
                </div>

                <div class="order-summary">
                    <h3 class="text-center mb-4">Resumen del Pedido</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Número de Pedido:</strong></p>
                            <p class="h4 text-primary">#<?php echo str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha y Hora:</strong></p>
                            <p><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><i class="fa-solid fa-location-dot"></i> Datos de Entrega</h5>
                            <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></p>
                            <p class="mb-1"><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono_cliente']); ?></p>
                            <p class="mb-1"><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion_entrega']); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Método de Pago:</strong></p>
                            <p><i class="fa-solid fa-money-bill"></i> <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong></p>
                            <p><span class="badge bg-warning"><?php echo htmlspecialchars($pedido['estado_pedido']); ?></span></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-end">Total: <span class="text-success">$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></span></h4>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <h5><i class="fa-solid fa-info-circle"></i> Información Importante</h5>
                    <ul class="mb-0">
                        <li>Tu pedido será preparado lo más pronto posible</li>
                        <li>Recibirás una notificación cuando tu pedido esté en camino</li>
                        <li>Tiempo estimado de entrega: 30-45 minutos</li>
                        <li>Guarda tu número de pedido para cualquier consulta</li>
                    </ul>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn-primary me-2">
                        <i class="fa-solid fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="info-footer d-flex justify-content-center align-items-center flex-column">
                        <div class="main-logo mb-3"></div>
                        <p class="m-0">¡Gracias por tu pedido!</p>
                        <p class="m-0">2025 © TinBurguer - Elaborado por Gio y David</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
    <script>
        // Limpiar el carrito después de confirmar el pedido
        localStorage.removeItem('tinburger_cart');
    </script>
</body>
</html>
