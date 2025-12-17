window.conectarseEndPoint = async function (operacion, datos = {}) {

    const url = `data.php?operacion=${encodeURIComponent(operacion)}`;
    mostrarModalDeCarga(true);
    let body;

    try {
        if (datos instanceof URLSearchParams) {
            body = datos;
        } else if (typeof datos === 'object' && datos !== null) {
            body = JSON.stringify(datos);
        } else {
            body = datos.toString();
        }
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body
        });

        if (!response.ok) {
            const txt = await response.text().catch(() => "");
            throw new Error(`HTTP ${response.status}: ${txt || "Error en la petición"}`);
        }

        const data = await response.json();

        return data;

    } catch (error) {
        console.error("Error en conectarseEndPoint:", error);
        throw error;

    } finally {
        mostrarModalDeCarga(false);
    }
}

window.mostrarModalDeCarga = function (opcion = true) {
    if (opcion) {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    } else {
        document.getElementById('loadingOverlay').classList.add('d-none');
    }
}
window.mostrarAlertaDePasoVacio = function (contenedor, mensaje) {
    if (!contenedor) return;

    contenedor.innerHTML = `
        <div class="alerta-paso-vacio">
            ${mensaje}
        </div>

        <!-- Input "fantasma" requerido que bloquea el paso -->
        <input 
            type="text" 
            class="input-bloqueo-paso"
            required
            value=""
            tabindex="-1"
            aria-hidden="true"
        >
    `;
}
window.conectarseEndPointSinModal = async function (operacion, datos = {}) {

    const url = `data.php?operacion=${encodeURIComponent(operacion)}`;
    let body;
    try {
        if (datos instanceof URLSearchParams) {
            body = datos;
        } else if (typeof datos === 'object' && datos !== null) {
            body = JSON.stringify(datos);
        } else {
            body = datos.toString();
        }
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body
        });
        if (!response.ok) {
            const txt = await response.text().catch(() => "");
            throw new Error(`HTTP ${response.status}: ${txt || "Error en la petición"}`);
        }

        const data = await response.json();

        if (data?.RESPUESTA && data.RESPUESTA !== "EXITO") {
            throw new Error(data.MENSAJE || "La operación devolvió error");
        }

        return data;

    } catch (error) {
        console.error("Error en conectarseEndPoint:", error);
        throw error;
    }
}