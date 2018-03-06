<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Este controlador se encargaran de la pantalla del login y el dashboard
 *
 * @author daw
 */
class ControladorIndex extends Controlador {
    
    function __construct(Modelo $modelo) {
        parent::__construct($modelo);
    }
    
    
    function index() {
        
        $op = Request::read('op');
        $res = request::read('res');
        
        // los números de $res son posiciones de un array que contiene los mensajes de la aplicación
        if ($res !== null) {
            $this->getModel()->setDato('message', Constants::$messages[$res]);
        }
        
        if ($this->isLogged()) {
            $this->getModel()->setDato('archivo' , 'index.html');
            if($this->isAdministrator()) {
                 $this->getModel()->setDato('link_member' , '<a href = "./?ruta=miembro">Miembros</a>');                 
            }
        }
        
        else {
            $this->getModel()->setDato('archivo' , 'login.html');
        }
        
    }
    
    function login(){
        
        $email = Request::read('login');
        $pass = Request::read('pass');
        
        $op = 'Login';
        $res = 0;
        // comprobamos que el email es válido
        if (Filter::isEmail($email)) {                                    
            $member = $this->getModel()->getFromEmail($email);
            
            // Comprobamos que el usuario existe y que su contraseña coincide con la guardada en la BD
            if ($member instanceof Member && Util::verificarClave($pass, $member->getPassword() ) ) {
                $this->getSesion()->login($member);
                
                $res = 1;
            }
            else {
                $res = 5;
            }
        }
                
        header("Location: index.php?op=$op&res=$res");
        exit;
        //echo Util::encriptar(Request::read('pass'));
    }
    
    function logout() {
        $this->getSesion()->logout();
        $this->index();
    }
}
