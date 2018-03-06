<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Family
 *
 * @author anton
 */
class Family extends Object {
     protected $id,$family;
     
     function __construct($id = null, $family = null) {
         $this->id = $id;
         $this->family = $family;
     }
     
     function getId() {
         return $this->id;
     }

     function getFamily() {
         return $this->family;
     }

     function setId($id) {
         $this->id = $id;
     }

     function setFamily($family) {
         $this->family = $family;
     }

    public function isValid() {
        return  $this->family !== null && strlen($this->family) <= 100;
    }

}
