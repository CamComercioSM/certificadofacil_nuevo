<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta de certificados - Ejemplo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 (CDN) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f3f6ff, #e3f2fd);
            min-height: 100vh;
        }

        .card-wizard {
            max-width: 900px;
            width: 100%;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .step-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .step-active {
            background-color: #0d6efd;
            color: #fff;
        }

        .step-inactive {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .small-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .fade-step {
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
        }

        .fade-step.hidden {
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            height: 0;
            overflow: hidden;
        }

        .fade-step.visible {
            opacity: 1;
            transform: translateY(0);
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card card-wizard">
            <div class="card-body p-4 p-md-5">
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="h4 mb-1">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Certificados en línea
                        </h1>
                        <p class="text-muted mb-0">
                            Consulta tu empresa y adquiere certificados al instante.
                        </p>
                    </div>
                </div>

                <!-- Indicador de pasos -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <div id="badgePaso1" class="step-badge step-active me-2">1</div>
                        <div>
                            <div class="fw-semibold small mb-0">Búsqueda</div>
                            <div class="small text-muted">Datos de la empresa</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 border-top border-dashed mx-2"></div>
                    <div class="d-flex align-items-center">
                        <div id="badgePaso2" class="step-badge step-inactive me-2">2</div>
                        <div>
                            <div class="fw-semibold small mb-0">Resultados</div>
                            <div class="small text-muted">Lista de empresas</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 border-top border-dashed mx-2"></div>
                    <div class="d-flex align-items-center">
                        <div id="badgePaso3" class="step-badge step-inactive me-2">3</div>
                        <div>
                            <div class="fw-semibold small mb-0">Certificados</div>
                            <div class="small text-muted">Selección y pago</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 border-top border-dashed mx-2"></div>
                    <div class="d-flex align-items-center">
                        <div id="badgePaso4" class="step-badge step-inactive me-2">4</div>
                        <div>
                            <div class="fw-semibold small mb-0">Resumen</div>
                            <div class="small text-muted">Información del pago</div>
                        </div>
                    </div>
                </div>

                <!-- ALERTA GENERAL -->
                <div id="alertGeneral" class="alert alert-warning d-none small py-2 px-3 mb-3"></div>

                <!-- PASO 1: Formulario de búsqueda -->
                <div id="paso1" class="fade-step visible">
                    <form id="formBusqueda" novalidate>
                        <div class="row g-3">
                            <!-- Cámara de comercio -->
                            <div class="col-12 col-md-6">
                                <label for="camaraComercio" class="form-label">
                                    Cámara de comercio <span class="text-danger">*</span>
                                </label>
                                <select id="camaraComercio" name="camaraComercio" class="form-select" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="citurcam">Camara de comercio de Santa Marta</option>
                                    <option value="otra_camara_1">Camara de comercio de Medellin</option>
                                    <option value="otra_camara_2">Camara de comercio de Bogota</option>
                                </select>
                                <div class="invalid-feedback">
                                    Selecciona la cámara de comercio.
                                </div>
                            </div>

                            <!-- Tipo de búsqueda -->
                            <div class="col-12 col-md-6">
                                <label class="form-label d-block">
                                    Tipo de búsqueda <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group w-100" role="group" aria-label="Tipo de búsqueda">
                                    <input type="radio" class="btn-check" name="tipoBusqueda" id="buscarRazonSocial"
                                        value="razon_social" autocomplete="off" required checked>
                                    <label class="btn btn-outline-primary" for="buscarRazonSocial">
                                        Razón social
                                    </label>

                                    <input type="radio" class="btn-check" name="tipoBusqueda" id="buscarNit"
                                        value="nit" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="buscarNit">
                                        NIT
                                    </label>

                                    <input type="radio" class="btn-check" name="tipoBusqueda" id="buscarMatricula"
                                        value="matricula" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="buscarMatricula">
                                        Matrícula mercantil
                                    </label>
                                </div>
                                <div class="small text-muted mt-1">
                                    Elige cómo deseas buscar la empresa.
                                </div>
                            </div>

                            <!-- Valor a buscar -->
                            <div class="col-12">
                                <label for="valorBusqueda" class="form-label">
                                    Dato a buscar <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    id="valorBusqueda"
                                    name="valorBusqueda"
                                    class="form-control"
                                    placeholder="Ej: COMERCIALIZADORA DEMO S.A.S o 900123456-7"
                                    required>
                                <div class="invalid-feedback">
                                    Ingresa el dato a buscar.
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12 d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>
                                    Consultar certificados
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Contenedor para mostrar redirecciones a otras cámaras -->
                    <div id="contenedorRedireccion" class="mt-4 d-none">
                        <div class="alert alert-info small mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Para esta cámara de comercio, debes realizar la consulta directamente en su portal:
                        </div>
                        <ul class="small ps-3 mb-0">
                            <li>
                                <a href="#" target="_blank">Portal certificados Otra Cámara 1</a>
                            </li>
                            <li>
                                <a href="#" target="_blank">Portal certificados Otra Cámara 2</a>
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- PASO 2: Resultados y selección de empresa -->
                <div id="paso2" class="fade-step hidden">
                    <div class="mb-3">
                        <span class="small-label text-uppercase">Resultados de la búsqueda</span>
                        <h2 class="h5 mb-1">Selecciona la empresa</h2>
                        <p class="text-muted mb-0">
                            Hemos encontrado varias posibles coincidencias. Elige la empresa para continuar con la compra de certificados.
                        </p>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Razón social</th>
                                    <th>NIT</th>
                                    <th>Matrícula</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEmpresas">
                                <!-- Se llena por JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnVolverPaso1DesdeResultados">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver a buscar
                        </button>
                    </div>
                </div>

                <div id="paso3" class="fade-step hidden">
                    <!-- Resumen de búsqueda -->
                    <div class="mb-3">
                        <span class="small-label text-uppercase">Resultado de búsqueda</span>
                        <h2 class="h5 mb-1" id="textoEmpresaEncontrada">Empresa demo S.A.S.</h2>
                        <p class="text-muted mb-0" id="textoDetalleBusqueda">
                            NIT 900123456-7 • Cámara de Comercio de Ejemplo (Citurcam)
                        </p>
                    </div>

                    <!-- Tabla de certificados -->
                    <div class="table-responsive mb-3">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Certificado</th>
                                    <th class="text-center">Precio unitario</th>
                                    <th class="text-center" style="width: 120px;">Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyCertificados">
                                <!-- Se llena por JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumen y acciones -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="small-label text-uppercase">Resumen de compra</div>
                            <div id="textoResumenCompra" class="fw-semibold">
                                No has seleccionado certificados aún.
                            </div>
                            <div class="small text-muted" id="textoDetalleTotal">
                                Total: $0
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <button id="btnVolverPaso1" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver a buscar
                            </button>
                            <button id="btnGenerarLinkPago" class="btn btn-success">
                                <i class="bi bi-credit-card me-1"></i>
                                Generar link de pago
                            </button>
                        </div>
                    </div>

                    <!-- Link de pago generado (mensaje rápido en Paso 2) -->
                    <div id="contenedorLinkPago" class="alert alert-success mt-3 d-none small">
                        <div class="fw-semibold mb-1">
                            <i class="bi bi-check-circle me-1"></i>
                            Link de pago generado
                        </div>
                        <div id="textoLinkPago" class="mb-1"></div>
                        <div class="text-muted">
                            Este es un ejemplo. Aquí integrarías tu pasarela de pagos real.
                        </div>
                    </div>
                </div>

                <!-- PASO 3: Resumen de pago -->
                <div id="paso4" class="fade-step hidden">
                    <div class="mb-3">
                        <span class="small-label text-uppercase">Resumen del pago</span>
                        <h2 class="h5 mb-1">
                            Detalles de la liquidación
                        </h2>
                        <p class="text-muted mb-0">
                            Conserva estos datos para futuras consultas o reimpresión de certificados.
                        </p>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="small-label d-block">ID Liquidación</label>
                            <div id="textoIdLiquidacion" class="fw-semibold">
                                -
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="small-label d-block">Número de recuperación</label>
                            <div id="textoNumeroRecuperacion" class="fw-semibold">
                                -
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="inputEnlacePago" class="small-label d-block mb-1">
                                Enlace de pago
                            </label>
                            <div class="input-group">
                                <input type="text"
                                    id="inputEnlacePago"
                                    class="form-control form-control-sm"
                                    readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" id="btnCopiarEnlace">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="small text-muted mt-1">
                                Puedes copiar el enlace por si deseas pagarlo desde otro dispositivo.
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6 d-flex flex-column gap-2">
                            <button class="btn btn-primary btn-sm" type="button" id="btnAbrirEnlace">
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Abrir enlace de pago
                            </button>
                            <button class="btn btn-outline-success btn-sm" type="button" id="btnEnviarWhatsapp">
                                <i class="bi bi-whatsapp me-1"></i>
                                Enviar enlace por WhatsApp
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="btnVolverPaso2">
                                <i class="bi bi-arrow-left me-1"></i>
                                Volver a certificados
                            </button>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="small-label d-block mb-1">
                                Código QR de pago
                            </label>
                            <div class="border rounded d-flex align-items-center justify-content-center p-3"
                                style="min-height: 180px;">
                                <!-- QR de ejemplo -->
                                <img id="qrPagoImg" alt="QR de pago" style="max-width: 160px; max-height: 160px;">
                            </div>
                            <div class="small text-muted mt-1">
                                Escanea el código desde la cámara de tu celular para ir al pago.
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-success w-100" type="button" id="btnDescargarCertificado">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i>
                            Si ya pagaste, puedes descargar tu certificado dando clic aquí
                        </button>
                        <div class="small text-muted mt-2">
                            En una implementación real, aquí validarías el pago en tu backend y
                            permitirías la descarga del certificado en PDF.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const certificadosMock = [{
                id: 'CERT-EXIST',
                nombre: 'Certificado de Existencia y Representación Legal',
                precio: 35000
            },
            {
                id: 'CERT-MATR',
                nombre: 'Certificado de Matrícula Mercantil',
                precio: 28000
            },
            {
                id: 'CERT-PROH',
                nombre: 'Certificado de Prohibiciones y Embargos',
                precio: 30000
            }
        ];

        const formBusqueda = document.getElementById('formBusqueda');
        const paso1 = document.getElementById('paso1');
        const paso2 = document.getElementById('paso2');
        const paso3 = document.getElementById('paso3');
        const paso4 = document.getElementById('paso4'); // resumen (antes paso3)
        const badgePaso1 = document.getElementById('badgePaso1');
        const badgePaso2 = document.getElementById('badgePaso2');
        const badgePaso3 = document.getElementById('badgePaso3');
        const badgePaso4 = document.getElementById('badgePaso4');
        const alertGeneral = document.getElementById('alertGeneral');
        const contenedorRedireccion = document.getElementById('contenedorRedireccion');
        const tbodyEmpresas = document.getElementById('tbodyEmpresas');
        const btnVolverPaso1DesdeResultados = document.getElementById('btnVolverPaso1DesdeResultados');
        const textoEmpresaEncontrada = document.getElementById('textoEmpresaEncontrada');
        const textoDetalleBusqueda = document.getElementById('textoDetalleBusqueda');
        const tbodyCertificados = document.getElementById('tbodyCertificados');
        const textoResumenCompra = document.getElementById('textoResumenCompra');
        const textoDetalleTotal = document.getElementById('textoDetalleTotal');
        const contenedorLinkPago = document.getElementById('contenedorLinkPago');
        const textoLinkPago = document.getElementById('textoLinkPago');

        const btnVolverPaso1 = document.getElementById('btnVolverPaso1');
        const btnGenerarLinkPago = document.getElementById('btnGenerarLinkPago');

        // --- Paso 3 elementos ---
        const textoIdLiquidacion = document.getElementById('textoIdLiquidacion');
        const textoNumeroRecuperacion = document.getElementById('textoNumeroRecuperacion');
        const inputEnlacePago = document.getElementById('inputEnlacePago');
        const btnCopiarEnlace = document.getElementById('btnCopiarEnlace');
        const btnAbrirEnlace = document.getElementById('btnAbrirEnlace');
        const btnEnviarWhatsapp = document.getElementById('btnEnviarWhatsapp');
        const btnVolverPaso2 = document.getElementById('btnVolverPaso2');
        const btnDescargarCertificado = document.getElementById('btnDescargarCertificado');
        const qrPagoImg = document.getElementById('qrPagoImg');

        // Estado del link de pago
        let linkPagoActual = '';
        let referenciaActual = '';
        let numeroRecuperacionActual = '';

        // --- Manejo del formulario de búsqueda ---
        formBusqueda.addEventListener('submit', function(e) {
            e.preventDefault();
            ocultarAlerta();
            contenedorRedireccion.classList.add('d-none');

            if (!formBusqueda.checkValidity()) {
                formBusqueda.classList.add('was-validated');
                mostrarAlerta('Por favor completa los campos obligatorios.', 'danger');
                return;
            }

            const camara = document.getElementById('camaraComercio').value;
            const tipoBusqueda = document.querySelector('input[name="tipoBusqueda"]:checked').value;
            const valorBusqueda = document.getElementById('valorBusqueda').value.trim();

            // Si la cámara NO es la principal, redirigimos (ejemplo)
            if (camara === 'otra_camara_1' || camara === 'otra_camara_2') {
                contenedorRedireccion.classList.remove('d-none');
                mostrarAlerta('Para esta cámara de comercio debes usar su portal oficial.', 'info');
                return;
            }

            // Simulamos éxito en la búsqueda
            simularResultadoExitoso(camara, tipoBusqueda, valorBusqueda);
        });

        function simularResultadoExitoso(camara, tipoBusqueda, valorBusqueda) {
            // Aquí podrías usar la respuesta real de tu API.
            // Simulamos varios resultados similares.
            const baseNombre = valorBusqueda || 'EMPRESA DEMO S.A.S.';
            const resultados = [{
                    id: 1,
                    razonSocial: baseNombre + ' 1',
                    nit: '900123456-1',
                    matricula: 'M-100001'
                },
                {
                    id: 2,
                    razonSocial: baseNombre + ' 2',
                    nit: '900123456-2',
                    matricula: 'M-100002'
                },
                {
                    id: 3,
                    razonSocial: baseNombre + ' 3',
                    nit: '900123456-3',
                    matricula: 'M-100003'
                }
            ];

            renderResultadosEmpresas(resultados);
            cambiarPaso(2);
        }

        function renderResultadosEmpresas(resultados) {
            tbodyEmpresas.innerHTML = '';

            resultados.forEach(emp => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${emp.razonSocial}</td>
            <td>${emp.nit}</td>
            <td>${emp.matricula}</td>
            <td class="text-end">
                <button type="button"
                        class="btn btn-sm btn-primary btnSeleccionarEmpresa"
                        data-id="${emp.id}"
                        data-razon="${emp.razonSocial}"
                        data-nit="${emp.nit}"
                        data-matricula="${emp.matricula}">
                    Seleccionar
                </button>
            </td>
        `;
                tbodyEmpresas.appendChild(tr);
            });

            // Eventos para cada botón "Seleccionar"
            tbodyEmpresas.querySelectorAll('.btnSeleccionarEmpresa').forEach(btn => {
                btn.addEventListener('click', function() {
                    const empresa = {
                        id: this.dataset.id,
                        razonSocial: this.dataset.razon,
                        nit: this.dataset.nit,
                        matricula: this.dataset.matricula
                    };
                    irAPasoCertificados(empresa);
                });
            });
        }

        function irAPasoCertificados(empresa) {
            // Rellenamos los textos que ya usabas en el paso de certificados
            textoEmpresaEncontrada.textContent = empresa.razonSocial;
            textoDetalleBusqueda.textContent = `NIT ${empresa.nit} • Matrícula ${empresa.matricula}`;

            // Pintar certificados (mock como antes)
            renderCertificados(certificadosMock);

            // Reset resumen y totales
            textoResumenCompra.innerHTML = 'No has seleccionado certificados aún.';
            textoDetalleTotal.textContent = 'Total: $0';
            contenedorLinkPago.classList.add('d-none');
            textoLinkPago.textContent = '';

            // Limpia datos de link de pago anteriores
            linkPagoActual = '';
            referenciaActual = '';
            numeroRecuperacionActual = '';
            inputEnlacePago.value = '';
            textoIdLiquidacion.textContent = '-';
            textoNumeroRecuperacion.textContent = '-';
            qrPagoImg.removeAttribute('src');

            // Ir al paso 3 (certificados)
            cambiarPaso(3);
        }


        function renderCertificados(certificados) {
            tbodyCertificados.innerHTML = '';

            certificados.forEach(cert => {
                const tr = document.createElement('tr');

                tr.innerHTML = `
                <td>
                    <div class="fw-semibold">${cert.nombre}</div>
                    <div class="small text-muted">Ref: ${cert.id}</div>
                </td>
                <td class="text-center">${formateaMoneda(cert.precio)}</td>
                <td class="text-center">
                    <input type="number"
                           class="form-control form-control-sm text-center cantidad-certificado"
                           min="0"
                           value="0"
                           data-id="${cert.id}"
                           data-nombre="${cert.nombre}"
                           data-precio="${cert.precio}">
                </td>
                <td class="text-end">
                    <span class="subtotal-certificado" data-id="${cert.id}">${formateaMoneda(0)}</span>
                </td>
            `;

                tbodyCertificados.appendChild(tr);
            });

            // Escuchamos cambios de cantidad
            tbodyCertificados.querySelectorAll('.cantidad-certificado').forEach(input => {
                input.addEventListener('input', actualizarTotales);
            });
        }

        function actualizarTotales() {
            let total = 0;
            const detallesSeleccion = [];

            tbodyCertificados.querySelectorAll('.cantidad-certificado').forEach(input => {
                const cant = parseInt(input.value, 10) || 0;
                const precio = parseInt(input.dataset.precio, 10) || 0;
                const id = input.dataset.id;
                const nombre = input.dataset.nombre;

                const subtotal = cant * precio;
                const spanSubtotal = tbodyCertificados.querySelector(`.subtotal-certificado[data-id="${id}"]`);

                if (spanSubtotal) {
                    spanSubtotal.textContent = formateaMoneda(subtotal);
                }

                if (cant > 0) {
                    total += subtotal;
                    detallesSeleccion.push(`${cant} x ${nombre}`);
                }
            });

            if (detallesSeleccion.length === 0) {
                textoResumenCompra.textContent = 'No has seleccionado certificados aún.';
            } else {
                // Cada certificado en una línea
                textoResumenCompra.innerHTML = detallesSeleccion.map(item => `• ${item}`).join('<br>');
            }

            textoDetalleTotal.textContent = 'Total: ' + formateaMoneda(total);

            // Si cambian las cantidades, ocultamos link previo
            contenedorLinkPago.classList.add('d-none');
        }

        btnVolverPaso1DesdeResultados.addEventListener('click', function() {
            cambiarPaso(1);
        });
        // Volver al paso 1
        btnVolverPaso1.addEventListener('click', function() {
            cambiarPaso(2);
        });

        // Volver al paso 2 desde el paso 3
        btnVolverPaso2.addEventListener('click', function() {
            cambiarPaso(3);
        });

        // Generar link de pago (simulado)
        btnGenerarLinkPago.addEventListener('click', function() {
            ocultarAlerta();

            let total = 0;
            const seleccion = [];

            tbodyCertificados.querySelectorAll('.cantidad-certificado').forEach(input => {
                const cant = parseInt(input.value, 10) || 0;
                const precio = parseInt(input.dataset.precio, 10) || 0;
                const nombre = input.dataset.nombre;

                if (cant > 0) {
                    total += cant * precio;
                    seleccion.push(`${cant} x ${nombre}`);
                }
            });

            if (seleccion.length === 0) {
                mostrarAlerta('Selecciona al menos un certificado antes de generar el link de pago.', 'danger');
                return;
            }

            // Simulación: creación de referencia y número de recuperación
            referenciaActual = 'LQ-' + Date.now();
            numeroRecuperacionActual = 'REC-' + Math.floor(Math.random() * 1_000_000).toString().padStart(6, '0');
            const urlPago = `https://mi-pasarela-ejemplo.com/pagar?ref=${encodeURIComponent(referenciaActual)}&monto=${total}`;

            linkPagoActual = urlPago;

            textoLinkPago.innerHTML = `
            <div class="mb-1">
                Referencia: <strong>${referenciaActual}</strong><br>
                Total a pagar: <strong>${formateaMoneda(total)}</strong>
            </div>
            <div>
                <a href="${urlPago}" target="_blank" rel="noopener noreferrer">
                    Abrir link de pago de ejemplo
                </a>
            </div>
        `;
            contenedorLinkPago.classList.remove('d-none');

            // Llenamos datos del Paso 3
            textoIdLiquidacion.textContent = referenciaActual;
            textoNumeroRecuperacion.textContent = numeroRecuperacionActual;
            inputEnlacePago.value = linkPagoActual;

            // QR de ejemplo (puedes cambiar por tu propio servicio)
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' +
                encodeURIComponent(linkPagoActual);
            qrPagoImg.src = qrUrl;

            // Vamos al Paso 4
            cambiarPaso(4);
        });

        // Copiar enlace
        btnCopiarEnlace.addEventListener('click', async function() {
            if (!linkPagoActual) {
                mostrarAlerta('No hay un enlace de pago generado todavía.', 'warning');
                return;
            }
            try {
                await navigator.clipboard.writeText(linkPagoActual);
                mostrarAlerta('Enlace copiado al portapapeles.', 'success');
            } catch (err) {
                mostrarAlerta('No se pudo copiar el enlace. Intenta manualmente.', 'danger');
            }
        });

        // Abrir enlace de pago
        btnAbrirEnlace.addEventListener('click', function() {
            if (!linkPagoActual) {
                mostrarAlerta('No hay un enlace de pago generado todavía.', 'warning');
                return;
            }
            window.open(linkPagoActual, '_blank', 'noopener');
        });

        // Enviar por WhatsApp
        btnEnviarWhatsapp.addEventListener('click', function() {
            if (!linkPagoActual) {
                mostrarAlerta('No hay un enlace de pago generado todavía.', 'warning');
                return;
            }
            const mensaje = `Hola, te comparto el enlace para el pago de tus certificados:\n\n` +
                `${linkPagoActual}\n\n` +
                `ID Liquidación: ${referenciaActual}\n` +
                `Número de recuperación: ${numeroRecuperacionActual}`;
            const urlWhatsapp = 'https://wa.me/?text=' + encodeURIComponent(mensaje);
            window.open(urlWhatsapp, '_blank', 'noopener');
        });

        // Descargar certificado (simulado)
        btnDescargarCertificado.addEventListener('click', function() {
            if (!linkPagoActual) {
                mostrarAlerta('No hay un enlace de pago generado todavía.', 'warning');
                return;
            }
            window.open('https://certificadofacil.tiendasicam32.net/recuperar', '_blank', 'noopener');
        });


        // --- Utilidades simples ---
        function formateaMoneda(valor) {
            return valor.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP',
                maximumFractionDigits: 0
            });
        }

        function mostrarAlerta(mensaje, tipo = 'warning') {
            alertGeneral.textContent = mensaje;
            alertGeneral.className = 'alert alert-' + tipo + ' small py-2 px-3 mb-3';
            alertGeneral.classList.remove('d-none');
        }

        function ocultarAlerta() {
            alertGeneral.classList.add('d-none');
        }

        function actualizarEstadoPaso(paso) {
            [badgePaso1, badgePaso2, badgePaso3, badgePaso4].forEach(b => {
                b.classList.remove('step-active');
                b.classList.add('step-inactive');
            });

            if (paso === 1) {
                badgePaso1.classList.replace('step-inactive', 'step-active');
            } else if (paso === 2) {
                badgePaso2.classList.replace('step-inactive', 'step-active');
            } else if (paso === 3) {
                badgePaso3.classList.replace('step-inactive', 'step-active');
            } else if (paso === 4) {
                badgePaso4.classList.replace('step-inactive', 'step-active');
            }
        }

        function cambiarPaso(paso) {
            [paso1, paso2, paso3, paso4].forEach(p => {
                p.classList.remove('visible');
                p.classList.add('hidden');
            });

            if (paso === 1) {
                paso1.classList.replace('hidden', 'visible');
            } else if (paso === 2) {
                paso2.classList.replace('hidden', 'visible');
            } else if (paso === 3) {
                paso3.classList.replace('hidden', 'visible');
            } else if (paso === 4) {
                paso4.classList.replace('hidden', 'visible');
            }

            actualizarEstadoPaso(paso);
        }
    </script>
</body>
</html>