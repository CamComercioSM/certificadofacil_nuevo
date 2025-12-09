<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo Wizard Mínimo</title>

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

</head>

<body class="sb-nav-fixed page-wizard">
    <div id="contenido-principal">
        <main class="d-flex justify-content-center py-5">
            <!-- VENTANA CENTRADA -->
            <div class="appointment-window">
                <!-- ENCABEZADO -->
                <div class="text-center mb-4">
                    <img src="https://www.ccsm.org.co/images/logo.png" width="220"
                        alt="Logo CCSM" class="img rounded img-fluid mx-auto d-block">
                    <div class="h1 fw-light fw-bold py-3 mb-1">Certificado Facil</div>
                    <p class="text-muted mb-0">Consulta tu empresa y adquiere certificados al instante. </p>
                </div>

                <!-- WIZARD -->
                <form class="wizard" id="formWizard">
                    <aside class="wizard-content container">

                        <!-- PASO 1 -->
                        <div class="wizard-step" data-wz-title="Búsqueda">
                            <div class="mt-3">
                                <label class="form-label">Camara de comercio</label>
                                <select name="camara_comercio" id="camaraDeComercio" class="form-control required" required>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Tipo de busqueda</label>
                                <select name="criterio_busqueda" id="criterioDeBusqueda" class="form-control required" required>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Criterio o palabra clave</label>
                                <input type="text" name="palabra_clave" id="palabraClave" class="form-control required" required>
                            </div>
                        </div>

                        <!-- PASO 2 -->
                        <div class="wizard-step" data-wz-title="Resultados">
                            <input type="hidden" id="flagEmpresas" name="flagEmpresas" value="0">
                            <p>Elige la empresa para la que deseas comprar tu certificado</p>
                            <div id="selectEmpresa" class="mt-3"></div>
                        </div>

                        <!-- PASO 3 -->
                        <div class="wizard-step" data-wz-title="Certificados">
                            <input type="hidden" id="flagCertificados" name="flagCertificados" value="0">
                            <p>Elige los certificados y cantidades que quieres adquirir</p>
                            <div id="selectcertificados" class="mt-3"></div>
                        </div>
                        <!-- PASO 4 -->
                        <div class="wizard-step" data-wz-title="Resumen">
                            <div id="resumenGeneralPago" class="mt-3"></div>
                        </div>
                    </aside>
                </form>
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
    <!-- <script src="/helper.js"></script> -->
    <script src="/certificados.js"></script>
</body>

</html>