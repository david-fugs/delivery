// ============================================
// MANEJO DE MODALES
// ============================================

// Función para abrir un modal específico
function openModal(modalId) {
    document.querySelector('.bg-opacity').classList.add('active');
    document.getElementById(modalId).classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Función para cerrar un modal específico
function closeModal(modalId) {
    document.querySelector('.bg-opacity').classList.remove('active');
    document.getElementById(modalId).classList.remove('active');
    document.body.style.overflow = 'visible';
}

// Función para cambiar entre modales
function switchModal(fromModalId, toModalId) {
    closeModal(fromModalId);
    setTimeout(() => openModal(toModalId), 200);
}

// Evento para abrir modal de login
const loginBtn = document.getElementById('goLogin');
if (loginBtn) {
    loginBtn.addEventListener('click', () => openModal('loginModal'));
}

// Cerrar modal al hacer click en el fondo
document.querySelector('.bg-opacity')?.addEventListener('click', function() {
    closeModal('loginModal');
    closeModal('registerModal');
    closeModal('cartModal');
    closeModal('checkoutModal');
});

// ============================================
// SISTEMA DE CARRITO DE COMPRAS
// ============================================

let cart = JSON.parse(localStorage.getItem('tinburger_cart')) || [];

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
        cartTotal.textContent = '0';
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
    cartTotal.textContent = total.toLocaleString('es-CO');
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
        
        document.getElementById('checkoutSummary').innerHTML = summaryHtml;
        document.getElementById('checkoutTotal').textContent = total.toLocaleString('es-CO');
        document.getElementById('cartData').value = JSON.stringify(cart);
        
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

// Event listeners para botones de agregar al carrito
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Botones de agregar al carrito
    document.querySelectorAll('.btn-add-cart:not(.disabled)').forEach(button => {
        button.addEventListener('click', function() {
            const productId = parseInt(this.dataset.id);
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);
            addToCart(productId, productName, productPrice);
        });
    });
    
    // Botón del carrito
    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            displayCart();
            openModal('cartModal');
        });
    }
    
    // Carrito deshabilitado para no logueados
    const cartIconDisabled = document.getElementById('cartIconDisabled');
    if (cartIconDisabled) {
        cartIconDisabled.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Debes iniciar sesión para ver el carrito');
        });
    }
});

// ============================================
// MANEJO DE FORMULARIO DE REGISTRO
// ============================================

const formRegister = document.getElementById('formRegister');
if (formRegister) {
    formRegister.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const messageDiv = document.getElementById('register-message');
            messageDiv.innerHTML = data;
            
            // Si el registro fue exitoso, cambiar a login después de 2 segundos
            if (data.includes('exitoso')) {
                setTimeout(() => {
                    switchModal('registerModal', 'loginModal');
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('register-message').innerHTML = 
                '<div class="alert alert-danger">Error en el registro</div>';
        });
    });
}

// ============================================
// EFECTOS Y ANIMACIONES
// ============================================

// Validar si el elemento carrusel existe en el DOM y dar Manejo de tiempo carousel
const myCarouselElement = document.querySelector('#carouselExampleSlidesOnly');
if (myCarouselElement) {
    const carousel = new bootstrap.Carousel(myCarouselElement, {
        interval: 1500,
        touch: false
    });
}

// Efecto parallax hamburguer
window.addEventListener('scroll', function () {
    const parallax = document.querySelector('#burguerFloat');
    if (parallax) {
        let scrollPosition = window.pageYOffset;
        parallax.style.transform = 'translateX(' + scrollPosition * 0.3 + 'px) rotate(11deg)';
    }
});

// Efecto parallax titulo principal del nombre del restaurante
window.addEventListener('scroll', function () {
    const parallax = document.querySelector('#myBrand');
    if (parallax) {
        let scrollPosition = window.pageYOffset;
        parallax.style.transform = 'translateX(' + scrollPosition * -0.3 + 'px)';
    }
});

// Función para agregar la clase active a la opción seleccionada y quitarlo de los demas
let optionSideMenu = document.getElementsByClassName("option-side-menu");
if (optionSideMenu.length > 0) {
    Array.from(optionSideMenu).forEach((item) => {
        item.addEventListener("click", () => {
            Array.from(optionSideMenu).forEach((el) => el.classList.remove("active"));
            item.classList.add("active");
        });
    });
}
