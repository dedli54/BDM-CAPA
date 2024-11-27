document.addEventListener('DOMContentLoaded', function() {
    // show form
    document.getElementById('buyCurso').addEventListener('click', function() {
        document.getElementById('formBuy').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });

    // hide form
    document.getElementById('overlay').addEventListener('click', function() {
        document.getElementById('formBuy').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });

    // card number validation
    document.getElementById('numeroTarjeta').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
    });

    // expiration date validation
    document.getElementById('vencimiento').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // the 3 numbers at the back XD validation
    document.getElementById('ccv').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.slice(0, 3);
        e.target.value = value;
    });

    // submit
    document.getElementById('formBuy').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData();
        formData.append('curso_id', window.location.search.split('=')[1]);
        formData.append('numero_tarjeta', document.getElementById('numeroTarjeta').value.replace(/\s/g, ''));
        formData.append('vencimiento', document.getElementById('vencimiento').value);
        formData.append('ccv', document.getElementById('ccv').value);

        fetch('../Controllers/comprarCurso.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('¡Curso comprado exitosamente!');
                window.location.href = 'perfil.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la compra');
        });
    });
});