//Funcion para mostrar la imagen
/*document.getElementById('foto').addEventListener('change', function(event) {
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
 */
//Mostrar Inputs de niveles
/* 
document.addEventListener('DOMContentLoaded', function() {
    const numFieldsSelect = document.getElementById('numFields');
    const fieldsContainer = document.getElementById('fieldsContainer');

    // Asegúrate de que la función se ejecute cuando cambie el valor
    numFieldsSelect.addEventListener('change', function() {
        const numFields = parseInt(this.value); // Número de campos seleccionados
        fieldsContainer.innerHTML = ''; // Limpia los campos actuales

        // Si se selecciona un número válido (mayor que 0), muestra los campos
        if (numFields > 0) {
            for (let i = 1; i <= numFields; i++) {
                const row = document.createElement('div');
                row.classList.add('row', 'mb-3', 'form-field');
                
                row.innerHTML = `
                <hr style="opacity:15%">
                <div class="col-md-12">
                    <label class="form-label fs-3">Nivel ${i}</label>
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
        }

        // Mostrar u ocultar los campos según el número seleccionado
        document.querySelectorAll('.form-field').forEach((field, index) => {
            if (index < numFields) {
                field.style.display = 'flex'; // Mostrar campos dentro del límite
            } else {
                field.style.display = 'none'; // Ocultar los campos fuera del límite
            }
        });
    });

    // Ejecuta la misma lógica al cargar la página para manejar el valor por defecto
    numFieldsSelect.dispatchEvent(new Event('change'));
});*/





//Ocultar precios dependiendo del check box 

/* 
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



});*/

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
/* 
document.getElementById('cNivel').addEventListener('input', function(event) {
    const input = event.target.value;

    const regex = /^\d+(\.\d{0,2})?$/;

    if (!regex.test(input)) {
        event.target.value = input.slice(0, -1); 
    } else {
    }
});*/





//Validar el curso
document.getElementById('dynamicForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let errores = [];
    
    const cName = document.getElementById('cName').value.trim();
    const cDesc = document.getElementById('cDesc').value.trim();
    const cTotal = document.getElementById('cTotal').value.trim();
    const categoria = document.getElementById('cCategoria').value;
    const img = document.getElementById('foto').files[0];
    const cursoId = document.querySelector('input[name="curso_id"]').value;

    // Validaciones
    if (!cName || !cDesc) {
        errores.push("Título y descripción son obligatorios");
    }

    if (categoria === 'Categoria') {
        errores.push("Debes seleccionar una categoría");
    }

    if (errores.length > 0) {
        alert(errores.join("\n"));
        return;
    }

    // Enviar datos
    const formData = new FormData();
    formData.append('p_titulo', cName);
    formData.append('p_descripcion', cDesc);
    formData.append('p_precio', cTotal);
    formData.append('p_id_categoria', categoria);
    formData.append('p_id_curso', cursoId);
    if (img) {
        formData.append('p_foto', img);
    }

    fetch('../Controllers/editCurso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Curso actualizado exitosamente');
            window.location.href = 'perfil.php';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el curso');
    });
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

    console.log("Datos enviados en FormData:");
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
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

            enviarNivelesCurso(numFields);

            // Crear un bucle para enviar cada nivel
            /*for (let i = 1; i <= numFields; i++) {
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
            }*/

        } else {
            alert('Error al crear el curso: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error al enviar datos:', error);
        alert('Hubo un error al enviar el formulario.');
    });
}

async function enviarNivelesCurso(numFields) {
    for (let i = 1; i <= numFields; i++) {
        const textInput = document.getElementById(`text${i}`).value.trim();
        const fileInput = document.getElementById(`file${i}`).files[0];

        const formDataNivel = new FormData();
        formDataNivel.append('p_texto', textInput || '');  // Si no hay texto, enviar cadena vacía
        formDataNivel.append('p_video', fileInput || '');  // Si no hay archivo, enviar cadena vacía
        formDataNivel.append('p_numero', i); // Número de nivel

        console.log("Datos enviados en FormData lvl:");
        for (let [key, value] of formDataNivel.entries()) {
            console.log(`${key}:`, value);
        }

        try {
            const response = await fetch('../Controllers/addNivelesCurso.php', {
                method: 'POST',
                body: formDataNivel
            });

            const data = await response.json();
            console.log(`Respuesta nivel ${i}:`, data);  // Ver la respuesta completa

            if (!data.success) {
                alert('Error al agregar nivel ' + i + ': ' + data.message);
            } else {
                alert('Nivel agregado: ' + i);
            }
        } catch (error) {
            console.error('Error al enviar nivel ' + i, error);
            alert('Hubo un error al agregar el nivel ' + i);
        }
    }
}
