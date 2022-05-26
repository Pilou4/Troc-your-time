let collection = document.querySelector("#announcement_pictureFiles");

/**
 * FORMULAIRES ANNONCES
 */
if (collection !== null) {
    let buttonAdd;
    buttonAdd = document.createElement("button");
    buttonAdd.className = "admin__content__form__button__new";
    buttonAdd.innerText = "Ajouter une image";
    let countButton = 0;
    let newButtonAdd = collection.append(buttonAdd);
    let index = collection.dataset.index = collection.querySelectorAll("input").length;

    buttonAdd.addEventListener("click", function () {
        addButton(collection, newButtonAdd);
    })

    function addButton(collection, newButtonAdd) {
        countButton++
        
        // let index = collection.dataset.index;
        let prototype = collection.dataset.prototype;
        prototype = prototype.replace(/__name__/g, index);
        let content = document.createElement("html");
        content.innerHTML = prototype;
        let newForm = content.querySelector("div");
        let buttonDelete = document.createElement("button");
        buttonDelete.type = "button";
        buttonDelete.id = "delete-pictures-" + index;
        buttonDelete.className = "admin__content__form__button__delete";
        buttonDelete.innerText = "Supprimer une image";

            newForm.append(buttonDelete);

        collection.dataset.index++;

        let buttonAdd = collection.querySelector(".admin__content__form__button__new");
        collection.insertBefore(newForm, buttonAdd);

        buttonDelete.addEventListener("click", function () {
            this.previousElementSibling.parentElement.remove();
        });
    }
    window.onload = () => {
        if (collection.dataset.index !== null) {
            let index = collection.dataset.index;
            for (let i = 0; i < index; i++) {
                // const element = array[i];
                let updateForms = document.querySelectorAll("#category_subCategories_" + i);
                updateForms.forEach(updateForm => {
                    let fieldset = updateForm.closest('fieldset');
                    let buttonDelete = document.createElement("button");
                    buttonDelete.type = "button";
                    buttonDelete.id = "delete-sub-category-" + index;
                    buttonDelete.className = "admin__content__form__button__delete";
                    buttonDelete.innerText = "Supprimer une image";      
                    updateForm.append(buttonDelete);
                    buttonDelete.addEventListener("click", function () {
                        this.previousElementSibling.parentElement.remove();
                        fieldset.remove();
                    });
                });
            }   
        }
    }
}

const filtersForm = document.querySelector('#filters');

/**
 * FILTRE DES ANNONCES PAR SOUS CATÉGORIES
 */

if (filtersForm) {
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener('change', (evt) => {
            // evt.preventDefault();
            const form = new FormData(filtersForm);
            const params = new URLSearchParams(); // queryString
            const title = document.querySelector('#title');


            console.log(title);
            form.forEach((value, key) => {
                params.append(key, value)
                console.log(params.toString());
            });

            const url = new URL(window.location.href); // url active
            
            fetch(url.pathname + '?' + params.toString() + "&ajax=1", {
                headers: {
                    'x-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => 
                response.json())
            .then(data => {
                const content = document.querySelector("#content");

                // On remplace le contenu
                content.innerHTML = data.content;

                // On met à jour l'url
                history.pushState({}, null, url.pathname + "?" + params.toString());
            })
            .catch(error => alert(error))
        });
    });
}