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

/* Live AJAX search with suggestions */
(() => {
    const input = document.getElementById('search-input');
    const suggestions = document.getElementById('search-suggestions');
    const box = document.getElementById('search-box');
    let activeIndex = -1;
    let items = [];

    if (!input) return;

    function debounce(fn, ms) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), ms);
        };
    }

    async function fetchSuggestions(q) {
        if (!q || q.trim().length < 1) {
            renderSuggestions([]);
            return;
        }

        try {
            const res = await fetch('ajax/search.php?q=' + encodeURIComponent(q));
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            items = data;
            renderSuggestions(items);
        } catch (err) {
            console.error('Search error', err);
            renderSuggestions([]);
        }
    }

    const debouncedFetch = debounce((e) => fetchSuggestions(e.target.value), 220);

    input.addEventListener('input', debouncedFetch);

    input.addEventListener('keydown', (e) => {
        const list = suggestions.querySelectorAll('li');
        if (e.key === 'ArrowDown') {
            activeIndex = Math.min(activeIndex + 1, list.length - 1);
            updateActive(list);
            e.preventDefault();
        } else if (e.key === 'ArrowUp') {
            activeIndex = Math.max(activeIndex - 1, 0);
            updateActive(list);
            e.preventDefault();
        } else if (e.key === 'Enter') {
            if (activeIndex >= 0 && list[activeIndex]) {
                list[activeIndex].click();
                e.preventDefault();
            }
        } else if (e.key === 'Escape') {
            suggestions.innerHTML = '';
            items = [];
            activeIndex = -1;
        }
    });

    function updateActive(list) {
        list.forEach((li, i) => {
            li.setAttribute('aria-selected', i === activeIndex ? 'true' : 'false');
        });
        if (list[activeIndex]) list[activeIndex].scrollIntoView({ block: 'nearest' });
    }

    function renderSuggestions(data) {
        suggestions.innerHTML = '';
        activeIndex = -1;
        if (!data || data.length === 0) return;
        const fragment = document.createDocumentFragment();
        data.forEach(item => {
            const li = document.createElement('li');
            li.tabIndex = 0;
            li.innerHTML = `<strong>${escapeHtml(item.nom)}</strong> <span class="small-price">${Number(item.prix).toFixed(2)} €</span>`;
            li.addEventListener('click', () => {
                // Redirect to product details
                window.location.href = `produits/produit_details.php?id=${encodeURIComponent(item.id)}`;
            });
            fragment.appendChild(li);
        });
        suggestions.appendChild(fragment);
    }

    function escapeHtml(str) {
        return String(str).replace(/[&<>"'`]/g, (s) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '`': '&#96;'
        }[s]));
    }

    // Close suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!box.contains(e.target) && e.target.id !== 'search-toggle') {
            suggestions.innerHTML = '';
            box.classList.remove('open');
        }
    });

    // add focused class while typing for nicer visuals
    input.addEventListener('focus', () => {
        box.classList.add('focused');
        box.classList.add('open');
    });

    input.addEventListener('blur', () => {
        box.classList.remove('focused');
    });

    // Toggle search via icon (useful for small screens)
    const toggle = document.getElementById('search-toggle');
    if (toggle) {
        toggle.addEventListener('click', (e) => {
            // Toggle open class on the parent search-box
            box.classList.toggle('open');
            if (box.classList.contains('open')) {
                setTimeout(() => input.focus(), 80);
            } else {
                suggestions.innerHTML = '';
            }
            e.stopPropagation();
        });
    }

})();

// Visitor counter polling (home page)
(function(){
    const counterEl = document.getElementById('visitor-counter');
    if (!counterEl) return;

    async function fetchCounter(){
        try {
            const res = await fetch('ajax/visitor_counter.php?page=home');
            if (!res.ok) return;
            const data = await res.json();
            const countEl = counterEl.querySelector('.visitor-count');
            if (countEl) {
                const prev = Number(countEl.textContent) || 0;
                countEl.textContent = data.active;
                // add pulse animation class
                countEl.classList.remove('pulse');
                // force reflow to restart animation
                void countEl.offsetWidth;
                countEl.classList.add('pulse');
                // nothing else to animate — pulse handles the visual feedback
            }
        } catch (e) {
            // ignore
        }
    }

    // initial fetch and periodic polling
    fetchCounter();
    setInterval(fetchCounter, 8000);

})();
