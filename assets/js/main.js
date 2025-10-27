// Función para abrir el pop-up
function openLoginPopUp() {
    document.querySelector('.bg-opacity').classList.add('active');
    document.querySelector('.popUp').classList.add('active');
    let body = document.querySelector('body');
    if (".popUp.active") {
        body.style.overflow = 'hidden';
    }

}
// Evento click para abrir el pop-up
let loginBtn = document.getElementById('goLogin');
loginBtn.addEventListener('click', openLoginPopUp);

// Funcion para cerrar el pop-up
function closeLoginPopUp() {
    document.querySelector('.bg-opacity').classList.remove('active');
    document.querySelector('.popUp').classList.remove('active');
    let body = document.querySelector('body');
    if (".popUp.active") {
        body.style.overflowY = 'visible';
    }
}

// Validar si el elemento carrusel !existe¡ en el DOM y dar Manejo de tiempo carousel
const myCarouselElement = document.querySelector('#carouselExampleSlidesOnly')
if (myCarouselElement) {
    const carousel = new bootstrap.Carousel(myCarouselElement, {
        interval: 1500,
        touch: false
    })
}

// Efecto parallax hamburguer
window.addEventListener('scroll', function () {
    const parallax = document.querySelector('#burguerFloat');
    let scrollPosition = window.pageYOffset;
    parallax.style.transform = 'translateX(' + scrollPosition * 0.3 + 'px) rotate(11deg)';
});

// Efecto parallax titulo principal del nombre del resturante
window.addEventListener('scroll', function () {
    const parallax = document.querySelector('#myBrand');
    let scrollPosition = window.pageYOffset;
    parallax.style.transform = 'translateX(' + scrollPosition * -0.3 + 'px)';
});

// Funcion para agregar la clase active a la opción seleccionada y quitarlo de los demas
let optionSideMenu = document.getElementsByClassName("option-side-menu");
console.log(optionSideMenu)
Array.from(optionSideMenu).forEach((item) => {
    item.addEventListener("click", () => {
        Array.from(optionSideMenu).forEach((el) => el.classList.remove("active"));
        item.classList.add("active");
    })
})
