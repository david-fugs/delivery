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

// Manejo de tiempo carousel
const myCarouselElement = document.querySelector('#carouselExampleSlidesOnly')
const carousel = new bootstrap.Carousel(myCarouselElement, {
    interval: 1500,
    touch: false
})

// Efecto parallax hamburguer
window.addEventListener('scroll', function () {
    const parallax = document.querySelector('#burguerFloat');
    let scrollPosition = window.pageYOffset;
    parallax.style.transform = 'translateX(' + scrollPosition * 0.3 + 'px) rotate(11deg)';
});