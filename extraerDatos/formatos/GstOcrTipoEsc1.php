<?php

class GstOcrTipoEsc1 extends GstOcrTipoEsc
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
            'especificacion valor acto',
            'naturaleza juridica del acto valor',
            'naturaleza juridica delacto valor'
        ], 0, true);


        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'personas que intervienen identificacion',
            'personas que intervienen en el acto',
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

    protected function getPDatosVendedor(){
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['datosVendedor']){
            return parent::$posiciones['datosVendedor'];
        }

        $textoOCR = str_replace("\n", " ", parent::$textoOCR);
        $textoOCR = str_replace("  ", " ", $textoOCR);
        //buscar 'codigo especificacion valor acto'
        $posIni = GstOcrTools::strpos_arr($textoOCR, [
            'personas que intervienen identificacion vendedores',
            'personas que intervienen en el acto el vendedor identificacion',
        ], 0, true);
        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'compradores',
            'la sociedad compradora identificacion'
        ]);

        if (!$posIni){
            $posIni = GstOcrTools::strpos_arr($textoOCR, [
                'otorgante',
                'los beneficiarios identificacion'
            ], 0, true);
            $posFin = GstOcrTools::strpos_arr($textoOCR, [
                'direccion del inmueble',
                "\nen la ciudad de"
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
        parent::$posiciones['datosVendedor'] = $res;
        return $res;
    }

    protected function getPDatosComprador(){
        //Para mejorar el rendimiento, se utliliza esto y abajo se hace el primer guardado
        if (parent::$posiciones['datosComprador']){
            return parent::$posiciones['datosComprador'];
        }

        //buscar 'codigo especificacion valor acto'
        $textoOCR = parent::$textoOCR;
        $textoOCR = str_replace("\n", " ", $textoOCR);
        $textoOCR = str_replace("  ", " ", $textoOCR);

        $posIni = GstOcrTools::strpos_arr($textoOCR, [
            'compradores',
            'la sociedad compradora identificacion',
            'la fideicomitente identificacion'
        ], 0, true);
        $posFin = GstOcrTools::strpos_arr($textoOCR, [
            'los beneficiarios identificacion'
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






    public function extraerDatos(){
        $this->extraerDatosActos(); //ya1
        $this->extraerDatosComprador(); //ya1
        $this->extraerDatosVendedor(); //ya1
        $this->extraerUInmueble();

        $this->extraerTipoDocumento(); //ya1 ya2 ya3
        $this->extraerTipoNegocio(); //document_type PP
    }

}