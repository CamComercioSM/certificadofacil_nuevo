let wz_class = ".wizard";
//let empresasBusqueda = null;
let camaraDeComercioID = null;
let criterioDeBusqueda = null;
let valorBusqueda = null;
let numero_matricula = null;
let razonsocial = null;
let nit = null;
let certificadosDisponibles = null;
let datosInicioDeTransaccion = null;
let empresasBusqueda = {
    "RESPUESTA": "EXITO",
    "MENSAJE": "",
    "DATOS": {
        "codigoerror": "0000",
        "mensajeerror": "",
        "total": 2,
        "expedientes": [
            {
                "tipopersona": "Persona",
                "matricula": "38266",
                "proponente": "",
                "nombre": "SERNA GOMEZ JESUS ANTONIO",
                "idclase": "1",
                "identificacion": "2525045",
                "nit": "",
                "organizacion": "01",
                "categoria": "",
                "estadomatricula": "MC",
                "ultanorenovado": "2004",
                "procesosentramite": "N",
                "embargosentramite": "N",
                "recursosentramite": "N",
                "fechamatricula": "1995-06-08",
                "fecharenovacion": "2004-01-16",
                "municipiotextual": "SANTA MARTA",
                "establecimientos": "[]",
                "certificados": "[{\"tipocertificado\":\"CerMat\",\"descripcioncertificado\":\"Certificado de matr\\u00edcula mercantil\",\"servicio\":\"01010101\",\"valor\":\"5800\"},{\"tipocertificado\":\"CerLibRegMer\",\"descripcioncertificado\":\"Certificado de libros de comercio\",\"servicio\":\"01010104\",\"valor\":\"11600\"}]"
            },
            {
                "tipopersona": "Persona",
                "matricula": "129316",
                "proponente": "",
                "nombre": "POSADA DE CORREA MARIA ADELINA DEL NIÑO JESUS",
                "idclase": "1",
                "identificacion": "25254904",
                "nit": "252549044",
                "organizacion": "01",
                "categoria": "",
                "estadomatricula": "MC",
                "ultanorenovado": "2011",
                "procesosentramite": "N",
                "embargosentramite": "N",
                "recursosentramite": "N",
                "fechamatricula": "2011-02-09",
                "fecharenovacion": "2011-02-09",
                "municipiotextual": "SANTA MARTA",
                "establecimientos": "[]",
                "certificados": "[{\"tipocertificado\":\"CerMat\",\"descripcioncertificado\":\"Certificado de matr\\u00edcula mercantil\",\"servicio\":\"01010101\",\"valor\":\"5800\"},{\"tipocertificado\":\"CerLibRegMer\",\"descripcioncertificado\":\"Certificado de libros de comercio\",\"servicio\":\"01010104\",\"valor\":\"11600\"}]"
            }
        ]
    },
    "ERROR": null
}
let registrarInicioTransaccion = {
    "RESPUESTA": "EXITO",
    "MENSAJE": "",
    "DATOS": {
        "certificadoFacilTransaID": 266070,
        "certificadoFacilTransaFCHSOLICITUD": "2025-12-09 16:26:42",
        "certificadoFacilTransaCAMARA": "32",
        "certificadoFacilTransaMATRICULA": "228017",
        "certificadoFacilTransaRAZONSOCIAL": "MIGUEL ANGEL ACEVEDO FLOREZ",
        "certificadoFacilTransaNIT": "10043827255",
        "certificadoFacilTransaIP": "190.8.178.82",
        "certificadoFacilTransaDATOSCOMPRA": null,
        "certificadoFacilTransaCODIGORECUPERACION": null,
        "certificadoFacilTransaNUMEROLIQUIDACION": null,
        "certificadoFacilTransaTOTAL": null,
        "certificadoFacilTransaENLACESII": null,
        "certificadoFacilTransaENLACE": null,
        "certificadoFacilTransaERROR": null,
        "certificadoFacilTransaPAGADO": "PENDIENTE",
        "certificadoFacilTransaFCHPAGO": null,
        "certificadoFacilTransaRECIBOPAGO": null,
        "certificadoFacilTransaOPERACIONPAGO": null,
        "certificadoFacilTransaNUMAUTORIZACION": null,
        "certificadoFacilTransaRADICADO": null,
        "certificadoFacilTransaDATOSVENTA": null,
        "certificadoFacilTransaLATITUD": "",
        "certificadoFacilTransaLONGITUD": "",
        "certificadoFacilTransaCANAL": "TIENDASICAM32",
        "certificadoFacilTransaESTADO": "ACTIVO",
        "certificadoFacilTransaUSRCAMBIO": null,
        "certificadoFacilTransaFCHCAMBIO": "2025-12-09 16:26:42"
    },
    "ERROR": null
}
let generarEnlacePago = {
  "RESPUESTA": "EXITO",
  "MENSAJE": "",
  "DATOS": {
    "codigoerror": "0000",
    "mensajeerror": "",
    "idliquidacion": 2543742,
    "numerorecuperacion": "9V0XTT",
    "urlparapago": "https://siisantamarta.confecamaras.co/lanzarVirtual.php?_empresa=32&_opcion=pagoelectronico&_numrec=9V0XTT",
    "valortotal": 5800
  },
  "ERROR": null
}

const args = {
    "wz_class": ".wizard",
    "wz_nav_style": "dots",
    "wz_button_style": ".btn .btn-sm .mx-3",
    "wz_ori": "horizontal",
    "buttons": true,
    "navigation": "all",
    "finish": "Generar link de pago",
    "bubble": true,
    "next": "buscar",
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

    }
    if (step === 3) {
    }

});


async function cargarInformacionCamarasAndTipoBusqueda() {
    const selectCamaraComercio = document.getElementById('camaraDeComercio');
    const selectTipoBusqueda = document.getElementById('criterioDeBusqueda');


    if (!selectTipoBusqueda || !selectCamaraComercio) return;
    // const respuesta = await conectarseEndPoint('datosRecuperacionCertificados');
    let respuesta = {
        camaras: [
            { id: "CCSM", nombre: "Cámara de Comercio de Santa Marta" },
            { id: "CCB", nombre: "Cámara de Comercio de Bogotá" }
        ],
        tiposBusqueda: [

            { id: "RAZONSOCIAL", nombre: "Razón Social" },
            { id: "NIT", nombre: "NIT" },
            { id: "MATRICULA", nombre: "Número de Matrícula" },
        ]
    };
    selectCamaraComercio.innerHTML = '<option value="">Seleccione...</option>';

    respuesta.camaras.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.id;
        opt.textContent = item.nombre;
        selectCamaraComercio.appendChild(opt);
    });

    selectTipoBusqueda.innerHTML = '<option value="">Seleccione...</option>';

    respuesta.tiposBusqueda.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.id;
        opt.textContent = item.nombre;
        selectTipoBusqueda.appendChild(opt);
    });

}
async function crearTarjetasDeEmpresasDisponibles() {
    const contenedor = document.getElementById('selectEmpresa');
    // if (!camaraDeComercioID || !criterioDeBusqueda || !valorBusqueda) {
    //     document.getElementById('contenedorTarjetasEmpresas').innerHTML = '<p class="text-center">Por favor seleccione la cámara de comercio, el criterio de búsqueda e ingrese un valor para la búsqueda.</p>';
    //     return;
    // }
    // const res = await conectarseEndPoint('buscarEmpresas', { camaraDeComercioID, criterioDeBusqueda, valorBusqueda });
    const res = empresasBusqueda;
    const resp = res.DATOS || [];
    empresasBusqueda = resp.expedientes || [];

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
        // 🔧 Ajusta estos campos a tu respuesta real
        const tipo = emp.tipopersona || "-";
        const matricula = emp.matricula || "-";
        const razonSocial = emp.nombre || "-";
        const nit = emp.nit || emp.identificacion || "-";
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
                    <span class="text-muted small">${nit}</span>
                </td>
                <td class="text-end">
                    <div class="text-muted small">Actualizado el</div>
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


}
function selectEmpresa(matricula, element) {
    if (!empresasBusqueda || !empresasBusqueda.length) return;
    console.log('Matrícula seleccionada:', matricula);
    // Buscar la empresa por matrícula
    const empresa = empresasBusqueda.find(emp =>
        emp.matricula === matricula);
    
    if (empresa) {
        numero_matricula = empresa.matricula || null;
        razonsocial = empresa.nombre || null;
        nit = empresa.identificacion || null;
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

    // Pasar al siguiente paso del wizard
    avanzarPaso();
}


async function crearTiposDeCertificadosDisponibles() {
    const contenedor = document.getElementById('selectcertificados');
    if (!contenedor) return;
    if (!numero_matricula || !razonsocial || !nit) return;
    // Si quieres usar el inicio de transacción, ya tienes el resp aquí:
    // const res  = await conectarseEndPoint('registrarInicioTransaccion', {numero_matricula, razonsocial, nit});
    // const resp = res.DATOS || {};
    const resp = registrarInicioTransaccion.DATOS;
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




window.conectarseEndPoint = async function (operacion, params = {}) {
    const api = 'https://api.citurcam.com/' + operacion;

    if (typeof params !== 'object' || params === null) {
        params = params.toString();
    }
    mostrarModalDeCarga();
    try {
        const response = await fetch(api, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: JSON.stringify(params)
        });

        if (!response.ok) {
            throw new Error('Error en la petición: ' + response.status);
        }

        return await response.json();

    } catch (error) {
        console.error("Error en conectarseEndPoint:", error);
        throw error;

    } finally {
        mostrarModalDeCarga(false);
    }
}
function mostrarModalDeCarga(opcion = true) {
    if (opcion) {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    } else {
        document.getElementById('loadingOverlay').classList.add('d-none');
    }
}
