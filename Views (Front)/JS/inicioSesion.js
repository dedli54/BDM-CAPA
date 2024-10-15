document.getElementById('loginForm').addEventListener('submit', function(event) {
    
    event.preventDefault();

    const email = document.getElementById('email').value.trim();
    const pw = document.getElementById('pw').value.trim();

    let errores = [];

    if (pw ==="" || email ==="" ) {
        errores.push("Faltan campos por llenar");
    }else
        if (pw.length < 8){
            errores.push("El contraseña debe tener 8 caracteres");

        }


    if (errores.length > 0) {
        alert(errores.join("\n"));

    } else {
        alert("Accediendo al sitio");
        window.location.href = 'landPage.html';
        //event.target.submit();
    }
});