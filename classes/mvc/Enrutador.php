<?php

class Enrutador {
    
    private $rutas = array();

    function __construct() {
        $this->rutas['index'] = new Ruta('ModeloMiembro', 'VistaGeneral', 'ControladorIndex');
        $this->rutas['cliente'] = new Ruta('ModeloCliente', 'VistaCliente', 'ControladorCliente');
        $this->rutas['miembro'] = new Ruta('ModeloMiembro', 'VistaMiembro', 'ControladorMiembro');
        $this->rutas['producto'] = new Ruta('ModeloProducto', 'VistaProducto', 'ControladorProducto');        
        $this->rutas['ajax'] = new Ruta('ModeloProducto', 'VistaAjax', 'ControladorAjax');        
        $this->rutas['tpv'] = new Ruta('ModeloTicket', 'VistaTPV', 'ControladorTPV');
        $this->rutas['ticket'] = new Ruta('ModeloTicket','VistaTicket','ControladorTicket');
    }

    function getRoute($ruta) {
        if (!isset($this->rutas[$ruta])) {
            return $this->rutas['index'];
        }
        return $this->rutas[$ruta];
    }
}