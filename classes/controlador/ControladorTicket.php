<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorTicket
 *
 * @author Ricardo
 */
class ControladorTicket extends Controlador {
    
    
    
    function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        
        if(!$this->isLogged()) {
             header("Location: index.php");
             exit;
        }
    }
    
    function index() {
        
        $this->tabla();
        $this->getModel()->setDato('archivo' , 'index.html'); 
        
    }
    
    
    private function obtenerPaginas(Pagination $pagination, $where = array() ) {
        $whereString = "";
        
        
        foreach($where as $i => $value) {
            if ($value instanceof DateTime) {
                $fecha = $value->format('Y-n-j');
                $whereString .= "&$i=$fecha";
            } 
            
            else {
                $whereString .= "&$i=$value";
            }
        }
        
        $current = $pagination->getCurrentPage();
        $previous = $pagination->previousPage();
        $next = $pagination->NextPage();
        $last = $pagination->getLastPage();
        
        $rango = $pagination->getRange();
        
        $paginasRango = '';
        foreach ($rango as $numero) :
                if ($numero == $current) {
                    $paginasRango .= "<span class = 'pagination_current'>$numero</span>";
                }
                else {
                    $paginasRango .= "<a href='?ruta=ticket&page=$numero$whereString'>$numero</a>";
                }
        endforeach;
        return
            "<div class='paginas'>
                            <a href='?ruta=ticket&page=1$whereString'>Primera</a>
                            <a href='?ruta=ticket&page=$previous$whereString'><i class='fa fa-arrow-left'></i></a> 
                            
                            $paginasRango
                               
                            <a href='?ruta=ticket&page=$next$whereString'><i class='fa fa-arrow-right'></i></a>
                            <a href='?ruta=ticket&page=$last$whereString'>Ultima</a>
            </div>";
    }
    
    private function lineaTabla() {
        $linea =    '<tr class = "ticket_tr">  
                            <td>{{idTicket}}</td>
                            <td>{{idClient}}</td>
                            <td>{{member_mail}}</td>
                            <td>{{datetime}}</td>';
        
        $linea .= '</tr>';
        return $linea;
    }
    
    private function argumentosConsulta() {
        
        $args = array();
        
        $fecha = Request::read('date');
       
        // presuponemos que el formato en el que enviamos la fecha desde javascript siempre tendra el año al principio;
        $format = $fecha && strpos($fecha, '0', 4) !== false ? 'Y-m-d' : 'Y-n-j' ;
        
        if($fecha && Filter::isDate($fecha, $format) ) {
            $fecha = new DateTime($fecha);
            $args['date'] = $fecha;
        }
        
        $idTicket = Request::read('idTicket');
        
        if($idTicket && Filter::isInteger($idTicket)) {
            $args['id'] = $idTicket;
        }
        $idCliente = Request::read('idClient');
        
        if($idCliente) {
            $args['idClient'] = $idCliente;
        }
        
        return $args;
        
    }
    
    
    private function tabla() {     
            // paginacion
            
            $argsQuery =  $this->argumentosConsulta();
            
            $rows = $this->getModel()->getCountAllWhere($argsQuery);
            $page = Request::read('page');
            
            if($page === null) {
                $page = 1;
            }
            
            $rpp = 30; //rows per page                        
            $pagination = new Pagination($rows,$page,$rpp);
            
            $td = $this->lineaTabla(); // obtiene la estructura html de cada fila de la tabla
            
            // obtenemos los clientes de la página actual
            $tickets = $this->getModel()->getAllLimit($pagination->getOffset(), $pagination->getRpp(), $argsQuery);
            
            $allTd = ''; // aquí guardaremos el código html de todas las filas.                        
            foreach($tickets as $ticket) {
                
                $valores = array();
                $valores['idTicket']    = $ticket->getId();
                $valores['idClient']    = $ticket->getIdClient() !== null ? $ticket->getIdClient()->getId() : 'No hay cliente asociado' ;
                $valores['member_mail'] = $ticket->getIdMember()->getLogin();
                $valores['datetime']    = Util::formatearFechaEnFormatoEspañol($ticket->getDate() );
                $r = Util::renderText($td, $valores);
                $allTd .= $r;
            }
            
            if ($allTd === '') {
                $allTd = 'No Hay ningún ticket con ese criterio';
            }
            
            $this->getModel()->setDato('tdTickets', $allTd);
            
            if ($tickets !== null && count ($tickets)  < $rows) {
                // aquí asignamos el código html con los vinculos de las páginas.
                $this->getModel()->setDato('pagination', $this->obtenerPaginas($pagination, $argsQuery) );
            }
            
            $this->getModel()->setDatos($argsQuery);
    }
}