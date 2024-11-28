document.getElementById('editCategoria').addEventListener('click', function() {
    document.getElementById('formEditarCategoria').style.display = 'block';
    document.getElementById('overlayEditar').style.display = 'block';
    cargarCategorias(); // Cargar las categorías para editar
});


document.getElementById('overlayEditar').addEventListener('click', function() {
    document.getElementById('formEditarCategoria').style.display = 'none';
    document.getElementById('overlayEditar').style.display = 'none';
});


async function cargarCategorias() {
    try {
        const response = await fetch('../Controllers/APIeditarcategoria.php', {
            method: 'GET',
        });

        if (response.ok) {
            const categorias = await response.json();
            if (Array.isArray(categorias) && categorias.length > 0) {
                const categoriaSelect = document.getElementById('categoriaId');
                categoriaSelect.innerHTML = '<option value="">Seleccione una categoría</option>'; // Limpiar opciones anteriores
                categorias.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nombre;
                    categoriaSelect.appendChild(option);
                });
            } else {
                alert('No se encontraron categorías.');
            }
        } else {
            alert('Error al cargar categorías.');
        }
    } catch (error) {
        alert('Error al obtener categorías: ' + error.message);
    }
}




document.getElementById('formEditar').addEventListener('submit', async function(event) {
    event.preventDefault(); 

    const form = document.getElementById('formEditar');
    
    
    const categoriaId = document.getElementById('categoriaId').value;
    const nombre = document.getElementById('nameCatEditar').value;
    const descripcion = document.getElementById('definicionEditar').value;

    
    if (!categoriaId || !nombre || !descripcion) {
        alert('Todos los campos son obligatorios.');
        return;
    }

    
    const data = {
        id: categoriaId,
        nombre: nombre,
        descripcion: descripcion
    };

    try {
        const response = await fetch('../Controllers/APIeditarcategoria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'  
            },
            body: JSON.stringify(data)  
        });

        const result = await response.json(); 
        if (result.success) {
            alert(result.message); 
            document.getElementById('formEditarCategoria').style.display = 'none';
            document.getElementById('overlayEditar').style.display = 'none';
        } else {
            alert(result.message || 'Error al editar la categoría.');
        }
    } catch (error) {
        alert('Ocurrió un error: ' + error.message);
    }
});