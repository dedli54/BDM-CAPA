paypal.Buttons({
    createOrder: function(data, actions) {
        // Get course price from page
        const price = document.querySelector('.btnDiv p').textContent
            .replace('Precio: $', '')
            .trim();
            
        return actions.order.create({
            purchase_units: [{
                amount: {
                    currency_code: 'MXN',
                    value: price
                }
            }]
        });
    },
    
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Get course ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const cursoId = urlParams.get('id');
            
            // Send to backend to process purchase
            return fetch('../Controllers/comprarCursoPaypal.php', {
               
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    curso_id: cursoId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('¡Compra completada! Ya estás inscrito en el curso.');
                  //  window.location.href = 'perfil.php'; // Redirect to profile/dashboard
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    }
}).render('#paypal-button-container');