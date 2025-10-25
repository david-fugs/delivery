<?php
    include('libs.php');
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
                            <ul class="d-flex align-items-center">
                                <li>
                                    <a href="#" class="px-3">Hacer un pedido <i class="fa-solid fa-truck px-2"></i></a>
                                </li>
                                <li>
                                    <a class="px-3" id="goLogin">Iniciar sesion 
                                    <i class="fa-regular fa-circle-user px-2"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="px-3"><i class="fa-solid fa-cart-shopping px-2"></i>
                                    </a>
                                    <span class="notification">1</span>
                                </li>
                                <li><span></span></li>
                            </ul>
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
                <div class="row">
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
        <div class ="bg-opacity"></div>
        <div class="popUp">
            <div class="login w-100">
                <span class="close-popUp" id="close-popUp" onclick="closeLoginPopUp()"><i class="fa-solid fa-xmark"></i></span>
                <form action="login.php" method="post" class="d-flex flex-column">
                    <h3 class="d-flex align-items-center justify-content-center m-0">Bienvenido... <span class="main-logo"></span></h3>
                    <div class="inputsForm">
                        <fieldset class="d-flex flex-column align-items-center justify-content-center">
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center  text-start">Email
                                    <input class="my-3" type="email" name="email" id="inpt-email" placeholder="Ingresa tu email" require>
                                </label>         
                            </div>
                            <div class="data-users w-100">
                                <label for="" class="d-flex flex-column justify-content-center  text-start">Contraseña
                                    <input class="my-3" type="password" name="contrasena"   id="inpt-pass" placeholder="Ingresa tu contraseña" require>
                                </label>
                            </div>
                            <div class="buttonsForm d-flex w-auto">
                                <input type="submit" value="Iniciar Sesión" id="btn-login" name="btn-login" class="mt-4 mx-2">
                                <a href="user_register.php" id="btn-go-register" class="mt-4 mx-2">Registrarse</a>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</html>