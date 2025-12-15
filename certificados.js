let wz_class = ".wizard";
let empresasBusqueda = null;
let camara_comercio = null;
let criterio_busqueda = null;
let palabra_clave = null;
let numero_matricula = null;
let razonsocial = null;
let nit = null;
let certificadosDisponibles = null;
let datosInicioDeTransaccion = null;
let tiposBusqueda = [
    { id: "RAZONSOCIAL", nombre: "Razón Social" },
    { id: "NIT", nombre: "NIT" },
    { id: "MATRICULA", nombre: "Número de Matrícula" },
]

const args = {
    "wz_class": ".wizard",
    "wz_nav_style": "dots",
    "wz_button_style": ".btn .btn-sm .mx-3",
    "wz_ori": "horizontal",
    "buttons": true,
    "navigation": "all",
    "finish": "Finalizar",
    "bubble": true,
    "next": "Siguiente",
    "prev": "Atras"
};

const wizard = new Wizard(args);
wizard.init();
const $wz_doc = document.querySelector(wz_class);
cargarInformacionCamarasAndTipoBusqueda();
function avanzarPaso() {
    const nextBtn = document.querySelector('.wizard .wizard-btn.next');
    if (nextBtn) {
        nextBtn.click();
    }
}
$wz_doc.addEventListener("wz.btn.next", function (e) {

    const step = wizard.current_step;

    if (step === 0) {
        crearTarjetasDeEmpresasDisponibles();
    }
    if (step === 1) {
        crearTiposDeCertificadosDisponibles();
    }
    if (step === 2) {
        generarLinkDePago();
    }
});

let selectCamaraComercio = document.getElementById('camaraDeComercio');
let inputBusqueda = document.getElementById('palabraClave');
let selectTipoBusqueda = document.getElementById('criterioDeBusqueda');
selectCamaraComercio.addEventListener('change', function () {

    const camaraIDSeleccionada = Number(this.value);
    const textoBusqueda = inputBusqueda.value.trim();

    if (textoBusqueda.length === 0) {
        console.warn("Debes escribir una palabra clave antes de redirigir.");
        return;
    }

    // 2. Obtener la cámara seleccionada
    const camara = listadoCamaras.DATOS.find(c => c.camaraID === camaraIDSeleccionada);
    if (!camara) return;

    // 3. No redirigir si la cámara es 32
    if (camara.camaraID === 32) {
        console.log("Esta cámara no redirige.");
        return;
    }

    // 4. Validar que exista un enlace de redirección
    const enlace = camara.camaraUrlENLACE_VISTAPROPIA;
    if (!enlace) {
        console.warn("La cámara no tiene enlace configurado.");
        return;
    }

    // 5. Redirigir
    window.open(enlace, "_blank");
});

async function cargarInformacionCamarasAndTipoBusqueda() {
    const selectCamaraComercio = document.getElementById('camaraDeComercio');
    const selectTipoBusqueda = document.getElementById('criterioDeBusqueda');
    if (!selectTipoBusqueda || !selectCamaraComercio) return;
    const res = await conectarseEndPoint('listadoCamaras');
    const resp = res.DATOS || [];
    //let resp = listadoCamaras.DATOS;

    resp.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.camaraID;
        opt.textContent = item.camaraNOMBRE;
        selectCamaraComercio.appendChild(opt);
    });
    selectCamaraComercio.value = "32";

    tiposBusqueda.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.id;
        opt.textContent = item.nombre;
        selectTipoBusqueda.appendChild(opt);
    });

}

async function crearTarjetasDeEmpresasDisponibles() {
    camara_comercio = document.getElementById('camaraDeComercio');
    criterio_busqueda = document.getElementById('criterioDeBusqueda');
    palabra_clave = document.getElementById('palabraClave');
    const contenedor = document.getElementById('selectEmpresa');
    if (!camara_comercio || !criterio_busqueda || !palabra_clave) {
        document.getElementById('contenedorTarjetasEmpresas').innerHTML = '<p class="text-center">Por favor seleccione la cámara de comercio, el criterio de búsqueda e ingrese un valor para la búsqueda.</p>';
        return;
    }
    mostrarModalDeCarga();
    const res = await conectarseEndPoint('buscarTiposCertificados', { camara_comercio, criterio_busqueda, palabra_clave });
    //const res = empresasBusqueda;
    const resp = res.DATOS || [];
    empresasBusqueda = resp.expedientes;

    // Construimos "tabla" pero cada fila es una tarjeta
    let html = `
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tipo / Matrícula</th>
                        <th>Razón social</th>
                        <th>NIT / Num. ID</th>
                        <th class="text-end">Actualizado el</th>
                    </tr>
                </thead>
                <tbody>
    `;

    empresasBusqueda.forEach((emp, index) => {
        const tipo = emp.tipopersona || "-";
        const matricula = emp.matricula || "-";
        const razonSocial = emp.nombre || "-";
        const nit = emp.identificacion || "-";
        const renovacion = emp.fecharenovacion || "-";

        html += `
            <tr class="empresa-card">
                <td>
                    <label class="option-card w-100 mb-0">
                        <input 
                            type="radio" 
                            name="empresaSeleccionada" 
                            id="empresa${index}" 
                            value="${matricula}"
                            data-index="${index}"
                            data-require-if="flagEmpresa:0"
                            onchange="selectEmpresa('${matricula}', this)"
                        >
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">${tipo}</span>
                            <span class="text-muted small">Matrícula: ${matricula}</span>
                        </div>
                    </label>
                </td>
                <td>
                    <div class="fw-semibold">${razonSocial}</div>
                </td>
                <td>
                    <span class="text-black small">${nit}</span>
                </td>
                <td class="text-end">
                    <div class="fw-semibold">${renovacion}</div>
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    contenedor.innerHTML = html;
    setTimeout(() => mostrarModalDeCarga(false), 3000);
}
function selectEmpresa(matricula, element) {
    console.log('Matrícula seleccionada:', matricula);
    numero_matricula = matricula;
    // Buscar la empresa por matrícula
    const empresa = empresasBusqueda.find(emp =>
        emp.matricula == numero_matricula);
    if (empresa) {
        numero_matricula = empresa.matricula || '';
        razonsocial = empresa.nombre || '';
        proponente = empresa.proponente || '';
        nit = empresa.nit || empresa.identificacion || '';
        certificadosDisponibles = JSON.parse(empresa.certificados) || [];
    }

    // Quitar selección visual previa
    document
        .querySelectorAll("#selectEmpresa .empresa-card")
        .forEach(row => row.classList.remove("selected"));

    // Marcar la fila actual
    const row = element.closest('.empresa-card');
    if (row) {
        row.classList.add("selected");
    }

    // Actualizar flag para validación Wizard-JS
    const flag = document.getElementById('flagEmpresaSeleccionada');
    const algunoMarcado = !!document.querySelector('input[name="empresaSeleccionada"]:checked');
    if (flag) flag.value = algunoMarcado ? '1' : '0';

    avanzarPaso();
}


async function crearTiposDeCertificadosDisponibles() {
    const contenedor = document.getElementById('selectcertificados');
    if (!contenedor) return;
    if (!numero_matricula || !razonsocial || !nit) return;
    // Si quieres usar el inicio de transacción, ya tienes el resp aquí:
    const res  = await conectarseEndPoint('registrarInicioTransaccion', {numero_matricula, razonsocial, nit,  camara: camara_comercio});
    const resp = res.DATOS || {};
    datosInicioDeTransaccion = resp;
    // Aquí puedes usar `resp` si lo necesitas (idTransaccion, etc.)

    if (!certificadosDisponibles || !certificadosDisponibles.length) {
        contenedor.innerHTML = `
            <div class="alert alert-warning mb-0 text-center">
                No hay certificados disponibles para esta empresa.
            </div>
        `;
        return;
    }

    // Inicializar cantidad y subtotal en el array global
    certificadosDisponibles.forEach(c => {
        c.cantidad = 0;
        c.subtotal = 0;
    });

    let html = `
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Certificado</th>
                        <th class="text-end">Precio unitario</th>
                        <th class="text-center" style="width:110px;">Cantidad</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
    `;

    certificadosDisponibles.forEach((cert, index) => {
        const valorUnitario = Number(cert.valor) || 0;

        html += `
            <tr>
                <td>
                    <div class="fw-semibold">
                        ${cert.descripcioncertificado}
                    </div>
                    <div class="text-muted small">
                        Ref: ${cert.tipocertificado}
                    </div>
                </td>
                <td class="text-end">
                    ${formatearMoneda(valorUnitario)}
                </td>
                <td class="text-center">
                    <input 
                        type="number"
                        min="0"
                        value="0"
                        class="form-control form-control-sm text-center"
                        oninput="actualizarCantidadCertificado(${index}, this)"
                    >
                </td>
                <td class="text-end">
                    <span id="subtotal-cert-${index}">
                        ${formatearMoneda(0)}
                    </span>
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <h6 class="text-uppercase text-muted mb-1" style="font-size:0.8rem;">
                Resumen de compra
            </h6>
            <p id="textoResumenCompra" class="mb-1 text-muted small">
                No has seleccionado certificados aún.
            </p>
            <div class="fw-semibold">
                Total: <span id="totalCertificados">${formatearMoneda(0)}</span>
            </div>
        </div>
    `;

    contenedor.innerHTML = html;
}

async function generarLinkDePago() {
    mostrarModalDeCarga();
    if (!datosInicioDeTransaccion) return;
    const contenedor = document.getElementById('resumenGeneralPago');
    let certificadoFaciltransaID = datosInicioDeTransaccion.certificadoFaciltransaID;
    const res = await conectarseEndPoint('generarEnlacePago', {matricula: numero_matricula, proponente, razonsocial, certificadoFaciltransaID});
    const resp = res.DATOS || [];
    const liquidacion = resp.idliquidacion;
    const recuperacion = resp.numerorecuperacion || "SIN-DATO";
    const enlace = resp.urlparapago || "#";
    const qr = resp.qrBase64 || "";

    let html = `
        <div class="resumen-pago mt-3">

            <h5 class="mb-1">Resumen del pago</h5>
            <p class="text-muted mb-4">
                Conserva estos datos para futuras consultas o reimpresión de certificados.
            </p>

            <!-- FILA PRINCIPAL -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="info-box">
                        <div class="info-label">ID Liquidación</div>
                        <div class="info-value">${liquidacion}</div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="info-box">
                        <div class="info-label">Número de recuperación</div>
                        <div class="info-value">${recuperacion}</div>
                    </div>
                </div>
            </div>

            <!-- ENLACE + QR -->
            <div class="row g-4 align-items-start">
                
                <!-- IZQUIERDA -->
                <div class="col-12 col-lg-7">

                    <label class="fw-semibold mb-1">Enlace de pago</label>

                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="campoEnlacePago" value="${enlace}" readonly>
                        <button class="btn btn-outline-secondary" onclick="copiarEnlacePago()">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>

                    <small class="text-muted">Puedes copiar el enlace si deseas pagarlo desde otro dispositivo.</small>

                    <!-- BOTONES -->
                    <div class="d-flex flex-column flex-md-row gap-2 mt-3">

                        <a href="${enlace}" target="_blank" class="btn btn-primary w-100">
                            <i class="bi bi-link-45deg me-1"></i>
                            Abrir enlace de pago
                        </a>

                        <a href="https://wa.me/?text=${encodeURIComponent(enlace)}" 
                           target="_blank" 
                           class="btn btn-outline-success w-100">
                            <i class="bi bi-whatsapp me-1"></i>
                            Enviar enlace por WhatsApp
                        </a>

                        <button type="button" 
                                class="btn btn-outline-secondary w-100" 
                                onclick="wizard.prev()">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver a certificados
                        </button>
                    </div>
                </div>

                <!-- DERECHA QR -->
                <div class="col-12 col-lg-5">
                    <div class="qr-box text-center">
                        ${qr ? `<img src="data:image/png;base64,${qr}" class="img-fluid" />`
            : `<div class="qr-placeholder"></div>`}
                        <small class="text-muted d-block mt-2">Escanea el código desde tu celular para pagar.</small>
                    </div>
                </div>

            </div>

            <!-- BOTÓN DE DESCARGA -->
            <div class="mt-4">
                <button class="btn btn-success w-100 py-2 fw-semibold">
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>
                    Si ya pagaste, puedes descargar tu certificado dando clic aquí
                </button>
            </div>

        </div>
    `;

    contenedor.innerHTML = html;
    setTimeout(() => mostrarModalDeCarga(false), 3000);
}


function actualizarCantidadCertificado(index, input) {
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

function calcularTotalCertificados() {
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

function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(valor || 0);
}

async function conectarseEndPoint(operacion) {
    const api = 'data.php?operacion=' + encodeURIComponent(operacion);

    mostrarModalDeCarga(true);

    try {
        const response = await fetch(api, { method: 'GET' });

        if (!response.ok) {
            throw new Error('Error en la petición: ' + response.status);
        }

        return await response.json();

    } catch (error) {
        console.error('Error en conectarseEndPoint:', error);
        throw error;

    } finally {
        mostrarModalDeCarga(false);
    }
}

// window.conectarseEndPoint = async function (operacion, params = {}) {
//     const api = 'https://api.citurcam.com/' + operacion;

//     if (typeof params !== 'object' || params === null) {
//         params = params.toString();
//     }
//     mostrarModalDeCarga();
//     try {
//         const response = await fetch(api, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: JSON.stringify(params)
//         });

//         if (!response.ok) {
//             throw new Error('Error en la petición: ' + response.status);
//         }

//         return await response.json();

//     } catch (error) {
//         console.error("Error en conectarseEndPoint:", error);
//         throw error;

//     } finally {
//         mostrarModalDeCarga(false);
//     }
// }
function mostrarModalDeCarga(opcion = true) {
    if (opcion) {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    } else {
        document.getElementById('loadingOverlay').classList.add('d-none');
    }
}
