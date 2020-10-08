<?php
include_once('OcrEscExActos.php');
include_once('OcrEscExUsuarios.php');
include_once('OcrEscExUInmueble.php');

abstract class GstOcrTipoEsc
{
    use OcrEscExActos;
    use OcrEscExUsuarios;
    use OcrEscExUInmueble;


    public static $actos;
    public static $textoOCR;
    public static $textoOCROriginal;
    public static $tipoDocumento = 'escritura';
    public static $posiciones = 'Posiciones';
    public $out;

    //array de posiciones para cache
    abstract protected function getPDatosActos();
    abstract protected function getPDatosVendedor();
    abstract protected function getPDatosComprador();
    abstract protected function getPUInmueble();

    abstract public function extraerDatos();


    public function __construct($actos, $textoOCR)
    {
        self::$posiciones = [
            'datosActos'=>null,
            'datosVendedor'=>null,
            'datosComprador'=>null,
            'uInmueble'=>null,
        ];

        self::$actos = $actos;
        self::$textoOCROriginal = json_decode($textoOCR, true);
        self::$textoOCROriginal = strtolower(self::$textoOCROriginal['ocr_text']);
        self::$textoOCROriginal = strtolower(GstOcrTools::quitarTildes(self::$textoOCROriginal));
        self::$textoOCR = $this->tratarTextoOCR();
    }

    private function tratarTextoOCR(){
        $textoOCR = strtolower(self::$textoOCROriginal);
        $textoOCR = str_replace(['=','-'], '', $textoOCR);   //le dejo los puntos
        //$textoOCR = str_replace([':','$','(',')','['], ' ', $textoOCR);

        $textoOCR2 = preg_split("/[:()\[ ]/u", $textoOCR, -1,PREG_SPLIT_NO_EMPTY);
        $textoOCR2 = implode(" ", $textoOCR2);
        return $textoOCR2;
    }




    public function validarTipoActo()
    {
        $va1 = $this->getPDatosActos();
        $va2 = $this->getPDatosVendedor();
        $va3 = $this->getPDatosComprador();
        $va4 = $this->getPUInmueble();
        $todos = ($va1 && ($va2 or $va3) && $va4);
        return [$todos, !!$va1, !!$va2, !!$va3, !!$va4];
    }





    //METODOS DE ESTRACCION DE DATOS:
    protected function extraerTipoDocumento(){
        $this->out['tipoDocumento'] = self::$tipoDocumento;
        return self::$tipoDocumento;
    }

    /**
     * la variable del array tipoNegocio se llena en la funcion extraerDatosUsuario cuando se encuentra algun
     * usuario con nit
     * @return mixed|string
     */
    protected function extraerTipoNegocio(){
        if (!$this->out['tipoNegocio']){
            $this->out['tipoNegocio'] = 'PP';
        }
        return $this->out['tipoNegocio'];
    }
}