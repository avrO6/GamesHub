
let boton = document.getElementsByName('descuento');
let span = document.getElementById('descuento');

    // Agregar un evento de cambio al checkbox
function total(){

    let total = `".$precio_total - intval(substr($_SESSION['Puntos'], 0, -2))."`

    span.innerHTML = total;
}

        
    


