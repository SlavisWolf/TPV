<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorCliente
 *
 * @author daw
 */
class ControladorCliente extends Controlador {
    
     function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        
        if(!$this->isLogged()) {
             header("Location: index.php");
             exit;
        }
    }
    
    
    function index() {
        
        //$op = Request::read('op');
        $res = request::read('res');
        
        // los números de $res son posiciones de un array que contiene los mensajes de la aplicación
        if ($res !== null) {
            $this->getModel()->setDato('message', '<span class = "mesagge_server">' .' ' . Constants::$messages[$res] . '</span>');
        }
        
        if ($this->isLogged()) {
            $this->getModel()->setDato('archivo' , 'index.html'); // no es el index original, sino el de clientes (carpeta client)
            
            // solo el admin puede crear,borrar o editar clientes
            if ($this->isAdministrator()) { 
                $nuevo = '<a class="enlace" href = "./?ruta=cliente&accion=nuevo" >Nuevo Cliente</a>';
                $this->getModel()->setDato('nuevo' , $nuevo); 
            }
            
            
            //muestra la tabla con los clientes de la BD
            $this->tabla();
            
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
        
    }
    
    // muestra la vista de crear cliente
    function nuevo() {
        
        if ($this->isAdministrator()) {
            $this->getModel()->setDato('archivo' , 'nuevo.html'); 
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    // recoge los datos del formulario de nuevo cliente, los comprueba y si son correctos los inserta en la BD
    function donuevo() { 
        $res = 0;
        // solo el admin puede insertar
        if ($this->isAdministrator()) {
            $client = new Client();
            $client->read();
            
            if($client->isValid()) {
                $res = $this->getModel()->add($client);    
                if ($res >= 1 ) {
                    $res = 15;
                }
            }
            else {
                $res = 10;
            }
            
            $this->redireccionar( $res);
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    
    function editar() {
        $id = Request::read('id');
        
         if ($this->isAdministrator()) {
           $client =  $this->getModel()->get($id);
           // comprobamos que el cliente exista
           if ($client instanceof Client) {
               $this->getModel()->setDato('archivo' , 'editar.html');
               $this->getModel()->setDatos($client->toArray());                            
           }
           else {
               $this->index();
           }
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    function doeditar() {
        $id = Request::read('id');
        
        $res = 0;
         if ($this->isAdministrator()) {
           $client =  $this->getModel()->get($id);
           $client->read();
           
           //comprobamos que los datos introducidos sean válidos.
           if($client->isValid()) {
               $res = $this->getModel()->edit($client);               
               if ($res >= 1) {
                   $res = 20;
               }
           }
           else {
               $$res = 10;
           }
           
           $this->redireccionar($res);
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function borrar() {
        
        $id = Request::read('id');
        
        $res = 0;
         if ($this->isAdministrator()) {
            $res = $this->getModel()->delete($id);
            if($res >= 1) {
                $res = 25;
            }

            $this->redireccionar($res);
        }        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function redireccionar( $res) {
         header("Location: ./?ruta=cliente&res=$res"); 
         exit;
    }
    
    private function lineaTabla() {
        $linea =    '<tr>  
                            <td>{{id}}</td>
                            <td>{{name}}</td>
                            <td>{{surname}}</td>
                            <td>{{tin}}</td>
                            <td>{{address}}</td>
                            <td>{{location}}</td>
                            <td>{{postalCode}}</td>
                            <td>{{province}}</td>
                            <td>{{email}}</td>';
        
        // solo los admins pueden editar y borrar.
        if($this->isAdministrator()) {
            $linea .= 
                            '<td>
                                <a class = "button_icon" href="?ruta=cliente&accion=editar&id={{id}}">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            </td>
                            
                            <td class="borrar-cliente"><a class = "button_icon" href="?ruta=cliente&accion=borrar&id={{id}}">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </td>';
        }
        
        $linea .= '</tr>';
        return $linea;
    }
    
    
    private function obtenerPaginas(Pagination $pagination) {
        
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
                    $paginasRango .= "<a href='?ruta=cliente&page=$numero'>$numero</a>";
                }
        endforeach;
        return
            "<div class='paginas'>
                            <a href='?ruta=cliente&page=1'>Primera</a>
                            <a href='?ruta=cliente&page=$previous'><i class='fa fa-arrow-left'></i></a> 
                            
                            $paginasRango
                               
                            <a href='?ruta=cliente&page=$next'><i class='fa fa-arrow-right'></i></a>
                            <a href='?ruta=cliente&page=$last'>Ultima</a>
            </div>";
    }
    
    private function tabla() {     
            // paginacion
            $rows = $this->getModel()->getCountAll();
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            
            $rpp = 8; //rows per page                        
            $pagination = new Pagination($rows,$page,$rpp);
            
            $td = $this->lineaTabla(); // obtiene la estructura html de cada fila de la tabla
            
            // obtenemos los clientes de la página actual
            $clients = $this->getModel()->getAllLimit($pagination->getOffset(), $pagination->getRpp());
            
            $allTd = ''; // aquí guardaremos el código html de todas las filas.                        
            foreach($clients as $client) {
                $r = Util::renderText($td, $client->toArray());
                $allTd .= $r;
            }
            
            if ($allTd === '') {
                $allTd = 'No se han encontrado usuarios';
            }
            
            $this->getModel()->setDato('tdCliente', $allTd);
            
            if ($clients !== null && count ($clients)  < $rows) {
                // aquí asignamos el código html con los vinculos de las páginas.
                $this->getModel()->setDato('pagination', $this->obtenerPaginas($pagination) );
            }
    }
    
}
