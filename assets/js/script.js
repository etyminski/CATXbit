document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('gallery_modal');
    const modalGif = document.getElementById('modalGif');
    const modalLoading = document.querySelector('.modal-loading');
    const closeModal = document.querySelector('.close-modal');
    
    // Adiciona evento de clique para TODOS os cards da galeria
    const galleryCards = document.querySelectorAll('.gallery_card');
    
    galleryCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            // Encontra a imagem dentro do card e pega o GIF do data-gif
            const imgElement = this.querySelector('img');
            const specificGif = imgElement.getAttribute('data-gif');
            
            openModal(specificGif, imgElement.alt);
        });
    });
    
    function openModal(gifPath, altText) {
        // Mostra o modal e loading
        modal.style.display = 'block';
        modalLoading.style.display = 'block';
        modalGif.style.display = 'none';
        
        // Prepara o GIF
        modalGif.onload = function() {
            // Esconde loading e mostra o GIF
            modalLoading.style.display = 'none';
            modalGif.style.display = 'block';
        };
        
        // Carrega o GIF específico do data-gif
        modalGif.src = gifPath;
        modalGif.alt = altText;
    }
    
    // Fechar modal
    closeModal.addEventListener('click', function() {
        closeModalFunction();
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunction();
        }
    });
    
    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModalFunction();
        }
    });
    
    function closeModalFunction() {
        modal.style.display = 'none';
        // Para o GIF removendo o src
        modalGif.src = '';
    }
    
});