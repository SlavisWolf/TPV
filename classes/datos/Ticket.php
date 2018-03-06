<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ticket
 *
 * @author anton
 */
class Ticket extends Object {
    
    protected $id,$date,$idMember,$idClient;
    
    function __construct($id = null, $date = null, $idMember = null, $idClient = null) {
        $this->id = $id;
        $this->date = $date;
        $this->idMember = $idMember;
        $this->idClient = $idClient;
    }
    
    function getId() {
        return $this->id;
    }

    function getDate() {
        return $this->date;
    }

    function getIdMember() {
        return $this->idMember;
    }

    function getIdClient() {
        return $this->idClient;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setIdMember($idMember) {
        $this->idMember = $idMember;
    }

    function setIdClient($idClient) {
        $this->idClient = $idClient;
    }
    
    
    /*METODO SOBREESCRITO DEL PADRE AQUI HACEMOS QUE EL OBJETO FECHA SE CONVIERTA EN UN DATETIME*/
    public function set(array $array ,$initialPost = 0) {               
        parent::set($array, $initialPost);
        $this->date = new DateTime($this->date);
    }
    
    public function isValid() {
        
    }

}
