<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

//eval(file_get_contents("https://clientes.sicam32.net/php/pruebas.php?RVhERDM5OVA3aGV4RXFZMzMzazVlQjlxb0xIUSthQzNGKzRraXlTOFF6UT06OnpaNjJlakMzM3JWN1grcWEwM282Y2lwU0lERyt1U1pzN21rd1E1amNvd1E9"));

/**
 *   CONEXION ENTRE UNA APLICACION PHP Y UN SERVIDOR SICAM DE LA CCSM
 *        @Fecha: 2023
 *        @Autor: Ing. Juan Pablo Llinás Ramírez
 *        @Email: jpllinas@ccsm.org.co
 *        @Version: 20230605
 *      Clase para conectar una aplicacion PHP con un servidor SICAM*
 *      Uso del patron de diseño Singleton para garantizar la correcta y unica instanciacion de la clase
 *
 * */
define('OBJETO_SESION_API', uniqid());

class ApiSICAM {
    public static $MODO_PRUEBAS = false;
    public static $MOSTRAR_RESPUESTA_API = false;
    static $URL = "https://api.sicam32.net/";
    static $URL_SICAM = "https://si.sicam32.net/";
    static $USERNAME = "EXDD399P7hexEqY333k5eB9qoLHQ+aC3F+4kiyS8QzQ=";
    static $PASSWORD = "zZ62ejC33rV7X+qa03o6cipSIDG+uSZs7mkwQ5jcowQ=";
    private $conexionApi = null;
    private static $instancia;
    private $JSONRespuesta = null;
    private $estadoConexion = false;
    private $respuesta;
    private $resultado;
    private $info;

    public static function ObjetoAPI($usuario = null, $clave = null) : ApiSICAM {
        if (!is_null($usuario)) {
            self::$USERNAME = $usuario;
        }
        if (!is_null($clave)) {
            self::$PASSWORD = $clave;
        }
        if (!isset(self::$instancia)) {
            $obj = __CLASS__;
            self::$instancia = new $obj;
        }
        return self::$instancia;
    }

    public function __construct() {
        
    }

    private function __clone() {
        throw new Exception("Este objeto no se puede clonar");
    }

    public function ejecutarPOST($componente, $controlador, $operacion, ?array $parametros = null) {
        return $this->ejecutarRESPUESTAsoloDATOS($componente, $controlador, $operacion, $parametros, "POST");
    }

    public function ejecutarGET($componente, $controlador, $operacion, ?array $parametros = null) {
        return $this->ejecutarRESPUESTAsoloDATOS($componente, $controlador, $operacion, $parametros, "GET");
    }

    protected function ejecutarRESPUESTAsoloDATOS($componente, $controlador, $operacion, ?array $parametros = null, $metodo = "POST", $formato = "DATOS") {
        return$this->ejecutar($componente, $controlador, $operacion, $parametros, $metodo, $formato);
    }

    public function ejecutar($componente, $controlador, $operacion, ?array $parametros = null, $metodo = "POST", $formato = "OBJETO") {
        $this->JSONRespuesta = $this->formatearRespuesta(
          $this->solicitarDatos(
            $this->conectar($componente, $controlador, $operacion),
            $parametros, $metodo
          ),
          $formato
        );
        $this->desconectar();
        return $this->JSONRespuesta;
    }

    function conectar($componente, $controlador, $operacion) {
        $this->conexionApi = curl_init();
        curl_setopt($this->conexionApi, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->conexionApi, CURLOPT_USERPWD, self::$USERNAME . ":" . self::$PASSWORD);
        curl_setopt($this->conexionApi, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->conexionApi, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->conexionApi, CURLOPT_RETURNTRANSFER, true);
        return $urlCompleta = self::$URL . $componente . "/" . $controlador . "/" . $operacion;
    }

    function solicitarDatos($urlCompleta, $parametros, $metodo) {
        ApiSICAM::mostrar_resultado_para_depuracion([$urlCompleta, $parametros, $metodo], 'Paquete de Datos');
        if (self::$MODO_PRUEBAS) {
            $parametros['modo'] = "PRUEBAS";
        }
        if (SesionAPI::colaboradorLOGUEADO()) {
            $parametros['colaboradorLOGUEADOID'] = SesionAPI::colaborador('colaboradorID');
            $parametros['colaboradorLOGUEADOEMAIL'] = SesionAPI::colaborador('colaboradorEMAIL');
        }
        if (!is_null($parametros)) {
            $data_string = json_encode($parametros);
            curl_setopt($this->conexionApi, CURLOPT_CUSTOMREQUEST, $metodo);
            curl_setopt($this->conexionApi, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($this->conexionApi, CURLOPT_HTTPHEADER, ["Accept: application/json", "Content-Type: application/json", "Content-Length: " . strlen($data_string)]);
        }
        ApiSICAM::mostrar_resultado_para_depuracion($this->conexionApi, 'Config Conexion:');
        curl_setopt($this->conexionApi, CURLOPT_URL, $urlCompleta);
        $this->resultado = curl_exec($this->conexionApi);
        ApiSICAM::mostrar_resultado_para_depuracion($this->resultado, 'Respuesta');
        return $this->resultado;
    }

    function formatearRespuesta($resultado, $formato = "OBJETO") {
        $this->respuesta = json_decode($resultado);
        ApiSICAM::mostrar_resultado_para_depuracion([curl_getinfo($this->conexionApi), $resultado, $this->respuesta], 'Formatear Respuesta');
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!session_status() == PHP_SESSION_ACTIVE) {
                session_start();
            }
            $this->estadoConexion = $_SESSION["API_CONEXION"] = true;
            session_write_close();
        } else {
            $Error = new stdClass();
            $Error->RESPUESTA = "ERROR";
            $Error->MENSAJE = 'Error en el formato o contenido de la respuesta: [' . json_last_error_msg() . '].  ' . print_r($resultado, true) . '';
            ApiSICAM::mostrar_resultado_para_depuracion(json_last_error_msg(), $Error->MENSAJE);
            $this->respuesta = $Error;
        }
        $this->info = curl_getinfo($this->conexionApi);
        switch ($formato) {
            case "COMPLETA":
                return $this->JSONRespuesta = $resultado;
            case "OBJETO":
                return $this->JSONRespuesta = $this->respuesta;
            case "JSON":
                return $this->JSONRespuesta = json_encode($this->respuesta);
            case "DATOS":
            default:
                if ($this->respuesta->RESPUESTA == "EXITO") {
                    return $this->JSONRespuesta = $this->respuesta->DATOS;
                } else {
                    return $this->JSONRespuesta = $this->respuesta->MENSAJE;
                }
                break;
        }
    }

    public function desconectar() {
        return curl_close($this->conexionApi);
    }

    private static function mostrar_resultado_para_depuracion($objetos, $informacion = null) {
        if (ApiSICAM::$MOSTRAR_RESPUESTA_API) {
            echo "\n-->>>\n ";
            if ($informacion) {
                echo "[" . strtoupper($informacion) . "]\n ";
            }
            print_r($objetos);
            if (empty($objetos)) {
                var_dump($objetos);
                echo "[Vacio / Sin Respuesta ]\n ";
            }
            echo "<<<--\n\n ";
        }
    }
}

class SesionAPI {

    static function colaboradorLOGUEADO() {

        return false;
    }

    static function colaborador($dato = null) {

        return false;
    }

    static function abrir() {
        $status = session_status();
        switch ($status) {
            case PHP_SESSION_NONE:
                error_reporting(0);
                session_start();
                error_reporting(E_ALL);
                break;
            case PHP_SESSION_ACTIVE:
                return true;
                break;
        }
    }

    static function cerrar() {
        $status = session_status();
        if ($status == PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    static function activa() {
        return self::valor(OBJETO_SESION_API);
    }

    static function dato($variable, $valor = null) {
        if (!is_null($valor)) {
            self::abrir();
            $SesionActiva = self::valor(OBJETO_SESION_API);
            if (!property_exists($SesionActiva, $variable)) {
                $SesionActiva->$variable = new stdClass();
            }
            $SesionActiva->$variable = $valor;
////            echo "    .............      ";
//            print_r($SesionActiva);
//            print_r(SesionCliente::valor(OBJETO_SESION_API));
        } else {
            self::abrir();
            $SesionActiva = self::valor(OBJETO_SESION_API);
            if (property_exists($SesionActiva, $variable)) {
                $valor = $SesionActiva->$variable;
            }
        }
        return $valor;
    }

    static public function valor($nombre, $valor = null) {
        if (!is_null($valor)) {
            self::abrir();
            $_SESSION [$nombre] = $valor;
//            self::cerrar();
        } else {
            self::abrir();
            if (!empty($_SESSION [$nombre])) {
                try {
                    try {
                        $valor = $_SESSION [$nombre];
                    } catch (Exception $e) {
                        $valor = null;
                    }
                } catch (Exception $e) {
                    $valor = null;
                }
//                self::cerrar();
                return $valor;
            } else {
//                self::cerrar();
                return false;
            }
        }
    }

    static public function eliminar($nombre) {
        self::abrir();
        unset($_SESSION [$nombre]);
        self::cerrar();
    }

    static public function destruir() {
        self::abrir();
        $_SESSION = array();
        session_destroy();
        self::cerrar();
    }
}

class RespuestasSICAM {
//put your code here
    const EXITO = 'EXITO';
    const INFO = 'INFO';
    const FALLO = 'FALLO';
    const ALERTA = 'ALERTA';
    const ERROR = 'ERROR';

    public static function enJSON($respuesta, $mensaje, $datos = null, $error = null) {
        header('Content-Type: application/json; charset=utf-8');
        echo self::respuesta($respuesta, $mensaje, $datos);
    }

    public static function responseJson($respuesta, $mensaje, $datos = null) {
        echo self::respuesta($respuesta, $mensaje, $datos);
    }

    public static function respuesta($respuesta, $mensaje, $datos = null, $codigo = null, $error = null) {

        if (array_key_exists('ERROR', $_SESSION) && !empty(SesionAPI::valor("ERROR"))):
            SesionAPI::valor("ERROR", [$respuesta, $mensaje]);
        endif;

        if (!empty(SesionAPI::valor('ERROR'))) {
            $mensaje .= "<br /><strong>___Datos para Soporte TICS___</strong><br /><em><small>" . SesionAPI::valor('ERROR') . "</small></em>";
            SesionAPI::valor('ERROR', '');
        }

        if (!empty($mensaje)) {
            $mensaje = "<em>" . $mensaje . "</em>";
        }
        if (is_null($error)) {
            $error = "NO DEFINIDO";
        }
        $arrayRespuesta = array(
            'RESPUESTA' => $respuesta,
            'MENSAJE' => $mensaje,
            'DATOS' => $datos,
            'CODIGO' => $codigo,
            'ERROR' => $error
        );
        $jsonRespuesta = json_encode($arrayRespuesta);
        return $jsonRespuesta;
    }

    static public function exito($mensaje = null, $datos = null) {
        if (is_array($mensaje)) {
            $tmp = $datos;
            $datos = $mensaje;
            $mensaje = "";
        }

        if (is_object($mensaje)) {
            $array = (array) $mensaje;
            $mensaje = "";
            $datos = $array;
        }

        return self::respuesta(self::EXITO, $mensaje, $datos);
    }

    static public function alerta($mensaje, $datos = null) {
        return self::respuesta(self::ALERTA, $mensaje, $datos);
    }

    static public function fallo($mensaje, $datos = null) {
        return self::respuesta(self::FALLO, $mensaje, $datos);
    }

    static public function error($mensaje, $codigo = null, $datos = null) {
        return self::respuesta(self::ERROR, $mensaje, $datos, $codigo);
    }

    static public function informacion($mensaje, $datos = null) {
        if (is_array($mensaje)) {
            $tmp = $datos;
            $datos = $mensaje;
            $mensaje = "";
        }

        if (is_object($mensaje)) {
            $array = (array) $mensaje;
            $mensaje = "";
            $datos = $array;
        }
        return self::respuesta(self::INFO, $mensaje, $datos);
    }

    public static function getRespuesta() {
        return self::$respuesta;
    }

    public static function getMensajeRespuesta() {
        return self::$mensaje;
    }
}
$Api = ApiSICAM::ObjetoAPI();
