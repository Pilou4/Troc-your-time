let collection = document.querySelector("#announcement_pictureFiles");

if (collection !== null) {
    let buttonAdd;
    buttonAdd = document.createElement("button");
    buttonAdd.className = "admin__content__form__button__new";
    buttonAdd.innerText = "Ajouter une image";

    let newButtonAdd = collection.append(buttonAdd);
    collection.dataset.index = collection.querySelectorAll("input").length;

    buttonAdd.addEventListener("click", function () {
        addButton(collection, newButtonAdd);
    })

    function addButton(collection, newButtonAdd) {
        let index = collection.dataset.index;
        let prototype = collection.dataset.prototype;
        prototype = prototype.replace(/__name__/g, index);
        let content = document.createElement("html");
        content.innerHTML = prototype;
        let newForm = content.querySelector("div");
        let buttonDelete = document.createElement("button");
        buttonDelete.type = "button";
        buttonDelete.id = "delete-sub-category-" + index;
        buttonDelete.className = "admin__content__form__button__delete";
        buttonDelete.innerText = "Supprimer une sous catégorie";

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
                    buttonDelete.innerText = "Supprimer une sous catégorie";      
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


