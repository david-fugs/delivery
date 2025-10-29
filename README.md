# 🍔 TinBurguer - Sistema de Delivery

Sistema completo de delivery con gestión de inventario, pedidos y usuarios.

## 📋 Características

### Para Usuarios (Rol 2)
- ✅ Registro e inicio de sesión
- ✅ Visualización de menú con precios
- ✅ Carrito de compras funcional
- ✅ Sistema de checkout con datos de entrega
- ✅ Confirmación de pedidos
- ✅ No pueden agregar al carrito sin estar logueados

### Para Administradores (Rol 1)
- ✅ Panel de administración con estadísticas
- ✅ Gestión completa de inventario (CRUD)
- ✅ Visualización y gestión de pedidos
- ✅ Control de usuarios y roles
- ✅ Visualización de ingresos

## 🗄️ Base de Datos

### Tablas Creadas:
1. **usuarios** - Información de usuarios (admin y clientes)
2. **productos** - Catálogo de productos
3. **inventario** - Control de stock con código, cantidad y locación
4. **pedidos** - Registro de pedidos con estado y timestamps
5. **detalle_pedidos** - Detalles de cada pedido

## 🚀 Instalación

### Paso 1: Importar Base de Datos
```bash
1. Abre phpMyAdmin (http://localhost/phpmyadmin o http://localhost:3307/phpmyadmin)
2. Crea una nueva base de datos llamada "tinburguer"
3. Importa el archivo: tinburguer.sql
```

### Paso 2: Configurar Conexión
Verifica que el archivo `conection.php` tenga los datos correctos:
```php
$host = "localhost:3307";  // o "localhost" si usas el puerto 3306
$user = "root";
$pass = "";
$db = "tinburguer";
```

### Paso 3: Crear Carpeta para Imágenes
```bash
# Asegúrate de que exista:
c:\xampp\htdocs\delivery\assets\img\
```

## 👤 Usuarios de Prueba

### Administrador
- **Email:** gio@gmail.com
- **Contraseña:** 123
- **Rol:** 1 (Administrador)

### Usuario Normal
- **Email:** norma@gmail.com
- **Contraseña:** 321
- **Rol:** 2 (Usuario)

## 📁 Estructura de Archivos

```
delivery/
├── index.php                    # Página principal
├── login.php                    # Procesamiento de login
├── register.php                 # Procesamiento de registro
├── log_out.php                  # Cerrar sesión
├── conection.php                # Conexión a BD
├── libs.php                     # Librerías
├── main_admin.php               # Dashboard administrador
├── inventario.php               # Gestión de inventario
├── pedidos.php                  # Gestión de pedidos
├── usuarios.php                 # Control de usuarios
├── procesar_pedido.php          # Procesar compra
├── pedido_confirmado.php        # Confirmación de pedido
├── get_detalle_pedido.php       # Detalle de pedido (AJAX)
├── tinburguer.sql               # Base de datos
├── assets/
│   ├── css/
│   │   └── styles.css           # Estilos
│   ├── js/
│   │   └── main.js              # JavaScript
│   └── img/                     # Imágenes
```

## 🎨 Funcionalidades Implementadas

### Sistema de Modales
- ✅ Modal de Login separado
- ✅ Modal de Registro separado
- ✅ Modal de Carrito de compras
- ✅ Modal de Checkout
- ✅ Transiciones suaves entre modales

### Sistema de Carrito
- ✅ Agregar productos al carrito
- ✅ Aumentar/disminuir cantidad
- ✅ Eliminar productos
- ✅ Contador de items en el navbar
- ✅ Persistencia con localStorage
- ✅ Solo disponible para usuarios logueados

### Sistema de Pedidos
- ✅ Formulario de datos de entrega
- ✅ Validación de campos
- ✅ Cálculo automático de totales
- ✅ Registro de fecha y hora
- ✅ Estados de pedido (Pendiente, En Preparación, En Camino, Entregado, Cancelado)
- ✅ Actualización de inventario al realizar pedido
- ✅ Espacio preparado para integración de API de pagos

### Gestión de Inventario
- ✅ Ver todos los productos
- ✅ Agregar nuevos productos
- ✅ Editar productos existentes
- ✅ Eliminar productos
- ✅ Control de stock con alertas
- ✅ Código de artículo único
- ✅ Locación de almacén
- ✅ Timestamp de última actualización

### Panel de Administración
- ✅ Estadísticas en tiempo real
- ✅ Total de pedidos
- ✅ Pedidos del día
- ✅ Total de usuarios
- ✅ Ingresos del mes
- ✅ Últimos pedidos
- ✅ Accesos rápidos

## 🔐 Sistema de Roles

### Rol 1 - Administrador
Acceso a:
- Panel de administración
- Gestión de inventario
- Gestión de pedidos
- Control de usuarios
- Estadísticas completas

### Rol 2 - Usuario
Acceso a:
- Menú de productos
- Carrito de compras
- Realizar pedidos
- Ver confirmación de pedido

## 💳 Sistema de Pagos (Preparado para el Futuro)

En el archivo `procesar_pedido.php` hay un espacio preparado para integrar una API de pagos:

```php
// ================================================
// TODO: Integrar API de pagos (Stripe, PayU, Mercado Pago, etc.)
// 
// if ($metodo_pago == 'Tarjeta') {
//     // Iniciar proceso de pago con la API
//     ...
// }
// ================================================
```

### APIs Recomendadas para Colombia:
- **Mercado Pago** - Fácil integración
- **PayU** - Popular en Colombia
- **ePayco** - Colombiana
- **Stripe** - Internacional

## 🎯 Próximas Mejoras Sugeridas

1. **Sistema de Notificaciones**
   - Email al confirmar pedido
   - SMS de seguimiento
   - Notificaciones push

2. **Seguimiento en Tiempo Real**
   - Mapa con ubicación del pedido
   - Actualizaciones automáticas de estado

3. **Reportes**
   - Reporte de ventas
   - Productos más vendidos
   - Análisis de clientes

4. **Imágenes de Productos**
   - Subida de imágenes
   - Galería de productos
   - Optimización de imágenes

5. **Sistema de Cupones**
   - Descuentos
   - Promociones
   - Puntos de fidelidad

## 🛠️ Tecnologías Utilizadas

- **Frontend:**
  - HTML5
  - CSS3 (Custom + Bootstrap 5.3.8)
  - JavaScript (Vanilla)
  - Font Awesome 6

- **Backend:**
  - PHP 8.1
  - MySQL 8.0

- **Librerías:**
  - Bootstrap 5.3.8
  - Font Awesome 6
  - LocalStorage API

## 📱 Responsive Design

La aplicación es completamente responsive y funciona en:
- 📱 Móviles
- 📱 Tablets
- 💻 Desktop

## ⚠️ Notas Importantes

1. **Seguridad:**
   - Las contraseñas están en texto plano (se recomienda usar password_hash() en producción)
   - Implementar protección CSRF
   - Validar todas las entradas del usuario

2. **Imágenes:**
   - Agregar imágenes reales de productos en `assets/img/`
   - Se incluye manejo de error si la imagen no existe

3. **Inventario:**
   - El sistema actualiza automáticamente el inventario al procesar pedidos
   - Se valida que haya stock suficiente antes de procesar

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos
- Verifica que XAMPP esté corriendo
- Confirma el puerto de MySQL (3306 o 3307)
- Verifica credenciales en `conection.php`

### No se ven las imágenes
- Verifica que la carpeta `assets/img/` exista
- Agrega imágenes con los nombres correctos
- Se mostrará una imagen placeholder si no existe

### El carrito no guarda items
- Verifica que JavaScript esté habilitado
- Revisa la consola del navegador
- Limpia el localStorage si hay problemas

## 👥 Créditos

Desarrollado por: **Gio y David**  
Año: **2025**

## 📄 Licencia

Este proyecto es de uso educativo.

---

¡Disfruta de TinBurguer! 🍔🍟
