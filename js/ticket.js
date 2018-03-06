
(function () {
     
    $(document).ready(function() {
        
        $('#botonBuscar').click(buscarTickets);
           
    });
    
    function buscarTickets() {
         /*Obtenemos los valores de los input*/
            var fecha = $('#buscador_fecha').val();
            var idCliente = $('#buscador_cliente').val() ;
            var idTicket = Number($('#buscador_ticket').val() );
            
            /*Procedemos a validar los datos*/
           var error = false;
           
           console.log(fecha);
           
           if(typeof fecha !== typeof undefined && fecha !==  false  && fecha !== '' ) {
               var fecha = validarCadenaFecha(fecha);
               
                if (fecha === false) {
                    $('#buscador_fecha').next('.error').addClass('error_visible');
                    error = true;
               }
           }
           
           
           /*if (typeof idCliente !== typeof undefined && idCliente !==  false idCliente !== '') {
               if(isNaN(idCliente) || !Number.isInteger(idCliente) ) {
                    $('#buscador_cliente').next('.error').addClass('error_visible');
                    error = true;
               }
           }*/
           
           if (idTicket !== 0) {
               if(isNaN(idTicket) || !Number.isInteger(idTicket) ) {
                    $('#buscador_ticket').next('.error').addClass('error_visible');
                    error = true;
               }
           }
           
            console.log(fecha);
           
           // si el usuario comete errores abortamos
           if(error === false) {
               
               var url = "./?ruta=ticket";
               
               if(typeof fecha !== typeof undefined && fecha !==  false && fecha !== '') {
                   // sumamos 1 porque Date tiene los meses del 0 al 11
                   var mes = fecha.getMonth() + 1;
                   
                   url += "&date=" + fecha.getFullYear() + '-' + mes + '-' + fecha.getDate(); //getDay devuelve el dia de la semana
               }
               
               if(idTicket !== 0) {
                    url += "&idTicket=" + idTicket;
               }
               
               if(typeof idCliente !== typeof undefined && idCliente !==  false &&  idCliente !== 0 && idCliente !== '') {
                    url += "&idClient=" + idCliente;
               }
               
               console.log(url);
               
               window.location.replace(url);
            }
    }

}())