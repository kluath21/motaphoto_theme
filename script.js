document.addEventListener('DOMContentLoaded', function() {
    
    const contactLink = document.querySelector('.open-contact-modal');
    const overlay = document.getElementById('overlay');
    const closeModal = document.querySelector('.close-modal');
    // Ouvrir la modal
    contactLink.addEventListener('click', function(event) {
        event.preventDefault();
        overlay.classList.remove('hidden');
    });

    // Fermer la modal
    closeModal.addEventListener('click', function() {
        overlay.classList.add('hidden');
    });

    // Fermer la modal en cliquant sur l'overlay
    window.addEventListener('click', function(event) {
        if (event.target == overlay) {
            overlay.classList.add('hidden');
        }
    });
});
