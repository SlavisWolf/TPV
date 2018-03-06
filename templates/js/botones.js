$(document).ready(function () {
    
    function getClientes(page = 1) {       
        $.ajax(             
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    page: page,
                    ruta: 'ajax',
                    accion: 'getallclients'
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
                console.log(json);
                //currentPage = page; // actualizamos la pagina actual si la operación ha sido exitosa                
                mostrarTexto(pintaClientes(json.clientes));
            }
        )
    }
    
    
    
    $('#asignar-cliente').on('click',function(){
        getClientes();
    });
    
    function mostrarTexto(txt) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog"></div>');
        var texto = textoMostrar(txt);
        $('body').append(dialog);
        $('.dialog').append(boxDialog);
        $('.boxDialog').append(texto);
        $('.aceptar-dialog').on('click', function () {
            var cliente = $('.boxDialog .tablaClientes .situado')[0];
            if(cliente!=undefined){
                var id=cliente.getAttribute('data-id');
                var nombre=cliente.cells[0].textContent;
                var email=cliente.cells[1].textContent;
                var tin=cliente.cells[2].textContent;
                if($('#areaCliente')!=undefined){
                    $('#areaCliente').remove();
                }
                transformarLI(id,nombre,email,tin);
            }
            $('.dialog').remove();
        });
        $('.boxDialog .tablaClientes .client-tr').on('click',function(e){
            aniadirSituado(e.currentTarget);
        });
    }

    function textoMostrar(texto) {
        var salida = "";
        salida += "<h2>Selecciona un cliente</h2>";
        salida += "<table class='tablaClientes'><thead><td>Nombre</td><td>Email</td><td>TIN</td>";
        salida+="<tbody>" + texto + "</tbody></table>";
        salida += "<button class='aceptar-dialog'>Aceptar</>";
        return salida;
    }
    
    function pintaClientes(clientes){
        var salida="";
        $.each(clientes,function(i,cliente){
            salida+='<tr class="client-tr" data-id="'+cliente.id+'"><td>'+cliente.name+' '+cliente.surname+'</td><td>'+cliente.email+'</td><td>'+cliente.tin+'</td></tr>'
        });
        return salida;
    }
    
    function aniadirSituado(elemento){
        var todos = $('.boxDialog .tablaClientes .client-tr');
        for (var i = 0; i < todos.length; i++) {
            if (todos[i].classList.contains('situado')) {
                var actual = todos[i];
                actual.classList.remove('situado');
            }
        }
        if(actual==elemento){
            elemento.classList.remove('situado');
        }else{
            elemento.classList.add('situado');
        }
    }
    
    function transformarLI(id,nombre,email,tin){
        // data-id="+cliente.getAttribute('data-id')+">
        var area="<div id='areaCliente' data-id='"+id+"'><h1>Área cliente</h1></div>";
        $('.nav-menu-izquierda').append(area);
        var nombre="<p>"+nombre+"</p>";
        $('#areaCliente').append(nombre);
        var email="<p>"+email+"</p>";
        $('#areaCliente').append(email);
        var tin="<p>"+tin+"</p>";
        $('#areaCliente').append(tin);
        var boton="<button id='delCliente' class='aceptar-dialog'>Desvincular</button>";
        $('#areaCliente').append(boton);
        $('#delCliente').on('click',function(){
            $('#areaCliente').remove();
        });
    }
    
    $('#delCliente').on('click',function(){
            $('#areaCliente').remove();
    });
    
    $('#nuevo-ticket').on('click',function(){
        $('#areaCliente').remove();
        $('.table tbody tr').remove();
    });
    
    $('#guardar-ticket').on('click',function(){
        $.ajax(             
            {
                url: 'index.php',
                type: 'get',
                data: {
                    ruta: 'tpv',
                    accion: 'saveticket'
                },
            }
        ).done(
            function(){
                $('#areaCliente').remove();
                $('.table tbody tr').remove();
                $('#total-final')[0].innerText="0";
            }
        )
    });
});