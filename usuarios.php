<?php
session_start();
include('conection.php');
include('libs.php');
$mysqli = bd_conection();

// Verificar que sea administrador
if (!isset($_SESSION["id_user"]) || $_SESSION["user_rol"] != 1) {
    header("Location: index.php");
    exit();
}

// Cambiar rol de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_rol'])) {
    $id_user = intval($_POST['id_user']);
    $nuevo_rol = intval($_POST['rol']);
    
    $sql = "UPDATE usuarios SET rol = ? WHERE id_user = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $nuevo_rol, $id_user);
    
    if ($stmt->execute()) {
        $mensaje = "Rol actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el rol";
        $tipo_mensaje = "danger";
    }
}

// Eliminar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_usuario'])) {
    $id_user = intval($_POST['id_user']);
    
    // No permitir eliminar al admin actual
    if ($id_user != $_SESSION["id_user"]) {
        $sql = "DELETE FROM usuarios WHERE id_user = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_user);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario eliminado";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al eliminar usuario";
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = "No puedes eliminar tu propia cuenta";
        $tipo_mensaje = "warning";
    }
}

// Obtener todos los usuarios
$sql_usuarios = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
$result_usuarios = $mysqli->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - TinBurguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
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

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4"><i class="fa-solid fa-users"></i> Control de Usuarios</h1>
                
                <?php if (isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <div class="table-inventory">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Rol</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_usuarios && $result_usuarios->num_rows > 0): ?>
                                <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $usuario['id_user']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_usuario']); ?>
                                        <?php if ($usuario['id_user'] == $_SESSION["id_user"]): ?>
                                            <span class="badge bg-info">Tú</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefono'] ?? 'N/A'); ?></td>
                                    <td><small><?php echo htmlspecialchars($usuario['direccion'] ?? 'N/A'); ?></small></td>
                                    <td>
                                        <?php if ($usuario['rol'] == 1): ?>
                                            <span class="badge bg-danger">Administrador</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Usuario</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="cambiarRol(<?php echo $usuario['id_user']; ?>, <?php echo $usuario['rol']; ?>)">
                                            <i class="fa-solid fa-user-gear"></i>
                                        </button>
                                        <?php if ($usuario['id_user'] != $_SESSION["id_user"]): ?>
                                        <button class="btn-action btn-delete" onclick="confirmarEliminar(<?php echo $usuario['id_user']; ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No hay usuarios registrados</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Rol -->
    <div class="modal fade" id="modalRol" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Rol de Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="cambiar_rol" value="1">
                        <input type="hidden" name="id_user" id="rol_id_user">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Rol</label>
                            <select class="form-control" name="rol" id="rol_select" required>
                                <option value="2">Usuario</option>
                                <option value="1">Administrador</option>
                            </select>
                            <small class="text-muted">Los administradores tienen acceso completo al sistema</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Form oculto para eliminar -->
    <form method="POST" id="formEliminar" style="display: none;">
        <input type="hidden" name="eliminar_usuario" value="1">
        <input type="hidden" name="id_user" id="delete_id">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
    <script>
        function cambiarRol(idUser, rolActual) {
            document.getElementById('rol_id_user').value = idUser;
            document.getElementById('rol_select').value = rolActual;
            new bootstrap.Modal(document.getElementById('modalRol')).show();
        }
        
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
                document.getElementById('delete_id').value = id;
                document.getElementById('formEliminar').submit();
            }
        }
    </script>
</body>
</html>
