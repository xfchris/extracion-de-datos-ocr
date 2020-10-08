<?php


trait OcrEscExUInmueble
{
    protected function extraerUInmueble(){
        //buscar 'codigo especificacion valor acto'
        list($textoOCR, $posIni, $posFin, $fraseEncontrada, $conSaltoLinea) = $this->getPUInmueble();


        $textoAAnalizars = substr($textoOCR, $posIni, ($posFin - $posIni));


        if ($conSaltoLinea){
            $primerSaltoLinea = strpos($textoAAnalizars, "\n");
            $textoAAnalizars = substr($textoAAnalizars, $primerSaltoLinea+1, ($posFin - $primerSaltoLinea));
        }

        if (in_array($fraseEncontrada, ['ubicacion del predio','ubic del predio'])){
            $posFin = GstOcrTools::strpos_arr($textoAAnalizars, [
                'datos de la escritura publica',
                'codigo naturaleza juridica del'
            ],0);
        }else{
            $posFin = GstOcrTools::strpos_arr($textoAAnalizars, ['===','---','...','___','matricula inmobiliaria']);
        }


        $textoAAnalizars = substr($textoAAnalizars, 0, ($posFin - 0));
        $textoAAnalizars = trim($textoAAnalizars, '-= .:');

        $textoAAnalizars = str_replace("\n", " ", $textoAAnalizars);
        $this->out['ubicacion'] = preg_replace('/[^a-zA-Z0-9ñ \(\),\:_-]/ui', '', $textoAAnalizars);
        return $textoAAnalizars;
    }
}