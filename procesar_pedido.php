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
    
    if (!$cart_data || count($cart_data) == 0) {
        header("Location: index.php?error=carrito_vacio");
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
        // Insertar el pedido
        $sql_pedido = "INSERT INTO pedidos (id_user, nombre_cliente, telefono_cliente, direccion_entrega, total, metodo_pago, estado_pedido, estado_pago, fecha_creacion) 
                       VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', 'Pendiente', NOW())";
        $stmt_pedido = $mysqli->prepare($sql_pedido);
        $stmt_pedido->bind_param("isssds", $id_user, $nombre_cliente, $telefono_cliente, $direccion_entrega, $total, $metodo_pago);
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
        
        // Si todo está bien, confirmar la transacción
        $mysqli->commit();
        
        // Espacio para integración futura de API de pagos
        // ================================================
        // TODO: Integrar API de pagos (Stripe, PayU, Mercado Pago, etc.)
        // 
        // if ($metodo_pago == 'Tarjeta') {
        //     // Iniciar proceso de pago con la API
        //     $payment_result = process_payment($id_pedido, $total, $customer_data);
        //     
        //     if ($payment_result['success']) {
        //         // Actualizar estado del pago
        //         $transaction_id = $payment_result['transaction_id'];
        //         $update_pago = "UPDATE pedidos SET estado_pago = 'Completado', transaccion_pago = ? WHERE id_pedido = ?";
        //         $stmt_update = $mysqli->prepare($update_pago);
        //         $stmt_update->bind_param("si", $transaction_id, $id_pedido);
        //         $stmt_update->execute();
        //     } else {
        //         // Marcar pago como fallido
        //         $update_pago = "UPDATE pedidos SET estado_pago = 'Fallido' WHERE id_pedido = ?";
        //         $stmt_update = $mysqli->prepare($update_pago);
        //         $stmt_update->bind_param("i", $id_pedido);
        //         $stmt_update->execute();
        //     }
        // }
        // ================================================
        
        // Redirigir con éxito
        header("Location: pedido_confirmado.php?id=" . $id_pedido);
        exit();
        
    } catch (Exception $e) {
        // Si hay error, revertir todos los cambios
        $mysqli->rollback();
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
    
} else {
    header("Location: index.php");
    exit();
}
?>
