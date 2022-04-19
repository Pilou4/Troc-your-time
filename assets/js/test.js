import axios from 'axios';

let hearts = document.querySelectorAll('.la-heart');
var urlcourante = document.location.href; 
// alert (' URL : \n' +urlcourante);
if (hearts) {
    window.onload = () => {
        hearts.forEach(heart => {
            heart.style.cursor = "pointer";
            let profile = heart.dataset.profile;
            let announce = heart.dataset.announce;
            let favoris = heart.dataset.favoris; // ID DE L'ANNONCE EN FAVORIS
            
            console.log("FAVORIS : " + favoris + " PROFILE : " + profile);
            heart.style.color = "red";

            // pas d'utilisateur connecté
            if (profile === "0") {
                console.log("Pas d'utilisateur connecter");
                return;
            }

            // Utilisateur connecté mais n'a pas compléter son profile
            if (profile === "00") {
                console.log("Utilisateur connecté mais n'a pas compléter son profile");
                return;
            }

            // Utilisateur connecté
            if (profile !== "0" || profile !== "00") {
                console.log("Utilisateur connecté");
                if (favoris == profile) {
                    if (heart.classList == 'lar la-heart') {
                        heart.classList.add("las");
                        heart.classList.remove("lar");
                    }
                }
                heart.addEventListener("click", function () {
                    let xmlhttp = new XMLHttpRequest;
                    if (favoris == "" || favoris !== profile) {
                        xmlhttp.open("get", `http://localhost:8000/announcement/favorite/add/${announce}`);
                        xmlhttp.send();
                        heart.classList.add("las");
                        heart.classList.remove("lar");
                        favoris = profile;
                        console.log(favoris);
                    } else {
                        xmlhttp.open("get", `http://localhost:8000/announcement/favorite/remove/${announce}`);
                        xmlhttp.send();
                        heart.classList.add("lar");
                        heart.classList.remove("las");
                        favoris = "";
                        console.log(favoris);
                    }
                })
                
            }

        });
    }
}