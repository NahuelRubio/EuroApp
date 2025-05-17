window.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('content');
    const buttons = document.querySelectorAll('.bottom-nav button');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            fetch(btn.dataset.page + '.php')
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    if (btn.dataset.page === 'puntuar') {
                        const select = document.getElementById('country-select');
                        const section = document.getElementById('rating-section');
                        const range = document.getElementById('rating-range');
                        const output = document.getElementById('range-value');

                        select.addEventListener('change', () => {
                            section.style.display = select.value ? 'block' : 'none';
                        });

                        range.addEventListener('input', () => {
                            const val = parseInt(range.value);
                            output.textContent = val;

                            let color = '#4caf50'; // verde
                            if (val <= 10) color = '#e74c3c'; // rojo
                            else if (val <= 20) color = '#f39c12'; // naranja

                            output.style.color = color;

                            // porcentaje para fondo dinámico
                            const percent = (val - range.min) / (range.max - range.min) * 100;
                            range.style.background = `linear-gradient(to right, ${color} 0%, ${color} ${percent}%, #ddd ${percent}%, #ddd 100%)`;

                            // cambiar el borde del thumb
                            range.style.setProperty('--thumb-color', color);
                        });

                        range.dispatchEvent(new Event('input'));

                        document.getElementById('save-rating').addEventListener('click', () => {
                            const country = document.getElementById('country-select').value;
                            const score = parseInt(document.getElementById('rating-range').value);

                            fetch('/rate_country.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ country, score })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.status === 'ok') {
                                        reloadPuntuar();
                                    } else if (data.status === 'empate') {
                                        showModal(data.conflict, country, score);
                                    } else {
                                        alert('Error al guardar la puntuación');
                                    }
                                });
                        });

                    } else {
                        initDragDrop(btn.dataset.page);
                    }
                });
        });
    });

    document.querySelector('[data-page="dream_top"]').click();
});


function initDragDrop(section) {
    console.log('Activando drag & swap para:', section);
    const table = document.querySelector('#countries-table tbody');
    let selectedRow = null;

    function saveOrder() {
        const rows = Array.from(table.querySelectorAll('tr'));
        const order = rows.map(r => r.dataset.country);
        const normalizedSection = section === 'dream_top' ? 'dream' : 'real';

        fetch('/save_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ section: normalizedSection, order })
        })
            .then(res => res.json())
            .then(res => {
                console.log('Respuesta del servidor:', res);
                if (res.status !== 'ok') alert('Error al guardar');
            })
            .catch(err => {
                console.error('Error en la petición:', err);
                alert('Error de red al guardar');
            });
    }

    function updatePositions() {
        table.querySelectorAll('tr').forEach((row, i) => {
            row.querySelector('td').textContent = i + 1;
        });
    }


    function swapRows(row1, row2) {
        // Intercambiar contenido de la segunda celda (bandera + nombre)
        const cell1 = row1.querySelectorAll('td')[1];
        const cell2 = row2.querySelectorAll('td')[1];

        const temp = cell1.innerHTML;
        cell1.innerHTML = cell2.innerHTML;
        cell2.innerHTML = temp;

        // Intercambiar el atributo data-country
        const tmpData = row1.dataset.country;
        row1.dataset.country = row2.dataset.country;
        row2.dataset.country = tmpData;

        updatePositions();
        saveOrder();
    }



    function addRowEvents(row) {
        row.addEventListener('click', () => {
            if (selectedRow) {
                if (selectedRow === row) {
                    row.classList.remove('selected');
                    selectedRow = null;
                    return;
                }

                swapRows(selectedRow, row);
                table.querySelectorAll('tr').forEach(r => r.classList.remove('selected'));
                selectedRow = null;
            } else {
                table.querySelectorAll('tr').forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');
                selectedRow = row;
            }
        });
    }

    // Inicializar eventos al cargar
    table.querySelectorAll('tr').forEach(addRowEvents);
}

function reloadPuntuar() {
    const btn = document.querySelector('[data-page="puntuar"]');
    btn.classList.remove('active');
    btn.click();
}

function showModal(conflictWith, currentCountry, score) {
    const modal = document.getElementById('modal');
    const text = document.getElementById('modal-text');
    text.textContent = `"${currentCountry}" tiene la misma puntuación que "${conflictWith}". ¿Querés subir o bajar ${currentCountry} de posición?`;

    modal.style.display = 'flex';

    const sendAdjustedScore = (newScore) => {
        fetch('/rate_country.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ country: currentCountry, score: newScore })
        })
        .then(res => res.json())
        .then(data => {
            modal.style.display = 'none';

            if (data.status === 'ok') {
                reloadPuntuar();
            } else if (data.status === 'empate') {
                // ⚠️ Nuevo empate → volver a mostrar el modal
                showModal(data.conflict, currentCountry, newScore);
            } else {
                alert('Error al guardar puntuación');
            }
        });
    };

    document.getElementById('modal-up').onclick = () => {
        const adjusted = Math.min(score + 1, 100);
        sendAdjustedScore(adjusted);
    };

    document.getElementById('modal-down').onclick = () => {
        const adjusted = Math.max(score - 1, 1);
        sendAdjustedScore(adjusted);
    };
}

