<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author anton
 */
class Product extends Object {
    
   protected $id,$idFamily,$product,$price,$description;
    
    function __construct($id = null, $idFamily = null, $product = null, $price = null, $description = null) {
        $this->id = $id;
        $this->idFamily = $idFamily;
        $this->product = $product;
        $this->description = $description;
    }
    
    function getId() {
        return $this->id;
    }

    function getIdFamily() {
        return $this->idFamily;
    }

    function getProduct() {
        return $this->product;
    }
    
    function getPrice() {
        return $this->price;
    }

    function getDescription() {
        return $this->description;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIdFamily($idFamily) {
        $this->idFamily = $idFamily;
    }

    function setProduct($product) {
        $this->product = $product;
    }
    
    function setPrice($price) {
        $this->price = $price;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    public function isValid() {
        $product = $this->product !== null && strlen($this->product) <=100;
        $price= $this->price !== null && Filter::isFloat($this->price) && $this->price != 0;
        
        $this->price = $price ? bcdiv($this->price, 1, 2) : $this->price;
        
        $idFamilia = $this->idFamily !== null && Filter::isInteger($this->idFamily);
        
        return $product && $price && $idFamilia;
    } 
}
