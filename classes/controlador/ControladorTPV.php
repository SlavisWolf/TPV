<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorTPV
 *
 * @author anton
 */
class ControladorTPV extends Controlador {
    
    
    
    function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        
        if(!$this->isLogged()) {
             header("Location: index.php");
             exit;
        }
        
        /*header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");*/
        
        /*Si sigue fallando usar lo siguiente*/
        
        $this->getSesion()->cacheLimit();
        
    }
    
    function index() {                                   
        $this->getModel()->setDato('archivo' , 'index.html'); 
        
        // aqui hay que añadir la parte para que pinte si ya hay datos en la sesion.
        
        /*CREAMOS LA ESTRUCTURA HTML DE LOS DETALLES*/
        
        $details_sesion = $this->getDetails();
        $total_price = 0;
        if($details_sesion) {
            $estructureDetail = $this->htmlStructureTicketDetail();
            $productModel =  new ModeloProducto();

            $htmlDetails = '';
            
            foreach($details_sesion as $i => $detail) {
                
                $product = $productModel->getProduct($detail->getIdProduct() );
                if ($product instanceof Product) {
                        $values = array();
                        $values['product_id'] = $product->getId();
                        $values['product_title'] = $product->getProduct();
                        $values['product_description'] = Util::limitarTamanoTexto( $product->getDescription() );
                        $values['quantity'] = $detail->getQuantity();
                        $values['detail_price'] = bcdiv($detail->getPrice(),1, 2);
                        
                        $total_detail = $detail->getPrice() * $detail->getQuantity();
                        $values['total_price'] = bcdiv($total_detail, 1, 2) ;
                        
                        $total_price +=  $total_detail;
                        
                        if($i === count($details_sesion) -1  ) {
                            $values['selected'] = 'class="situado"';
                        }
                        $htmlDetails .= Util::renderText($estructureDetail, $values);
                }
            }
            
            $this->getModel()->setDato('ticket_details',$htmlDetails);
        }
        
        $this->getModel()->setDato('total_final', bcdiv($total_price, 1, 2) );
        
        
        /*CREAMOS EN CASO DE EXISTIR LA ZONA DEL CLIENTE*/
        
        $ticket_sesion = $this->getTicket();
        
        if($ticket_sesion && $ticket_sesion->getIdClient() != 0) {
            $clientModel = new ModeloCliente();
            $client =  $clientModel->get($ticket_sesion->getIdClient());
            $structureClient = $this->htmlStructureClient();
            
            $html_client = Util::renderText($structureClient, $client->toArray() );
            
            $this->getModel()->setDato('client_session', $html_client);
            
        }
        
    }
    
    // este método se llamara desde ajax
     function updateticketsesion() {
        $json = json_decode(file_get_contents('php://input'));
        
        //Obtenemos id del clientes
        $ticket = new Ticket();
        
        $idCliente = Request::read('idClient');
       
        if($idCliente != 0) {
            $ticket->setIdClient($idCliente);
        }
        
        $ticketDetails = array();
       
        foreach($json as $ticket_detail) {
            $object = new TicketDetail();
            $object->setIdProduct($ticket_detail->idProduct);
            $object->setQuantity($ticket_detail->quantity);
            $object->setPrice($ticket_detail->price);
            
            $ticketDetails[] = $object;
        }
        
        //echo Util::varDump($ticketDetails);
        
        $this->setTicket($ticket);
        $this->setDetails($ticketDetails);
    
        /*echo Util::varDump($this->getDetails() ) . "<br>";
        echo Util::varDump($this->getTicket());*/
        
        header('Content-Type:text/plain');
        echo "Actualizado";
        exit;
    }
    
    
    function deleteticketsesion() {
        $this->removeTicket();
        header('Content-Type:text/plain');
        echo "Ticket Borrado";
        exit;
    }
    
    function saveticket() {
        
        $ticket = $this->getTicket();
       
        if ($ticket) {
            $details = $this->getDetails();
            
            if($details) {
                $ticket->setIdMember($this->getUser()->getId() );

                $idTicket = $this->getModel()->add($ticket);
                if($idTicket >=1) {
                    foreach($details as $detail) {
                        $detail->setIdTicket($idTicket);
                        if($detail->isValid()) {
                            $this->getModel()->addDetail($detail);   
                        }
                    }
                }
            }
        }
        
       $this->removeTicket();
       exit;
    }
    
    /************METODOS PARA LA SESION***********/
    private function getTicket() {
        return $this->getSesion()->get('__ticket');
    }
    
    private function setTicket(Ticket $ticket) {
        $this->getSesion()->set('__ticket' , $ticket);
    }
    
    private function getDetails() {
        return $this->getSesion()->get('__details');
    }
    
    private function setDetails(Array $details) {
        $this->getSesion()->set('__details' , $details);
    }
    
    
    private function removeTicket() {
         $this->getSesion()->delete('__ticket');
         $this->getSesion()->delete('__details');
    }
    
    
    /*ESTRUCTURA DE LOS DETALLES Y DEL CLIENTE*/
    
    private function htmlStructureTicketDetail() {
        $html = '<tr {{selected}} data-id = "{{product_id}}">
                    <td>{{product_title}}</td>
                    <td>{{product_description}}</td>
                    <td>{{quantity}}</td>
                    <td>{{detail_price}}</td>
                    <td>{{total_price}}</td>
                ';
                
        return $html;
    }
    
    private function htmlStructureClient() {
        $html = '
                    <div id = "areaCliente" data-id = "{{id}}">
                        <h1>Área cliente</h1>
                        <p>{{name}} {{surname}}</p>
                        <p>{{email}}</p>
                        <p>{{tin}}</p>
                        <button id="delCliente" class="aceptar-dialog">Desvincular</button>
                    </div>
                ';
                
        return $html;    
    }
    
            
}


    


//<tr data-id="2"><td>Pan Integral</td><td>Pan integral de barr...</td><td>1</td><td>0.67</td><td>0.67</td></tr>