//Funcion para mostrar la imagen seleccionada en los forms

//Revisar siempre los ID´s cuando se llame, FOTO para el <img> y PREVIEW para <input>
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

/*

                <div class="mb-3 ">
                            <label for="foto" class="form-label fs-3 subtitulos">Selecciona una imagen</label>
                            <input type="file" class="form-control rounded-5" id="foto" accept="image/jpeg, image/png">
                        </div>
                        <div class="img-container d-flex justify-content-center">
                            <img id="preview" class="img-preview rounded-5" alt="Foto de perfil seleccionada">
                        </div>

*/