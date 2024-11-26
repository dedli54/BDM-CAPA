document.getElementById('buyCurso').addEventListener('click', function() {
    document.getElementById('formCategoria').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
  });

  // Opcional: ocultar el formulario y la superposición al hacer clic en la superposición
  document.getElementById('overlay').addEventListener('click', function() {
    document.getElementById('formCategoria').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
  });

  document.getElementById('formCategoria1').addEventListener('submit', async function(event) {


    event.preventDefault(); 

    const form = document.getElementById('formCategoria1'); //convertir a HTMLFormElement

    const formData = new FormData(form); 
    try {
        const response = await fetch('../Controllers/crear_categoria.php', {
            method: 'POST',
            body: formData,
        });

        if (response.ok) {
            const result = await response.text(); 
            alert(result); 
            document.getElementById('formCategoria').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';

        } else {
            alert('Error al procesar la solicitud.');
        }
    } catch (error) {
        alert('Ocurrió un error: ' + error.message);
    }


  });