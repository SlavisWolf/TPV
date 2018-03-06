<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModeloTicket
 *
 * @author anton
 */
class ModeloTicket extends Modelo {
    
    
    function get($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getTicket($ticket);
    }
    
    function getDetail($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getticketDetail($id);
    }
    
    //admite tanto el id como un objeto ticket como entrada
    function getAllTicketDetailOfTicket($ticket) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAllTicketDetailByTicket($ticket);
    }
    
    function add(Ticket $ticket) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addTicket($ticket);
    }
    
    
    function addDetail(TicketDetail $detail) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addticketDetail($detail);
    }
           
    function edit(Ticket $ticket) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editTicket($client);
    }
    
    
    function editDetail(TicketDetail $ticket) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editticketDetail($client);
    }
    
    function getCountAll() {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllTicket();
    }
    
    function getCountAllWhere(Array $args = array()) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllTicketWhere($args);
    }
    
    
    
    function getAllLimit($offset, $rpp, Array $args = array() ) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAlllimitTicket($offset, $rpp, $args);
    }
}
