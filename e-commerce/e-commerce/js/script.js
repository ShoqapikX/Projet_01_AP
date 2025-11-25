/**
 * Script JavaScript principal pour le site e-commerce Nike Basketball
 * 
 * Fonctionnalités :
 * - Slider automatique de présentation
 * - Changement d'image des produits au survol (hover effect)
 * - Menu déroulant du compte utilisateur
 * - Modal du panier
 * - Recherche AJAX en temps réel avec suggestions
 * 
 * @package E-Commerce
 * @version 1.0.0
 */

// ============================================
// SLIDER DE PRÉSENTATION
// ============================================

let slideIndex = 0;
const slides = document.querySelectorAll(".slide");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");

/**
 * Affiche la slide à l'index spécifié
 * @param {number} index - Index de la slide à afficher (0-based)
 */
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = (i === index) ? 'block' : 'none';
    });
}

/**
 * Passe à la slide suivante (avec rotation circulaire)
 */
function nextSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    showSlide(slideIndex);
}

/**
 * Retourne à la slide précédente (avec rotation circulaire)
 */
function prevSlide() {
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    showSlide(slideIndex);
}

// Événements des boutons de navigation
prev.addEventListener('click', prevSlide);
next.addEventListener('click', nextSlide);

// Initialisation du slider
showSlide(slideIndex);

// Rotation automatique toutes les 3 secondes
setInterval(nextSlide, 3000);

// ============================================
// EFFET HOVER SUR LES IMAGES DE PRODUITS
// ============================================

const productImages = document.querySelectorAll('.product-image');

productImages.forEach((image) => {
    // Stocke l'URL de l'image par défaut
    const originalSrc = image.src;
    
    // Récupère l'URL de l'image au survol depuis l'attribut data-hover
    const hoverSrc = image.getAttribute('data-hover');

    // Change l'image au survol de la souris
    image.addEventListener('mouseover', () => {
        image.src = hoverSrc;
    });

    // Restaure l'image originale quand la souris quitte
    image.addEventListener('mouseout', () => {
        image.src = originalSrc;
    });
});

// ============================================
// MENU DÉROULANT DU COMPTE UTILISATEUR
// ============================================

document.getElementById('account-icon').addEventListener('click', function (event) {
    var dropdown = document.getElementById('account-dropdown');
    dropdown.classList.toggle('show'); // Ajouter/supprimer la classe 'show'
    event.stopPropagation(); // Empêcher la propagation de l'événement
});

// Fermer le menu si l'utilisateur clique en dehors
window.addEventListener('click', function (e) {
    var dropdown = document.getElementById('account-dropdown');
    if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

// ============================================
// MODAL DU PANIER
// ============================================

const cartIcon = document.querySelector('.cart');
const cartModal = document.getElementById('cart-modal');
const closePopup = document.querySelector('.close-popup');

/**
 * Affiche le popup du panier
 */
function afficherpop(){
    cartModal.style.display = 'block';
}

// Ouvrir le panier au clic sur l'icône
cartIcon.addEventListener('click', afficherpop);

// Fermer le panier au clic sur le bouton de fermeture
closePopup.addEventListener('click', () => {
    cartModal.style.display = 'none';
});

// Fermer le panier si on clique en dehors du modal
window.addEventListener('click', (event) => {
    if(event.target === cartModal){
        cartModal.style.display = 'none';
    }
});

// ============================================
// RECHERCHE AJAX EN TEMPS RÉEL
// ============================================

const searchInput = document.getElementById('search-input-ajax');
const searchSuggestions = document.getElementById('search-suggestions');
let searchTimeout;

if (searchInput && searchSuggestions) {
    /**
     * Gestion de la recherche en temps réel
     * - Attend 300ms après la dernière frappe pour limiter les requêtes
     * - Affiche des suggestions avec images et prix
     * - Minimum 2 caractères requis
     */
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Effacer le timeout précédent (debouncing)
        clearTimeout(searchTimeout);
        
        // Ne rien faire si moins de 2 caractères
        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            searchSuggestions.innerHTML = '';
            return;
        }
        
        // Attendre 300ms après la dernière frappe avant de faire la recherche
        searchTimeout = setTimeout(() => {
            // Requête AJAX vers le serveur
            fetch(`./produits/recherche_ajax.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Aucun résultat trouvé
                    if (data.length === 0) {
                        searchSuggestions.innerHTML = '<div style="padding: 15px; text-align: center; color: #666;">Aucun produit trouvé</div>';
                        searchSuggestions.style.display = 'block';
                        return;
                    }
                    
                    // Construire le HTML des suggestions
                    let html = '';
                    data.forEach(produit => {
                        html += `
                            <a href="./produits/produit_details.php?id=${produit.id}" 
                               style="display: flex; align-items: center; padding: 12px; text-decoration: none; color: #333; border-bottom: 1px solid #eee; transition: background-color 0.2s;"
                               onmouseover="this.style.backgroundColor='#f5f5f5'" 
                               onmouseout="this.style.backgroundColor='white'">
                                <img src="${produit.image_url}" 
                                     alt="${produit.nom}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 12px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: bold; margin-bottom: 4px;">${produit.nom}</div>
                                    <div style="font-size: 14px; color: #666;">
                                        ${produit.marque ? produit.marque + ' - ' : ''}
                                        <span style="font-weight: bold; color: #000;">${parseFloat(produit.prix).toFixed(2)} €</span>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    
                    searchSuggestions.innerHTML = html;
                    searchSuggestions.style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                });
        }, 300); // Délai de 300ms (debounce)
    });
    
    // Fermer les suggestions si on clique en dehors
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
    
    // Réafficher les suggestions si on clique dans le champ et qu'il y a déjà du contenu
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && searchSuggestions.innerHTML !== '') {
            searchSuggestions.style.display = 'block';
        }
    });
}

