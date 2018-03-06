<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TicketDetail
 *
 * @author anton
 */
class TicketDetail extends Object {
    
    protected $id,$idTicket,$idProduct,$quantity,$price;
    
    function __construct($id = null, $idTicket = null, $idProduct = null, $quantity = null, $price = null) {
        $this->id = $id;
        $this->idTicket = $idTicket;
        $this->idProduct = $idProduct;
        $this->quantity = $quantity;
        $this->price = $price;
    }
    
    function getId() {
        return $this->id;
    }

    function getIdTicket() {
        return $this->idTicket;
    }

    function getIdProduct() {
        return $this->idProduct;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getPrice() {
        return $this->price;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIdTicket($idTicket) {
        $this->idTicket = $idTicket;
    }

    function setIdProduct($idProduct) {
        $this->idProduct = $idProduct;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setPrice($price) {
        $this->price = $price;
    }
    
     public function isValid() {
        $idTicket = $this->idTicket !== null && $this->idTicket !== ''  && Filter::isInteger($this->idTicket);
        $idProducto = $this->idProduct !== null && $this->idProduct !== ''  && Filter::isInteger($this->idProduct);
        $quantity = $this->quantity !== null && $this->quantity !== ''  && Filter::isInteger($this->quantity);
        $price = $this->price !== null && $this->price !== ''  && Filter::isFloat($this->price);
        
        return $idTicket && $idProducto && $quantity && $price;
    } 
}
