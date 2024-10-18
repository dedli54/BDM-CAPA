

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

//Mostrar Inputs de niveles

document.addEventListener('DOMContentLoaded', function() {
        const numFieldsSelect = document.getElementById('numFields');
        const fieldsContainer = document.getElementById('fieldsContainer');

        numFieldsSelect.addEventListener('change', function() {
            const numFields = parseInt(this.value);
            fieldsContainer.innerHTML = ''; // Limpia los campos actuales

            for (let i = 1; i <= numFields; i++) {
                const row = document.createElement('div');
                row.classList.add('row', 'mb-3', 'form-field');
                
                row.innerHTML = `
                <hr style="opacity:15%">
                <div class="col-md-12">
                        <label class="form-label fs-3 " >Nivel ${i}</label>
                    </div>

                    <div class="col-md-6">
                        <label for="text${i}" class="form-label fs-4">Texto ${i}</label>
                        <textarea class="form-control textarea-md" id="text${i}" placeholder="Ingresa texto ${i}"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="file${i}" class="form-label fs-4">Archivo ${i}</label>
                        <input type="file" class="form-control" id="file${i}" accept="video/mp4,video/*">
                    </div>
                `;

                fieldsContainer.appendChild(row);
            }

            // Mostrar los campos seleccionados
            document.querySelectorAll('.form-field').forEach((field, index) => {
                if (index < numFields) {
                    field.style.display = 'flex';
                } else {
                    field.style.display = 'none';
                }
            });
        });
    });


//Ocultar precios dependiendo del check box 

document.getElementById('cGratis').addEventListener('change', function(event){
    const elementosCosto = document.getElementsByClassName('costo');
    
    if (this.checked) {

        for (let i = 0; i < elementosCosto.length; i++) {
            elementosCosto[i].style.display = 'none';
        }
    } else {

        for (let i = 0; i < elementosCosto.length; i++) {
            elementosCosto[i].style.display = 'block';
        }
    }



});

//Precio regex
document.getElementById('cTotal').addEventListener('input', function(event) {
    const input = event.target.value;

    const regex = /^\d+(\.\d{0,2})?$/;

    if (!regex.test(input)) {
        event.target.value = input.slice(0, -1); 
    } else {
    }
});

//Precio regex 2
document.getElementById('cNivel').addEventListener('input', function(event) {
    const input = event.target.value;

    const regex = /^\d+(\.\d{0,2})?$/;

    if (!regex.test(input)) {
        event.target.value = input.slice(0, -1); 
    } else {
    }
});



//Validar el curso
document.getElementById('dynamicForm').addEventListener('submit', function(event) {
    
        event.preventDefault();
        let errores = [];
    
        const cName = document.getElementById('cName').value.trim();
        const cDesc = document.getElementById('cDesc').value.trim();

        const img = document.getElementById('foto').files[0];

        const cGratis = document.getElementById('cGratis');
        const gratisBool = cGratis.checked;

        const cTotal = document.getElementById('cTotal').value.trim();
        const cNivel = document.getElementById('cNivel').value.trim();

        const freeLvl = document.getElementById('freeLvl').value; //No se usa aún

        //Validar txt
        if (cName === "" || cDesc ===""  ) {
            errores.push("Falta llenar nombre o descripcion");
        }

        //Val de precios
        if(!cGratis.checked){


            if (cTotal === "" || cNivel ===""  ) {
                errores.push("Faltó informacion de costos");
            }else{

                const tot = cTotal.parseFloat();
                const lvl = cNivel.parseFloat();

                if(lvl >= tot)
                errores.push("Costo de nivel no puede ser menor");



            }

        }

        //categoria e imagen
        const categoria = document.getElementById('cCategoria').value;
        if (categoria === 'Categoria') {
                errores.push('Debes seleccionar una categoría');
            }
    
        if (!img) {
                errores.push('Debes seleccionar una imagen');
            }


        // Validar que cada nivel tenga texto o video      
        const numFieldsSelect = document.getElementById('numFields');

        
        

            // Validacion de que todos los niveles tengan info
            const numFields = parseInt(numFieldsSelect.value);

        let allFieldsValid = true; // Para verificar todos los campos
        let videos = 0; // Para verificar todos los campos
        

        for (let i = 1; i <= numFieldsSelect.value; i++) {
                const textInput = document.getElementById(`text${i}`);
                const fileInput = document.getElementById(`file${i}`);
                const textValue = textInput.value.trim();
                const fileValue = fileInput.files.length > 0;
    
                

                if(fileValue){
                    videos = videos + 1; //Cuenta los videos
                }
    
                if (!textValue && !fileValue) {
                    allFieldsValid = false; // No hay texto ni archivo
                    errores.push(`Nivel ${i} no tiene informacion`);
                }
        }

        if (numFields <= 0) {
            errores.push("Selecciona una cantidad de niveles");

        }else
            if (videos === 0) {
                    errores.push("Debe haber al menos un video");

            }

    

        //FINAL
        if (errores.length > 0) {
            alert(errores.join("\n"));
    
        } else {
            alert("Curso creado");
            //window.location.href = 'inicioSesion.html';
            //event.target.submit();
        }
    });