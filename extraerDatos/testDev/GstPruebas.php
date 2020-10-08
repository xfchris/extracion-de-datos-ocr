<?php


class GstPruebas
{
    static function extraerDatos($type='pdf'){
        $dirOCR = 'extraerDatos/';
        include_once($dirOCR.'GstOcr.php');
        $rutaOCRPHP = $dirOCR.'formatos/';

        $numPaginasOCR=5;
        $optionsOCR=3;

        try {
            $escPrueba = $_GET['escritura']+0;
            $rutaEscritura = getcwd().'/'.$rutaOCRPHP.'escriturasDePruebas/escritura'.$escPrueba.'.'.$type;

            $actos = json_decode(file_get_contents($dirOCR.'testDev/actos.json'));



            $gstOcr = new GstOcr($rutaEscritura,$actos);

            if ($_GET['_nxDebug']=='True'){
                echo $gstOcr->ejecutarSoloOCR($numPaginasOCR,$optionsOCR,true);

            }elseif ($_GET['op']=='textoOcr') {
                $ocrText = $gstOcr->ejecutarSoloOCR($numPaginasOCR, $optionsOCR);
                $ocrText = json_decode($ocrText, true);
                echo strtolower($ocrText['ocr_text']);

            }else{
                $gstOcr->usarFormato($_GET['formato']);
                $gstOcr->ejecutarOCRYExtraerDatos($numPaginasOCR,$optionsOCR);
                echo json_encode($gstOcr->out);
            }

        }catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
}