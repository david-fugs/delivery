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
            <div class="row d-sm-none d-md-flex calc-h-80">
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
                                <div class="baner-promotions d-flex align-items-center ps-5">
                                    <span>Obten  mayores descuentos de
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
                        <div class="row">
                            <div class="col-12">
                                <h4 class="color-main-title my-5">Descuentos de la Semana</h4>
                                <ul class="week-discounts">
                                    <li class="d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-center pt-2 pe-4">
                                            <span class="discount">25% Off</span>
                                            <span class="likes"><i class="fa-solid fa-heart"></i></span>
                                        </div>
                                        <div><img src="assets/img/bacon-cheese.png" alt="Hamburguesa Cheese Bacon" class="d-block m-auto"></div>
                                        <div class="info-prom ps-3 pb-2">
                                            <div class="stars">
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                            </div>
                                            <div class="cost-name-ham">
                                                <h6 class="color-main-title">Angust</h6>
                                                <span class="priceHamb"><b class="color-second-title">$</b>23.900</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-center pt-2 pe-4">
                                            <span class="discount">25% Off</span>
                                            <span class="likes"><i class="fa-solid fa-heart"></i></span>
                                        </div>
                                        <div><img src="assets/img/doble-carne.png" alt="Hamburguesa doble carne" class="d-block m-auto"></div>
                                        <div class="info-prom ps-3 pb-2">
                                            <div class="stars">
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                            </div>
                                            <div class="cost-name-ham">
                                                <h6 class="color-main-title">Bacon Cheese</h6>
                                                <span class="priceHamb"><b class="color-second-title">$</b>23.900</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-center pt-2 pe-4">
                                            <span class="discount">25% Off</span>
                                            <span class="likes"><i class="fa-regular fa-heart"></i></span>
                                        </div>
                                        <div><img src="assets/img/queso-doble-carne.png" alt="Hmburguesa queso doble carne" class="d-block m-auto"></div>
                                        <div class="info-prom ps-3 pb-2">
                                            <div class="stars">
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-solid fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                            </div>
                                            <div class="cost-name-ham">
                                                <h6 class="color-main-title">Doble Queso</h6>
                                                <span class="priceHamb"><b class="color-second-title">$</b>23.900</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 right-side-menu"><!-- menu lateral derecho -->
                    <h5 class="color-main-title my-5 ms-3">Creditos...</h6>
                    <div class="balance d-flex align-items-center justify-content-center m-auto">
                        <div class="d-flex align-items-center justify-content-center flex-column me-3">
                            <h6>Tu credito</h6>
                            <span>$15.000</span>    
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <span><i class="fa-solid fa-money-bill-transfer"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</html>