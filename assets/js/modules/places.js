// GÉOLOCALISATION
import Places from 'places.js';

let profileAddress = document.querySelector('#profile_address');
if (profileAddress) {
    let place = Places({
        container: profileAddress
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#profile_zipcode').value = e.suggestion.postcode
        document.querySelector('#profile_city').value = e.suggestion.city
        document.querySelector('#profile_street').value = e.suggestion.street
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
        console.log(e.suggestion.name);
        document.querySelector('#announcement_zipcode').value = e.suggestion.postcode
        document.querySelector('#announcement_city').value = e.suggestion.city
        document.querySelector('#announcement_department').value = e.suggestion.county
        document.querySelector('#announcement_region').value = e.suggestion.administrative
        document.querySelector('#announcement_lat').value = e.suggestion.latlng.lat
        document.querySelector('#announcement_lng').value = e.suggestion.latlng.lng
    })
}

let inputUpdateAdress = document.querySelector('#announcement_update_address');
if (inputUpdateAdress) {
    let place = Places({
        container: inputUpdateAdress
    })
    place.on('change', e => {
        console.log(e.suggestion.city);
        document.querySelector('#announcement_zipcode').value = e.suggestion.postcode
        document.querySelector('#announcement_city').value = e.suggestion.city
        document.querySelector('#announcement_department').value = e.suggestion.county
        document.querySelector('#announcement_region').value = e.suggestion.administrative
        document.querySelector('#announcement_lat').value = e.suggestion.latlng.lat
        document.querySelector('#announcement_lng').value = e.suggestion.latlng.lng
    })
}

let inputUpdateProfileAddress = document.querySelector('#profile_adress_address');
if (inputUpdateProfileAddress) {
    let place = Places({
        container: inputUpdateProfileAddress
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#profile_adress_zipcode').value = e.suggestion.postcode
        document.querySelector('#profile_adress_city').value = e.suggestion.city
        document.querySelector('#profile_adress_department').value = e.suggestion.county
        document.querySelector('#profile_adress_region').value = e.suggestion.administrative
        document.querySelector('#profile_adress_lat').value = e.suggestion.latlng.lat
        document.querySelector('#profile_adress_lng').value = e.suggestion.latlng.lng
    })
}

let searchAddressAnnounce = document.querySelector('#search_address_announce')
if (searchAddressAnnounce !== null) {
    let place = Places({
        container: searchAddressAnnounce
    })
    place.on('change', e => {
        console.log(e.suggestion);
        document.querySelector('#search_address_announce_lat').value = e.suggestion.latlng.lat
        document.querySelector('#search_address_announce_lng').value = e.suggestion.latlng.lng
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
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
}