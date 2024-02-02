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

function generarClave() {
    let clave = '';
    
    for (let i = 0; i < 5; i++) {
        for (let j = 0; j < 5; j++) {
            clave += String.fromCharCode(Math.floor(Math.random() * 26) + 65); // Generar letra mayúscula
        }
        
        if (i < 4) {
            clave += '-'; // Agregar el guion después de cada bloque de 5 caracteres
        }
    }
    
    return clave;
}

/*for (let index = 0; index < 50; index++) {
   const claveGenerada = generarClave();
document.write(claveGenerada + "</br>");
    
}*/

/* Total dinamico */

let checkbox = document.getElementsByName('descuento')
let span = document.getElementById('descuento')

if(checkbox.checked){
    span.innerHTML = `".$precio_total - substr($_SESSION['Puntos'], 0, -2)."`;
}