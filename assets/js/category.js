let collection = document.querySelector("#category_subCategories");


if (collection !== null) {
    let buttonAdd;
    // let span;
    buttonAdd = document.createElement("button");
    buttonAdd.className = "admin__content__form__button__new";
    buttonAdd.innerText = "Ajouter une sous catégorie";
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

/**
 * Ajout image au categorie
 */
let categories = document.querySelectorAll('#homepage-category');

if (categories) {
    categories.forEach(category => {
        let filename = category.dataset.filename;
        category.style.backgroundImage = "url(/images/category/picture/" + filename + ")";
        category.style.backgroundRepeat = "no-repeat";
        category.style.backgroundSize = "contain";
        category.style.backgroundPosition = "10px bottom";
        // 'public/images/category/picture625f144275ba7_mains.jpeg'
    });
}


$(document).on('change', '#announcement_category', function () {
    let $field = $(this); // category
    let $catgoryField = $('#announcement_category');
    let $form = $field.closest('form');
    let target = '#' + $field.attr('id').replace('category', 'subCategory');
    let data = {};
    data[$field.attr('name')] = $field.val();
    data[$catgoryField.attr('name')] = $field.val();
    // console.log(data[$catgoryField.attr('name')] = $field.val());
    $.post($form.attr('action'), data).then(function (data) {
        
        let $input = $(data).find(target);
        $(target).replaceWith($input);
    })
})


// let category = document.querySelector('#announcement_category');
// http://localhost:8000/sub/category/list/byCategory
// category.addEventListener('change', function () {
//     let field = this;
//     let form = field.closest('form');
//     let data = {};
//     data = this.name + "=" + this.value;
//     console.log(field);
//     fetch(form.action, {
//         method: form.getAttribute("method"),
//         body: data,
//         headers: {
//             "Content-Type": "application/x-www-form-urlencoded;charset: UTF-8"
//         }
//     })
//     .then(response => response.text())
//     .then(html => {
//         let content = document.createElement("html");
//         content.innerHTML = html;
//         let newSelectCategory = document.querySelector("#announcement_category");
//         newSelectCategory.replaceWith(newSelectCategory);
//         let newSelect = content.querySelector("#announcement_subCategory");
//         document.querySelector("#announcement_subCategory").replaceWith(newSelect);
//     })
//     .catch(error => console.log(error));
// })

