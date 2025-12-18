<?php

$permitidas = [
    'https://citurcam.com',
    'https://api.citurcam.com',
    'https://citas.citurcam.com',
    'http://localhost:3000',
    'http://localhost'
];

$originENTRANDO = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? $_SERVER['HTTP_HOST'] ?? '';
$originPermitido = false;

// 2. Comprobar si el origen de la solicitud está en la lista de permitidos
foreach ($permitidas as $url) {
    // Usamos strpos con verificación estricta para asegurar que el origen es un prefijo (o el mismo)
    // de una URL permitida, o que es la URL exacta.
    if (strpos($originENTRANDO, $url) === 0 || $originENTRANDO === $url) {
        $originPermitido = $originENTRANDO;
        break;
    }
}

// 3. Establecer las cabeceras CORS SOLO si el origen está permitido
if ($originPermitido) {
    // Permite al navegador enviar las cabeceras de contenido
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept');

    // Devuelve el ORIGEN EXACTO que está haciendo la solicitud.
    header('Access-Control-Allow-Origin: ' . $originPermitido);

    // Necesario para solicitudes con método POST
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

$operacion = null;
if (isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];
} elseif (isset($_GET['operacion'])) {
    $operacion = $_GET['operacion'];
}

$datos = [];
$jsonPOST = json_decode(file_get_contents('php://input'), true);
if ($jsonPOST) {
    $datos = $jsonPOST;
} elseif (isset($_POST['datos'])) {
    $datos = $_POST['datos'];
} elseif (isset($_GET['datos'])) {
    $datos = $_GET['datos'];
} elseif (!empty($_POST)) {
    $datos = $_POST;
}
require('libs/ApiSICAM.php');
// Se asume la existencia de RespuestasSistema.php y Responder.php si son necesarios
// para replicar la lógica original de tus funciones. Para el script de la API, 
// solo necesitamos el resultado final en $respuesta.

$Api = ApiSICAM::ObjetoAPI();
//$Api::$MODO_PRUEBAS = true;
//$Api::$MOSTRAR_RESPUESTA_API = true;

// Inicializa $respuesta para evitar errores si no hay 'operacion'
$respuesta = ['RESPUESTA' => 'ERROR', 'MENSAJE' => 'Operación no válida o no especificada'];

switch ($operacion) {

    case 'listadoCamaras':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'listadoCamaras'
        );
        break;
    case 'buscarTiposCertificados':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'buscarTiposCertificados',
            $datos,
        );
        break;
    case 'registrarInicioTransaccion':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'registrarInicioTransaccion',
            $datos,
        );
        break;
    case 'generarEnlacePago':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'generarEnlacePago',
            $datos,
        );
        break;
    case 'consultarEstadoPagoSII':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'consultarEstadoPagoSII',
            $datos,
        );
        break;
    case 'validarEstadoPagoSII':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'validarEstadoPagoSII',
            $datos,
        );
        break;
    case 'registrarCopiarEnlace':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'registrarCopiarEnlace',
            $datos,
        );
        break;
    case 'registrarAbrirEnlace':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'registrarAbrirEnlace',
            $datos,
        );
        break;
    case 'registrarCompartirEnlace':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'registrarCompartirEnlace',
            $datos,
        );
        break;
    case 'recuperarCertificados':
        $respuesta = $Api->ejecutar(
            'tienda-apps',
            'certificadoFacil',
            'recuperarCertificados',
            $datos,
        );
        break;
    default:
        // $respuesta ya está definida arriba con un error por defecto.
        break;
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($respuesta);
