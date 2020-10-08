<?php
include_once('GstOcrTools.php');
include_once('GstOcrTipoEsc.php');

/**
 * Class GstOcr
 * Clase ocr la cual recibira tod.o el texto y se encargarà de extraer la data que necesita
 */
class GstOcr{
    public $textoOCR;
    public $tipoEscObj; //Tipo de escritura clase
    public $outOld; //informacion de salida antigua //Funcioando actualmente
    public $out; //informacion de salida
    public $usarFormato = '*';
    public static $actos = null;
    public static $rutaEscritura = null;


    public function __construct($rutaEsctirura, $actos)
    {
        self::$rutaEscritura = $rutaEsctirura;
        self::$actos = $actos;
    }

    public function ejecutarSoloOCR($numPaginasOCR=5, $optionsOCR=3, $mostrarErrorSHELL=false){
        $rutaOCR = 'ocr/acts_detection/';

        //con la 2, solo me trae el texto del OCR    //$nombreEscrituraCache = 'escritura'.$ticket.'.pdf.json';
        $fileSc = self::$rutaEscritura;
        if (!file_exists($fileSc)){
            throw new Exception('la escritura ('.$fileSc.') no fue encontrada');
        }
        if (pathinfo($fileSc)['extension'] == 'txt'){
            $res = ['ocr_text'=>file_get_contents($fileSc)];
            $this->textoOCR = json_encode($res);

        }else{
            $python = 'cd '.getcwd().'/'.$rutaOCR.'; python3.6 application_extractor_function.py '.$fileSc.' '.$numPaginasOCR.' '.$optionsOCR;
            if ($mostrarErrorSHELL){
                $esShell=' 2>&1';
            }
            $this->textoOCR = shell_exec($python.$esShell); //.' 2>&1'

        }
        return $this->textoOCR; //.' 2>&1'

    }

    public function usarFormato($num = '*'){
        if (preg_match("/[0-9]+/", $num)){
            $this->usarFormato = $num;
        }
    }
    public function ejecutarOCRYExtraerDatos($numPaginasOCR=5, $optionsOCR=3){
        $this->ejecutarSoloOCR($numPaginasOCR, $optionsOCR);

        if ($this->textoOCR){
            //llenar valores
            $this->prepararTexto($this->textoOCR);

            if ($this->esUnaEscritura()){
                //detectar tipo de escritura
                $this->detectarTipoEscritura();
                //extraer datos
                $this->extraerDatosEscritura();
            }
        }
    }

    //Detecta si es una escritura o no
    private function esUnaEscritura(){
        return true;
    }

    /**
     * Preparo el texto
     * @param $textoOCR
     */
    private function prepararTexto($textoOCR){
        $textoOCR = GstOcrTools::quitarTildes($textoOCR);
        $this->textoOCR = $textoOCR;
    }

    /**
     * Detecto el tipo de escritura
     * @throws Exception
     */
    private function detectarTipoEscritura(){
        $dir = dirname(__FILE__);
        $ruta = $dir.'/formatos/';
        $formatos = glob($ruta."GstOcrTipoEsc".$this->usarFormato.".php");

        $reporte = [];
        foreach ($formatos as $formato) {
            include_once($formato);
            $tipoEscClassFile = str_ireplace([$ruta, '.php'], '', $formato);
            $this->tipoEscObj = new $tipoEscClassFile($this->getActos(), $this->textoOCR);

            //Si es el tipo de acto, retorna true.
            $r = $this->tipoEscObj->validarTipoActo();
            $reporte[$tipoEscClassFile] = $r;
            if (($r[0] && $_GET['op']!='validacion') ||
                (is_numeric($this->usarFormato) && $_GET['op']!='validacion')){
                $this->tipoEscObj->out['_ocrFormato'] = $tipoEscClassFile;
                return true;
            }
        }
        $msg = 'No se pudo detectar el tipo de escritura';
        if (NOPRODUCCION === true || ($_GET['op']=='validacion')){
           $msg = json_encode($reporte);
        }
        throw New Exception($msg);
    }

    private function extraerDatosEscritura(){
        $ocrEscritura = $this->tipoEscObj;
        $ocrEscritura->extraerDatos();
        $this->out = $ocrEscritura->out;
        $this->outOld = $this->datosAOCRV1($this->out);
    }

    private function datosAOCRV1($out){
        $res = [];
        foreach ($out['actos'] as $acto) {
            $valor = $acto->valor;
            if (preg_match('/(\.00)$/ui', $valor) && $valor){ //si tiene .00
                $valor = preg_replace('/(\.00)$/ui', '', $valor);
            }
            $valor = str_replace('.', '', $valor);
            $res['value_acts'][] = ($valor+0);
            $res['acts'][] = $acto->nombre;
            $res['code_acts'][] = ($acto->codigo+0);
        }
        $res['document_type'] = [$out['tipoDocumento']];

        foreach ($out['compradores'] as $item) {
            $res['names'][] = $item['nombre'];
            $res['identification_number'][] = $item['cc'];
            $res['identification_number_cc'][] = $item['tipo'];

        }
        foreach ($out['vendedores'] as $item) {
            $res['names'][] = $item['nombre'];
            $res['identification_number'][] = $item['cc'];
            $res['identification_number_cc'][] = $item['tipo'];
        }

        $res['business_type'] = [$out['tipoNegocio']];
        $res['ubication'] = $out['ubicacion'];


        return $res;
    }


    //Herramientas=============================================//
    private function getActos(){
        return self::$actos;
    }

}