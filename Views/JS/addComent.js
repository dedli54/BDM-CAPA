/* document.getElementById('addComentBtn').addEventListener('click', function() {
    document.getElementById('formCategoria').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
  });*/

  // Opcional: ocultar el formulario y la superposición al hacer clic en la superposición
  document.getElementById('overlay').addEventListener('click', function() {
    document.getElementById('congratsScreen').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
  });

  document.getElementById('formComentario').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../Controllers/crearComentario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('Comentario enviado exitosamente');
            window.location.reload(); // Refresh page to show new comment
        } else {
            throw new Error('Error al enviar comentario');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar el comentario');
    });
  });