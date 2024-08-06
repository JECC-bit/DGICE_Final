document.addEventListener('DOMContentLoaded', function() {
    // Eliminar bootcamp
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const idBootcamp = this.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar este bootcamp?')) {
                fetch('scripts/eliminar_bootcamp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${idBootcamp}&_method=DELETE`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Bootcamp eliminado con éxito.');
                        location.reload(); // Refrescar la página
                    } else {
                        console.error('Error:', data.error);
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error);
                });
            }
        });
    });
});
