<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModeloUsuario
 *
 * @author daw
 */
class ModeloMiembro extends Modelo {
    //put your code here
    
    function  getFromEmail($email) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getMemberFromEmail($email);
    }
    
    function get($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getMember($id);
    }
    
    function add(Member $member) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->addMember($member);
    }
    
    function getCountAllNotCurrent($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getCountAllMemberNotCurrent($id);
    }
    
    function getAllLimitNotCurrent($offset, $rpp, $id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->getAllLimitMemberNotCurrent($offset, $rpp, $id);
    }
    
    function edit (Member $member) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editMember($member);
    }
    
    function editNoPassword (Member $member) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->editMemberNoPassword($member);
    }
    
    function delete($id) {
        $manager = new ManageBakery($this->getDataBase());
        return $manager->removeMember($id);
    }
    
    
}
