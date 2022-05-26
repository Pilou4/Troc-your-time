let closeButton = document.querySelector('#message-close');

/**
 * Gestion de la nav pour les pages concernant les MESSAGES
 */
if (closeButton) {
    let closeButton = document.querySelector('#message-close');
    (function () {    
        let content = document.querySelector('#messagerie');
        let sidebarOpened = true;
        let openButton = document.querySelector('#message-open');
        let nav = document.querySelector('.message__nav');
        let main = document.querySelector('.message__contents');
        closeButton.addEventListener('click', (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            nav.style.transform = 'translateX(-250px)';
            main.style.transform = 'translateX(0px)';
            openButton.style.transition = 'opacity 1.5s';
            openButton.style.opacity = 1;
            openButton.style.cursor = 'pointer';
            sidebarOpened = false;
            main.classList.add('width--is_open');
        });
        openButton.addEventListener('click', (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            nav.style.transform = 'translateX(0px)';
            main.style.transform = 'translateX(250px)';
            openButton.style.transition = 'opacity 1s';
            openButton.style.opacity = 0;
            openButton.style.cursor = "grab";
            sidebarOpened = true;
            main.classList.remove('width--is_open');
        });    
    })()
}