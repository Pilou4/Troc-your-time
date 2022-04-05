// GÉOLOCALISATION
import Places from 'places.js';

let inputAddress = document.querySelector('#profile_address');

if (inputAddress) {
    let place = Places({
        container: inputAddress
    })
    place.on('change', e => {
        // console.log(e.suggestion);
        document.querySelector('#profile_street').value = e.suggestion.name
        document.querySelector('#profile_city').value = e.suggestion.city
        document.querySelector('#profile_zipcode').value = e.suggestion.postcode
        document.querySelector('#profile_lat').value = e.suggestion.latlng.lat
        document.querySelector('#profile_lng').value = e.suggestion.latlng.lng
    })
}

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import './js/carousel'

// any CSS you import will output into a single css file (app.css in this case)
import './styles/styles.scss';
import './styles/admin.scss';

// start the Stimulus application
import './bootstrap';
