// ============================================
// SISTEMA DE CARRITO (Importado desde main.js)
// ============================================

// cart está declarado en main.js como variable global

// Actualizar contador del carrito
function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// Agregar producto al carrito
function addToCart(productId, productName, productPrice) {
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            quantity: 1
        });
    }

    localStorage.setItem('tinburger_cart', JSON.stringify(cart));
    updateCartCount();
    showCartNotification('Producto agregado al carrito');
}

// Remover producto del carrito
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('tinburger_cart', JSON.stringify(cart));
    updateCartCount();
    displayCart();
}

// Actualizar cantidad de un producto
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            localStorage.setItem('tinburger_cart', JSON.stringify(cart));
            displayCart();
        }
    }
}

// Mostrar contenido del carrito
function displayCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="text-center">Tu carrito está vacío</p>';
        if (cartTotal) cartTotal.textContent = '0';
        return;
    }

    let total = 0;
    let html = '';

    cart.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        html += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h6>${item.name}</h6>
                    <p class="cart-item-price">$${item.price.toLocaleString('es-CO')}</p>
                </div>
                <div class="cart-item-controls">
                    <button class="btn-qty" onclick="updateQuantity(${item.id}, -1)">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="cart-item-qty">${item.quantity}</span>
                    <button class="btn-qty" onclick="updateQuantity(${item.id}, 1)">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn-remove" onclick="removeFromCart(${item.id})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="cart-item-subtotal">
                    $${subtotal.toLocaleString('es-CO')}
                </div>
            </div>
        `;
    });

    cartItemsContainer.innerHTML = html;
    if (cartTotal) cartTotal.textContent = total.toLocaleString('es-CO');
    updateCartCount();
}

// Mostrar formulario de checkout
function showCheckoutForm() {
    if (cart.length === 0) {
        alert('Tu carrito está vacío');
        return;
    }

    closeModal('cartModal');
    setTimeout(() => {
        // Llenar resumen de checkout
        let summaryHtml = '';
        let total = 0;

        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            summaryHtml += `
                <div class="checkout-item">
                    <span>${item.name} x ${item.quantity}</span>
                    <span>$${subtotal.toLocaleString('es-CO')}</span>
                </div>
            `;
        });

        const checkoutSummary = document.getElementById('checkoutSummary');
        const checkoutTotal = document.getElementById('checkoutTotal');
        const cartData = document.getElementById('cartData');
        
        if (checkoutSummary) checkoutSummary.innerHTML = summaryHtml;
        if (checkoutTotal) checkoutTotal.textContent = total.toLocaleString('es-CO');
        if (cartData) cartData.value = JSON.stringify(cart);

        openModal('checkoutModal');
    }, 200);
}

// Notificación temporal
function showCartNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Funciones de modal (importadas desde main.js)
function openModal(modalId) {
    const bgOpacity = document.querySelector('.bg-opacity');
    const modal = document.getElementById(modalId);
    if (bgOpacity) bgOpacity.classList.add('active');
    if (modal) modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const bgOpacity = document.querySelector('.bg-opacity');
    const modal = document.getElementById(modalId);
    if (bgOpacity) bgOpacity.classList.remove('active');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = 'visible';
}

// ============================================
// NAVEGACIÓN ENTRE SECCIONES DEL DASHBOARD
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambio de secciones
    const menuOptions = document.querySelectorAll('.option-side-menu');
    const sections = document.querySelectorAll('.content-section');
    
    menuOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remover clase active de todas las opciones
            menuOptions.forEach(opt => opt.classList.remove('active'));
            
            // Agregar clase active a la opción seleccionada
            this.classList.add('active');
            
            // Obtener la sección a mostrar
            const sectionName = this.getAttribute('data-section');
            
            // Ocultar todas las secciones
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Mostrar la sección seleccionada
            const targetSection = document.getElementById('section-' + sectionName);
            if (targetSection) {
                targetSection.style.display = 'block';
                
                // Si es la sección de pedidos, cargar los pedidos
                if (sectionName === 'pedidos') {
                    cargarPedidosUsuario();
                }
            }
        });
    });
    
    // Inicializar contador del carrito
    updateCartCount();
    
    // Función para inicializar event listeners de agregar al carrito
    function initAddToCartButtons() {
        const addCartButtons = document.querySelectorAll('.btn-add-cart:not(.disabled)');
        addCartButtons.forEach(button => {
            // Remover event listener anterior si existe
            button.replaceWith(button.cloneNode(true));
        });
        
        // Volver a obtener los botones después de clonarlos
        document.querySelectorAll('.btn-add-cart:not(.disabled)').forEach(button => {
            button.addEventListener('click', function() {
                const productId = parseInt(this.dataset.id);
                const productName = this.dataset.name;
                const productPrice = parseFloat(this.dataset.price);
                addToCart(productId, productName, productPrice);
            });
        });
    }
    
    // Inicializar botones al cargar
    initAddToCartButtons();
    
    // Botón del carrito en el header
    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            displayCart();
            openModal('cartModal');
        });
    }
    
    // Reinicializar botones cuando se cambie de sección
    menuOptions.forEach(option => {
        const originalClick = option.onclick;
        option.addEventListener('click', function() {
            setTimeout(() => {
                initAddToCartButtons();
            }, 100);
        });
    });
    
    // Inicializar Stripe
    initializeStripe();
    
    // Capturar ubicación del usuario
    capturarUbicacion();
});

// ============================================
// CARGAR PEDIDOS DEL USUARIO
// ============================================

function cargarPedidosUsuario() {
    fetch('get_pedidos_usuario.php')
        .then(response => response.json())
        .then(data => {
            const listaPedidos = document.getElementById('listaPedidos');
            
            if (data.error) {
                listaPedidos.innerHTML = '<p class="text-center text-danger">Error al cargar pedidos</p>';
                return;
            }
            
            if (data.pedidos.length === 0) {
                listaPedidos.innerHTML = '<p class="text-center">No tienes pedidos aún</p>';
                return;
            }
            
            let html = '<div class="pedidos-list">';
            
            data.pedidos.forEach(pedido => {
                const estadoClass = {
                    'Pendiente': 'badge-warning',
                    'En Preparacion': 'badge-info',
                    'En Camino': 'badge-primary',
                    'Entregado': 'badge-success',
                    'Cancelado': 'badge-danger'
                }[pedido.estado_pedido] || 'badge-secondary';
                
                const estadoPagoClass = {
                    'Pendiente': 'badge-warning',
                    'Procesando': 'badge-info',
                    'Completado': 'badge-success',
                    'Fallido': 'badge-danger'
                }[pedido.estado_pago] || 'badge-secondary';
                
                html += `
                    <div class="pedido-card mb-4">
                                <div class="pedido-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>Pedido #${pedido.id_pedido}</h5>
                                        <small class="text-muted">${pedido.fecha_creacion}</small>
                                    </div>
                                    <div>
                                        <span class="badge ${estadoClass}">${pedido.estado_pedido}</span>
                                        ${pedido.estado_pago && pedido.estado_pago !== 'Pendiente' ? `<span class="badge ${estadoPagoClass}">${pedido.estado_pago}</span>` : ''}
                                    </div>
                                </div>
                        <div class="pedido-body mt-3">
                            <p><strong>Dirección:</strong> ${pedido.direccion_entrega}</p>
                            <p><strong>Teléfono:</strong> ${pedido.telefono_cliente}</p>
                            <p><strong>Método de Pago:</strong> ${pedido.metodo_pago}</p>
                            <p><strong>Total:</strong> $${parseFloat(pedido.total).toLocaleString('es-CO')}</p>
                            ${pedido.latitud && pedido.longitud ? `
                                <p><strong>Ubicación:</strong> 
                                    <a href="https://www.google.com/maps?q=${pedido.latitud},${pedido.longitud}" target="_blank">
                                        Ver en mapa <i class="fa-solid fa-map-marker-alt"></i>
                                    </a>
                                </p>
                            ` : ''}
                        </div>
                        <div class="pedido-footer mt-3">
                            <button class="btn-ver-detalle" onclick="verDetallePedido(${pedido.id_pedido})">
                                Ver Detalle <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            listaPedidos.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('listaPedidos').innerHTML = 
                '<p class="text-center text-danger">Error al cargar pedidos</p>';
        });
}

// ============================================
// VER DETALLE DE PEDIDO
// ============================================

function verDetallePedido(idPedido) {
    fetch(`get_detalle_pedido_usuario.php?id=${idPedido}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error al cargar el detalle del pedido');
                return;
            }
            
            let html = `
                <div class="pedido-detalle-content">
                    <h4>Pedido #${data.pedido.id_pedido}</h4>
                    <p><strong>Cliente:</strong> ${data.pedido.nombre_cliente}</p>
                    <p><strong>Teléfono:</strong> ${data.pedido.telefono_cliente}</p>
                    <p><strong>Dirección:</strong> ${data.pedido.direccion_entrega}</p>
                    <p><strong>Fecha:</strong> ${data.pedido.fecha_creacion}</p>
                    <p><strong>Estado:</strong> <span class="badge badge-info">${data.pedido.estado_pedido}</span></p>
                    <p><strong>Estado Pago:</strong> <span class="badge badge-success">${data.pedido.estado_pago}</span></p>
                    <p><strong>Método de Pago:</strong> ${data.pedido.metodo_pago}</p>
            `;
            
            // Mostrar ubicaciones si existen
            if (data.pedido.latitud && data.pedido.longitud) {
                html += `
                    <div class="ubicaciones-section mt-4">
                        <h5><i class="fa-solid fa-map-marker-alt"></i> Ubicaciones</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="ubicacion-card">
                                    <h6><i class="fa-solid fa-user text-success"></i> Tu Ubicación</h6>
                                    <p class="mb-2">Ubicación al realizar el pedido</p>
                                    <p class="mb-2"><small><strong>Coordenadas:</strong><br>
                                    Lat: ${data.pedido.latitud}, Lng: ${data.pedido.longitud}</small></p>
                                    <a href="https://www.google.com/maps?q=${data.pedido.latitud},${data.pedido.longitud}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-map"></i> Ver en Google Maps
                                    </a>
                                </div>
                            </div>
                `;
                
                if (data.pedido.latitud_repartidor && data.pedido.longitud_repartidor) {
                    html += `
                            <div class="col-md-6 mb-3">
                                <div class="ubicacion-card">
                                    <h6><i class="fa-solid fa-motorcycle text-primary"></i> Ubicación del Repartidor</h6>
                                    <p class="mb-2">Ubicación al marcar "En Camino"</p>
                                    <p class="mb-2"><small><strong>Coordenadas:</strong><br>
                                    Lat: ${data.pedido.latitud_repartidor}, Lng: ${data.pedido.longitud_repartidor}</small></p>
                    `;
                    
                    if (data.pedido.fecha_en_camino) {
                        html += `<p class="mb-2"><small><strong>Fecha:</strong> ${data.pedido.fecha_en_camino}</small></p>`;
                    }
                    
                    html += `
                                    <a href="https://www.google.com/maps?q=${data.pedido.latitud_repartidor},${data.pedido.longitud_repartidor}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-map"></i> Ver en Google Maps
                                    </a>
                                </div>
                            </div>
                    `;
                }
                
                html += `
                        </div>
                    </div>
                `;
            }
            
            html += `
                    <h5 class="mt-4">Productos:</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.detalles.forEach(detalle => {
                html += `
                    <tr>
                        <td>${detalle.nombre_producto}</td>
                        <td>${detalle.cantidad}</td>
                        <td>$${parseFloat(detalle.precio_unitario).toLocaleString('es-CO')}</td>
                        <td>$${parseFloat(detalle.subtotal).toLocaleString('es-CO')}</td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>$${parseFloat(data.pedido.total).toLocaleString('es-CO')}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            
            document.getElementById('pedidoDetalleContent').innerHTML = html;
            openModal('pedidoDetalleModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el detalle del pedido');
        });
}

// ============================================
// INTEGRACIÓN DE STRIPE (VERSIÓN DIDÁCTICA)
// ============================================

let stripe;
let elements;
let cardElement;

function initializeStripe() {
    // SIMULACIÓN DE PAGO CON TARJETA - Sistema didáctico
    console.log('Sistema de pago con tarjeta (simulación didáctica)');
    
    // Manejar cambio de método de pago
    const paymentOptions = document.querySelectorAll('input[name="metodo_pago"]');
    const cardSection = document.getElementById('cardPaymentSection');
    
    if (paymentOptions && cardSection) {
        paymentOptions.forEach(option => {
            option.addEventListener('change', function() {
                if (this.value === 'Tarjeta') {
                    cardSection.style.display = 'block';
                } else {
                    cardSection.style.display = 'none';
                }
            });
        });
    }
    
    // Formatear número de tarjeta automáticamente
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // Formatear fecha de expiración
    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    }
    
    // Validar solo números en CVV
    const cardCvvInput = document.getElementById('cardCvv');
    if (cardCvvInput) {
        cardCvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
    
    // Interceptar envío del formulario
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked')?.value;
            
            if (metodoPago === 'Tarjeta') {
                // Validar campos de tarjeta
                const cardNumber = document.getElementById('cardNumber')?.value;
                const cardExpiry = document.getElementById('cardExpiry')?.value;
                const cardCvv = document.getElementById('cardCvv')?.value;
                const cardName = document.getElementById('cardName')?.value;
                
                if (!cardNumber || !cardExpiry || !cardCvv || !cardName) {
                    e.preventDefault();
                    alert('Por favor completa todos los datos de la tarjeta');
                    return false;
                }
                
                // Simulación exitosa
                console.log('Pago con tarjeta simulado correctamente');
                // El formulario continuará con el envío normal
            }
        });
    }
}

// ============================================
// CAPTURA DE UBICACIÓN DEL USUARIO
// ============================================

function capturarUbicacion() {
    if (!navigator.geolocation) {
        console.log('Geolocalización no soportada');
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const latitud = position.coords.latitude;
            const longitud = position.coords.longitude;
            
            // Guardar en campos ocultos para enviar con el pedido
            document.getElementById('latitudInput').value = latitud;
            document.getElementById('longitudInput').value = longitud;
            
            console.log('Ubicación capturada:', latitud, longitud);
        },
        function(error) {
            console.log('Error obteniendo ubicación:', error.message);
        }
    );
}
