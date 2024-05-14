
document.addEventListener('DOMContentLoaded', function() {
    const contactButtons = document.querySelectorAll('.open-contact-modal');
    const overlay = document.querySelector('.overlay');
    const popup = document.querySelector('.popup');
    const closeButtons = document.querySelectorAll('.close-modal');

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

    contactButtons.forEach(button => button.addEventListener('click', toggleModal));
    closeButtons.forEach(button => button.addEventListener('click', toggleModal));

    // Ajouter l'écouteur d'événements uniquement si la modal est ouverte
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




// code pour la section filter
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
        // console.log(response);
        if (response.success && response.data) {
            const newHtml = response.data;
            const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
            photoListContainer.innerHTML = newHtml.trim() !== '' ? newHtml : console.error('La réponse Ajax a renvoyé un contenu vide.');
            addHoverEffectToPhotos();
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
            addHoverEffectToPhotos();
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

    // Fonction pour créer les listes déroulantes personnalisés
    function createCustomDropdown(dropdownElement, dropdownType) { // dropdownType est une chaîne de caractères pour identifier le type de dropdown
        const dropdownToggle = dropdownElement.querySelector('.dropdown-toggle'); // Récupère le bouton de bascule
        const dropdownList = dropdownElement.querySelector('.dropdown-list'); // Récupère la liste déroulante

        dropdownToggle.addEventListener('click', function() { // Ajoute un écouteur d'événement pour le clic sur le bouton de bascule
            dropdownList.classList.toggle('show'); // Bascule la classe "show" pour afficher ou masquer la liste déroulante
        });

        handleDropdownItemStates(dropdownList, dropdownType); // Appelle la fonction pour gérer les états des éléments de la liste déroulante

        document.addEventListener('click', function(e) { // Ajoute un écouteur d'événement pour fermer la liste déroulante lors du clic en dehors de celle-ci
            if (!dropdownElement.contains(e.target)) { // Vérifie si l'élément cliqué est en dehors de la liste déroulante
                dropdownList.classList.remove('show'); // Masque la liste déroulante
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

    // Ajouter les écouteurs d'événements de survol aux photos initiales
    addHoverEffectToPhotos();

    // Créer les dropdowns personnalisés
    createCustomDropdown(categoryDropdown, 'category');
    createCustomDropdown(formatDropdown, 'format');
    createCustomDropdown(dateDropdown, 'date');

    // Ajouter un écouteur d'événement pour le bouton "Charger plus"
    loadMoreButton.addEventListener('click', handleLoadPhotos);
});

// jQuery(document).ready(function($) {
//     $('#date-dropdown .dropdown-list li').on('click', function() {
//         var selectedValue = $(this).data('value'); // Récupère la valeur sélectionnée

//         $.ajax_motaphoto({
//             url: ajax_url, // Assurez-vous que ajaxurl est défini correctement
//             type: 'POST',
//             data: {
//                 action: 'load_photos_by_date', // action hook pour le backend
//                 date_filter: selectedValue // la valeur de filtrage sélectionnée
//             },
//             success: function(response) {
//                 $('#some-container').html(response); // Mettez à jour le contenu avec la réponse
//             }
//         });
//     });
// });


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

// Menu burger
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const siteNavigation = document.getElementById('site-navigation');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            siteNavigation.style.display = expanded ? 'none' : 'block';
        });
    }
});