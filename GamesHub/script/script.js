const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});


/* ----- Implementacion de modo oscuro y modo claro ----- */

let modoOscuro = () => {

    document.querySelector('body').setAttribute('data-bs-theme','dark');

}

let modoClaro = () => {

    document.querySelector('body').setAttribute('data-bs-theme','light');

}

let cambiarModo = () => {

    if (document.querySelector('body').getAttribute('data-bs-theme') === 'light'){
        modoOscuro();
    }else{
        modoClaro();
    }
}
/* ----- * ----- */