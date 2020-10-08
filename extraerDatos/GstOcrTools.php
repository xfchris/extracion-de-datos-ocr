<?php


class GstOcrTools
{
    public static function quitarTildes($texto){
        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        return strtr( $texto, $unwanted_array );
    }

    /**
     *
     * @param $haystack
     * @param $needle
     * @param int $offset
     * @param false $sumLong Le suma a la posicion encontrada, la longitud del texto que fue a buscar
     * @return array|false|int
     */
    public static function strpos_arr($haystack, $needle, $offset=0, $sumLong=false, $addLaEncontrda=false) {
        if(!is_array($needle)){
            $needle = array($needle);
        }
        foreach($needle as $k => $what) {
            if(($pos = stripos($haystack, $what, $offset))!==false){
                if ($sumLong){
                    $res = $pos+strlen($what); //sumo la posicion y la longitud del texto a buscar
                }else{
                    $res = $pos;
                }
                if ($addLaEncontrda){
                    return [$res, $what];
                }
                return $res;
            }
        }
        return false;
    }

    /**
     * Quita las palabras que tengan x caracter como Maximo
     * @param $texto
     * @param $numCaracterMax
     * @return mixed
     */
    public static function quitarPalabrasDe($texto, $numCaracterMax=1){
        $texto = " ".trim($texto)." ";

        //preg_match_all("/ [a-zA-Z0-9]{1,".$numCaracterMax."} /ui", $texto, $coincidencias);
        $texto = preg_replace("/ [a-zA-Z0-9]{1,".$numCaracterMax."} /ui", " ", $texto);

        return trim($texto);
    }
}