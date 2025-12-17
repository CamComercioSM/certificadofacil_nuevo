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

window.actualizarCantidadCertificado = function (index, input) {
    if (!certificadosDisponibles || !certificadosDisponibles[index]) return;

    let cantidad = parseInt(input.value, 10);
    if (isNaN(cantidad) || cantidad < 0) cantidad = 0;

    const cert = certificadosDisponibles[index];
    const valorUnitario = Number(cert.valor) || 0;

    cert.cantidad = cantidad;
    cert.subtotal = valorUnitario * cantidad;

    // Actualizar subtotal visual
    const spanSubtotal = document.getElementById(`subtotal-cert-${index}`);
    if (spanSubtotal) {
        spanSubtotal.textContent = formatearMoneda(cert.subtotal);
    }

    calcularTotalCertificados();
}

window.calcularTotalCertificados = function () {
    const textoResumen = document.getElementById('textoResumenCompra');
    const totalSpan = document.getElementById('totalCertificados');

    if (!textoResumen || !totalSpan) return;

    const seleccionados = certificadosDisponibles.filter(c => c.cantidad > 0);
    const total = seleccionados.reduce((acc, c) => acc + c.subtotal, 0);

    if (!seleccionados.length) {
        textoResumen.textContent = "No has seleccionado certificados aún.";
    } else {
        const detalles = seleccionados.map(c =>
            `${c.cantidad} x ${c.descripcioncertificado} (${formatearMoneda(c.subtotal)})`
        );
        textoResumen.innerHTML = detalles.join('<br>');
    }

    totalSpan.textContent = formatearMoneda(total);
}

window.formatearMoneda = function (valor) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(valor || 0);
}


window.copiarEnlacePago = async function () {
    const input = document.getElementById('campoEnlacePago');
    input.select();
    document.execCommand('copy');
    const datos = formulario('registrarCopiarEnlace', { certificadoFaciltransaID });
    const res = await conectarseEndPointSinModal('registrarCopiarEnlace', datos);
    console.log(res);
}
window.abrirEnlacePago = async function (url) {
    if (!url) return;
    window.open(url, '_blank', 'noopener');
    const datos = formulario('registrarAbrirEnlace', { certificadoFaciltransaID });
    const res = await conectarseEndPointSinModal('registrarAbrirEnlace', datos);
    console.log(res);
}
window.compartirEnlacePago = async function (enlace) {
    if (!enlace) return;

    const url = `https://wa.me/?text=${encodeURIComponent(enlace)}`;
    window.open(url, '_blank', 'noopener');
    const datos = formulario('registrarCompartirEnlace', { certificadoFaciltransaID });
    const res = await conectarseEndPointSinModal('registrarCompartirEnlace', datos);
    console.log(res);
}

window.formulario = function (operacion, params = {}) {
    const datos = new URLSearchParams();
    datos.append("controlador", "formulario");
    datos.append("operacion", operacion);
    if (typeof params !== 'object' || params === null) {
        return datos;
    }

    Object.entries(params).forEach(([key, value]) => {
        if (value === undefined || value === null) return;

        // 🔹 Soporta arrays
        if (Array.isArray(value)) {
            value.forEach(v => {
                if (v !== undefined && v !== null) {
                    datos.append(`${key}[]`, v);
                }
            });
            return;
        }

        // 🔹 Soporta objetos simples (1 nivel)
        if (typeof value === 'object') {
            Object.entries(value).forEach(([k, v]) => {
                if (v !== undefined && v !== null) {
                    datos.append(`${key}[${k}]`, v);
                }
            });
            return;
        }

        // 🔹 Primitivos
        datos.append(key, value);
    });

    return datos;
}