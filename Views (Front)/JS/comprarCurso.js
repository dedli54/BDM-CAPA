document.getElementById('buyCurso').addEventListener('click', function() {
    document.getElementById('formBuy').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
  });

  // Opcional: ocultar el formulario y la superposición al hacer clic en la superposición
  document.getElementById('overlay').addEventListener('click', function() {
    document.getElementById('formBuy').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
  });

  document.getElementById('formBuy').addEventListener('submit', function(event) {
    event.preventDefault(); 
    alert('Curso agregado a tu catalogo');
  });