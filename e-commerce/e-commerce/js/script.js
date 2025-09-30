let slideIndex = 0;

const slides = document.querySelectorAll(".slide");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");


function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = (i === index) ? 'block' : 'none';
    });
}


function nextSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    showSlide(slideIndex);
}

function prevSlide() {
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    showSlide(slideIndex);
}

prev.addEventListener('click', prevSlide);
next.addEventListener('click', nextSlide);

showSlide(slideIndex);

setInterval(nextSlide, 3000)


// Cela nous permet de manipuler ces images lors d'événements comme le survol de la souris.
const productImages = document.querySelectorAll('.product-image');

// Utilisation de forEach pour parcourir toutes les images des produits.
productImages.forEach((image) => {
    // Stocke l'URL de l'image actuelle (celle par défaut) dans une variable 'originalSrc'.
    const originalSrc = image.src;

    // Récupère l'attribut 'data-hover' qui contient l'URL de l'image à afficher lorsque la souris passe dessus.
    const hoverSrc = image.getAttribute('data-hover');

    // Ajout d'un événement 'mouseover' (quand la souris passe au-dessus de l'image).
    // Lorsqu'on survole l'image, elle change pour l'image définie dans 'hoverSrc'.
    image.addEventListener('mouseover', () => {
        image.src = hoverSrc;
    });

    // Ajout d'un événement 'mouseout' (quand la souris quitte l'image).
    // Lorsque la souris quitte l'image, elle revient à l'image d'origine stockée dans 'originalSrc'.
    image.addEventListener('mouseout', () => {
        image.src = originalSrc;
    });
});

// Script pour basculer l'affichage du sous-menu du compte
document.getElementById('account-icon').addEventListener('click', function (event) {
    var dropdown = document.getElementById('account-dropdown');
    dropdown.classList.toggle('show'); // Ajouter/supprimer la classe 'show'
    event.stopPropagation(); // Empêcher la propagation de l'événement de clic
});

// Fermer le sous-menu si l'utilisateur clique en dehors de celui-ci
window.addEventListener('click', function (e) {
    var dropdown = document.getElementById('account-dropdown');
    if (!dropdown.contains(e.target)) { // Vérifie si le clic est en dehors du menu déroulant
        dropdown.classList.remove('show');
    }
});

// Select the cart icon and the cart modal
const cartIcon = document.querySelector('.cart');
const cartModal = document.getElementById('cart-modal');
const closePopup = document.querySelector('.close-popup');

// Function to show the cart popup

function afficherpop(){
    cartModal.style.display = 'block';
}
cartIcon.addEventListener('click', afficherpop);

closePopup.addEventListener('click', () => {
    cartModal.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if(event.target === cartModal){
        cartModal.style.display = 'none';
    }
    
});
