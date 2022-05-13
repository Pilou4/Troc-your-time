/**
 * VÉRIFICATION PASSWORD PAGE CREATE-ACCOUNT
 */
const formRegister = document.querySelector('#registration_form_email');

if (formRegister) {
    const form = document.querySelector('#registration_form_email').closest('form');
    const inputMail = document.querySelector('#registration_form_email');
    const inputPass = document.querySelector('#registration_form_password_first');
    const inputRepeat = document.querySelector('#registration_form_password_second');
    const charactersMin = document.querySelector('#characters');
    const charactersSpecial = document.querySelector('#special');
    const min = document.querySelector('#min');
    const maj = document.querySelector('#maj');
    const number = document.querySelector('#number');
    const confirm = document.querySelector('#confirm');

    // inputPass.addEventListener('blur', () => {
    //     if (inputPass.value.length <= 8) {
    //         inputPass.style.border = "2px solid red";
    //     } else {
    //         inputPass.style.border = "2px solid green";
    //     }
    // })
    form.addEventListener('change', () => {
        const regexSpecial = new RegExp(/!|_|:|-|;|&/);
        const regexMin = new RegExp(/[a-z]/);
        const regexMaj = new RegExp(/[A-Z]/);
        const regexNumber = new RegExp(/[0-9]/);

        if (inputPass.value.length <= 8) {
            // inputPass.style.border = "2px solid red";
            charactersMin.style.textDecoration = 'none';
        } else {
            // inputPass.style.border = "2px solid green";
            charactersMin.style.textDecoration = 'line-through';
        }
        if (regexSpecial.test(inputPass.value)) {
            charactersSpecial.style.textDecoration = 'line-through';
        } else {
            charactersSpecial.style.textDecoration = 'none';
        }
        if (regexMin.test(inputPass.value)) {
            min.style.textDecoration = 'line-through';
        } else {
            min.style.textDecoration = 'none';
        }
        if (regexMaj.test(inputPass.value)) {
            maj.style.textDecoration = 'line-through';
        } else {
            maj.style.textDecoration = 'none';
        }
        if (regexNumber.test(inputPass.value)) {
            number.style.textDecoration = 'line-through';
        } else {
            number.style.textDecoration = 'none';
        }
        if (inputPass.value.length >=8 && inputRepeat.value.length >= 8 && inputRepeat.value == inputPass.value) {
            confirm.style.textDecoration = 'line-through';
        } else {
            confirm.style.textDecoration = 'none';
        }
        
    })
}
