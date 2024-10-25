document.addEventListener('DOMContentLoaded', function() {
    fetch('perfil_type.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos del perfil.');
            }
            return response.json();
        })
        .then(data => {
            var userId = data.user_id;
            var userType = parseInt(data.user_type, 10);

            // Selecciona por clase
            var alumnos = document.querySelectorAll('.alumno');
            var profesores = document.querySelectorAll('.profesor');
            var admins = document.querySelectorAll('.admin');

            // Muestra u oculta elementos según el tipo de usuario
            switch (userType) {
                case 1: // alumno
                    mostrarElementos(alumnos);
                    ocultarElementos(profesores);
                    ocultarElementos(admins);
                    break;
                case 2: // profesor
                    ocultarElementos(alumnos);
                    mostrarElementos(profesores);
                    ocultarElementos(admins);
                    break;
                case 3: // admin
                    ocultarElementos(alumnos);
                    ocultarElementos(profesores);
                    mostrarElementos(admins);
                    break;
                default:
                    console.log("Tipo de usuario no válido.");
            }
        })
        .catch(error => console.error('Error:', error));
});

function mostrarElementos(elementos) {
    elementos.forEach(elemento => {
        elemento.style.display = 'block'; // Muestra el elemento
    });
}

function ocultarElementos(elementos) {
    elementos.forEach(elemento => {
        elemento.style.display = 'none'; // Oculta el elemento
    });
}
