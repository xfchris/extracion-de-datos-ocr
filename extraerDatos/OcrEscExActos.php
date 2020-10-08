<?php


trait OcrEscExActos
{
   /*
   * Funciones para estraer data
   */
    protected function extraerDatosActos(){
        list($textoOCR, $posIni, $posFin) = $this->getPDatosActos();

        $textoAAnalizars[] = substr($textoOCR, $posIni, ($posFin - $posIni));

        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 1);
        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 2);
        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 3);

        $listaABuscados = [];
        foreach (self::$actos as $acto) {
            $nombreActo = strtolower(GstOcrTools::quitarTildes(strtolower($acto->nombre)));
            //$nombreActo = str_replace(['sin cuantia','con cuantia'], '', $nombreActo);
            $nombreActo = trim($nombreActo);

            $codigoActo = $acto->codigo;

            //1. busco actos en lista de Actos Buscados, si no esta:
            if (!$listaABuscados[$codigoActo]){
                //2. lo añado a la lista de actos buscados
                $listaABuscados[$codigoActo] = $nombreActo;

                //3. busco acto en el texto de diferentes formas:
                foreach ($textoAAnalizars as $textoAAnalizar) {

                    if ($posActo = stripos($textoAAnalizar, $nombreActo)) { //normal
                        break;
                    }else{
                        $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 1);
                        if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                            $nombreActo = $nActo;
                            break;
                        }else{
                            $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 2);
                            if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                                $nombreActo = $nActo;
                                break;
                            }else{
                                $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 3);
                                if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                                    $nombreActo = $nActo;
                                    break;
                                }
                            }
                        }
                    }
                }

                //quitandole palabras de 1 caracter al acto

                //4. busco quitandole palabras de 1 caracter al texto a analizar

                if ($posActo!==false){
                    //4. Si esta, lo añado a la lista de encontrados y extraigo el valor del acto
                    $actoEncontrado = $acto;
                    $actoEncontrado->valor = $this->extraerValorActo($textoAAnalizar, $posActo, $nombreActo);
                    $listaAEncontrados[] = $actoEncontrado;
                }
            }
        }
        if ($listaAEncontrados){
            $this->out['actos'] = $listaAEncontrados;
        }
        return $listaAEncontrados;
    }



    private function extraerValorActo($textoAAnalizar, $posActo, $nombreActo){
        $posFinal = $posActo+strlen($nombreActo);
        $textoValorA = trim(substr($textoAAnalizar, $posFinal));
        $textoValorA = str_replace('$ ', ' $', $textoValorA);
        $textoValorA = str_replace('  ', ' ', $textoValorA);
        preg_match("/([a-zA-Z0-9.,\$%]{3,})( [a-zA-Z0-9.,\$%]+){0,4}/ui", trim($textoValorA,'.,:'), $concidencias);

        unset($concidencias[0]);
        foreach ($concidencias as $concidencia) {
            if (stripos(' '.$concidencia, ' $')!==FALSE){
                return explode(" $", ' '.$concidencia)[1];
            }
        }
        return '';
        /*
        $concidencias[1] = trim($concidencias[1],' $');
        $concidencias[2] = trim($concidencias[2],' $');
        $concidencias[3] = trim($concidencias[3],' $');
        $res = '';
        //a. Si el primero es un numero, retorne ese numero
        if (is_numeric(str_replace(['.',','],'',$concidencias[1]))){
            $res = $concidencias[1];
            //b. si es un solo catacter, y despues le sigue un numero:
        }elseif(strlen($concidencias[1])===1 && is_numeric(str_replace(['.',','],'',$concidencias[2]))){
            $res = $concidencias[2];
        }
        return $res;*/
    }


    /**
     * Extraer datos de actos en formato 5
     * @return mixed
    protected function extraerDatosActosFormato5(){
        list($textoOCR, $posIni, $posFin) = $this->getPDatosActos();

        $textoAAnalizars[] = substr($textoOCR, $posIni, ($posFin - $posIni));

        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 1);
        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 2);
        $textoAAnalizars[] = GstOcrTools::quitarPalabrasDe($textoAAnalizars[0], 3);

        $listaABuscados = [];
        foreach (self::$actos as $acto) {
            $nombreActo = strtolower(GstOcrTools::quitarTildes(strtolower($acto->nombre)));
            //$nombreActo = str_replace(['sin cuantia','con cuantia'], '', $nombreActo);
            $nombreActo = trim($nombreActo);

            $codigoActo = $acto->codigo;

            //1. busco actos en lista de Actos Buscados, si no esta:
            if (!$listaABuscados[$codigoActo]){
                //2. lo añado a la lista de actos buscados
                $listaABuscados[$codigoActo] = $nombreActo;

                //3. busco acto en el texto de diferentes formas:
                foreach ($textoAAnalizars as $textoAAnalizar) {

                    if ($posActo = stripos($textoAAnalizar, $nombreActo)) { //normal
                        break;
                    }else{
                        $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 1);
                        if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                            $nombreActo = $nActo;
                            break;
                        }else{
                            $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 2);
                            if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                                $nombreActo = $nActo;
                                break;
                            }else{
                                $nActo = GstOcrTools::quitarPalabrasDe($nombreActo, 3);
                                if ($posActo = stripos($textoAAnalizar, $nActo) && $nActo){
                                    $nombreActo = $nActo;
                                    break;
                                }
                            }
                        }
                    }
                }

                //quitandole palabras de 1 caracter al acto

                //4. busco quitandole palabras de 1 caracter al texto a analizar

                if ($posActo!==false){
                    //4. Si esta, lo añado a la lista de encontrados y extraigo el valor del acto
                    $actoEncontrado = $acto;
                    $actoEncontrado->valor = $this->extraerValorActo($textoAAnalizar, $posActo, $nombreActo);
                    $listaAEncontrados[] = $actoEncontrado;
                }
            }
        }
        if ($listaAEncontrados){
            $this->out['actos'] = $listaAEncontrados;
        }
        return $listaAEncontrados;
    }

    private function extraerValorActoFormato5($textoAAnalizar, $posActo, $nombreActo){
        $posFinal = $posActo+strlen($nombreActo);
        $textoValorA = trim(substr($textoAAnalizar, $posFinal));
        $textoValorA = str_replace(' $ ', ' $', $textoValorA);
        preg_match("/([a-zA-Z0-9.,]{3,})( [a-zA-Z0-9.,\$]+){0,3}/ui", trim($textoValorA,'.,:'), $concidencias);

        unset($concidencias[0]);
        foreach ($concidencias as $concidencia) {
            if (stripos($concidencia, ' $')!==FALSE){
                return explode(" $",$concidencia)[1];
            }
        }
    }*/

}