$(document).ready(function () {
    
    $('.borrar-cliente').on('click',function(e){
        e.preventDefault();
        var padre=e.currentTarget.parentNode;
        
        var id=padre.children[0].textContent;
        var ruta='./?ruta=cliente&accion=borrar&id='+id;
        var texto="Nombre: "+padre.children[1].textContent+' '+padre.children[2].textContent+'<br>';
        texto+="TIN: "+padre.children[3].textContent+'<br>';
        texto+="Correo: "+padre.children[8].textContent+'<br>';
        var queEs="cliente";
        mostrarTexto(id,texto,e,ruta,queEs);
    });
    
    $('.borrar-miembro').on('click',function(e){
        e.preventDefault();
        var padre=e.currentTarget.parentNode.parentNode;
        
        var id=padre.children[0].textContent;
        var ruta='./?ruta=miembro&accion=borrar&id='+id;
        var texto="Correo: "+padre.children[1].textContent;
        var queEs="miembro";
        mostrarTexto(id,texto,e,ruta,queEs);
    });
    
    
    function mostrarTexto(id,txt,evento,ruta,queEs) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog"></div>');
        var texto = textoMostrar(txt,queEs);
        $('body').append(dialog);
        $('.dialog').append(boxDialog);
        $('.boxDialog').append(texto);
        $('.aceptar-dialog').on('click', function () {
            $('.dialog').remove();
            window.location = ruta;
        });
        $('.borrar-unidad').on('click',function(){
            $('.dialog').remove();
            
        });

    }

    function textoMostrar(texto,queEs) {
        var salida = "";
        salida += "<h2>Se va a borrar al "+queEs+":</h2>";
        salida += "<p>" + texto + "</p>";
        salida += "<button class='aceptar-dialog'>Aceptar</button>";
        salida += "<button class='borrar-unidad'>Cancelar</button>";
        return salida;
    }
    
});