<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de certificados</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top, #f3f4ff 0, #eaeaea 40%, #dcdcdc 100%);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .recovery-wrapper {
            width: 100%;
            max-width: 720px;
            padding: 1.5rem;
        }

        .recovery-card {
            border-radius: 1.25rem;
            border: none;
            overflow: hidden;
        }

        .step-indicator {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .step-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            font-size: 0.8rem;
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .step-pill.active {
            background: linear-gradient(120deg, #2563eb, #4f46e5);
            color: white;
        }

        .step-circle {
            width: 1.3rem;
            height: 1.3rem;
            border-radius: 999px;
            border: 2px solid currentColor;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mode-toggle {
            display: flex;
            gap: 0.5rem;
            background-color: #f3f4f6;
            padding: 0.25rem;
            border-radius: 999px;
        }

        .mode-toggle button {
            flex: 1;
            border-radius: 999px !important;
            border: none;
            font-size: 0.85rem;
        }

        .mode-toggle button.active {
            background: white;
            box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.4);
            font-weight: 500;
        }

        .form-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .results-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .cert-card {
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            background-color: #f9fafb;
        }

        .cert-card h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .cert-meta {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .alert-minimal {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
        }

        @media (max-width: 576px) {
            .recovery-card {
                border-radius: 1rem;
            }
        }
    </style>
</head>

<body>
    <main class="recovery-wrapper">
        <div class="card shadow-lg recovery-card">
            <div class="card-body p-4 p-md-5">
                <!-- Indicador de pasos -->
                <div class="step-indicator mb-2">
                    <div id="stepPill1" class="step-pill active">
                        <span class="step-circle">1</span> Datos de recuperación
                    </div>
                    <div id="stepPill2" class="step-pill">
                        <span class="step-circle">2</span> Certificados disponibles
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h4 mb-1">Recuperar certificados</h1>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            Si ya realizaste tu pago ingresa los datos de recuperación.
                        </p>
                    </div>
                </div>

                <!-- Alertas -->
                <div id="alertContainer" class="mb-3" style="display: none;"></div>

                <!-- Paso 1: elección de modo + formularios -->
                <section id="step1" aria-label="Paso 1: Datos de recuperación">
                    <!-- Selector de modo -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="form-section-title">Modo de recuperación</span>
                        </div>
                        <div class="mode-toggle">
                            <button type="button" id="btnModo1" class="btn btn-light active">
                                Con matrícula / recuperación / liquidación
                            </button>
                            <button type="button" id="btnModo2" class="btn btn-light">
                                Con recibo de pago
                            </button>
                        </div>
                    </div>

                    <!-- MODO 1 -->
                    <form id="formModo1" class="mt-3" autocomplete="off">
                        <p class="text-muted" style="font-size: 0.85rem;">
                            Puedes usar alguno de estos datos para buscar tus certificados.
                            Debes diligenciar al menos uno de los campos.
                        </p>

                        <div class="mb-3">
                            <label for="matricula" class="form-label">Número de Matrícula</label>
                            <input type="text" class="form-control" id="matricula" name="matricula"
                                placeholder="Ej: 123-456789">
                        </div>

                        <div class="mb-3">
                            <label for="codigoRecuperacion" class="form-label">Código de Recuperación</label>
                            <input type="text" class="form-control" id="codigoRecuperacion" name="codigoRecuperacion"
                                placeholder="Ej: ABCD-1234">
                        </div>

                        <div class="mb-4">
                            <label for="numeroLiquidacion" class="form-label">Número de Liquidación</label>
                            <input type="text" class="form-control" id="numeroLiquidacion" name="numeroLiquidacion"
                                placeholder="Ej: 9876543210">
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-end">
                            <button type="submit" class="btn btn-primary w-100 w-sm-auto">
                                Recuperar certificado
                            </button>
                        </div>
                    </form>

                    <!-- MODO 2 -->
                    <form id="formModo2" class="mt-3" autocomplete="off" style="display: none;">
                        <p class="text-muted" style="font-size: 0.85rem;">
                            Ingresa el número o referencia de tu recibo de pago para buscar los certificados asociados.
                        </p>

                        <div class="mb-4">
                            <label for="reciboPago" class="form-label">Recibo de Pago</label>
                            <input type="text" class="form-control" id="reciboPago" name="reciboPago"
                                placeholder="Ej: RP-2025-00012345" required>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-end">
                            <button type="submit" class="btn btn-primary w-100 w-sm-auto">
                                Recuperar certificado
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Paso 2: resultados -->
                <section id="step2" aria-label="Paso 2: Certificados disponibles" style="display: none;">
                    <div class="border-top pt-3 mt-3">
                        <h2 class="h6 mb-2">Certificados encontrados</h2>
                        <p id="resumenBusqueda" class="text-muted" style="font-size: 0.85rem;"></p>

                        <div id="resultsContainer" class="results-list mb-3"></div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" id="btnNuevaBusqueda" class="btn btn-outline-secondary btn-sm">
                                Nueva búsqueda
                            </button>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <!-- Bootstrap JS (opcional, por si usas componentes de BS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Referencias básicas
        const params = new URLSearchParams(window.location.search);

        let matricula = params.get("matricula");
        let codigoRecuperacion = params.get("codigoRecuperacion");
        let liquidacion = params.get("liquidacion");

        document.getElementById('matricula').value  = matricula;
        document.getElementById('codigoRecuperacion').value  = codigoRecuperacion;
        document.getElementById('numeroLiquidacion').value  = liquidacion;
        console.log(matricula, codigoRecuperacion, liquidacion);

        const btnModo1 = document.getElementById("btnModo1");
        const btnModo2 = document.getElementById("btnModo2");
        const formModo1 = document.getElementById("formModo1");
        const formModo2 = document.getElementById("formModo2");

        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");
        const stepPill1 = document.getElementById("stepPill1");
        const stepPill2 = document.getElementById("stepPill2");

        const alertContainer = document.getElementById("alertContainer");
        const resultsContainer = document.getElementById("resultsContainer");
        const resumenBusqueda = document.getElementById("resumenBusqueda");
        const btnNuevaBusqueda = document.getElementById("btnNuevaBusqueda");

        let modoActual = "modo1";

        function mostrarAlerta(tipo, mensaje) {
            // tipo: "success", "danger", "warning", "info"
            alertContainer.innerHTML = `
                <div class="alert alert-${tipo} alert-minimal mb-0" role="alert">
                    ${mensaje}
                </div>
            `;
            alertContainer.style.display = "block";
        }

        function limpiarAlerta() {
            alertContainer.innerHTML = "";
            alertContainer.style.display = "none";
        }

        function activarPaso1() {
            step1.style.display = "block";
            step2.style.display = "none";

            stepPill1.classList.add("active");
            stepPill2.classList.remove("active");
        }

        function activarPaso2() {
            step1.style.display = "none";
            step2.style.display = "block";

            stepPill1.classList.remove("active");
            stepPill2.classList.add("active");
        }

        function cambiarModo(modo) {
            if (modo === "modo1") {
                modoActual = "modo1";
                btnModo1.classList.add("active");
                btnModo2.classList.remove("active");
                formModo1.style.display = "block";
                formModo2.style.display = "none";
            } else {
                modoActual = "modo2";
                btnModo2.classList.add("active");
                btnModo1.classList.remove("active");
                formModo2.style.display = "block";
                formModo1.style.display = "none";
            }

            // Nueva selección de modo implica que volvemos al paso 1
            activarPaso1();
            limpiarAlerta();
        }

        // ==========================
        // Simulación de búsqueda
        // (Reemplazar por tu API real)
        // ==========================

        async function buscarCertificadosModo1({
            matricula,
            codigoRecuperacion,
            numeroLiquidacion
        }) {
            // TODO: Reemplazar este mock por una llamada real:
            // ej: return await conectarseEndPoint('recuperarCertificados', {...});
            // Aquí solo devolvemos unos datos de prueba si algo coincide:
            const hayDatos = matricula || codigoRecuperacion || numeroLiquidacion;

            if (!hayDatos) {
                return [];
            }

            // Ejemplo de resultado ficticio
            return [{
                    id: 1,
                    descripcion: "Certificado de Tradición y Libertad",
                    matricula: matricula || "123-456789",
                    fecha: "2025-12-01",
                    enlaceDescarga: "#"
                },
                {
                    id: 2,
                    descripcion: "Certificado de Existencia y Representación Legal",
                    matricula: matricula || "123-456789",
                    fecha: "2025-11-28",
                    enlaceDescarga: "#"
                }
            ];
        }

        async function buscarCertificadosModo2({
            reciboPago
        }) {
            // TODO: Reemplazar este mock por tu llamada real al backend
            if (!reciboPago) {
                return [];
            }

            // Resultado ficticio
            return [{
                id: 3,
                descripcion: "Certificado Cámara de Comercio",
                referenciaPago: reciboPago,
                fecha: "2025-11-30",
                enlaceDescarga: "#"
            }];
        }

        // Mostrar resultados en el paso 2
        function pintarResultados(certificados, resumen) {
            resultsContainer.innerHTML = "";

            certificados.forEach(cert => {
                const div = document.createElement("div");
                div.className = "cert-card";

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>${cert.descripcion}</h6>
                            <div class="cert-meta mt-1">
                                ${cert.matricula ? `Matrícula: <strong>${cert.matricula}</strong><br>` : ""}
                                ${cert.referenciaPago ? `Recibo de pago: <strong>${cert.referenciaPago}</strong><br>` : ""}
                                Fecha de generación: <strong>${cert.fecha}</strong>
                            </div>
                        </div>
                        <div class="ms-3">
                            <a href="${cert.enlaceDescarga}"
                               class="btn btn-sm btn-outline-primary">
                                Descargar
                            </a>
                        </div>
                    </div>
                `;

                resultsContainer.appendChild(div);
            });

            resumenBusqueda.textContent = resumen;
            activarPaso2();
        }

        // ==========================
        // Eventos
        // ==========================

        btnModo1.addEventListener("click", () => cambiarModo("modo1"));
        btnModo2.addEventListener("click", () => cambiarModo("modo2"));

        btnNuevaBusqueda.addEventListener("click", () => {
            activarPaso1();
            limpiarAlerta();
            resultsContainer.innerHTML = "";
            resumenBusqueda.textContent = "";

            // Opcional: limpiar formularios
            formModo1.reset();
            formModo2.reset();
        });

        // Submit MODO 1
        formModo1.addEventListener("submit", async (e) => {
            e.preventDefault();
            limpiarAlerta();

            const matricula = document.getElementById("matricula").value.trim();
            const codigoRecuperacion = document.getElementById("codigoRecuperacion").value.trim();
            const numeroLiquidacion = document.getElementById("numeroLiquidacion").value.trim();

            // Validar que haya por lo menos un dato
            if (!matricula && !codigoRecuperacion && !numeroLiquidacion) {
                mostrarAlerta("warning", "Debes diligenciar al menos uno de los campos para realizar la búsqueda.");
                return;
            }

            // Aquí podrías mostrar un loader si quieres
            const certificados = await buscarCertificadosModo1({
                matricula,
                codigoRecuperacion,
                numeroLiquidacion
            });

            if (!certificados || certificados.length === 0) {
                mostrarAlerta("info", "No se encontraron certificados o procesos para recuperar con los datos ingresados.");
                return;
            }

            pintarResultados(certificados, "Estos son los certificados asociados a los datos ingresados.");
        });

        // Submit MODO 2
        formModo2.addEventListener("submit", async (e) => {
            e.preventDefault();
            limpiarAlerta();

            const reciboPago = document.getElementById("reciboPago").value.trim();

            if (!reciboPago) {
                mostrarAlerta("warning", "Ingresa el número o referencia de tu recibo de pago.");
                return;
            }

            const certificados = await buscarCertificadosModo2({
                reciboPago
            });

            if (!certificados || certificados.length === 0) {
                mostrarAlerta("info", "No se encontraron certificados asociados al recibo de pago ingresado.");
                return;
            }

            pintarResultados(certificados, "Estos son los certificados asociados a tu recibo de pago.");
        });
    </script>
</body>

</html>