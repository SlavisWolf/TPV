<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Member
 *
 * @author anton
 */
class Member extends Object {
    
    protected $id,$login,$password;
    
    function __construct($id = null, $login = null, $password = null) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }
        
    function getId() {
        return $this->id;
    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    public function isValid() {
        return $login = $this->login !== null && strlen($this->login) <=40 && Filter::isEmail($this->login);
    }

}
