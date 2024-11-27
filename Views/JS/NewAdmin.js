
//Solo 10 numeros en telefono y 8 en las contraseñas
document.getElementById('telefono').addEventListener('input', function() {

    const maxLength = 10;
    const value = this.value;

    this.value = this.value.replace(/[^0-9]/g, '');

    if (value.length > maxLength) {
        this.value = value.slice(0, maxLength);
    }
});

document.getElementById('contraseña').addEventListener('input', function() {

    const maxLength = 8;
    const value = this.value;

    if (value.length > maxLength) {
        this.value = value.slice(0, maxLength);
    }
});





document.getElementById('newUsuario').addEventListener('submit', function(event) {
    
    event.preventDefault();

    const nombre = document.getElementById('nombre_usuario').value.trim();
    const email = document.getElementById('email').value.trim();
    const pw = document.getElementById('contraseña').value.trim();
    //const pw_conf = document.getElementById('pw_conf').value.trim();

    //columna 2
    const apellido = document.getElementById('apellido').value.trim();
    const telefono = document.getElementById('telefono').value.trim();

    const img = document.getElementById('foto').files[0]; //input

    const fecha = document.getElementById('fecha_nacimiento').value;
    const genero = document.getElementById('genero').value;
    const cuenta = '3';

    let errores = [];

    if (nombre === "" || pw ===""  || apellido ==="" || telefono ===""|| email ==="" /* pwconf */) {
        errores.push("Faltan campos por llenar");
    }

    // Validar que se haya seleccionado una opcion del select
    const generosCb = ['1', '2', '3'];
    if (!generosCb.includes(genero)) {
        errores.push("Debes seleccionar un género válido");
    }

    

    if (fecha) {
        const fechaSeleccionada = new Date(fecha);
        const fechaActual = new Date();
        fechaActual.setHours(0, 0, 0, 0);

        if (fechaSeleccionada >= fechaActual) {
            errores.push("Fecha no valida");
        }
    } else {
        errores.push("Debes seleccionar una fecha de nacimiento");
    }

    /*if(pw!=pw_conf){
        errores.push("Las contraseñas deben coincidir");

    }*/

    if (!img) {
        errores.push("Debes seleccionar una foto");
    }

    if (telefono.length < 10){
        errores.push("El telefono debe tener 10 caracteres");

    }
        //Validaciones de la clase de las semana 4 (contraseña y correo)

        function validatePassword() {
            var p = document.getElementById('contraseña').value,
                errors = [];
            
            if (p.length !== 8) {
                errors.push("Tu contraseña debe contener 8 caracteres.");
            }
            if (p.search(/[a-z]/i) < 0) {
                errors.push("Tu contraseña debe contener letras minusculas");
            }
            if (p.search(/[A-Z]/) < 0) {
                errors.push("Tu contraseña debe contener letras mayusculas");
            }
            if (p.search(/[0-9]/) < 0) {
                errors.push("Tu contraseña debe contener numeros"); 
            }
            if (p.search(/[\W_]/) < 0) { 
                errors.push("Tu contraseña debe contener caracteres especiales");
            }
            
            if (errors.length > 0) {
                //alert(errors.join("\n"));
                return false;
            }
            return true;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errores.push("El formato del correo electrónico no es válido");
        }


    if(!validatePassword()){
        errores.push("La contraseña debe contener una mayuscula, una minuscula, un numero, un caracter especial y ser de 8 caracteres");

    }

    if (errores.length > 0) {
        alert(errores.join("\n"));

    } else {
        //alert("Usuario creado con exito :)");
        ////window.location.href = 'inicioSesion.html';
        //event.target.submit();

        
        enviarPhpNewUser({ nombre, apellido, email, pw, telefono, fecha, genero, cuenta, img });
    }
});


function enviarPhpNewUser(datos) {
    const formData = new FormData();
    
    // Agrega los datos al FormData
    formData.append('nombre_usuario', datos.nombre); // Asegúrate de que el nombre del campo coincida con el que espera el PHP
    formData.append('nombre', datos.nombre);
    formData.append('apellidos', datos.apellido);
    formData.append('email', datos.email);
    formData.append('pw', datos.pw);
    formData.append('telefono', datos.telefono);
    formData.append('fecha', datos.fecha);
    formData.append('genero', datos.genero);
    formData.append('cuenta', datos.cuenta);
    
    // Si hay imagen, agrégala
    if (datos.img) {
        formData.append('foto', datos.img);
    }

    // Enviar la solicitud al PHP
    fetch('../Controllers/register.php', { // Ajusta la ruta según tu estructura de carpetas
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // o .json() si esperas JSON
    .then(data => {

        console.log(data);
        
        if (data.includes("Usuario registrado correctamente")) {
            alert("Usuario registrado :)");


            window.location.href = 'perfil.php'; // Ajusta la ruta si es necesario
        } else {
            alert("Error al registrar: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}