<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="shortcut icon" href="assets/img/pizza-sin-fondo.png" />
    <title>TinBurguer Registro de usuarios</title>  
</head>
<body>
    <div class="container vh-100">
        <div class="row vh-100">
            <div class="col-12 col-md-6 offset-none offset-md-3">
                <div class="formUserRegister">
                    <form action="register.php" method="POST" class="">
                        <h2 class="text-center">Registro de usuarios</h2>
                        <?php include("register.php"); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" required>
                        </div><div class="mb-3">
                            <label for="email" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="user_last_name" name="user_last_name" required>
                        </div><div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                        </div>
                        <div class="buttonsForm d-flex justify-content-center w-auto">
                            <input type="submit" value="Registrarse" id="btn-register" name="btn-register" class="mt-4 mx-2">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>