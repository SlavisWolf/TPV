<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author anton
 */
class Client extends Object {
    
    protected $id,$name,$surname,$tin,$address,$location,$postalCode,$province,$email;
        
    function __construct($id = null, $name = null, $surname = null, $tin = null, $address = null, $location = null, $postalCode = null, $province = null, $email = null) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->tin = $tin;
        $this->address = $address;
        $this->location = $location;
        $this->postalCode = $postalCode;
        $this->province = $province;
        $this->email = $email;
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getSurname() {
        return $this->surname;
    }

    function getTin() {
        return $this->tin;
    }

    function getAddress() {
        return $this->address;
    }

    function getLocation() {
        return $this->location;
    }

    function getPostalCode() {
        return $this->postalCode;
    }

    function getProvince() {
        return $this->province;
    }

    function getEmail() {
        return $this->email;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSurname($surname) {
        $this->surname = $surname;
    }

    function setTin($tin) {
        $this->tin = $tin;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setLocation($location) {
        $this->location = $location;
    }

    function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    function setProvince($province) {
        $this->province = $province;
    }

    function setEmail($email) {
        $this->email = $email;
    }
    
    public function isValid() {
        $name = $this->name !== null && strlen($this->name) <=40;
        $surname = $this->surname !== null && strlen($this->surname) <=60;
        $tin = $this->tin !== null && strlen($this->tin) <=20 
                && (Util::verificarDNI($this->tin) 
                    || Util::verificarNIE($this->tin) 
                    || Util::verificarCIF($this->tin) );
                    
        // si es correcto lo ponemos en MAYUSCULAS
        $this->tin = $tin ? strtoupper( $this->tin) : $this->tin;
        
        $address = $this->address !== null && strlen($this->address) <=100;
        
        // el null es válido, pero si no es null hay que comprobar que la longitud sea correcta.
        $location = $this->location === null ? true : strlen($this->location) <=100;
        $postalCode = $this->postalCode === null ? true : strlen($this->postalCode) === 5 && Filter::isInteger($this->postalCode) ;
        $province = $this->province === null ? true : strlen($this->province) <=30;
        $email = $this->email === null ? true : strlen($this->email) <=100 && Filter::isEmail($this->email);
        
        // tienen que todos ser true para que devuelva true
        return $name && $surname && $tin && $address && $location && $postalCode && $province && $email;
    }

}
