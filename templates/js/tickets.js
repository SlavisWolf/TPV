$(document).ready(function () {
    
    $('.ticket_tr').on('click',function(e){
        var id=e.currentTarget.children[0].textContent;
        var cliente=e.currentTarget.children[1].textContent;
        var fecha=e.currentTarget.children[3].textContent;
        var texto="";
        getTicket(id,fecha,cliente);
    });
    
    function getTicket(id,fecha,cliente) {
        cliente=parseInt(cliente);
        $.ajax(             
            {
                url: 'index.php?ruta=ajax&accion=getticket&id='+id+'&idCliente='+cliente,
                type: 'get',
                dataType: 'json'
            }
        ).done(  
            function(json){
                console.log(json);
                var texto="<table>";
                texto +="<thead><td>Id producto</td><td>Producto</td><td>Cantidad</td><td>Precio total</td></thead><tbody>"
                for(var i=0;i<json.lineas.length;i++){
                    var linea=json.lineas[i];
                    texto+="<tr><td>"+linea.idProducto+"</td>";
                    texto+="<td>"+linea.producto+"</td>";
                    texto+="<td>"+linea.cantidad+"</td>";
                    texto+="<td>"+linea.precioTotal+"</td></tr>";
                }
                texto+="</tbody></table>";
                
                cliente=json.cliente;
                
                mostrarTexto(texto,fecha,cliente);
            }
        )
    }
    
    
    function mostrarTexto(txt,fecha,cliente) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog box-ticket"></div>');
        var overflow = $('<div class="overflow"></div>');
        var texto = textoMostrar(txt,fecha,cliente);
        var salida="";
        salida += "<h4>Ticket de la fecha: "+fecha+"</h2>";
        salida += "<h4>Asociado al cliente: "+cliente+"</h2>";
        $('body').append(dialog);
        $('.dialog').append(boxDialog);
        $('.boxDialog').append(salida);
        $('.boxDialog').append(overflow);
        $('.overflow').append(texto);
        var boton="<button class='aceptar-dialog'>Aceptar</button>";
        $('.boxDialog').append(boton);
        $('.aceptar-dialog').on('click', function () {
            $('.dialog').remove();
        });
    }

    function textoMostrar(texto,fecha,cliente) {
        var salida = "";
        
        salida += texto;
        
        return salida;
    }
});