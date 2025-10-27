<?php
    include('libs.php');
?>
<!DOCTYPE html>
<html lang="en">
    <header class="pb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="info-header">
                        <nav class="d-flex justify-content-between align-items-center">
                            <div class="main-logo"></div>
                            <ul class="d-flex align-items-center">
                                <li>
                                    <a href="#" class="px-2"><i class="fa-solid fa-truck px-2"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-2"><i class="fa-solid fa-cart-shopping px-2"></i>
                                    </a>
                                    <span class="notification">1</span>
                                </li>
                                <li><a href="#" class="px-2"><i class="fa-solid fa-user"></i></span></li>
                                <li>
                                    <a class="px-2" id="goLogin"><a href="log_out.php" class=""><i class="fa-solid fa-power-off"></i></a>
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
            <div class="row d-sm-none d-md-flex">
                <div class="col-md-2 left-side-menu"><!-- menu lateral izquierdo -->
                    <div class="side-menu sections">
                        <h5 class=" color-txt-main2 text-center my-5">TinBurguer</h5>
                        <ul class="px-4 pb-5">
                            <li class="option-side-menu py-3 my-2 px-4 active">
                                <i class="fa-solid fa-house pe-3"></i>
                                <a href="#" class="mx-2">Incio</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4">
                                <i class="fa-solid fa-motorcycle pe-3"></i>
                                <a href="#" class="mx-2">Mi Pedido</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4">
                                <i class="fa-solid fa-sack-dollar pe-3"></i>
                                <a href="#" class="mx-2">Pagos</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4">
                                <i class="fa-solid fa-clock-rotate-left pe-3"></i>
                                <a href="#" class="mx-2">Historial</a>
                            </li>
                            <li class="option-side-menu py-3 my-3 px-4">
                                <i class="fa-solid fa-gear pe-3"></i>
                                <a href="#" class="mx-2">Ajustes</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7"><!-- menu central principal -->
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="baner-promotions border border-primary">
                                    <span><img src="assets/img/img-baner-descuentos.png" alt="Baner Descuentos"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Categorias</h4>
                                <ul>
                                    <li>
                                        <span>Hamburguesas</span>
                                        <i class="fa-solid fa-burger"></i>
                                    </li>
                                    <li>
                                        <span>Perros Calientes</span>
                                        <i class="fa-solid fa-hotdog"></i>
                                    </li>
                                    <li>
                                        <span>Pizzas</span>
                                        <i class="fa-solid fa-pizza-slice"></i>
                                    </li>
                                    <li>
                                        <span>Bebidas</span>
                                        <i class="fa-solid fa-martini-glass-citrus"></i>
                                    </li>
                                    <li>
                                        <span>Comida Tipica</span>
                                        <i class="fa-solid fa-bowl-food"></i>
                                    </li>
                                    <li>
                                        <span>Postres</span>
                                        <i class="fa-solid fa-ice-cream"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Descuentos de la Semana</h4>
                                <ul>
                                    <li>
                                        <span><!-- descuento --></span>
                                        <span><!-- me gusta --></span>
                                        <div><!-- img hamb --></div>
                                        <div class="stars">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="info-prom">
                                            <div class="cost-name-ham">
                                                <h6>hamburguer</h6>
                                                <span class="priceHamb"></span>
                                            </div>
                                            <span><!-- agregar al carrito --></span>
                                        </div>
                                    </li>
                                    <li>
                                        <span><!-- descuento --></span>
                                        <span><!-- me gusta --></span>
                                        <div><!-- img hamb --></div>
                                        <div class="stars">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="info-prom">
                                            <div class="cost-name-ham">
                                                <h6>hamburguer</h6>
                                                <span class="priceHamb"></span>
                                            </div>
                                            <span><!-- agregar al carrito --></span>
                                        </div>
                                    </li>
                                    <li>
                                        <span><!-- descuento --></span>
                                        <span><!-- me gusta --></span>
                                        <div><!-- img hamb --></div>
                                        <div class="stars">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="info-prom">
                                            <div class="cost-name-ham">
                                                <h6>hamburguer</h6>
                                                <span class="priceHamb"></span>
                                            </div>
                                            <span><!-- agregar al carrito --></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 right-side-menu"><!-- menu lateral derecho -->

                </div>
            </div>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</html>