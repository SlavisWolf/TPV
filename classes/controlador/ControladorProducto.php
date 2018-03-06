<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorFamilia
 *
 * @author anton
 */
class ControladorProducto extends Controlador {
   
    
    function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        
        if(!$this->isLogged()) {
             header("Location: index.php");
             exit;
        }
        
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        /*Si sigue fallando usar lo siguiente*/
        
        $this->getSesion()->cacheLimit();
    }
    
    function index() {
        
        //$op = Request::read('op');
        $res = request::read('res');
        
        // los números de $res son posiciones de un array que contiene los mensajes de la aplicación
        if ($res !== null) {
            $this->getModel()->setDato('message', '<span class = "mesagge_server">' .' ' . Constants::$messages[$res] . '</span>');
        }
        
        if ($this->isLogged()) {
            $this->getModel()->setDato('archivo' , 'index.html'); 
            
            if ($this->isAdministrator()) { 
                /*$nueva_familia = '<a class="enlace" href = "./?ruta=producto&accion=nuevafamilia" >Nueva Familia</a>';*/
                $nueva_familia = '<a class="enlace" href = "./?ruta=producto&accion=nuevafamilia" ><i class="icon-plus"></i></a>';
                $this->getModel()->setDato('nuevo_familia' , $nueva_familia); 
                
                /*$nuevo_producto = '<a class="enlace" href = "./?ruta=producto&accion=nuevoproducto" >Nuevo Producto</a>'; */
                $nuevo_producto = '<a class="enlace" href = "./?ruta=producto&accion=nuevoproducto" ><i class="icon-plus"></i></a>'; 
                $this->getModel()->setDato('nuevo_producto' , $nuevo_producto);
            }
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
        
    }
    
    
    // muestra la vista de crear familia
    function nuevafamilia() {
        
        if ($this->isAdministrator()) {
            
            $this->getModel()->setDato('archivo' , 'nueva_familia.html');
            
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
     // recoge los datos del formulario de nueva familia, los comprueba y si son correctos los inserta en la BD
    function donuevafamilia() {                 
        $res = 0;
        // solo el admin puede insertar
        if ($this->isAdministrator()) {
            $family = new Family();
            $family->read();
            
            //comprobamos que los datos de la familia sean válidos
            if($family->isValid() && isset($_FILES['family_image'])) {
                                
                //comprobamos que el usuario haya subido una foto png o jpeg.                
                if (UtilImages::checkFileIsValidImage($_FILES['family_image']['tmp_name'])) {
                        //insertamos la familia
                        $res = $this->getModel()->addFamily($family);

                        if ($res >= 1 ) {
                            
                            $fileUploader = new FileUpload('family_image', $family->getId(), Constants::FAMILY_IMAGE_DIRECTORY  , 2 * 2048 * 2048, FileUpload::SOBREESCRIBIR);
                            //si se sube correct<mente, procedemos a recortarla y ajustarla.
                            if ($fileUploader->upload()) {
                                $image_path = Constants::FAMILY_IMAGE_DIRECTORY . $family->getId();
                                UtilImages::resize_crop_image_file(Constants::WIDTH_FAMILY, Constants::HEIGHT_FAMILY, $image_path, $image_path);
                            }

                            // cambiamos res para el mensajito.
                            $res = 55;
                    }                                                           
                }
                
                else {
                    $res = 60;
                }                                  
            }
            else {
                $res = 10;
            }            
            $this->redireccionar( $res);
        }        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    function editfamily() {
        
        if ($this->isAdministrator()) {
            $id = Request::read('id');            
            $family = $this->getModel()->getFamily($id);
            
            if ($family instanceof Family) {
                 $this->getModel()->setDato('archivo' , 'editar_familia.html');
                 $this->getModel()->setDatos($family->toArray());
            }
            
            else {
                $this->redireccionar(0);
            }                        
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    
    function doeditfamily() {
        
       $res = 0;
        // solo el admin puede insertar
        if ($this->isAdministrator()) {
            $family = new Family();
            $family->read();
            
            $familyBD = $this->getModel()->getFamily($family->getId());
            
            //comprobamos que los datos de la familia sean válidos
            if($family->isValid() && $familyBD instanceof Family) {
                                
                //esto es opcional
                if (isset($_FILES['family_image']) && UtilImages::checkFileIsValidImage($_FILES['family_image']['tmp_name']) ) {
                    $fileUploader = new FileUpload('family_image', $family->getId(), Constants::FAMILY_IMAGE_DIRECTORY  , 2 * 2048 * 2048, FileUpload::SOBREESCRIBIR);
                    //si se sube correctamente, procedemos a recortarla y ajustarla.
                    if ($fileUploader->upload()) {
                        $image_path = Constants::FAMILY_IMAGE_DIRECTORY . $family->getId();
                        UtilImages::resize_crop_image_file(Constants::WIDTH_FAMILY, Constants::HEIGHT_FAMILY, $image_path, $image_path);
                    }
                }                                                                
                //editamos la familia
                $res = $this->getModel()->editFamily($family);
                if ($res >= 1 ) {
                    $res = 75;
                }                                                                                                                                            
            }
            else {
                $res = 10;
            }            
            $this->redireccionar( $res);
        }        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    function removefamily() {
        if ($this->isAdministrator()) {
            $idFamilia =  Request::read('id'); 
            $family = $this->getModel()->getFamily($idFamilia); 
            
            if ($family instanceof Family) {
                $numProducts = $this->getModel()->getCountAllProductsByFamily($idFamilia);
                if ($numProducts > 0) {
                    // actualizado
                    $this->getModel()->removeAllProductsOfFamily($idFamilia);
                    /*$this->getModel()->setDatos($family->toArray());
                    $this->getModel()->setDato('num_products', $numProducts);
                    $this->getModel()->setDato('archivo' , 'borrar_familia.html');*/
                }
                $this->getModel()->removeFamily($idFamilia);
                $this->redireccionar(80);
                
            }
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    // aqui borraremos tanto la familia como sus productos.
    function removefamilyandproducts() {
        if ($this->isAdministrator()) {
            $idFamilia =  Request::read('id');
            $res = $this->getModel()->removeAllProductsOfFamily($idFamilia);
            
            if ($res > 0) {
                $res = $this->getModel()->removeFamily($idFamilia);
                if ($res > 0) {
                    $res = 80;
                }
            }
            
            $this->redireccionar($res);
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }        
    
    function nuevoproducto() {
        if ($this->isAdministrator()) {
            
             if($this->getModel()->getCountAllFamily() == 0)  {
                $this->redireccionar(65);
             }
             else  {
                $this->getModel()->setDato('all_families', $this->optionSelectFamiliasHtml());
                $this->getModel()->setDato('archivo' , 'nuevo_producto.html');             
            }
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
                
    function donuevoproducto() {
                
        if ( $this->isAdministrator() ) {
            $product = new Product();
            $product->read();
            $res = 0;
            if($product->isValid() && isset($_FILES['product_image'])) { 
                
                 if (UtilImages::checkFileIsValidImage($_FILES['product_image']['tmp_name'])) {
                     $res = $this->getModel()->addProduct($product);
                     
                     if ($res >= 1 ) {                            
                            $fileUploader = new FileUpload('product_image', $product->getId(), Constants::PRODUCT_IMAGE_DIRECTORY  , 2 * 2048 * 2048, FileUpload::SOBREESCRIBIR);
                            //si se sube correctmente, procedemos a recortarla y ajustarla.
                            if ($fileUploader->upload()) {
                                $image_path = Constants::PRODUCT_IMAGE_DIRECTORY . $product->getId();
                                UtilImages::resize_crop_image_file(Constants::WIDTH_PRODUCT, Constants::HEIGHT_PRODUCT, $image_path, $image_path);
                            }

                            // cambiamos res para el mensajito.
                            $res = 70;
                    }                     
                 }
                 
                 else {
                    $res = 60;
                }  
            }

            else {
                $res = 10;
            }

            $this->redireccionar( $res);
        }
        
         else {            
             header("Location: index.php"); 
             exit;
        }                
    }
    
    function editproduct() {
        if ($this->isAdministrator()) {
            $id = Request::read('id');            
            $product = $this->getModel()->getProduct($id);
            
            if ($product instanceof Product) {
                 $this->getModel()->setDato('archivo' , 'editar_producto.html');
                 $this->getModel()->setDato('all_families', $this->optionSelectFamiliasHtml($product->getIdFamily() ) );
                 $this->getModel()->setDatos($product->toArray());
            }
            
            else {
                $this->redireccionar(0);
            }                        
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    function doeditproduct() {
        
        if ( $this->isAdministrator() ) {
            $product = new Product();
            $product->read();
            $productBD = $this->getModel()->getProduct($product->getId());
            $res = 0;
            
            if($product->isValid() && $productBD instanceof Product) { 
                                
                if (isset($_FILES['product_image']) && UtilImages::checkFileIsValidImage($_FILES['product_image']['tmp_name']) ) {
                    $fileUploader = new FileUpload('product_image', $product->getId(), Constants::PRODUCT_IMAGE_DIRECTORY  , 2 * 2048 * 2048, FileUpload::SOBREESCRIBIR);
                       //si se sube correctamente, procedemos a recortarla y ajustarla.
                       if ($fileUploader->upload()) {
                           $image_path = Constants::PRODUCT_IMAGE_DIRECTORY . $product->getId();
                           UtilImages::resize_crop_image_file(Constants::WIDTH_PRODUCT, Constants::HEIGHT_PRODUCT, $image_path, $image_path);
                       }
                }
                
                $res = $this->getModel()->editProduct($product);
                if ($res >= 1 ) {                                                   
                       // cambiamos res para el mensajito.
                       $res = 85;
               }                                                       
            }

            else {
                $res = 10;
            }
            
            $this->redireccionar( $res);
        }
        
         else {            
             header("Location: index.php"); 
             exit;
        }                
    }
    
    function removeproduct() {
        
        if ($this->isAdministrator()) {
            $id = Request::read('id');            
            $res  = $this->getModel()->removeProduct($id);
            
            //exit;
            if ($res >= 1) {
                $res = 90;
            }
            
            $this->redireccionar($res);
        }
        
        else {            
             header("Location: index.php"); 
             exit;
        }
    }
    
    
    //este método devolvera todas las familias como opciones de un select
    private function optionSelectFamiliasHtml($idFamiliaProducto = -1) {
        $familias = $this->getModel()->getAllFamilies();
        $html = '';
        foreach ($familias as $familia) {
            $nombre = $familia->getFamily();
            $id = $familia->getId();
            $selected = $id == $idFamiliaProducto ? 'selected' : '';
            
            //añadimos la familia al select
            $html .= "<option $selected value = '$id' >$nombre</option>";
        }
        
        return $html;
    }
            
    function redireccionar( $res) {
         header("Location: ./?ruta=producto&res=$res"); 
         exit;
    }
    
}
