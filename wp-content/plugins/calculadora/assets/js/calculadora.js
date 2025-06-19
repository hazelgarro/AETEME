function calcularPuntos() {
    let ganador = parseInt(document.getElementById('puntos_ganador').value);
    let perdedor = parseInt(document.getElementById('puntos_perdedor').value);
    let diferencia = Math.abs(ganador - perdedor);
    let puntos_ganador = 0, puntos_perdedor = 0;

    if (ganador > perdedor) { // Resultado esperado
        if (diferencia <= 50) { puntos_ganador = 8; puntos_perdedor = 5; }
        else if (diferencia <= 100) { puntos_ganador = 6; puntos_perdedor = 4; }
        else if (diferencia <= 250) { puntos_ganador = 4; puntos_perdedor = 3; }
        else if (diferencia <= 500) { puntos_ganador = 3; puntos_perdedor = 2; }
        else if (diferencia <= 1000) { puntos_ganador = 2; puntos_perdedor = 1; }
    } else { // Resultado inesperado
        if (diferencia <= 100) { puntos_ganador = 10; puntos_perdedor = 5; }
        else if (diferencia <= 250) { puntos_ganador = 15; puntos_perdedor = 8; }
        else if (diferencia <= 500) { puntos_ganador = 20; puntos_perdedor = 10; }
        else if (diferencia <= 1000) { puntos_ganador = 25; puntos_perdedor = 15; }
        else { puntos_ganador = 30; puntos_perdedor = 20; }
    }

    document.getElementById('puntos_ganador_resultado').innerText = puntos_ganador ? `${puntos_ganador} puntos` : '';
    document.getElementById('puntos_perdedor_resultado').innerText = puntos_perdedor ? `${puntos_perdedor} puntos` : '';
}

function limpiarCampos() {
    document.getElementById('puntos_ganador').value = '';
    document.getElementById('puntos_perdedor').value = '';
    document.getElementById('puntos_ganador_resultado').innerText = '—';
    document.getElementById('puntos_perdedor_resultado').innerText = '—';
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("limpiar").addEventListener("click", limpiarCampos);
});

document.addEventListener('DOMContentLoaded', function () {
    const botonCalcular = document.getElementById('calcular');
    const inputGanador = document.getElementById('puntos_ganador');
    const inputPerdedor = document.getElementById('puntos_perdedor');
    const errorGanador = document.getElementById('error_ganador');
    const errorPerdedor = document.getElementById('error_perdedor');

    botonCalcular.addEventListener('click', function (e) {
        e.preventDefault();
        let hayError = false;

        [errorGanador, errorPerdedor].forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });

        const valGanador = inputGanador.value.trim();
        const valPerdedor = inputPerdedor.value.trim();

        if (!valGanador) {
            errorGanador.textContent = 'Este campo es obligatorio. Debe ingresar un número.';
            errorGanador.style.display = 'block';
            hayError = true;
        }

        if (!valPerdedor) {
            errorPerdedor.textContent = 'Este campo es obligatorio. Debe ingresar un número.';
            errorPerdedor.style.display = 'block';
            hayError = true;
        }

        if (!hayError) {
            calcularPuntos();
        } else {
            document.getElementById('puntos_ganador_resultado').innerText = '—';
            document.getElementById('puntos_perdedor_resultado').innerText = '—';
        }
    });

    inputGanador.addEventListener('input', () => {
        errorGanador.style.display = 'none';
        errorGanador.textContent = '';
    });

    inputPerdedor.addEventListener('input', () => {
        errorPerdedor.style.display = 'none';
        errorPerdedor.textContent = '';
    });
});