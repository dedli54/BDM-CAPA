

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

                const tot = parseFloat(cTotal);
                const lvl = parseFloat(cNivel);

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
    
        } else { //3raE: terminó las validaciones del JS ahora manda a llamar al php

            // parseInt(localStorage.getItem('idProfe')) || 1; 
            const idProfe = parseInt(1); //3raE: obtener despues del localstorage
            // categoria llenar combobox con SP de categorias
            const csCategoria = parseInt(categoria);
            const tot = parseFloat(cTotal);

            enviarPhpNewCurso({cName,cDesc,img,tot,idProfe,csCategoria});

            /*
            
            CREATE PROCEDURE sp_agregar_curso (
                IN p_titulo VARCHAR(255),
                IN p_descripcion TEXT,
                IN p_precio DECIMAL(10,2),        --cTotal
                IN p_contenido TEXT,            --cDesc
                IN p_id_maestro INT,
                IN p_id_categoria INT,
                IN p_foto BLOB 
            
            */


            alert("Curso creado");
            //window.location.href = 'inicioSesion.html';
            //event.target.submit();
        }
    });

/*
function enviarPhpNewCurso(datos){
        const formData = new FormData();

        formData.append('p_titulo', datos.cName);
        formData.append('p_descripcion', datos.cDesc);
        formData.append('p_precio', datos.tot);
        formData.append('p_contenido', datos.cDesc);
        formData.append('p_id_maestro', datos.idProfe);
        formData.append('p_id_categoria', datos.csCategoria);

        if (datos.img) {
            formData.append('p_foto', datos.img);
        }

        // Aquí se enviarían los datos del fetch y etc.

        fetch('../Controllers/crearCurso.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Curso creado exitosamente');



                // Obtener el número de niveles
                const numFields = parseInt(document.getElementById('numFields').value);

                // Crear un bucle para enviar cada nivel
                for (let i = 1; i <= numFields; i++) {
                    const textInput = document.getElementById(`text${i}`).value.trim();
                    const fileInput = document.getElementById(`file${i}`).files[0];
                    
                    const formDataNivel = new FormData();
                    formDataNivel.append('p_texto', textInput || '');  // Si no hay texto, enviar cadena vacía
                    formDataNivel.append('p_video', fileInput || '');  // Si no hay archivo, enviar cadena vacía
                    formDataNivel.append('p_numero', i); // Número de nivel

                    // Enviar cada nivel al procedimiento almacenado
                    fetch('../Controllers/addNivelesCurso.php', {
                        method: 'POST',
                        body: formDataNivel
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Error al agregar nivel ' + i + ': ' + data.message);
                        }else{
                            alert('Nivel agregado: ' + i + ': ');

                        }
                    })
                    .catch(error => {
                        console.error('Error al enviar nivel ' + i, error);
                        alert('Hubo un error al agregar el nivel ' + i);
                    });
                }


                // Redirigir si es necesario
                // window.location.href = 'inicioSesion.html';
            } else {
                alert('Error al crear el curso: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al enviar datos:', error);
            alert('Hubo un error al enviar el formulario.');
        });

}
 */
function enviarPhpNewCurso(datos) {
    const formData = new FormData();

    formData.append('p_titulo', datos.cName);
    formData.append('p_descripcion', datos.cDesc);
    formData.append('p_precio', datos.tot);
    formData.append('p_contenido', datos.cDesc);
    formData.append('p_id_maestro', datos.idProfe);
    formData.append('p_id_categoria', datos.csCategoria);

    if (datos.img) {
        formData.append('p_foto', datos.img);
    }

    // Enviar datos para crear el curso
    fetch('../Controllers/crearCurso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Curso creado exitosamente');

            // Obtener el número de niveles
            const numFields = parseInt(document.getElementById('numFields').value);

            // Crear un bucle para enviar cada nivel
            for (let i = 1; i <= numFields; i++) {
                const textInput = document.getElementById(`text${i}`).value.trim();
                const fileInput = document.getElementById(`file${i}`).files[0];

                const formDataNivel = new FormData();
                formDataNivel.append('p_texto', textInput || '');  // Si no hay texto, enviar cadena vacía
                formDataNivel.append('p_video', fileInput || '');  // Si no hay archivo, enviar cadena vacía
                formDataNivel.append('p_numero', i); // Número de nivel

                // Enviar cada nivel al procedimiento almacenado
                fetch('../Controllers/addNivelesCurso.php', {
                    method: 'POST',
                    body: formDataNivel
                })
                .then(response => response.json())
                .then(data => {
                    console.log(`Respuesta nivel ${i}:`, data);  // Ver la respuesta completa
                    if (!data.success) {
                        alert('Error al agregar nivel ' + i + ': ' + data.message);
                    } else {
                        alert('Nivel agregado: ' + i);
                    }
                })
                .catch(error => {
                    console.error('Error al enviar nivel ' + i, error);
                    alert('Hubo un error al agregar el nivel ' + i);
                });
            }

        } else {
            alert('Error al crear el curso: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error al enviar datos:', error);
        alert('Hubo un error al enviar el formulario.');
    });
}
