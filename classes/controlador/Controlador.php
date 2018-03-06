<?php

/* vamos a dejarlo con sólo los métodos comunes a todos */

class Controlador {

    private $modelo;
    private $sesion;

    function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
        $this->sesion = new Session(Constants::NOMBRESESSION);
        if($this->isLogged()) {
            $usuario = $this->getUser();
            $this->getModel()->setDato('correo', $usuario->getLogin());
            $this->getModel()->setDato('rol', $this->isAdministrator() ? 'Administrador' : 'Miembro'); 
            $this->getModel()->setDato('gravatar', UtilImages::get_gravatar($usuario->getLogin(), 110));
            
            
            // los usuarios que no sean admins se les bloqueara el acceso a determinadas areas.
            
            
            if(!$this->isAdministrator()) {
                $this->getModel()->setDato('link_admin', 'elemento_oculto');
            }
        }      
    }

    function getModel() {
        return $this->modelo;
    }
    
    function getSesion() {
        return $this->sesion;
    }

    function getUser() {
        return $this->getSesion()->getUser();
    }

    function index() {
        $this->getModel()->setDato('index', 'index');
    }
    
    function isAdministrator() { // se barara en el correo del usuario.
        
        if ($this->isLogged()) {
            $correo = $this->getUser()->getLogin();
            return $correo === 'antoniojesus.ib@gmail.com'  || $correo === 'jimibuenosdias@gmail.com'  || $correo === 'ricardogonma@hotmail.com';
        }
        
        else {
            return false;
        }
    }
   

    function isLogged() {
        return $this->getSesion()->isLogged();
    }
}