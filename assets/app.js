// GÉOLOCALISATION
import Places from 'places.js';
import Map from './js/modules/map';

Map.init();

let category = document.querySelector('#announcement_category');

if (category !== null) {
    category.addEventListener('change', function () {
        /* closest function permettant de récupérer la balise HTML la plus proche */
        let form = this.closest('form');
        let data = this.name + "=" + this.value;
        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded;charset: UTF-8"
            }
        })
        .then(response => response.text())
        .then(html => {
            let content = document.createElement("html");
            content.innerHTML = html;
            let newSelect = content.querySelector("#announcement_subCategory");
            document.querySelector("#announcement_subCategory").replaceWith(newSelect);
        })
        .catch(error => console.log(error))
    });
}

let profileAddress = document.querySelector('#profile_address');
if (profileAddress) {
    let place = Places({
        container: profileAddress
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#profile_zipcode').value = e.suggestion.postcode
        document.querySelector('#profile_city').value = e.suggestion.city
        document.querySelector('#profile_street').value = e.suggestion.name
        document.querySelector('#profile_department').value = e.suggestion.county
        document.querySelector('#profile_region').value = e.suggestion.administrative
        document.querySelector('#profile_lat').value = e.suggestion.latlng.lat
        document.querySelector('#profile_lng').value = e.suggestion.latlng.lng
    })
}

let inputAddress = document.querySelector('#announcement_address');
if (inputAddress) {
    let place = Places({
        container: inputAddress
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#announcement_zipcode').value = e.suggestion.postcode
        document.querySelector('#announcement_city').value = e.suggestion.name
        document.querySelector('#announcement_department').value = e.suggestion.county
        document.querySelector('#announcement_region').value = e.suggestion.administrative
        document.querySelector('#announcement_lat').value = e.suggestion.latlng.lat
        document.querySelector('#announcement_lng').value = e.suggestion.latlng.lng
    })
}

let searchAddress = document.querySelector('#search_address')
if (searchAddress !== null) {
    let place = Places({
        container: searchAddress
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
}

let searchAddressProfile = document.querySelector('#search_address_profile')
if (searchAddressProfile !== null) {
    let place = Places({
        container: searchAddressProfile
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
}
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import './js/formCategory';
import './js/formAnnouncement';
import './js/test';
import './js/modules/carousel';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/styles.scss';
import './styles/profile.scss';
import './styles/form.scss';
import './styles/admin.scss';

// start the Stimulus application
import './bootstrap';
