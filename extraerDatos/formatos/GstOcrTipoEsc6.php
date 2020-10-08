<?php


class GstOcrTipoEsc6 extends GstOcrTipoEsc
{
    protected function getPDatosActos(){
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['datosActos']){
            return parent::$posiciones['datosActos'];
        }

        $textoOCR = parent::$textoOCR;
        $textoOCR = str_replace(["\n"], " ", $textoOCR);
        $textoOCR = str_replace('  ', " ", $textoOCR);

        //buscar 'codigo especificacion valor acto'
        $posIni = GstOcrTools::strpos_arr($textoOCR, [
            'clase de acto valor acto',
        ], 0, true);


        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'ubicaciÓn y direcciÓn del inmueble',
            'ubicacion y direccion del inmueble',
        ]);

        if (!$posIni){
            $posIni = GstOcrTools::strpos_arr($textoOCR, [
                'acto o contrato'
            ], 0, true);


            $posFin = GstOcrTools::strpos_arr($textoOCR, [
                'otorgante'
            ]);
        }

        if (!$posIni){
            $res =  null;
        }else{
            if ($posIni>$posFin){
                $posFin = $posIni + 250;
            }
            $res = [$textoOCR, $posIni, $posFin];
        }

        parent::$posiciones['datosActos'] = $res;
        return $res;
    }

    protected function getPDatosVendedor()
    {
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['datosVendedor']){
            return parent::$posiciones['datosVendedor'];
        }

        $textoOCR = str_replace("\n", " ", parent::$textoOCR);
        $textoOCR = str_replace("  ", " ", $textoOCR);
        //buscar 'codigo especificacion valor acto'
        $posIni = GstOcrTools::strpos_arr($textoOCR, [
            'vendedora',
            'vendedor',
        ], 0, true);
        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'compradora',
            'comprador'
        ]);

        if (!$posIni){
            $res =  null;
        }else{
            if ($posIni>$posFin){
                $posFin = $posIni + 250;
            }
            $res = [$textoOCR, $posIni, $posFin];
        }
        parent::$posiciones['datosVendedor'] = $res;
        return $res;
    }

    protected function getPDatosComprador()
    {
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['datosComprador']){
            return parent::$posiciones['datosComprador'];
        }

        //buscar 'codigo especificacion valor acto'
        $textoOCR = parent::$textoOCR;
        $textoOCR = str_replace("\n", " ", $textoOCR);
        $textoOCR = str_replace("  ", " ", $textoOCR);

        $posIni = GstOcrTools::strpos_arr($textoOCR, [
            'compradora',
            'comprador'
        ], 0, true);
        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'en el municipio de '
        ],1);

        if (!$posIni){
            $res =  null;
        }else{
            if ($posIni>$posFin){
                $posFin = $posIni + 250;
            }
            $res = [$textoOCR, $posIni, $posFin];
        }
        parent::$posiciones['datosComprador'] = $res;
        return $res;
    }


    protected function getPUInmueble(){
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['uInmueble']){
            return parent::$posiciones['uInmueble'];
        }

        $textoOCR = parent::$textoOCROriginal;
        list($posIni, $posIniFEncontrada) = GstOcrTools::strpos_arr($textoOCR, [
            'ubicacion del predio',
            'ubic del predio',
            'ubicacion de los predios',
            'direccion del inmueble',
        ], 0, true, true);

        if (!$posIni){
            return null;
        }

        //se añaden en el array las que no necesitan salto de linea
        if (!in_array($posIniFEncontrada, ['direccion del inmueble'])){
            $conSaltoLinea=true;
        }

        if (!$posIni){
            $res =  null;
        }else{
            //datos de la escritura publica
            $posFin = $posIni + 450;
            $res = [$textoOCR, $posIni, $posFin, $posIniFEncontrada, $conSaltoLinea];
        }
        parent::$posiciones['uInmueble'] = $res;
        return $res;
    }

    public function extraerDatos()
    {
        $this->extraerDatosActos();
        $this->extraerDatosComprador();
        $this->extraerDatosVendedor();
        $this->extraerUInmueble();

        $this->extraerTipoDocumento(); //escritura
        $this->extraerTipoNegocio(); //document_type PP
    }
}