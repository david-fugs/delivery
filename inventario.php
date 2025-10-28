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

// Procesar acciones (Agregar, Editar, Eliminar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // AGREGAR PRODUCTO E INVENTARIO
        if ($action == 'add') {
            $nombre = mysqli_real_escape_string($mysqli, $_POST['nombre_producto']);
            $descripcion = mysqli_real_escape_string($mysqli, $_POST['descripcion']);
            $precio = floatval($_POST['precio']);
            $categoria = mysqli_real_escape_string($mysqli, $_POST['categoria']);
            $codigo = mysqli_real_escape_string($mysqli, $_POST['codigo_articulo']);
            $cantidad = intval($_POST['cantidad']);
            $locacion = mysqli_real_escape_string($mysqli, $_POST['locacion']);
            
            $mysqli->begin_transaction();
            try {
                // Insertar producto
                $sql_prod = "INSERT INTO productos (nombre_producto, descripcion, precio, categoria, estado) VALUES (?, ?, ?, ?, 1)";
                $stmt = $mysqli->prepare($sql_prod);
                $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $categoria);
                $stmt->execute();
                $id_producto = $mysqli->insert_id;
                
                // Insertar en inventario
                $sql_inv = "INSERT INTO inventario (id_producto, codigo_articulo, cantidad, locacion) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql_inv);
                $stmt->bind_param("isis", $id_producto, $codigo, $cantidad, $locacion);
                $stmt->execute();
                
                $mysqli->commit();
                $mensaje = "Producto agregado exitosamente";
                $tipo_mensaje = "success";
            } catch (Exception $e) {
                $mysqli->rollback();
                $mensaje = "Error al agregar producto: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
        
        // EDITAR INVENTARIO
        elseif ($action == 'edit') {
            $id_inventario = intval($_POST['id_inventario']);
            $id_producto = intval($_POST['id_producto']);
            $nombre = mysqli_real_escape_string($mysqli, $_POST['nombre_producto']);
            $precio = floatval($_POST['precio']);
            $codigo = mysqli_real_escape_string($mysqli, $_POST['codigo_articulo']);
            $cantidad = intval($_POST['cantidad']);
            $locacion = mysqli_real_escape_string($mysqli, $_POST['locacion']);
            
            $mysqli->begin_transaction();
            try {
                // Actualizar producto
                $sql_prod = "UPDATE productos SET nombre_producto = ?, precio = ? WHERE id_producto = ?";
                $stmt = $mysqli->prepare($sql_prod);
                $stmt->bind_param("sdi", $nombre, $precio, $id_producto);
                $stmt->execute();
                
                // Actualizar inventario
                $sql_inv = "UPDATE inventario SET codigo_articulo = ?, cantidad = ?, locacion = ? WHERE id_inventario = ?";
                $stmt = $mysqli->prepare($sql_inv);
                $stmt->bind_param("sisi", $codigo, $cantidad, $locacion, $id_inventario);
                $stmt->execute();
                
                $mysqli->commit();
                $mensaje = "Producto actualizado exitosamente";
                $tipo_mensaje = "success";
            } catch (Exception $e) {
                $mysqli->rollback();
                $mensaje = "Error al actualizar: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
        
        // ELIMINAR
        elseif ($action == 'delete') {
            $id_inventario = intval($_POST['id_inventario']);
            
            $sql = "DELETE FROM inventario WHERE id_inventario = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $id_inventario);
            
            if ($stmt->execute()) {
                $mensaje = "Producto eliminado del inventario";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar";
                $tipo_mensaje = "danger";
            }
        }
    }
}

// Obtener inventario
$sql_inventario = "SELECT i.*, p.nombre_producto, p.precio, p.categoria 
                   FROM inventario i 
                   INNER JOIN productos p ON i.id_producto = p.id_producto 
                   ORDER BY p.categoria, p.nombre_producto";
$result_inventario = $mysqli->query($sql_inventario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - TinBurguer</title>
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
                <h1 class="text-center mb-4"><i class="fa-solid fa-boxes-stacked"></i> Gestión de Inventario</h1>
                
                <?php if (isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <div class="mb-4">
                    <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                        <i class="fa-solid fa-plus"></i> Agregar Producto
                    </button>
                </div>
                
                <div class="table-inventory">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Locación</th>
                                <th>Última Act.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_inventario && $result_inventario->num_rows > 0): ?>
                                <?php while ($item = $result_inventario->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['codigo_articulo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($item['categoria']); ?></td>
                                    <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $item['cantidad'] < 10 ? 'bg-danger' : 'bg-success'; ?>">
                                            <?php echo $item['cantidad']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['locacion']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($item['fecha_actualizacion'])); ?></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="editarItem(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="confirmarEliminar(<?php echo $item['id_inventario']; ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No hay productos en el inventario</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" name="nombre_producto" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" class="form-control" name="precio" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-control" name="categoria" required>
                                <option value="Hamburguesas Gourmet">Hamburguesas Gourmet</option>
                                <option value="Perros">Perros</option>
                                <option value="Entradas">Entradas</option>
                                <option value="Acompañantes">Acompañantes</option>
                                <option value="Big Combos">Big Combos</option>
                                <option value="Bebidas">Bebidas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código del Artículo</label>
                            <input type="text" class="form-control" name="codigo_articulo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad Inicial</label>
                            <input type="number" class="form-control" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Locación</label>
                            <input type="text" class="form-control" name="locacion" value="Almacén Principal" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditar">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id_inventario" id="edit_id_inventario">
                        <input type="hidden" name="id_producto" id="edit_id_producto">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" name="nombre_producto" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" class="form-control" name="precio" id="edit_precio" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código del Artículo</label>
                            <input type="text" class="form-control" name="codigo_articulo" id="edit_codigo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="edit_cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Locación</label>
                            <input type="text" class="form-control" name="locacion" id="edit_locacion" required>
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
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id_inventario" id="delete_id">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a0f7896d3.js" crossorigin="anonymous"></script>
    <script>
        function editarItem(item) {
            document.getElementById('edit_id_inventario').value = item.id_inventario;
            document.getElementById('edit_id_producto').value = item.id_producto;
            document.getElementById('edit_nombre').value = item.nombre_producto;
            document.getElementById('edit_precio').value = item.precio;
            document.getElementById('edit_codigo').value = item.codigo_articulo;
            document.getElementById('edit_cantidad').value = item.cantidad;
            document.getElementById('edit_locacion').value = item.locacion;
            
            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        }
        
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de eliminar este producto del inventario?')) {
                document.getElementById('delete_id').value = id;
                document.getElementById('formEliminar').submit();
            }
        }
    </script>
</body>
</html>
