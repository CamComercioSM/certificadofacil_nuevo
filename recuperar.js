let wz_class = ".wizard";
let certificados = null;
let informacionDeTransaccion = null;
const args = {
    "wz_class": ".wizard",
    "wz_nav_style": "tabs",
    "wz_button_style": ".btn .btn-sm .mx-3",
    "wz_ori": "horizontal",
    "navigation": "nav",
    "finish": "Finalizar",
    "bubble": true,
    "next": "Siguiente",
    "prev": "Atras"
};

let wizard = new Wizard(args);
wizard.init();
const $wz_doc = document.querySelector(wz_class);

async function recuperarCertificadoConMatricula() {
    const matricula = document.getElementById('matricula').value;
    const codigo = document.getElementById('codigoRecuperacion').value;
    const liquidacion = document.getElementById('numeroLiquidacion').value;
    if (!matricula || !codigo || !liquidacion) return;
    const datos = formulario('recuperarCertificados', { matricula, codigo, liquidacion });

    const res = await conectarseEndPoint('recuperarCertificados', datos);
    if (res?.RESPUESTA && res.RESPUESTA !== 'EXITO') {
        Swal.fire({
            icon: res.RESPUESTA === 'ALERTA' ? 'warning' : 'info',
            title: 'Recuperacion de certificados',
            text: 'No hay certificados para los datos ingresados',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    informacionDeTransaccion = res.DATOS.RECIBO || {};
    certificados = informacionDeTransaccion.certificados || '';
    renderVistaDeDescargarCertificados();
}

async function recuperarCertificadoConReciboDePago() {
    const recibo = document.getElementById('numeroReciboPago').value;
    if (!recibo) return;
    const datos = formulario('recuperarCertificados', { recibo });

    const res = await conectarseEndPoint('recuperarCertificados', datos);
    if (res?.RESPUESTA && res.RESPUESTA !== 'EXITO') {
        Swal.fire({
            icon: res.RESPUESTA === 'ALERTA' ? 'warning' : 'info',
            title: 'Recuperacion de certificados',
            text: res.MENSAJE || 'No hay certificados para los datos ingresados',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    informacionDeTransaccion = res.DATOS.RECIBO || {};
    certificados = informacionDeTransaccion.certificados || '';
    renderVistaDeDescargarCertificados();
}

function renderVistaDeDescargarCertificados() {
    const win = document.querySelector('.appointment-window-recuperar');
    const info = informacionDeTransaccion || {};
    let botonesHtml = '';
    if (certificados.length) {
        botonesHtml = certificados.map((c, idx) => {
            const url = c.path;
            const tipo = c.tipocertificado || 'Certificado';
            const codigo = c.codigoverificacion || '';

            if (!url) return '';

            return `
      <a href="${url}" target="_blank" rel="noopener noreferrer"
         class="btn btn-primary btn-lg text-start d-flex align-items-center justify-content-between">
        <span class="d-flex align-items-center gap-2">
          <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
          <span>
            <div class="fw-semibold">Descargar ${tipo}</div>
            <div class="small opacity-75">Código: ${codigo}</div>
          </span>
        </span>
        <i class="bi bi-box-arrow-up-right"></i>
      </a>
    `;
        }).join('');
    } else {
        botonesHtml = `<div class="text-muted">No hay certificados para descargar.</div>`;
    }
    win.innerHTML = `
    <div class="text-center mb-4">
      <div class="h3 fw-bold py-2 mb-1">Descarga de certificados</div>
      <h6 class="text-muted mb-0">Transacción recuperada exitosamente</h6>
    </div>

    <div class="row g-3">
      <!-- Izquierda: info transacción -->
      <div class="col-12 col-lg-7">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <div class="d-flex align-items-start justify-content-between mb-3">
        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-receipt fs-4 text-primary"></i>
          Información de la transacción
        </h5>
        <span class="badge text-bg-success d-flex align-items-center gap-1">
          <i class="bi bi-check2-circle"></i> Exitosa
        </span>
      </div>

        <div class="p-3 rounded-3 bg-light border mb-3">
            <div class="text-muted small">Comprador</div>
            <div class="fw-semibold fs-5 lh-sm">${info.nombre ?? ''}</div>

            <div class="mt-2 d-flex flex-wrap gap-2">
            <span class="badge text-bg-secondary">
                <i class="bi bi-person-vcard me-1"></i>${info.identificacion ?? ''}
            </span>
            <span class="badge text-bg-secondary">
                <i class="bi bi-envelope me-1"></i>${info.email ?? ''}
            </span>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
            <div class="text-muted small">Liquidación</div>
            <div class="fw-semibold">${info.idliquidacion ?? ''}</div>
            </div>

            <div class="col-12 col-md-6">
            <div class="text-muted small">Recibo</div>
            <div class="fw-semibold">${info.recibo ?? ''}</div>
            </div>

            <div class="col-12 col-md-6">
            <div class="text-muted small">Fecha</div>
            <div class="fw-semibold">${info.fecha ?? ''}</div>
            </div>

            <div class="col-12 col-md-6">
            <div class="text-muted small">Forma de pago</div>
            <div class="fw-semibold">${info.idformapago ?? ''}</div>
            </div>

            <div class="col-12 col-md-6">
            <div class="text-muted small">Número de autorización</div>
            <div class="fw-semibold">${info.numeroautorizacion ?? ''}</div>
            </div>

            <!-- Valor neto destacado -->
            <div class="col-12 col-md-6">
            <div class="p-3 rounded-3 border bg-white h-100">
                <div class="text-muted small">Valor neto</div>
                <div class="fw-bold fs-4">${info.valorneto ?? ''}</div>
            </div>
            </div>
        </div>

        </div>
        </div>
    </div>
      <!-- Derecha: botones descarga -->
      <div class="col-12 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">Certificados</h5>
            <div id="zonaBotonesDescarga" class="d-grid gap-2">
              ${botonesHtml || '<div class="text-muted">No hay certificados para descargar.</div>'}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3 d-grid">
      <button type="button" id="btnVolverBusqueda" class="btn btn-outline-secondary btn-lg">
        Volver a búsqueda
      </button>
    </div>
  `;
    win.querySelector('#btnVolverBusqueda')?.addEventListener('click', () => {
        window.location.reload();
    });
}
