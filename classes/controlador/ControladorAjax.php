<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorAjax
 *
 * @author daw
 */
class ControladorAjax extends Controlador {
    
    
    function pruebaAjax() {
        $lista = array();
        
        $lista[] = Request::read('dato1');
        $lista[] = Request::read('dato2');
        
        $this->getModel()->setDato('lista', $lista);
    }
    
    
    function getfamilies() {
        $page = Request::read('page');
        
        //paginacion
            $rows = $this->getModel()-> getCountAllFamily();
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            
            $rpp = Request::read('rpp');
            
            if ($rpp === null) {
                $rpp = 6; //rows per page                        
            }
            
            
            $pagination = new Pagination($rows,$page,$rpp);
            
            $families = $this->getModel()->getAllFamiliesLimitJSON($pagination->getOffset(), $pagination->getRpp());           
            $htmlPages = $this->getHtmlPages($pagination);
            $this->getModel()->setDato('familias', $families);
            $this->getModel()->setDato('paginas', $htmlPages);
            $this->getModel()->setDato('total', $rows);
            $this->getModel()->setDato('numPaginas', $pagination->getLastPage());
    }
    
    function getproducts() {
        $familia = Request::read('familia');
        $page = Request::read('page');
        
        
        // si no hemos pasado ninguna familia, coge todos.
        $rows = $familia == 0 ?  $this->getModel()->getCountAllProducts() 
                              :  $this->getModel()->getCountAllProductsByFamily($familia); 
        $page = Request::read('page');
        if($page === null) {
            $page = 1;
        }

        $rpp = Request::read('rpp');
        
        if ($rpp === null) {
                $rpp = 10; //rows per page                        
        }
            
        $pagination = new Pagination($rows,$page,$rpp);

        //aqui igual, si no hemos pasado familia,  cogera todos los productos.
        $producs = $familia <= 0 ? $this->getModel()->getAllProductsLimitJSON($pagination->getOffset(), $pagination->getRpp()) 
                                 : $this->getModel()->getAllProductsLimitByFamilyJSON($familia, $pagination->getOffset(), $pagination->getRpp());      
        
        $this->getModel()->setDato('productos', $producs);
        $this->getModel()->setDato('total', $rows); 
        $this->getModel()->setDato('numPaginas', $pagination->getLastPage());
    }
    
    
     function getproductssearch() {
        $texto = Request::read('texto');
        $page = Request::read('page');
        
        if ($texto !== null && $texto !== '') {
            // si no hemos pasado ninguna familia, coge todos.
            $rows = $this->getModel()->getCountAllProductsSearch($texto);
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }

            $rpp = Request::read('rpp');
        
            if ($rpp === null) {
                    $rpp = 10; //rows per page                        
            }                  
            $pagination = new Pagination($rows,$page,$rpp);

            //aqui igual, si no hemos pasado familia,  cogera todos los productos.
            $producs = $this->getModel()->getAllProductsLimitSearchJSON($texto, $pagination->getOffset(), $pagination->getRpp());

            $this->getModel()->setDato('productos', $producs);
            $this->getModel()->setDato('total', $rows);
            $this->getModel()->setDato('numPaginas', $pagination->getLastPage());
        }
        
    }
    
    
    
    function getallclients() {
        $modeloCliente = new ModeloCliente();
        $clients  =  $modeloCliente->getAllJSON();
        $this->getModel()->setDato('clientes', $clients);
    }
    
    
                          
    private function getHtmlPages(Pagination $pagination) {
        
        $current = $pagination->getCurrentPage();
        $previous = $pagination->previousPage();
        $next = $pagination->NextPage();
        $last = $pagination->getLastPage();
        
        $rango = $pagination->getRange();
        
        $paginasRango = '';
        foreach ($rango as $numero) :
                if($numero == $current) {
                    $paginasRango .= "<span class = 'pagination_current'>$numero</span>";
                }
                else {
                    $paginasRango .= "<a data-page-number='$numero'  href='#'>$numero</a>";
                }
        endforeach;
        return
            "
                            <a data-page-number='1' href='#'>Primera</a>
                            <a data-page-number='$previous' href='#'><i class='fa fa-arrow-left'></i></a> 
                            
                            $paginasRango
                               
                            <a data-page-number='$next' href='#'><i class='fa fa-arrow-right'></i></a>
                            <a data-page-number='$last' href='#'>Ultima</a>
            ";
    }
    
    function getticket(){
        $id=Request::read('id');
        $idCliente=Request::read('idCliente');
        if(is_numeric($idCliente)){
            $cliente=$this->getCliente($idCliente);
        }else{
            $cliente='No hay cliente asociado';
        }
        
        if($id!=null && $id!=''){
            $detalles=$this->getModel()->getAllTicketDetailsById($id);
            $todasLineas=array();
            $lineas=array();
            $i=0;
            foreach($detalles as $linea){
                $lineas['idProducto']=$linea['id'];
                $lineas['producto'] = $this->getModel()->getProductTicketDetail($linea['idProduct'])->getProduct();
                $cantidad = $linea['quantity'];
                $precio = $linea['price'];
                $lineas['cantidad']=$cantidad;
                $lineas['precioTotal']=$precio*$cantidad;
                array_push($todasLineas,$lineas);
            }
            $this->getModel()->setDato('cliente',$cliente);
            $this->getModel()->setDato('lineas', $todasLineas);
        }
    }
    
    function getCliente($id){
        
        
        $cliente=$this->getModel()->getClientById($id);
        $nombre=$cliente->getName();
        
        $apellidos=$cliente->getSurname();

        $nombreCompleto=$nombre.' '.$apellidos;
        return $nombreCompleto;
        
    }
}
