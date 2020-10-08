<?php


trait OcrEscExUsuarios
{
    protected function extraerDatosVendedor(){
        list($textoOCR, $posIni, $posFin) = $this->getPDatosVendedor();

        $res = $this->extraerDatosUsuario($posIni, $posFin, $textoOCR);
        if ($res){
            $this->out['vendedores'] = $res;
        }
        return $res;
    }

    private function extraerDatosUsuario($posIni, $posFin, $textoOCR){
        $textoAAnalizars = substr($textoOCR, $posIni, ($posFin - $posIni));

        //limpiar texto, es nesario que se repita las lineas
        $textoAAnalizars = str_replace([' no ','.','*'], [' ','',''], $textoAAnalizars);
        $textoAAnalizars = str_replace([' no ','.','*'], [' ','',''], $textoAAnalizars);

        $textoAAnalizars = preg_replace('/[^a-zA-Z0-9 \\n]+/ui', '', $textoAAnalizars);
        preg_match_all("/ (cc|nit)[ ]?([0-9]+)/ui", ' '.$textoAAnalizars.' ', $concidencias);

        $listaUsuarios = [];
        $concidencias[0][-1] = '';
        for($i=0; $i<count($concidencias[0])-1; $i++) {
            $patronC = $concidencias[0];
            $patronCcNit = $concidencias[1][$i];
            $patronCCnum = $concidencias[2][$i];

            //".trim($patronC[$i-1])."
            if (strpos($textoAAnalizars, "\n")){
                preg_match("/\\n[ ]*([a-zA-Z0-9 ]+) ".trim($patronC[$i])."/ui",
                    $textoAAnalizars, $c1);
            }else{
                preg_match("/".trim($patronC[$i-1])." ([a-zA-Z0-9 ]+) ".trim($patronC[$i])."/ui",
                    $textoAAnalizars, $c1);
            }



            $n = $c1[1];
            $n = preg_replace('/ [a-zA-Z0-9ñ]{1}$/', '', $n);
            $n = preg_replace('/^[a-zA-Z0-9ñ]{1} /', '', $n);
            $n = trim($n);
            if ($n){
                $listaUsuarios[] = ['nombre' => $n,
                    'cc' => $patronCCnum,
                    'tipo' => $patronCcNit
                ];
            }
            if ($patronCcNit=='nit'){
                $this->out['tipoNegocio'] = 'PO';
            }
        }
        return $listaUsuarios;
    }

    protected function extraerDatosComprador(){
        list($textoOCR, $posIni, $posFin) = $this->getPDatosComprador();

        $res = $this->extraerDatosUsuario($posIni, $posFin,$textoOCR);
        if ($res){
            $this->out['compradores'] = $res;
        }
        return $res;
    }

}