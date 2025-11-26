<?php
session_start();
include("conection.php");
$mysqli = bd_conection();

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION["id_user"];
    $nombre_cliente = mysqli_real_escape_string($mysqli, $_POST["nombre_cliente"]);
    $telefono_cliente = mysqli_real_escape_string($mysqli, $_POST["telefono_cliente"]);
    $direccion_entrega = mysqli_real_escape_string($mysqli, $_POST["direccion_entrega"]);
    $metodo_pago = mysqli_real_escape_string($mysqli, $_POST["metodo_pago"]);
    $cart_data = json_decode($_POST["cart_data"], true);
    
    // Capturar ubicación
    $latitud = isset($_POST["latitud"]) ? floatval($_POST["latitud"]) : null;
    $longitud = isset($_POST["longitud"]) ? floatval($_POST["longitud"]) : null;
    
    // Token de Stripe (si se usó pago con tarjeta)
    $stripe_token = isset($_POST["stripe_token"]) ? mysqli_real_escape_string($mysqli, $_POST["stripe_token"]) : null;
    
    if (!$cart_data || count($cart_data) == 0) {
        header("Location: main_user_dash.php?error=carrito_vacio");
        exit();
    }
    
    // Calcular el total
    $total = 0;
    foreach ($cart_data as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Iniciar transacción
    $mysqli->begin_transaction();
    
    try {
        // Insertar el pedido con geolocalización
        $sql_pedido = "INSERT INTO pedidos (id_user, nombre_cliente, telefono_cliente, direccion_entrega, total, metodo_pago, estado_pedido, estado_pago, latitud, longitud, fecha_creacion) 
                       VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', 'Pendiente', ?, ?, NOW())";
        $stmt_pedido = $mysqli->prepare($sql_pedido);
        if (!$stmt_pedido) {
            throw new Exception('Error al preparar consulta pedido: ' . $mysqli->error);
        }
        // Tipos: i (int), s (string), s (string), s (string), d (double), s (string), d (double), d (double)
        $tipoParams = "isssdsdd";
        $bindLat = $latitud === null ? null : $latitud;
        $bindLng = $longitud === null ? null : $longitud;
        $stmt_pedido->bind_param($tipoParams, $id_user, $nombre_cliente, $telefono_cliente, $direccion_entrega, $total, $metodo_pago, $bindLat, $bindLng);
        $stmt_pedido->execute();
        
        $id_pedido = $mysqli->insert_id;
        
        // Insertar los detalles del pedido
        $sql_detalle = "INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt_detalle = $mysqli->prepare($sql_detalle);
        
        foreach ($cart_data as $item) {
            $id_producto = $item['id'];
            $cantidad = $item['quantity'];
            $precio_unitario = $item['price'];
            $subtotal = $precio_unitario * $cantidad;
            
            $stmt_detalle->bind_param("iiidd", $id_pedido, $id_producto, $cantidad, $precio_unitario, $subtotal);
            $stmt_detalle->execute();
            
            // Actualizar inventario (reducir cantidad)
            $sql_update_inventario = "UPDATE inventario SET cantidad = cantidad - ? WHERE id_producto = ? AND cantidad >= ?";
            $stmt_inventario = $mysqli->prepare($sql_update_inventario);
            $stmt_inventario->bind_param("iii", $cantidad, $id_producto, $cantidad);
            $stmt_inventario->execute();
            
            if ($stmt_inventario->affected_rows == 0) {
                throw new Exception("No hay suficiente stock para el producto ID: " . $id_producto);
            }
        }
        
        // Procesar pago con Stripe (versión didáctica)
        if ($metodo_pago == 'Stripe' && $stripe_token) {
            // Aquí iría la integración real con Stripe API
            // Por ahora, solo guardamos el token y simulamos un pago exitoso
            
            // require_once('vendor/autoload.php'); // Composer autoload para Stripe PHP SDK
            // \Stripe\Stripe::setApiKey('sk_test_tu_clave_secreta');
            
            // try {
            //     $charge = \Stripe\Charge::create([
            //         'amount' => $total * 100, // Stripe maneja centavos
            //         'currency' => 'cop',
            //         'source' => $stripe_token,
            //         'description' => 'Pedido #' . $id_pedido,
            //     ]);
            //     
            //     if ($charge->status == 'succeeded') {
            //         $transaction_id = $charge->id;
            //         $update_pago = "UPDATE pedidos SET estado_pago = 'Completado', transaccion_pago = ? WHERE id_pedido = ?";
            //         $stmt_update = $mysqli->prepare($update_pago);
            //         $stmt_update->bind_param("si", $transaction_id, $id_pedido);
            //         $stmt_update->execute();
            //     }
            // } catch (\Stripe\Exception\CardException $e) {
            //     throw new Exception("Error en el pago: " . $e->getMessage());
            // }
            
            // SIMULACIÓN DIDÁCTICA: Marcar como procesando
            $update_pago = "UPDATE pedidos SET estado_pago = 'Procesando', transaccion_pago = ? WHERE id_pedido = ?";
            $stmt_update = $mysqli->prepare($update_pago);
            $stmt_update->bind_param("si", $stripe_token, $id_pedido);
            $stmt_update->execute();
        }
        
        // Si todo está bien, confirmar la transacción
        $mysqli->commit();
        
        // Limpiar el carrito
        echo "<script>localStorage.removeItem('tinburger_cart');</script>";
        
        // Redirigir con éxito
        header("Location: pedido_confirmado.php?id=" . $id_pedido);
        exit();
        
    } catch (Exception $e) {
        // Si hay error, revertir todos los cambios
        $mysqli->rollback();
        header("Location: main_user_dash.php?error=" . urlencode($e->getMessage()));
        exit();
    }
    
} else {
    header("Location: main_user_dash.php");
    exit();
}
?>
