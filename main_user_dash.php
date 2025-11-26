<?php
    include('libs.php');
    include('conection.php');
    $mysqli = bd_conection();
    session_start();
    
    // Verificar si el usuario está logueado
    if (!isset($_SESSION["id_user"])) {
        header("Location: index.php");
        exit();
    }
    
    $sql = $mysqli->prepare("SELECT nombre_usuario, apellido_usuario FROM usuarios WHERE id_user = ?");
    $sql->bind_param("i", $_SESSION["id_user"]);
    $sql->execute();
    $resultado = $sql->get_result();
    if ($resultado && $datos = $resultado->fetch_object()) {
        $_SESSION["apellido_usuario"] = $datos->apellido_usuario;
        $_SESSION["nombre_usuario"] = $datos->nombre_usuario;
    } else {
        header("Location: index.php");
        exit();
    }
    
    // Obtener productos desde la base de datos
    $sql_productos = "SELECT * FROM productos WHERE estado = 1 ORDER BY categoria, nombre_producto";
    $result_productos = $mysqli->query($sql_productos);
    
    // Mapeo de imágenes según categoría
    $imagen_categoria = [
        'Entradas' => 'dedos-queso.jpg',
        'Acompañantes' => 'acompañantes.jpg',
        'Hamburguesas Gourmet' => 'hamburguer-gourmet.jpg',
        'Especiales de 1/4 Libra' => 'cuarto-libra.jpg',
        'Perros' => 'hot-dog.jpg',
        'Big Combos' => 'combos.jpg',
        'Bebidas' => 'coctel-color.png'
    ];
?>
<!DOCTYPE html>
<html lang="en">
    <header class="head-user-dash pb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="info-header">
                        <nav class="d-flex justify-content-between align-items-center">
                            <div class="main-logo"></div>
                            <ul class="d-flex align-items-center">
                                <li>
                                    <a href="index.php" class="px-2" title="Ir al inicio"><i class="fa-solid fa-home px-2"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-2" id="cartIcon" title="Ver carrito"><i class="fa-solid fa-cart-shopping px-2"></i></a>
                                    <span class="notification" id="cartCount">0</span>
                                </li>
                                <li>
                                    <span class="px-2 text-main2">Hola, <?php echo htmlspecialchars($_SESSION["nombre_usuario"]); ?></span>
                                </li>
                                <li>
                                    <a href="log_out.php" class="px-2" title="Cerrar Sesión"><i class="fa-solid fa-power-off"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row d-md-none">
                <div class="col-12"></div>
            </div>
            <div class="row d-sm-none d-md-flex calc-h-80">
                <div class="col-md-2 left-side-menu"><!-- menu lateral izquierdo -->
                    <div class="side-menu sections">
                        <h5 class="color-txt-main2 text-center my-5">TinBurguer</h5>
                        <h6 class="color-main-title mb-4 text-center"><?php echo htmlspecialchars($_SESSION["nombre_usuario"]) . " " . htmlspecialchars($_SESSION["apellido_usuario"])?></h6>
                        <ul class="user-right-menu px-xxl-4 pb-5">
                            <li class="option-side-menu py-3 my-2 px-4 active" data-section="inicio">
                                <i class="fa-solid fa-house pe-3"></i>
                                <a href="#" class="mx-2">Inicio</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4" data-section="pedidos">
                                <i class="fa-solid fa-motorcycle pe-3"></i>
                                <a href="#" class="mx-2">Mis Pedidos</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4" data-section="productos">
                                <i class="fa-solid fa-burger pe-3"></i>
                                <a href="#" class="mx-2">Productos</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7 offset-md-2"><!-- menu central principal -->
                    <div class="container">
                        <!-- SECCIÓN INICIO -->
                        <div id="section-inicio" class="content-section active">
                        <div class="row">
                            <div class="col-12">
                                <div class="baner-promotions d-flex align-items-center ps-5">
                                    <span>Obtén mayores descuentos de
                                        <br>Hasta 40% en nuestra App.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4 class="color-main-title my-5">Categorias</h4>
                                <ul class="user-categories">
                                    <li>
                                        <span class="color-second-title mt-2">Hamburguesas</span>
                                        <span class="icon-categories cat-ham"></span>
                                    </li>
                                    <li>
                                        <span class="color-second-title mt-2">Perros Calientes</span>
                                        <span class="icon-categories cat-hotDog"></span>
                                    </li>
                                    <li>
                                        <span class="color-second-title mt-2">Pizzas</span>
                                        <span class="icon-categories cat-pizza"></span>
                                    </li>
                                    <li>
                                        <span class="color-second-title mt-2">Bebidas</span>
                                        <span class="icon-categories cat-drinks"></span>
                                    </li>
                                    <li>
                                        <span class="color-second-title mt-2">Comida Tipica</span>
                                        <span class="icon-categories cat-tipicFood"></span>
                                    </li>
                                    <li>
                                        <span class="color-second-title mt-2">Postres</span>
                                        <span class="icon-categories cat-desserts"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Sección de Productos desde la BD -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <h2 class="text-center">NUESTROS <span>PRODUCTOS</span></h2>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <?php
                            // Obtener productos desde la base de datos
                            $sql_productos_inicio = "SELECT * FROM productos WHERE estado = 1 ORDER BY categoria, nombre_producto";
                            $result_productos_inicio = $mysqli->query($sql_productos_inicio);
                            
                            if($result_productos_inicio && $result_productos_inicio->num_rows > 0):
                                while($producto = $result_productos_inicio->fetch_assoc()):
                                    // Usar imagen según categoría si no tiene imagen propia
                                    $img_producto = $producto['imagen'] && file_exists('assets/img/' . $producto['imagen']) 
                                        ? $producto['imagen'] 
                                        : ($imagen_categoria[$producto['categoria']] ?? 'placeholder.jpg');
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="product-card">
                                    <div class="product-img">
                                        <img src="assets/img/<?php echo htmlspecialchars($img_producto); ?>" 
                                            alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                            onerror="this.src='assets/img/placeholder.jpg'">
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                        <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                        <div class="product-footer d-flex justify-content-between align-items-center">
                                            <span class="product-price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                                            <button class="btn-add-cart" 
                                                    data-id="<?php echo $producto['id_producto']; ?>"
                                                    data-name="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                    data-price="<?php echo $producto['precio']; ?>">
                                                <i class="fa-solid fa-cart-plus"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <div class="col-12">
                                <p class="text-center">No hay productos disponibles en este momento.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        </div><!-- Fin sección inicio -->
                        
                        <!-- SECCIÓN MIS PEDIDOS -->
                        <div id="section-pedidos" class="content-section" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="color-main-title my-5">Mis Pedidos</h4>
                                    <div id="listaPedidos">
                                        <!-- Los pedidos se cargarán dinámicamente aquí -->
                                    </div>
                                </div>
                            </div>
                        </div><!-- Fin sección pedidos -->
                        
                        <!-- SECCIÓN PRODUCTOS -->
                        <div id="section-productos" class="content-section" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="text-center">NUESTROS <span>PRODUCTOS</span></h2>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <?php
                                // Reiniciar el puntero del resultado para poder iterar de nuevo
                                if($result_productos) {
                                    $result_productos->data_seek(0);
                                }
                                
                                if($result_productos && $result_productos->num_rows > 0):
                                    while($producto = $result_productos->fetch_assoc()):
                                        // Usar imagen según categoría si no tiene imagen propia
                                        $img_producto = $producto['imagen'] && file_exists('assets/img/' . $producto['imagen']) 
                                            ? $producto['imagen'] 
                                            : ($imagen_categoria[$producto['categoria']] ?? 'placeholder.jpg');
                                ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="product-card">
                                        <div class="product-img">
                                            <img src="assets/img/<?php echo htmlspecialchars($img_producto); ?>" 
                                                alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                onerror="this.src='assets/img/placeholder.jpg'">
                                        </div>
                                        <div class="product-info">
                                            <h5 class="product-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                            <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                            <div class="product-footer d-flex justify-content-between align-items-center">
                                                <span class="product-price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                                                <button class="btn-add-cart" 
                                                        data-id="<?php echo $producto['id_producto']; ?>"
                                                        data-name="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                        data-price="<?php echo $producto['precio']; ?>">
                                                    <i class="fa-solid fa-cart-plus"></i> Agregar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <div class="col-12">
                                    <p class="text-center">No hay productos disponibles en este momento.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div><!-- Fin sección productos -->
                        
                    </div>
                </div>
                <div class="col-md-3 right-side-menu"><!-- menu lateral derecho -->
                    <h5 class="color-main-title my-5 ms-3">Tu Saldo...</h6>
                    <div class="balance d-flex align-items-center justify-content-center m-auto">
                        <div class="d-flex align-items-center justify-content-center flex-column me-3">
                            <h6>Tu credito</h6>
                            <span>$15.000</span>    
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <span><i class="fa-solid fa-money-bill-transfer"></i></span>
                        </div>
                    </div>
                    <h5 class="color-main-title my-5 ms-3">Tu Ubicación...</h5>
                    <div id="user-map" class="my-5"><!-- Visualizador del mapa --></div>
                </div>
            </div>
        </div>
        
        <!-- Fondo con opacidad -->
        <div class="bg-opacity"></div>
        
        <!-- Modal del Carrito -->
        <div class="popUp cart-modal" id="cartModal">
            <div class="cart-container w-100">
                <span class="close-popUp" onclick="closeModal('cartModal')"><i class="fa-solid fa-xmark"></i></span>
                <h3 class="text-center mb-4"><i class="fa-solid fa-cart-shopping"></i> Tu Carrito</h3>
                <div id="cartItems" class="cart-items">
                    <p class="text-center">Tu carrito está vacío</p>
                </div>
                <div class="cart-footer">
                    <div class="cart-total">
                        <h4>Total: $<span id="cartTotal">0</span></h4>
                    </div>
                    <button class="btn-checkout" id="btnCheckout" onclick="showCheckoutForm()">
                        Proceder al Pago <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Modal de Checkout -->
        <div class="popUp checkout-modal" id="checkoutModal">
            <div class="checkout-container w-100">
                <span class="close-popUp" onclick="closeModal('checkoutModal')"><i class="fa-solid fa-xmark"></i></span>
                <h3 class="text-center mb-4">Finalizar Pedido</h3>
                <form id="checkoutForm" method="post" action="procesar_pedido_user.php">
                    <div class="inputsForm">
                        <div class="data-users w-100">
                            <label class="d-flex flex-column justify-content-center text-start">Nombre Completo
                                <input class="my-3" type="text" name="nombre_cliente" value="<?php echo htmlspecialchars($_SESSION["nombre_usuario"] . " " . $_SESSION["apellido_usuario"]); ?>" required>
                            </label>
                        </div>
                        <div class="data-users w-100">
                            <label class="d-flex flex-column justify-content-center text-start">Teléfono
                                <input class="my-3" type="tel" name="telefono_cliente" required>
                            </label>
                        </div>
                        <div class="data-users w-100">
                            <label class="d-flex flex-column justify-content-center text-start">Dirección de Entrega
                                <textarea class="my-3" name="direccion_entrega" rows="3" required></textarea>
                            </label>
                        </div>
                        <div class="payment-section">
                            <h5>Método de Pago</h5>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="metodo_pago" value="Efectivo" checked>
                                    <span><i class="fa-solid fa-money-bill"></i> Efectivo</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="metodo_pago" value="Tarjeta">
                                    <span><i class="fa-solid fa-credit-card"></i> Tarjeta de Crédito/Débito</span>
                                </label>
                            </div>
                            
                            <!-- Sección de pago con tarjeta (simulación) -->
                            <div id="cardPaymentSection" style="display: none;" class="mt-4">
                                <div class="card-form">
                                    <div class="mb-3">
                                        <label class="form-label">Número de Tarjeta</label>
                                        <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Fecha de Expiración</label>
                                            <input type="text" class="form-control" id="cardExpiry" placeholder="MM/AA" maxlength="5">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cardCvv" placeholder="123" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nombre en la Tarjeta</label>
                                        <input type="text" class="form-control" id="cardName" placeholder="NOMBRE APELLIDO">
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="checkout-summary mt-4">
                            <h5>Resumen del Pedido</h5>
                            <div id="checkoutSummary"></div>
                            <div class="checkout-total">
                                <strong>Total a Pagar: $<span id="checkoutTotal">0</span></strong>
                            </div>
                        </div>
                        <input type="hidden" name="cart_data" id="cartData">
                        <input type="hidden" name="latitud" id="latitudInput">
                        <input type="hidden" name="longitud" id="longitudInput">
                        <input type="hidden" name="stripe_token" id="stripeToken">
                        <button type="submit" class="btn-confirm-order mt-4" id="btnConfirmOrder">
                            Confirmar Pedido <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal de detalles de pedido -->
        <div class="popUp" id="pedidoDetalleModal">
            <div class="checkout-container w-100">
                <span class="close-popUp" onclick="closeModal('pedidoDetalleModal')"><i class="fa-solid fa-xmark"></i></span>
                <h3 class="text-center mb-4">Detalles del Pedido</h3>
                <div id="pedidoDetalleContent">
                    <!-- Se cargará dinámicamente -->
                </div>
            </div>
        </div>
        
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/user_dash.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbcqOJPrZsXFWU5RRpvY99GQxE4G2rDok&callback=initMap"></script>
</html>