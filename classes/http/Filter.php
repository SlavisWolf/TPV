<?php

class Filter {

    static function isCondicionQueMeHeInventado($value) {
        return preg_match('/^[A-Za-z][A-Za-z0-9]{5,9}$/', $value);
    }

    static function isBoolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }


    // function recomendada en la pagina oficial de php, eso si cuidado que el formato debe estar correcto al 100%, cuidado con los ceros
    static function isDate($date, $format = 'Y-m-d') { // Y-n-j para que sea sin ceros
        $dateObject = DateTime::createFromFormat($format, $date);
        return $dateObject && $dateObject->format($format) == $date;
    }

    static function isEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    static function isFloat($value) {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    static function isInteger($value) {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    static function isIP($value) {
        return filter_var($value, FILTER_VALIDATE_IP);
    }

    static function isLogin($value) {
        //empieza por una letra y tiene al menos 5 caracteres, sin espacios
        //iniciales ni finales
    }

    static function isMaxLength($value, $length) {

    }

    static function isMinLength($value, $length) {

    }

    static function isNumber($value) {        
    }

    static function isTime($value) {

    }

    static function isURL($value) {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
}