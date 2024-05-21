
document.addEventListener('DOMContentLoaded', function() {
    const contactButtons = document.querySelectorAll('.open-contact-modal');
    const overlay = document.getElementById('contact-popup');
    const popup = overlay.querySelector('.popup');
    const closeButtons = document.querySelectorAll('.close-modal');
    const refPhotoField = document.getElementById('ref-photo-field');

    const toggleModal = () => {
        const isOpen = overlay.classList.contains('open');
        overlay.classList.toggle('animate-zoom-out', isOpen);
        overlay.classList.toggle('animate-zoom-in', !isOpen);
        overlay.classList.toggle('open');
        // Mettre à jour aria-hidden pour l'accessibilité
        overlay.setAttribute('aria-hidden', String(!isOpen));
        // Gestion du focus pour l'accessibilité
        if (!isOpen) {
            popup.setAttribute('tabindex', '-1');
            popup.focus();
        } else {
            popup.removeAttribute('tabindex');
        }
    };

    contactButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Récupérer la référence de la photo à partir de l'attribut data-reference
            const reference = this.getAttribute('data-reference');
            // Pré-remplir le champ "REF. PHOTO" dans le formulaire de contact
            if (refPhotoField) {
                refPhotoField.value = reference;
            }
            toggleModal();
        });
    });

    closeButtons.forEach(button => button.addEventListener('click', toggleModal));

    // Ajouter l'écouteur d'événements pour fermer la modal lorsqu'on clique en dehors de celle-ci
    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) {
            toggleModal();
        }
    });

    // Ajouter l'écouteur pour les touches du clavier
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && overlay.classList.contains('open')) {
            toggleModal();
        }
    });
});

document.addEventListener('DOMContentLoaded', function initialize() {
    // Déclaration des constantes et variables
    const categoryDropdown = document.getElementById('category-dropdown');
    const formatDropdown = document.getElementById('format-dropdown');
    const dateDropdown = document.getElementById('date-dropdown');
    const loadMoreButton = document.getElementById('load-more-button');
    let selectedCategory = '';
    let selectedFormat = '';
    let selectedDate = '';
    let page = 2;
    let maxphotos = 0;

    // Fonction de changement de filtre
    function handleFilterChange(selectedValue, dropdownType) {
        switch (dropdownType) {
            case 'category':
                selectedCategory = selectedValue;
                break;
            case 'format':
                selectedFormat = selectedValue;
                break;
            case 'date':
                selectedDate = selectedValue;
                break;
        }
        
        page = 2;
        loadPhotosByCategoryAndFormat();
    }

    // Fonction de chargement des photos en fonction des filtres
    function loadPhotosByCategoryAndFormat() {
        let formData = new FormData();
        formData.append('action', 'world');
        formData.append('category', selectedCategory);
        formData.append('format', selectedFormat);
        formData.append('date', selectedDate);
        fetch(ajax_motaphoto.ajax_url, {
            method: 'POST',           
            body: formData,
        })
        .then(response => response.json())
        .then(handleFilterSuccess)
        .catch(handleFilterError);
    }

    // Fonction de gestion du succès du chargement des photos
    function handleFilterSuccess(response) {
        if (response.success && response.data) {
            const newHtml = response.data;
            const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
            photoListContainer.innerHTML = newHtml.trim() !== '' ? newHtml : console.error('La réponse Ajax a renvoyé un contenu vide.');
            attachLightboxEvents(); // Réattache les événements lightbox après le chargement
        } else {
            handleFilterError(response.data);
        }
    }

    // Fonction de gestion des erreurs de chargement des photos
    function handleFilterError(error) {
        console.error('Erreur lors du chargement des photos :', error && error.message ? error.message : 'Erreur inconnue');
    }

    // Fonction de chargement supplémentaire de photos
    function handleLoadPhotos() {
        maxphotos = maxphotos + 8;
        let formData = new FormData();
        formData.append('action', 'world');
        formData.append('category', selectedCategory);
        formData.append('format', selectedFormat);
        formData.append('date', selectedDate);
        formData.append('page', page);
        formData.append('offset', maxphotos);
        
        fetch(ajax_motaphoto.ajax_url, {
            method: 'POST',           
            body: formData,
        })
        .then(response => response.json())
        .then(handleLoadMoreSuccess)
        .catch(handleLoadMoreError);
    }

    // Fonction de gestion du succès du chargement supplémentaire de photos
    function handleLoadMoreSuccess(response) {
        if (response.success && response.data) {
            const newHtml = response.data;
            const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
            photoListContainer.innerHTML += newHtml.trim() !== '' ? newHtml : console.error('La réponse Ajax a renvoyé un contenu vide.');
            page++;
            attachLightboxEvents(); // Réattache les événements lightbox après le chargement
        } else {
            handleLoadMoreError(response.data);
        }
    }

    // Fonction de gestion des erreurs du chargement supplémentaire de photos
    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement supplémentaire de photos :', error && error.message ? error.message : 'Erreur inconnue');
    }

    // Fonction pour gérer les états spécifiques de l'élément sélectionné
    function handleDropdownItemStates(dropdownList, dropdownType) {
        const dropdownToggle = dropdownList.parentElement.querySelector('.dropdown-toggle');

        dropdownList.querySelectorAll('li').forEach(item => {
            item.addEventListener('click', function() {
                const selectedValue = item.getAttribute('data-value');
                const selectedLabel = item.getAttribute('data-label');
                dropdownToggle.textContent = selectedLabel;
                handleFilterChange(selectedValue, dropdownType);
                dropdownList.querySelectorAll('li').forEach(item => {
                    item.classList.remove('selected');
                });
                item.classList.add('selected');
                dropdownList.classList.remove('show');
            });
        });
    }

    // Fonction pour créer les listes déroulantes personnalisées
    function createCustomDropdown(dropdownElement, dropdownType) {
        const dropdownToggle = dropdownElement.querySelector('.dropdown-toggle');
        const dropdownList = dropdownElement.querySelector('.dropdown-list');

        dropdownToggle.addEventListener('click', function() {
            dropdownList.classList.toggle('show');
        });

        handleDropdownItemStates(dropdownList, dropdownType);

        document.addEventListener('click', function(e) {
            if (!dropdownElement.contains(e.target)) {
                dropdownList.classList.remove('show');
            }
        });
    }

    // Fonction pour ajouter les écouteurs d'événements de survol aux éléments de la liste des photos
    function addHoverEffectToPhotos() {
        document.querySelectorAll('.photo-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.classList.add('hovered');
            });

            item.addEventListener('mouseleave', function() {
                this.classList.remove('hovered');
            });
        });
    }

    // Fonction pour attacher les événements de la lightbox aux éléments fullscreen-icon
    function attachLightboxEvents() {
        const photoTrigger = document.querySelectorAll('.fullscreen-icon');
        const lightboxOverlay = document.getElementById('lightbox-overlay');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxRef = document.getElementById('lightbox-reference');
        const lightboxCategorie = document.getElementById('lightbox-category');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCloseButton = document.getElementById('lightbox-close');
        let currentPhotoIndex = 0;
        let photoItems = document.querySelectorAll('.photo-item'); // Déclarer photoItems ici

        if (!photoTrigger.length || !lightboxPrev || !lightboxNext || !lightboxCloseButton || !lightboxOverlay) {
            console.error('One or more necessary elements are missing.');
            return;
        }

        lightboxCloseButton.addEventListener('click', () => {
            lightboxOverlay.style.display = 'none';
        });

        photoTrigger.forEach((photoItem, index) => {
            photoItem.addEventListener('click', (event) => {
                event.preventDefault(); // Empêche la redirection
                openLightbox(index);
            });
        });

        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);

        document.addEventListener('keyup', function(event) {
            if (event.key === "Escape") {
                lightboxOverlay.style.display = 'none';
            }
        });

        function openLightbox(index) {
            const img = photoItems[index].querySelector('img');
            lightboxImage.src = img.src;
            lightboxRef.textContent = photoItems[index].querySelector('.photo-reference').textContent;
            lightboxCategorie.textContent = photoItems[index].querySelector('.photo-category').textContent;
            lightboxOverlay.style.display = 'block';
            currentPhotoIndex = index;
        }

        function showPrevPhoto() {
            if (currentPhotoIndex > 0) {
                openLightbox(--currentPhotoIndex);
            }
        }

        function showNextPhoto() {
            if (currentPhotoIndex < photoItems.length - 1) {
                openLightbox(++currentPhotoIndex);
            }
        }
    }

    // Ajouter les écouteurs d'événements de survol aux photos initiales
    addHoverEffectToPhotos();

    // Créer les dropdowns personnalisés
    if (categoryDropdown) createCustomDropdown(categoryDropdown, 'category');
    if (formatDropdown) createCustomDropdown(formatDropdown, 'format');
    if (dateDropdown) createCustomDropdown(dateDropdown, 'date');

    // Ajouter un écouteur d'événement pour le bouton "Charger plus"
    if (loadMoreButton) loadMoreButton.addEventListener('click', handleLoadPhotos);

    // Attacher les événements de la lightbox initialement
    attachLightboxEvents();
});



// code pour la section single-photo

// Cible la flèche précédente
const prevArrow = document.querySelector('.post-navigation__previous-arrow');
if (prevArrow) {
    // Gestionnaire d'événements pour le survol de la souris
    prevArrow.addEventListener('mouseenter', function() {
        // Change l'opacité lors du survol
        const prevThumbnail = document.querySelector('.post-navigation__previous-thumbnail');
        if (prevThumbnail) {
            prevThumbnail.style.opacity = '1';
        }
    });

    // Gestionnaire d'événements pour le départ de la souris
    prevArrow.addEventListener('mouseleave', function() {
        // Remet l'opacité à 0 lorsque la souris quitte
        const prevThumbnail = document.querySelector('.post-navigation__previous-thumbnail');
        if (prevThumbnail) {
            prevThumbnail.style.opacity = '0';
        }
    });
}

// Cible la flèche suivante
const nextArrow = document.querySelector('.post-navigation__next-arrow');
if (nextArrow) {
    // Gestionnaire d'événements pour le survol de la souris
    nextArrow.addEventListener('mouseenter', function() {
        // Change l'opacité lors du survol
        const nextThumbnail = document.querySelector('.post-navigation__next-thumbnail');
        if (nextThumbnail) {
            nextThumbnail.style.opacity = '1';
        }
    });

    // Gestionnaire d'événements pour le départ de la souris
    nextArrow.addEventListener('mouseleave', function() {
        // Remet l'opacité à 0 lorsque la souris quitte
        const nextThumbnail = document.querySelector('.post-navigation__next-thumbnail');
        if (nextThumbnail) {
            nextThumbnail.style.opacity = '0';
        }
    });
}
// code pour le menu mobile
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menuClose = document.querySelector('.menu-close');
    const primaryMenu = document.getElementById('primary-menu');

    function toggleMenu(expanded) {
        menuToggle.setAttribute('aria-expanded', expanded);
        menuClose.setAttribute('aria-expanded', expanded);
        primaryMenu.classList.toggle('show', expanded);
        menuClose.style.display = expanded ? 'block' : 'none';
        menuToggle.style.display = expanded ? 'none' : 'block';
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            toggleMenu(!expanded);
        });
    }

    if (menuClose) {
        menuClose.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            toggleMenu(!expanded);
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) { // Breakpoint pour passer en version desktop
            menuClose.style.display = 'none';
            menuToggle.style.display = 'none';
            primaryMenu.classList.remove('show');
        } else {
            menuToggle.style.display = 'block';
            menuClose.style.display = 'none';
            primaryMenu.classList.remove('show');
        }
    });

    // Vérifiez la taille de la fenêtre au chargement de la page
    if (window.innerWidth >= 768) {
        menuToggle.style.display = 'none';
        menuClose.style.display = 'none';
        primaryMenu.classList.remove('show');
    } else {
        menuToggle.style.display = 'block';
        menuClose.style.display = 'none';
        primaryMenu.classList.remove('show');
    }
});



