

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value.trim();
    const pw = document.getElementById('pw').value.trim();

    let errores = [];

    if (pw === "" || email === "") {
        errores.push("Faltan campos por llenar");
    } else 

    if (errores.length > 0) {
        alert(errores.join("\n"));
    } else {
        // Aquí puedes agregar la lógica para enviar los datos
        fetch('../Controllers/login.php', { //  
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password: pw })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Inicio de sesión exitoso");
                window.location.href = 'landPage.php'; // Recuerda que lo que hace es redireccionar desde la pagina en donde estamos, no desde el JS 
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
