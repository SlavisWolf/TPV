<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorMiembro
 *
 * @author daw
 */
class ControladorMiembro extends Controlador {
   
     function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        
        if(!$this->isLogged()) {
             header("Location: index.php");
             exit;
        }
    }
    
    
    function index() {
                
        $res = request::read('res');
        
        // los números de $res son posiciones de un array que contiene los mensajes de la aplicación
        if ($res !== null) {
            $this->getModel()->setDato('message', '<span class = "mesagge_server">' .' ' . Constants::$messages[$res] . '</span>');
        }
        
        if ($this->isAdministrator()) {
            $this->getModel()->setDato('archivo' , 'index.html'); // no es el index original, sino el de miembros (carpeta member)
            
            // solo el admin puede crear,borrar o editar miembros
            $nuevo = '<a class="enlace" href = "./?ruta=miembro&accion=nuevo" >Nuevo Miembro</a>';
            $this->getModel()->setDato('nuevo' , $nuevo); 
            
            
             //muestra la tabla con los clientes de la BD
            $this->tabla();
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
        
    }
    
    // muestra la vista de crear miembro
    function nuevo() {
        
        if ($this->isAdministrator()) {
            $this->getModel()->setDato('archivo' , 'nuevo.html'); 
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function donuevo() {
        
         if ($this->isAdministrator()) {
             $res = 0;
             $member = new Member();
             $member->read();
             // comprobamos que el correo tenga un formato válido
             if ($member->isValid()) {
                 $pass_rep = Request::read('rep_password');
                 
                 // las contraseñas deben contener un valor y ser iguales.
                 if ($pass_rep !== '' && $pass_rep != null && $pass_rep === $member->getPassword() ) {
                     $verificado = $this->getModel()->getFromEmail($member->getLogin() );
                     // comprobamos si existe ese correo en uso.
                     if ($verificado instanceof Member) {
                         $res = 35;
                     }
                     
                     else { // si superamos todos los ifs, finalmente insertamos.
                         $res = $this->getModel()->add($member);
                         if ($res >= 1) {
                             $res = 40;
                         }                         
                     }
                 }
                 
                 else {
                     $res = 30 ;
                 }
             }
             else {
                 $res = 10;
             }
             
             $this->redireccionar($res);
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
        
    }
    
    function editar() {
        if ($this->isAdministrator()) {                         
            $member = $this->getModel()->get( Request::read('id') );            
            // comprobamos que el miembro existe
            if ($member instanceof Member) {
                $this->getModel()->setDatos($member->toArray());
                $this->getModel()->setDato('archivo' , 'editar.html'); 
            }
            
            else {
                $this->index();
            }
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }        
    }
    
    
    function doeditar() {                                      
        if ($this->isAdministrator()) {                        
            $login = Request::read('login');
            $password = Request::read('password');
            $password_rep = Request::read('rep_password');
             
            $member = $this->getModel()->get( Request::read('id') );
            
            $member->setLogin($login);
            $res = 0;
            if ($member->isValid()) {
                
                // si se introduce nueva contraseña y es válida, cambiaremos la pass al usuario, sino solo el correo.
                if ($password !== null && $password !== '' && $password === $password_rep) {
                    $member->setPassword($password);
                    $res = $this->getModel()->edit($member);
                }
                
                else {
                    $res = $this->getModel()->editNoPassword($member);
                }
                
                if ($res >= 1) {
                    $res = 45;
                }
            }
            
            else {
                $res = 10;
            }
            
            $this->redireccionar($res);                                  
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function borrar() {
        $id = Request::read('id');        
        $res = 0;
        if ($this->isAdministrator()) {
            $res = $this->getModel()->delete($id);
            if($res >= 1) {
                $res = 50;
            }
            $this->redireccionar($res);
        }        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function perfil() {
        $this->getModel()->setDato('archivo' , 'perfil.html'); 
    }

    function editarpass() {
        
        $pass =  Request::read('password');
        $rep_pass =  Request::read('rep_password');
        $res = 0;
        if ($pass !== null && $pass !== '' && $pass === $rep_pass) {
            $member = $this->getUser();
            $member->setPassword($pass);
            $res = $this->getModel()->edit($member);
            
            if ($res >=1) {
                $res = 95;
            }
        }
        
        else {
            $res = 30;
        }
        
        header("Location: ./?res=$res"); 
        exit;
    }
    
    
    function redireccionar( $res) {
         header("Location: ./?ruta=miembro&res=$res"); 
         exit;
    }
    
    
    
    
    private function tabla() {     
            $currentMemberId = $this->getUser()->getId();
            // paginacion
            $rows = $this->getModel()->getCountAllNotCurrent($currentMemberId);
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            
            $rpp = 8; //rows per page                        
            $pagination = new Pagination($rows,$page,$rpp);
            
            $td = $this->lineaTabla(); // obtiene la estructura html de cada fila de la tabla
            
            // obtenemos los miembros de la página actual
            $members = $this->getModel()->getAllLimitNotCurrent($pagination->getOffset(), $pagination->getRpp(), $currentMemberId);
            
            $allTd = ''; // aquí guardaremos el código html de todas las filas.

            foreach($members as $member) {
                $r = Util::renderText($td, $member->toArray());
                $allTd .= $r;
            }
            
            if ($allTd === '') {
                $allTd = 'No se han encontrado usuarios';
            }
            
            $this->getModel()->setDato('tdMiembro', $allTd);
            
            if ($members !== null && count ($members)  < $rows) {
                // aquí asignamos el código html con los vinculos de las páginas.
                $this->getModel()->setDato('pagination', $this->obtenerPaginas($pagination) );
            }
    }
    
    private function lineaTabla() {
        $linea =    '<tr>  
                            <td>{{id}}</td>
                            <td>{{login}}</td>';
        
        // solo los admins pueden editar y borrar.
        
          $linea .= 
                            '
                            <td class = "celda_links">
                            
                                <a class = "button_icon" href="?ruta=miembro&accion=editar&id={{id}}">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            
                                <a class = "button_icon borrar-miembro" href="?ruta=miembro&accion=borrar&id={{id}}">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </td>';
        
        
        $linea .= '</tr>';
        return $linea;
    }
    
    private function obtenerPaginas(Pagination $pagination) {
        
        $current = $pagination->getCurrentPage();
        $previous = $pagination->previousPage();
        $next = $pagination->NextPage();
        $last = $pagination->getLastPage();
        
        $rango = $pagination->getRange();
        
        $paginasRango = '';
        foreach ($rango as $numero) :
                if ($numero == $current) {
                    $paginasRango .= "<span class = 'pagination_current'>$numero</span>";
                }
                else {
                    $paginasRango .= "<a href='?ruta=miembro&page=$numero'>$numero</a>";                
                }
        endforeach;
        return
            "<div class='paginas'>
                            <a href='?ruta=miembro&page=1'>Primera</a>
                            <a href='?ruta=miembro&page=$previous'><i class='fa fa-arrow-left'></i></a> 
                            
                            $paginasRango
                               
                            <a href='?ruta=miembro&page=$next'><i class='fa fa-arrow-right'></i></a>
                            <a href='?ruta=miembro&page=$last'>Ultima</a>
            </div>";
    }
    
}
