import axios from 'axios';

let hearts = document.querySelectorAll('.la-heart');
var urlcourante = document.location.href; 
// alert (' URL : \n' +urlcourante);
if (hearts) {
    hearts.forEach(heart => { 
        let favoris = 
        // console.log("TEST" + heart);
        heart.addEventListener("click", function () {
                console.log("TEST" + heart);
        });
        
        
        // heart.addEventListener("click", function () {
        //     let xmlhttp = new XMLHttpRequest;
        //     xmlhttp.open("get", `/announcement/favorite/add/${this.dataset.id}`)
        //     xmlhttp.send()
        // })
    });
}