document.addEventListener('DOMContentLoaded', function() {
    const campusSelect = document.getElementById('campus');
    const facultadSelect = document.getElementById('facultad');
    const carreraSelect = document.getElementById('carrera');

    fetch('scripts/obtener_datos.php?tipo=campus')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            data.forEach(campus => {
                const option = document.createElement('option');
                option.value = campus.Id_campus;
                option.textContent = campus.Campus;
                campusSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });

        // Rellenar el selector de semestres
        var semestreSelect = document.getElementById('semestre');
        for (var i = 1; i <= 12; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            semestreSelect.appendChild(option);
        }

        // Rellenar el selector de grupos
        var grupoSelect = document.getElementById('grupo');
        for (var j = 0; j < 26; j++) {
            var option = document.createElement('option');
            option.value = String.fromCharCode(65 + j); // 65 es el código ASCII para 'A'
            option.textContent = String.fromCharCode(65 + j);
            grupoSelect.appendChild(option);
        }

    campusSelect.addEventListener('change', function() {
        facultadSelect.innerHTML = '<option value="">---</option>';
        carreraSelect.innerHTML = '<option value="">---</option>';
        if (campusSelect.value) {
            fetch(`scripts/obtener_datos.php?tipo=facultades&id_campus=${campusSelect.value}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    data.forEach(facultad => {
                        const option = document.createElement('option');
                        option.value = facultad.Id_facultad;
                        option.textContent = facultad.Facultad;
                        facultadSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }
    });

    facultadSelect.addEventListener('change', function() {
        carreraSelect.innerHTML = '<option value="">---</option>';
        if (facultadSelect.value) {
            fetch(`scripts/obtener_datos.php?tipo=carreras&id_facultad=${facultadSelect.value}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    data.forEach(carrera => {
                        const option = document.createElement('option');
                        option.value = carrera.Id_carrera;
                        option.textContent = carrera.Carrera;
                        carreraSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }
    });
});