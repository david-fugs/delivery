<?php
    session_start();
    include('libs.php');
    include('conection.php');
    $mysqli = bd_conection();
    
    // Verificar si el usuario está logueado
    $is_logged = isset($_SESSION["id_user"]);
    $user_rol = $is_logged ? $_SESSION["user_rol"] : 0;
    $user_name = $is_logged ? $_SESSION["user_name"] : '';
?>
<!DOCTYPE html>
<html lang="en">
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="info-header">
                        <nav class="d-flex justify-content-between align-items-center">
                            <div class="main-logo"></div>
                            <?php if($is_logged && $user_rol == 1): ?>
                                <!-- Navbar para Administrador -->
                                <ul class="d-flex align-items-center">
                                    <li>
                                        <a href="main_admin.php" class="px-3">Panel Admin <i class="fa-solid fa-gauge px-2"></i></a>
                                    </li>
                                    <li>
                                        <a href="inventario.php" class="px-3">Inventario <i class="fa-solid fa-boxes-stacked px-2"></i></a>
                                    </li>
                                    <li>
                                        <a href="pedidos.php" class="px-3">Pedidos <i class="fa-solid fa-clipboard-list px-2"></i></a>
                                    </li>
                                    <li>
                                        <a href="usuarios.php" class="px-3">Usuarios <i class="fa-solid fa-users px-2"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" class="px-3" id="cartIcon"><i class="fa-solid fa-cart-shopping px-2"></i></a>
                                        <span class="notification" id="cartCount">0</span>
                                    </li>
                                    <li>
                                        <span class="px-3 text-main2">Hola, <?php echo htmlspecialchars($user_name); ?></span>
                                    </li>
                                    <li>
                                        <a href="log_out.php" class="px-3" title="Cerrar Sesión"><i class="fa-solid fa-right-from-bracket px-2"></i></a>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <!-- Navbar para visitantes (no logueados) -->
                                <ul class="d-flex align-items-center">
                                    <li>
                                        <a href="#menu" class="px-3">Hacer un pedido <i class="fa-solid fa-truck px-2"></i></a>
                                    </li>
                                    <li>
                                        <a class="px-3" id="goLogin">Iniciar sesión 
                                        <i class="fa-regular fa-circle-user px-2"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" class="px-3" id="cartIconDisabled"><i class="fa-solid fa-cart-shopping px-2"></i></a>
                                        <span class="notification">0</span>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <body>
        <div class="hamb-banner">
            <div class="img-banner">
                <span class="burguerOut" id="burguerFloat"></span>
                <h1 class="brand" id="myBrand">TinBurguer</h1>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center">NUESTRO ACLAMADO <span>MENU</span></h2>
                </div>
                <div class="row" id="menu">
                    <div class="col-12">
                        <div class="food-grid">
                            <div class="type-food food-1">
                                <span>Entradas</span>
                            </div>
                            <div class="type-food food-2">
                                <span>Acompañantes</span>
                            </div>
                            <div class="type-food food-3">
                                <span>Hamburguesas Gourmet</span>
                            </div>
                            <div class="type-food food-4">
                                <span>Especiales de 1/4 Libra</span>
                            </div>
                            <div class="type-food food-5">
                                <span>Perros</span>
                            </div>
                            <div class="type-food food-6">
                                <span>Big Combos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección de Productos -->
            <div class="row mt-5">
                <div class="col-12">
                    <h2 class="text-center">NUESTROS <span>PRODUCTOS</span></h2>
                </div>
            </div>
            <div class="row mb-5">
                <?php
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
                                <?php if($is_logged): ?>
                                    <button class="btn-add-cart" 
                                            data-id="<?php echo $producto['id_producto']; ?>"
                                            data-name="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                            data-price="<?php echo $producto['precio']; ?>">
                                        <i class="fa-solid fa-cart-plus"></i> Agregar
                                    </button>
                                <?php else: ?>
                                    <button class="btn-add-cart disabled" onclick="alert('Debes iniciar sesión para agregar productos al carrito')">
                                        <i class="fa-solid fa-lock"></i> Iniciar sesión
                                    </button>
                                <?php endif; ?>
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
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center">NUESTROS <span>RECOMENDADOS</span></h2>
                </div>
            </div>
            <div class="row mb-100">
                <div class="col-12">
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item item-1 active">Cheese Burguer</div>
                            <div class="carousel-item item-2">Bacon Burguer</div>
                            <div class="carousel-item item-3">Comida Tipica Colombiana</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="info-footer d-flex justify-content-between align-items-center">
                            <div class="main-logo"></div>
                            <p class="m-0 d-none d-md-block">2025 &copy; Elaborado por Gio y David</p>
                            <ul class="d-flex flex-column d-md-block">
                                <li class="text-start"><a href="#">Local</a></li>
                                <li class="text-start"><a href="login.php">Iniciar sesión</li>
                                <li class="text-start"><a href="">Términos y condiciones</a></li>
                                <input type="submit" value="Pedir" id="btn-buy" name="btn-pedir">
                            </ul>
                            <ul class="d-flex align-items-center m-0 flex-column flex-md-row">
                                <li>
                                    <a href="#" class="px-3"><i class="fa-brands fa-facebook-f"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-3"><i class="fa-brands fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-3"><i class="fa-brands fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-3"><i class="fa-brands fa-tiktok"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Fondo con opacidad -->
        <div class ="bg-opacity"></div>
        
        <!-- Modal de Login -->
        <div class="popUp" id="loginModal">
            <div class="login w-100">
                <span class="close-popUp" onclick="closeModal('loginModal')"><i class="fa-solid fa-xmark"></i></span>
                <form action="login.php" method="post" class="d-flex flex-column">
                    <h3 class="d-flex align-items-center justify-content-center m-0">Bienvenido... <span class="main-logo"></span></h3>
                    <div class="inputsForm">
                        <fieldset class="d-flex flex-column align-items-center justify-content-center">
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Email
                                    <input class="my-3" type="email" name="email" id="inpt-email" placeholder="Ingresa tu email" required>
                                </label>         
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Contraseña
                                    <input class="my-3" type="password" name="contrasena" id="inpt-pass" placeholder="Ingresa tu contraseña" required>
                                </label>
                            </div>
                            <div class="buttonsForm d-flex w-auto">
                                <input type="submit" value="Iniciar Sesión" id="btn-login" name="btn-login" class="mt-4 mx-2">
                                <button type="button" id="btn-go-register" class="mt-4 mx-2" onclick="switchModal('loginModal', 'registerModal')">Registrarse</button>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal de Registro -->
        <div class="popUp" id="registerModal">
            <div class="login w-100">
                <span class="close-popUp" onclick="closeModal('registerModal')"><i class="fa-solid fa-xmark"></i></span>
                <form action="register.php" method="post" class="d-flex flex-column" id="formRegister">
                    <h3 class="d-flex align-items-center justify-content-center m-0">Regístrate <span class="main-logo"></span></h3>
                    <div class="inputsForm">
                        <fieldset class="d-flex flex-column align-items-center justify-content-center">
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Nombre
                                    <input class="my-3" type="text" name="user_name" placeholder="Ingresa tu nombre" required>
                                </label>         
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Apellido
                                    <input class="my-3" type="text" name="user_last_name" placeholder="Ingresa tu apellido" required>
                                </label>
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Telefono
                                    <input class="my-3" type="text" name="telefono" placeholder="Telefono" required>
                                </label>
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Dirección
                                    <input class="my-3" type="text" name="direccion" placeholder="Ingresa tu apellido" required>
                                </label>
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Email
                                    <input class="my-3" type="email" name="email" placeholder="Ingresa tu email" required>
                                </label>
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center text-start">Contraseña
                                    <input class="my-3" type="password" name="contrasena" placeholder="Ingresa tu contraseña" required>
                                </label>
                            </div>
                            <div id="register-message" class="mt-2"></div>
                            <div class="buttonsForm d-flex w-auto">
                                <input type="submit" value="Registrarse" id="btn-register" name="btn-register" class="mt-4 mx-2">
                                <button type="button" class="mt-4 mx-2" id="btn-have-count" onclick="switchModal('registerModal', 'loginModal')">Ya tengo cuenta</button>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
        
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
                <form id="checkoutForm" method="post" action="procesar_pedido.php">
                    <div class="inputsForm">
                        <div class="data-users w-100">
                            <label class="d-flex flex-column justify-content-center text-start">Nombre Completo
                                <input class="my-3" type="text" name="nombre_cliente" required>
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
                                    <span><i class="fa-solid fa-credit-card"></i> Tarjeta (Próximamente)</span>
                                </label>
                            </div>
                            <!-- Espacio para futura integración de API de pagos -->
                            <div id="paymentGateway" style="display: none;">
                                <!-- Aquí se integrará la API de pagos en el futuro -->
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
                        <button type="submit" class="btn-confirm-order mt-4">
                            Confirmar Pedido <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</html>