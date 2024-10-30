<?php
namespace App\Utils;

class Utils{

    public static function replaceSpecialCharacters($string) {
        $search  = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        return str_replace($search, $replace, $string);
    }

    public static function generarSecuencial($valor){
        $valorReal=intval($valor)+1;
        return str_pad($valorReal, 9, '0', STR_PAD_LEFT);
    }
    
    
}