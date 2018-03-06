<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModeloCliente
 *
 * @author daw
 */
class ModeloCliente extends Modelo {
    
    function add($client) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addClient($client);
    }
    
    function getAll() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAllClient();
    }
    
    function getAllJSON() {
       $clients = $this->getAll();
       return Util::objectsToArrays($clients);
    }
    
    function getAllLimit($offset, $rpp) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitClient($offset,$rpp);
    }
    
    function getCountAll() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllClient();
    }
    
    function get($id) {
         $manager = new ManageBakery($this->getDataBase());
         return $manager->getClient($id);
    }
    
    function edit($client) {
         $manager = new ManageBakery($this->getDataBase());
         return $manager->editClient($client);
    }
    
    function delete($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->removeClient($id);
    }
    
}
