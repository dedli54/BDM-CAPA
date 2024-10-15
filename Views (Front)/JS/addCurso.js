

//Funcion para mostrar la imagen
document.getElementById('foto').addEventListener('change', function(event) {
    const file = event.target.files[0]; 

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('preview');
            previewImage.src = e.target.result; // Asignar url a la imagen
            previewImage.style.display = 'block'; 
        }
        reader.readAsDataURL(file); // Lee el archivo como una URL de datos
    }
});

//Solo 10 numeros en telefono y 8 en las contraseñas
