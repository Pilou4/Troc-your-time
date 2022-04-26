let closeButton = document.querySelector('#message-close');

// if (closeButton) {
//     let openButton = document.querySelector('#message-open');
//     let nav = document.querySelector('.message__nav');
//     let main = document.querySelector('.message__content');
//     closeButton.addEventListener('click', () => {
//         nav.style.transform = 'translateX(-250px)';
//         main.style.transform = 'translateX(-250px)';
//         openButton.style.transition = '3s';
//         openButton.style.opacity = 1;
//         openButton.style.cursor = 'pointer';
//     });
//     openButton.addEventListener('click', () => {
//         nav.style.transform = 'translateX(0px)';
//         main.style.transform = 'translateX(0px)';
//         openButton.style.transition = '1s';
//         openButton.style.opacity = 0;
//         openButton.style.cursor = "grab";
//     });
// }
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
            openButton.style.transition = '3s';
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
            openButton.style.transition = '1s';
            openButton.style.opacity = 0;
            openButton.style.cursor = "grab";
            sidebarOpened = true;
            main.classList.remove('width--is_open');
        });    
    })()
}
// button.on('click', function (evt) {
    //     evt.preventDefault();
    //     evt.stopPropagation();
    //     sidebarOpened = true;
    //     $('body').toggleClass('with--sidebar')
    // })

    // document.body.addEventListener('click', function () {
    //     if (sidebarOpened) {
    //         document.body.classList.remove('with--sidebar')
    //         header.style.opacity = "100%";
    //         links.forEach(link => {
    //             link.style.color = "white";
    //         });
    //     }
    // })