<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModeloFamilia
 *
 * @author anton
 */
class ModeloProducto extends Modelo {
    
    
    function addFamily(Family $family) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addFamily($family);
    }
    
    function editFamily(Family $family) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editFamily($family);
    }
    
    function removeFamily($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->removeFamily($id);
    }
            
    function getFamily($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getFamily($id);
    }
    
    function getAllFamilies() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAllFamily();
    }
    
     function getAllFamiliesLimit($offset, $rpp) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitFamily($offset,$rpp);
    }
    
    //devuelve un array de arrays con los valores del objeto para que json encode pueda trabajar bien con el.
    function getAllFamiliesLimitJSON($offset, $rpp) {       
        $familias = $this->getAllFamiliesLimit($offset, $rpp);
        return Util::objectsToArrays($familias);
    }
    
    function getCountAllFamily() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllFamily();
    }
    
    
    function getAllProductsLimit($offset, $rpp) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitProduct($offset,$rpp);
    }
    
    //devuelve un array de arrays con los valores del objeto para que json encode pueda trabajar bien con el.
    function getAllProductsLimitJSON($offset, $rpp) {       
        $products = $this->getAllProductsLimit($offset, $rpp);                
        return Util::objectsToArrays($products);
    }
    
    function getAllProductsLimitByFamily($idFamilia, $offset, $rpp) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitProductByFamily($idFamilia, $offset,$rpp);
    }
    
    //devuelve un array de arrays con los valores del objeto para que json encode pueda trabajar bien con el.
    function getAllProductsLimitByFamilyJSON($idFamilia, $offset, $rpp) {       
        $products = $this->getAllProductsLimitByFamily($idFamilia, $offset, $rpp);
        return Util::objectsToArrays($products);
    }
    
    function getAllProductsLimitSearch($text, $offset, $rpp) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitProductSearch($text, $offset,$rpp);
    }
    
    //devuelve un array de arrays con los valores del objeto para que json encode pueda trabajar bien con el.
    function getAllProductsLimitSearchJSON($text, $offset, $rpp) {       
        $products = $this->getAllProductsLimitSearch($text, $offset, $rpp);
        return Util::objectsToArrays($products);
    }
    
    function getCountAllProducts() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllProduct();
    }
    
    function getCountAllProductsByFamily($idFamilia) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllProductByFamily($idFamilia);
    }
    
    function getCountAllProductsSearch($text) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllProductSearch($text);
    }
    
    function getProduct($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getProduct($id);
    }
    
    function getProductTicketDetail($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getProductTicketDetail($id);
    }
    
    
    function addProduct(Product $product) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addProduct($product);
    }
    
    function editProduct(Product $product) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editProduct($product);
    }
    
    function removeProduct($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->removeProduct($id);
    }
    
    function removeAllProductsOfFamily($idFamily) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->removeAllProductsOfFamily($idFamily);
    }
    
    function getAllTicketDetailsById($id){
        $manager = new ManageBakery($this->getDataBase());
        return Util::objectsToArrays($manager->getAllTicketDetailsById($id));
    }
    
    function getClientById($id){
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getClient($id);
    }
}
