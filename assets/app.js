import Map from './js/modules/map';

Map.init();

/**
 *  Responsive HEADER
 */
const header = document.querySelector('.header');
const nav = document.querySelector('.nav');
const links = document.querySelectorAll('.header__nav__link');

// navbar responsive
(function () {
    let button = $('#header__icon')
    let sidebarOpened = false;

    button.on('click', function (evt) {
        evt.preventDefault();
        evt.stopPropagation();
        sidebarOpened = true;
        $('body').toggleClass('with--sidebar')
    })

    document.body.addEventListener('click', function () {
        if (sidebarOpened) {
            document.body.classList.remove('with--sidebar')
            header.style.opacity = "100%";
            links.forEach(link => {
                link.style.color = "white";
            });
        }
    })
})()


/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import './js/modules/places'
import './js/category';
import './js/announces';
import './js/message';
import './js/favorite';
import './js/tomSelect';
import './js/modules/carousel';
import './js/form';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/styles.scss';
import './styles/animations.scss';
import './styles/profile.scss';
import './styles/form.scss';
import './styles/admin.scss';
import './styles/announces.scss';
import './styles/responsives.scss';

// start the Stimulus application
import './bootstrap';
