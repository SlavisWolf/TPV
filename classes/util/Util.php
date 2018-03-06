<?php

class Util {

    static function encriptar($cadena, $coste = 10) {
        $opciones = array(
            'cost' => $coste
        );
        return password_hash($cadena, PASSWORD_DEFAULT, $opciones);
    }
    
    static function renderHtmlSelect(array $array, $name, $valor = null) {
        $html = '<select name="' . $name . '">';
        foreach ($array as $key => $value) {
            $selected = '';
            if ($valor == $key) {
                $selected = 'selected="selected"';
            }
            $html .= '<option ' . $selected . 'value=' . $key . '>' . $value . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function renderTemplate($template, array $data = array()) {
        if (!file_exists($template)) {
            return '';
        }
        $content = file_get_contents($template);
        return self::renderText($content, $data);
    }

    static function renderText($text, array $data = array()) {
        foreach ($data as $indice => $dato) {
            $text = str_replace('{{' . $indice . '}}', $dato, $text);
        }
        //quitar los {{...}} restantes
        $text = preg_replace('/{{[^\s]+}}/', '', $text);
        return $text;
    }

    static function varDump($valor){
        return '<pre>' . var_export($valor, true) . '</pre>';   
    }

    static function verificarClave($claveSinEncriptar, $claveEncriptada) {
        return password_verify($claveSinEncriptar, $claveEncriptada);
    }
    
    // convierte un array de objetos (objetos de la clase Object) en un array de arrays
    static function objectsToArrays($list) {
        $new_list = array();
        foreach($list as $index => $value) {
            $new_list[$index] = $value->toArray();
        }                       
        return $new_list;
    }    
    
    
    
    // adaptacion de nuestra funcion de javascript a PHP
    static function limitarTamanoTexto($texto, $longitud = 20) {
    
        if ($texto === null)
            return null;
        
        if (strlen($texto) > $longitud)  {
            $proximoEspacioIndex =  strpos($texto, ' ', $longitud);
            if ($proximoEspacioIndex !== false) {
                return  substr($texto, 0, $proximoEspacioIndex) . "...";
            }                     
        }    
        return $texto; 
    }



    // se le puede pasar un string que acepte datetime para crear una fecha
    static function formatearFechaEnFormatoEspañol($fecha) {
        $format = 'd/m/Y H:i:s';
        
        if ($fecha instanceof DateTime) {
            return $fecha->format($format);
        }
        
        else if ($objeto = new DateTime($fecha)) {
            return $objeto->format($format);
        }
        
        else {
            return false;
        }
        
    }
    
    
    static function verificarDNI($dni) {
        $letra = array("T", "R", "W", "A", "G", "M", "Y", "F", "P", "D", "X", "B", "N", "J", "Z", "S", "Q", "V", "H", "L", "C", "K", "E");
        $numero =  mb_substr($dni, 0, strlen($dni) - 1);
        
        if (is_numeric($numero) ) { // si es un numero
            // compara cadenas, insensible a mayusculas
            if(strcasecmp(mb_substr($dni, strlen($dni) - 1),  $letra[$numero % 23]) === 0) {
                return true;
            }
        }
        return false;
    }
    
    static function verificarNIE($nie) {

        $numeroInicial;
        switch(strtoupper(mb_substr($nie, 0, 1 ) ) ) {
             case 'X':
                $numeroInicial = 0;
                break; 
                
            case 'Y':
                $numeroInicial = 1;
                break; 
                
            case 'Z':
                $numeroInicial = 2;
                break; 
                
            default:
                return false;
        }
        return self::verificarDNI($numeroInicial . mb_substr($nie, 1) );
    }
    
    static function verificarCIF($cif) {
         // las 3 ultimas letras son para el formato antiguo
        $tipoOrganizacion = array("A", "B", "C", "D", "E", "F", "G", "H", "N", "P", "Q", "S", "K" , "L", "M", "J", "R", "W", "U" , "V");
        $solo_letras = array( "K", "P", "Q" , "S"); // si la primera letra esta aquí, el ultimo digito sera una letra
        $solo_numero = array("A", "B", "E" , "H"); // si la primera letra esta aquí, el ultimo digito sera un número
        
        // tras hacer los calculos, esta sera una de las posibles letras para el último dígito.
        $letra_resultado = array("J", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J");
        
        
        // comprobamos que la letra inicial sea válida
        $organizacion = strtoupper(mb_substr($cif, 0, 1 ) );
        if(in_array($organizacion, $tipoOrganizacion) ) {
            
            $numerosIntermedios = mb_substr($cif, 1, strlen($cif) - 2);
            
            if(is_numeric($numerosIntermedios) ) {
                $pares_impares = self::obtenerParesImparesCadena($numerosIntermedios);
                
                $operacion_pares = array_sum($pares_impares['pares']);
                $operacion_impares = self::operacionImparesCIF($pares_impares['impares']);
                
                $sumaTotal = $operacion_pares + $operacion_impares;
                
                $unidades_suma = intval( mb_substr($sumaTotal, strlen($sumaTotal) - 1) ) ;
                
                 //falla aqui cuando el resultado de unidades_suma da 0;
                $unidades_operado = 10 - $unidades_suma;
                
                
                if ($unidades_operado === 10) {
                    $unidades_operado = 0;
                }
                
                $digito_control =  mb_substr($cif, strlen($cif) - 1) ;
                
                if(in_array($organizacion, $solo_letras) ) {
                    if (strcasecmp ($digito_control, $letra_resultado[$unidades_operado]) === 0 ) {
                        return true;
                    }
                }
                
                
                else if (in_array($organizacion, $solo_numero) ) {
                
                    if (intval($digito_control) === $unidades_operado ) {
                        return true;
                    }
                }
                
                else {
                    if (strcasecmp ($digito_control, $letra_resultado[$unidades_operado]) === 0  || intval($digito_control) === $unidades_operado) {
                        return true;
                    }
                }
               
            }
        }
    }
    
    static function obtenerParesImparesCadena($cadena) {
        $pares = array();
        $impares = array();
        
        for($i = 0; $i < strlen($cadena); $i++) {
            
            $letra = mb_substr($cadena, $i, 1);
            
            if( ($i + 1) % 2 === 0 ) { // comprobamos si la posicion es par, le sumamos 1 para indicar que la primera posicion contara como el número 1
                $pares[] = $letra;
            }
            
            else {
                $impares[] = $letra;
            }
            
        }
        return array('pares' => $pares, 'impares' => $impares);
    }
    
    static function operacionImparesCIF($lista) {
       $total = 0;
       
       foreach ($lista as $i => $value) {
           
           if(is_numeric($value) ) {
               $numero = $value * 2;
               // array map aplica la funcion que le pasamos como primer parametro, a cada elemento del array, en este caso, hago que el resultado sea numerico.
               $numeroArray = array_map('intval', str_split($numero));
               $total += array_sum($numeroArray);
           }
       }
      
       return $total;
    }
    
    
    
}
