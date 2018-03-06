'use strict';
(function(){

    
    
    
    $(document).ready(function() {
        
        $('#btn-nuevocliente').on('click', function() {
            if($('#desplegable-nuevocliente').hasClass( 'show' )){
                
                $('#desplegable-nuevocliente').removeClass( 'show' );
            }
            else{
                
            $('#desplegable-nuevocliente').addClass( 'show' );    
            }
            
        });
        
        $('#btn-nuevomiembro').on('click', function() {
            
            $('#desplegable-nuevomiembro').toggleClass( 'show' );    
            
            
        });
        
        
    });

}());