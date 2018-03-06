<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VistaGeneral
 *
 * @author daw
 */
class VistaGeneral extends Vista {
    
    function __construct(Modelo $modelo) {
        parent::__construct($modelo);
    }
    
    private function index() {

        $datos = $this->getModel()->getDatos();
        $archivo = 'templates/' . $datos['archivo'];
        return Util::renderTemplate($archivo, $datos);
    }

    function render($accion) {
        
        if(!method_exists(get_class(), $accion)) {
            $accion = 'index';
        }
        return $this->$accion();
    }
    
}
