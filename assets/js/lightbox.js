// ce script permet d'afficher une image en grand format lorsqu'on clique dessus
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const photoTrigger = document.querySelectorAll('.fullscreen-icon');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCloseButton = document.getElementById('lightbox-close');
        const lightboxOverlay = document.getElementById('lightbox-overlay');

        let currentPhotoIndex = 0;
// on vérifie si les éléments nécessaires sont bien présents dans le DOM
        if (!photoTrigger.length || !lightboxPrev || !lightboxNext || !lightboxCloseButton || !lightboxOverlay) {
            console.error('One or more necessary elements are missing.');
            return;
        }
// on ajoute un écouteur d'événement sur chaque élément de la galerie
        lightboxCloseButton.addEventListener('click', () => {
            lightboxOverlay.style.display = 'none';
        });

        photoTrigger.forEach((photoItem, index) => {
            photoItem.addEventListener('click', () => {
                openLightbox(index);
            });
        });

        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);

        document.addEventListener('keyup', function(event) { // on ajoute un écouteur d'événement sur la touche "echap" pour fermer la lightbox
            if (event.key === "Escape") {
                lightboxOverlay.style.display = 'none';
            }
        });
// on crée une fonction pour ouvrir la lightbox
        function openLightbox(index) { // on récupère l'index de l'image cliquée
            const photoItems = document.querySelectorAll('.photo-item');

            const img = photoItems[index].querySelector('img'); // on récupère l'élément img de l'image cliquée
            const lightboxImage = document.getElementById('lightbox-image'); // on récupère l'élément img de la lightbox
            const lightboxRef = document.getElementById('lightbox-reference'); // on récupère l'élément reference de la lightbox
            const lightboxCategorie = document.getElementById('lightbox-category'); // on récupère l'élément category de la lightbox

            if (!lightboxRef || !lightboxCategorie) { // on vérifie si les éléments nécessaires sont bien présents dans le DOM
                console.error('Reference or category element not found in the DOM');
                return;
            }

            lightboxImage.src = img.src;
            lightboxRef.textContent = photoItems[index].querySelector('.photo-reference').textContent;
            lightboxCategorie.textContent = photoItems[index].querySelector('.photo-category').textContent;
            document.getElementById('lightbox-overlay').style.display = 'block';
            currentPhotoIndex = index;
        }

        function showPrevPhoto() { // on crée une fonction pour afficher l'image précédente
            if (currentPhotoIndex > 0) {
                openLightbox(--currentPhotoIndex);
            }
        }

        function showNextPhoto() { // on crée une fonction pour afficher l'image suivante
            if (currentPhotoIndex < photoTrigger.length - 1) {
                openLightbox(++currentPhotoIndex);
            }
        }
    });
})();
