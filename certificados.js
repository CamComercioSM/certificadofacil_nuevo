let wz_class = ".wizard";
let empresasBusqueda = null;
let camara_comercio = null;
let criterio_busqueda = null;
let palabra_clave = null;
let numero_matricula = null;
let razonsocial = null;
let NIT = null;
let certificadoFaciltransaID = null;
let certificadosDisponibles = null;
let datosInicioDeTransaccion = null;
let timerEstadoPago = null;
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
function regresarPaso() {
    const prevBtn = document.querySelector('.wizard .wizard-btn.prev');
    if (prevBtn) {
        prevBtn.click();
    }
}

$wz_doc.addEventListener("wz.btn.next", function (e) {

    const step = wizard.current_step;

    if (step === 1) {
        crearTiposDeCertificadosDisponibles();
    }
    if (step === 2) {
        generarLinkDePago();
        timerEstadoPago = setInterval(async () => {
            const certificadoFaciltransaID = datosInicioDeTransaccion.certificadoFacilTransaID;
            if (!certificadoFaciltransaID) return;
            const datos = formulario('consultarEstadoPagoSII', { certificadoFaciltransaID });
            try {
                const res = await conectarseEndPointSinModal('consultarEstadoPagoSII', datos);
                console.log(res);
            } catch (e) {
                console.error(e);
            }
        }, 30000);
    }
});
let selectCamaraComercio = document.getElementById('camaraDeComercio');
let inputBusqueda = document.getElementById('palabraClave');
let TipoBusqueda = document.getElementById('criterioDeBusqueda');
selectCamaraComercio.addEventListener('change', function () {

    const camaraIDSeleccionada = Number(this.value);
    const textoBusqueda = inputBusqueda.value.trim();

    if (textoBusqueda.length === 0) {
        console.warn("Debes escribir una palabra clave antes de redirigir.");
        return;
    }

    const camara = listadoCamaras.DATOS.find(c => c.camaraID === camaraIDSeleccionada);
    if (!camara) return;

    if (camara.camaraID === 32) {
        return;
    }

    const enlace = camara.camaraUrlENLACE_VISTAPROPIA;
    if (!enlace) {
        console.warn("La cámara no tiene enlace configurado.");
        return;
    }

    window.open(enlace, "_blank");
});


async function cargarInformacionCamarasAndTipoBusqueda() {
    const selectCamaraComercio = document.getElementById('camaraDeComercio');
    const tipoBusqueda = document.getElementById('criterioDeBusqueda');
    if (!tipoBusqueda || !selectCamaraComercio) return;

    const res = await conectarseEndPoint("listadoCamaras");
    const resp = res.DATOS || [];

    resp.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.camaraID;
        opt.textContent = item.camaraNOMBRE;
        selectCamaraComercio.appendChild(opt);
    });
    selectCamaraComercio.value = "32";

    tipoBusqueda.innerHTML = "";

    tiposBusqueda.forEach((item, index) => {
        tipoBusqueda.innerHTML += `
      <input type="radio" class="btn-check"
        name="criterio_busqueda"
        id="tipoBusqueda${index}"
        value="${item.id}"
        onchange="selectTipoBusqueda('${item.id}')">
      <label class="btn btn-outline-primary" for="tipoBusqueda${index}">
        ${item.nombre}
      </label>
    `;
    });

    const first = document.querySelector('#criterioDeBusqueda input[name="criterio_busqueda"]');
    if (first) {
        first.checked = true;
        selectTipoBusqueda(first.value);
    }
}

function selectTipoBusqueda(tipo) {
    criterio_busqueda = tipo;

    const flag = document.getElementById('flagTipoBusqueda');
    if (flag) flag.value = '1';
}
function buscarEmpresasDisponibles() {
    const btnBuscar = document.getElementById('btnBuscar');

    btnBuscar.addEventListener('click', async () => {
        if (btnBuscar.disabled) return;   // evita re-entradas
        btnBuscar.disabled = true;

        try {
            camara_comercio = document.getElementById('camaraDeComercio')?.value;
            palabra_clave = document.getElementById('palabraClave')?.value;
            if (!camara_comercio || !criterio_busqueda || !palabra_clave) return;

            const datos = formulario('buscarTiposCertificados', {
                camara_comercio,
                criterio_busqueda,
                palabra_clave,
                pagina: 0
            });

            let res = await conectarseEndPoint('buscarTiposCertificados', datos);

            if (res.RESPUESTA !== 'EXITO') {
                Swal.fire({
                    icon: 'info',
                    title: 'Atención',
                    text: res.MENSAJE || 'La operación no fue exitosa'
                });
                return;
            }

            const expedientes = res?.DATOS?.expedientes || [];
            empresasBusqueda = expedientes;

            if (empresasBusqueda?.length) {
                await crearTarjetasDeEmpresasDisponibles();
                avanzarPaso();
            }
        } finally {
            btnBuscar.disabled = false; // siempre lo re-habilita
        }
    });
}

async function crearTarjetasDeEmpresasDisponibles() {
    const contenedor = document.getElementById('selectEmpresa');
    if (!contenedor) return;

    const flag = document.getElementById('flagEmpresas');
    if (flag) flag.value = '0';

    if (!empresasBusqueda?.length) {
        contenedor.innerHTML = '';
        mostrarAlertaDePasoVacio(contenedor, 'No hay empresas disponibles para mostrar');
        return;
    }

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
        const tipo = emp?.tipopersona || "-";
        const matricula = emp?.matricula || "-";
        const razonSocial = emp?.nombre || "-";
        const nit = emp?.identificacion || "-";
        const renovacion = emp?.fecharenovacion || "-";

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
                  data-require-if="flagEmpresas:0"
                  onchange="selectEmpresa('${matricula}', this)"
                >
                <div class="d-flex flex-column">
                  <span class="fw-semibold">${tipo}</span>
                  <span class="text-muted small">Matrícula: ${matricula}</span>
                </div>
              </label>
            </td>
            <td><div class="fw-semibold">${razonSocial}</div></td>
            <td><span class="text-black small">${nit}</span></td>
            <td class="text-end"><div class="fw-semibold">${renovacion}</div></td>
          </tr>
        `;
    });

    html += `
          </tbody>
        </table>
      </div>
    `;

    contenedor.innerHTML = html;
}

function selectEmpresa(matricula, element) {
    numero_matricula = matricula;
    // Buscar la empresa por matrícula
    const empresa = empresasBusqueda.find(emp =>
        emp.matricula == numero_matricula);
    if (empresa) {
        numero_matricula = empresa.matricula || '';
        razonsocial = empresa.nombre || '';
        proponente = empresa.proponente || '';
        NIT = empresa.nit || empresa.identificacion || '';
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
    if (!numero_matricula || !razonsocial || !NIT) return;
    // Si quieres usar el inicio de transacción, ya tienes el resp aquí:
    const datos = formulario('registrarInicioTransaccion', {
        numero_matricula,
        razonsocial,
        nit: NIT,
        camara: camara_comercio
    });
    let res;
    try {
        res = await conectarseEndPoint('registrarInicioTransaccion', datos);
    } catch (e) {
        contenedor.innerHTML = '';
        mostrarAlertaDePasoVacio(contenedor, e?.message || 'No fue posible iniciar la transacción.');
        return;
    }
    const resp = res.DATOS || {};
    datosInicioDeTransaccion = resp;
    certificadoFaciltransaID = datosInicioDeTransaccion.certificadoFacilTransaID;

    const flagCert = document.getElementById('flagCertificados');
    if (flagCert) flagCert.value = '0';
    if (!certificadosDisponibles || !certificadosDisponibles.length) {
        contenedor.innerHTML = '';
        mostrarAlertaDePasoVacio(contenedor, 'No hay certificados disponibles para esta empresa.');
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
                        name="cantidad"
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
    if (flagCert) flagCert.value = '1';
    contenedor.innerHTML = html;
}

async function generarLinkDePago() {
    if (!datosInicioDeTransaccion) return;

    const contenedor = document.getElementById('resumenGeneralPago');
    const datos = formulario('generarEnlacePago', {
        "matricula": numero_matricula,
        "proponente": proponente,
        razonsocial,
        certificadoFaciltransaID
    });
    if (certificadosDisponibles) {
        certificadosDisponibles.forEach(cert => {
            if (!cert.servicio) return;
            datos.append(`certificados[${cert.servicio}][]`, cert.valor);
            datos.append(`certificados[${cert.servicio}][]`, cert.cantidad);
        });
    }
    const res = await conectarseEndPoint("generarEnlacePago", datos);
    const resp = res.DATOS || [];
    const liquidacion = resp.idliquidacion;
    const recuperacion = resp.numerorecuperacion || "SIN-DATO";
    const enlace = resp.urlparapago || "#";
    let qr = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' +
        encodeURIComponent(enlace);

    let html = `
        <div class="resumen-pago mt-3">

            <h5 class="mb-1">Resumen del pago</h5>
            <p class="text-muted mb-4">
                Los numeros de liquidacion y recuperacion pueden usarse para completar el proceso de pago con ayuda de un asesor
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
                    <button type="button" class="btn btn-outline-secondary" onclick="copiarEnlacePago()">
                        <i class="bi bi-clipboard"></i>
                    </button>
                    </div>

                    <small class="text-muted">Puedes copiar el enlace si deseas pagarlo desde otro dispositivo.</small>

                    <!-- BOTONES -->
                    <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                        <button
                            type="button"
                            class="btn btn-primary w-100"
                            onclick="abrirEnlacePago('${enlace}')">
                            <i class="bi bi-link-45deg me-1"></i>
                            Abrir enlace de pago
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-success w-100"
                            onclick="compartirEnlacePago('${enlace}')">
                            <i class="bi bi-whatsapp me-1"></i>
                            Enviar enlace por WhatsApp
                        </button>

                        <button type="button" 
                                class="btn btn-outline-secondary w-100" 
                                onclick="clearInterval(timerEstadoPago); regresarPaso();">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver a certificados
                        </button>
                    </div>
                </div>

                <!-- DERECHA QR -->
                <div class="col-12 col-lg-5">
                    <div class="qr-box text-center">
                        ${qr
            ? `<img src="${qr}" class="img-fluid" alt="QR de pago" />`
            : `<div class="qr-placeholder"></div>`
        }
                        <small class="text-muted d-block mt-2">
                            Escanea el código desde tu celular para pagar.
                        </small>
                    </div>
                </div>
            </div>

            <!-- BOTÓN DE DESCARGA -->
            <div class="mt-4">
                <button
                    type="button"
                    class="btn btn-success w-100 py-2 fw-semibold"
                    onclick="validarEstadoPagoSII()"
                >
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>
                    Si ya pagaste, puedes descargar tu certificado dando clic aquí
                </button>
            </div>

        </div>
    `;

    contenedor.innerHTML = html;
}

async function validarEstadoPagoSII() {
    if (!datosInicioDeTransaccion) return;

    const datos = formulario('validarEstadoPagoSII', {
        certificadoFaciltransaID
    });

    try {
        const res = await conectarseEndPoint('validarEstadoPagoSII', datos);

        if (res?.RESPUESTA && res.RESPUESTA !== 'EXITO') {
            Swal.fire({
                icon: res.RESPUESTA === 'ALERTA' ? 'warning' : 'info',
                title: 'Estado de la transacción',
                html: res.MENSAJE || 'No fue posible validar el estado del pago.',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Éxito
        Swal.fire({
            icon: 'success',
            title: 'Pago confirmado',
            html: res.MENSAJE || 'El pago fue validado correctamente.',
            confirmButtonText: 'Aceptar'
        });

    } catch (e) {
        Swal.fire({
            icon: 'error',
            title: 'Error de comunicación',
            text: e.message || 'Ocurrió un error al validar el estado del pago',
            confirmButtonText: 'Aceptar'
        });
    }
}



