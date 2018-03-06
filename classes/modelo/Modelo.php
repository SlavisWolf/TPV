<?php

/* vamos a dejarlo con sólo los métodos comunes a todos */

class Modelo {

    private $dataBase;
    private $datos;

    function __construct() {
        $this->dataBase = new DataBase();
        $this->datos = array();
    }

    function __destruct() {
        $this->dataBase->closeConnection();
    }

    function getDataBase() {
        return $this->dataBase;
    }

    function getDato($nombre) {
        if(isset($this->datos[$nombre])){
            return $this->datos[$nombre];
        }
        return null;
    }

    function getDatos() {
        return $this->datos;
    }

    function setDato($nombre, $dato) {
        $this->datos[$nombre] = $dato;
    }
    
    // añadido, permite pasarle un array asociativo con varios datos
    function setDatos($array) {
        foreach ($array as $key => $value) {
            
            if ($value instanceof DateTime) {
                 $this->datos[$key] = $value->format('Y-m-d');
            }
            
            else {
                $this->datos[$key] = $value;
            }
        }
    }
    
}