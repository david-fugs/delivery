-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 23:20:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tinburguer`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(0, 0, 4, 1, 8000.00, 8000.00),
(0, 0, 6, 1, 55000.00, 55000.00),
(0, 0, 5, 2, 10000.00, 20000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `codigo_articulo` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `locacion` varchar(100) NOT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `id_producto`, `codigo_articulo`, `cantidad`, `locacion`, `fecha_actualizacion`) VALUES
(1, 1, 'CHB-001', 50, 'Almacén Principal', '2025-10-28 22:07:06'),
(2, 2, 'BCB-001', 35, 'Almacén Principal', '2025-10-28 22:07:06'),
(3, 3, 'HTD-001', 40, 'Almacén Principal', '2025-10-28 22:07:06'),
(4, 4, 'PAP-001', 99, 'Almacén Principal', '2025-10-28 22:10:28'),
(5, 5, 'DDQ-001', 58, 'Almacén Principal', '2025-10-28 22:10:28'),
(6, 6, 'CMB-001', 19, 'Almacén Principal', '2025-10-28 22:10:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `direccion_entrega` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado_pedido` enum('Pendiente','En Preparacion','En Camino','Entregado','Cancelado') DEFAULT 'Pendiente',
  `metodo_pago` varchar(50) DEFAULT 'Efectivo',
  `estado_pago` enum('Pendiente','Procesando','Completado','Fallido') DEFAULT 'Pendiente',
  `transaccion_pago` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_entrega` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_user`, `nombre_cliente`, `telefono_cliente`, `direccion_entrega`, `total`, `estado_pedido`, `metodo_pago`, `estado_pago`, `transaccion_pago`, `fecha_creacion`, `fecha_entrega`) VALUES
(0, 1, 'nombre', '232', 'calle 2 7 11', 83000.00, 'Pendiente', 'Efectivo', 'Pendiente', NULL, '2025-10-28 22:10:28', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `categoria`, `imagen`, `estado`, `fecha_creacion`) VALUES
(1, 'Cheese Burger', 'Deliciosa hamburguesa con queso cheddar', 15000.00, 'Hamburguesas Gourmet', 'cheese-burger.jpg', 1, '2025-10-28 22:07:06'),
(2, 'Bacon Burger', 'Hamburguesa con tocino crujiente', 18000.00, 'Hamburguesas Gourmet', 'bacon-burger.jpg', 1, '2025-10-28 22:07:06'),
(3, 'Hot Dog Clásico', 'Perro caliente tradicional', 12000.00, 'Perros', 'hotdog.jpg', 1, '2025-10-28 22:07:06'),
(4, 'Papas Fritas', 'Porción de papas crujientes', 8000.00, 'Acompañantes', 'papas.jpg', 1, '2025-10-28 22:07:06'),
(5, 'Dedos de Queso', 'Dedos de mozzarella empanizados', 10000.00, 'Entradas', 'dedos-queso.jpg', 1, '2025-10-28 22:07:06'),
(6, 'Combo Familiar', 'Incluye 4 hamburguesas y papas', 55000.00, 'Big Combos', 'combo.jpg', 1, '2025-10-28 22:07:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `apellido_usuario` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `nombre_usuario`, `apellido_usuario`, `email`, `contrasena`, `rol`, `telefono`, `direccion`, `fecha_registro`) VALUES
(1, 'Giovany', 'Quiroz', 'gio@gmail.com', '123', 1, '', '', '2025-10-28 22:12:27'),
(2, 'Norma', 'Chica', 'norma@gmail.com', '321', 1, '', '', '2025-10-28 22:12:27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
