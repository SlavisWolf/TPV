<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Constans
 *
 * @author anton
 */
class Constants {
    
    
    //base de datos
    const SERVER = 'localhost';
    const DATABASE = 'bakery';
    const USER = 'ubakery';
    const PASSWORD = 'cbakery';
    
     //sesión
    const NOMBRESESSION = 'sesion_tpv';
    
    
    static $messages = array (
                         -1         =>  'La operación no se pudo realizar correctamente.',
                          0         =>  'La operación no se pudo realizar correctamente.',
                          1         =>  'Operación realizada con éxito.',
                          5         =>  'Correo o contraseña erróneos.',
                          10        =>  'Datos Inválidos',
                          15        =>  'Cliente insertado correctamente',
                          20        =>  'Cliente editado correctamente',
                          25        =>  'Cliente borrado correctamente',
                          30        =>  'Contraseñas vacias o no coinciden',
                          35        =>  'Correo en uso',
                          40        =>  'Miembro insertado correctamente',
                          45        =>  'Miembro editado correctamente',
                          50        =>  'Miembro borrado correctamente',
                          55        =>  'Familia insertada correctamente',
                          60        =>  'Formato de imagen erroneo, suba jpeg o png',
                          65        =>  'No hay ninguna familia, cree familias antes de crear productos.',
                          70        =>  'Producto insertado correctamente',                          
                          75        =>  'Familia editada correctamente',
                          80        =>  'Familia borrada correctamente',
                          85        =>  'Producto editado correctamente',
                          90        =>  'Producto borrado correctamente',
                          95        =>  'Contraseña actualizada correctamente',
    ); 
                
    /*IMAGES*/
    // AQUI TENEMOS LAS RESOLUCIONES CON LAS QUE CORTAREMOS
    
    const WIDTH_FAMILY = 217;    
    const HEIGHT_FAMILY = 232;
    
    const WIDTH_PRODUCT = 268;
    const HEIGHT_PRODUCT = 188;
    
    /*DIRECTORIO IMAGENES */
    //PARTIMOS DESDE index.php
    CONST FAMILY_IMAGE_DIRECTORY = 'images/family/';
    CONST PRODUCT_IMAGE_DIRECTORY = 'images/product/';
    
    //ASPECT RATIO FOTOS
    
    //productos: 268 x  168 pixeles
    //familias  168 x 178 pixeles
}
