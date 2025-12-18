<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certifacil</title>
    <!-- Iconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Wizard-JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/AdrianVillamayor/Wizard-JS@2.0.3/dist/main.min.css">

    <!-- Tus estilos generales -->
    <link rel="stylesheet" href="/style.css">

    <!-- Script Wizard-JS -->
    <script src="https://cdn.jsdelivr.net/gh/AdrianVillamayor/Wizard-JS@2.0.3/dist/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body class="sb-nav-fixed page-wizard">
    <div id="contenido-principal">
        <main class="d-flex justify-content-center py-5">
            <!-- VENTANA CENTRADA -->
            <div class="appointment-window-recuperar">
                <!-- ENCABEZADO -->
                <div class="text-center mb-4">
                    <img src="https://www.ccsm.org.co/images/logo.png" width="220"
                        alt="Logo CCSM" class="img rounded img-fluid mx-auto d-block">
                    <div class="h1 fw-light fw-bold py-3 mb-1">Certificado Facil</div>
                    <h4 class="text-muted mb-0">Recuperar Certificados. </h4>
                </div>

                <!-- WIZARD -->
                <form class="wizard" id="formWizard">
                    <aside class="wizard-content container">

                        <!-- PASO 1 -->
                        <div class="wizard-step" data-wz-title="Con matricula">
                            <div class="mt-3">
                                <label class="form-label">Numero Matricula</label>
                                <input type="text" name="matricula" id="matricula" class="form-control">
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Codigo Recuperacion</label>
                                <input type="text" name="codigoRecuperacion" id="codigoRecuperacion" class="form-control">
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Numero de liquidacion</label>
                                <input type="text" name="numeroLiquidacion" id="numeroLiquidacion" class="form-control">
                            </div>
                            <div class="mt-4 d-grid">
                                <button
                                    type="button"
                                    id="btnRecuperarMatricula"
                                    onclick="recuperarCertificadoConMatricula();"
                                    class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="btn-text">Recuperar Certificado</span>
                                </button>
                            </div>
                        </div>

                        <!-- PASO 2 -->
                        <div class="wizard-step" data-wz-title="Con recibo de pago">
                            <div class="mt-3">
                                <label class="form-label">Recibo de pago</label>
                                <input type="text" name="numeroReciboPago" id="numeroReciboPago" class="form-control">
                            </div>
                            <div class="mt-4 d-grid">
                                <button
                                    type="button"
                                    id="btnRecuperarRecibo"
                                    onclick="recuperarCertificadoConReciboDePago();"
                                    class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="btn-text">Recuperar Certificado</span>
                                </button>
                            </div>
                        </div>

                    </aside>
                </form>
                <!-- VISTA ÉXITO -->
                <div id="successView" class="d-none mt-4">
                    <div id="vistaDescarga"></div>
                </div>
            </div>
        </main>
    </div>

    <div id="loadingOverlay" class="loading-overlay d-none">
        <div class="loading-content">
            <img src="https://cdnsicam.net/img/ccsm/__Logo%20C%C3%A1mara%20para%20el%20Magdalena.png"
                alt="Cargando..." class="loading-icon">
            <p class="loading-text">Cargando...</p>
        </div>
    </div>

    <!-- Tus otros scripts -->
    <script src="/healper.js"></script>
    <script src="/recuperar.js"></script>
</body>

</html>