$(document).ready(function(){
    
    function Producto(id,cantidad,precio){
        this.idProduct=id;
        this.quantity=cantidad;
        this.price=precio;
    }
    
    getFamilies();
    var idFamilia = -1;
    
    
    function getFamilies(page = 1) {       
        $.ajax(             
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    page: page,
                    ruta: 'ajax',
                    accion: 'getfamilies',
                    rpp: 7
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
                console.log(json);
                //currentPage = page; // actualizamos la pagina actual si la operación ha sido exitosa                
                pintaFamilias(json.familias, json.total,page);
                if(idFamilia === -1 && json.familias.length > 0) {
                    idFamilia = json.familias[0].id;
                    getProducts();
                }
            }
        )
    }
    
    function getProducts(familia = idFamilia, pagina = 1) { // si familia es 0 coge todos los productos.
        $.ajax(                
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    familia: familia,
                    page: pagina,
                    ruta: 'ajax',
                    accion: 'getproducts',
                    rpp: 13
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
               console.log(json);
               pintaProductos(json.productos, json.total,pagina);
            }
          )
    }
    
    function pintaFamilias(familias,total,pagina){
        var textoHTML='';
        var logitud=8;
        var contador=1;
        
        textoHTML+='<a class="anteriorF itemFamilias" href="#" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
        if(pagina==1){
            textoHTML+='style="background-color:grey;cursor:default"';
        }
        textoHTML+='src="./templates/img/previoF.png"></a>';
        $.each(familias, function(i, family) {
            //family['family']=> Es el nombre de la familia
            textoHTML+='<div class="itemFamilias" data-id="'+family['id']+'" href="templates/img/'+family['family']+'" title="'+family['family']+'" data-gallery="">';
            textoHTML+='<img class="rejillaf" src="./images/family/' + family['id'] + '"/>';
            textoHTML+='<p>'+family['family']+'</p>';
            textoHTML+='</div>';
            contador++;
        });
        while(contador<logitud){
            textoHTML+='<a class="itemNulosf" href="#" title="No hay más familias" data-gallery="">';
            textoHTML+='<img class="rejillaf" src="./templates/img/noMoreF.png"></a>';
            contador++;
        }
        var numElementos=total-pagina*7;
        
        if(numElementos<=0){
            textoHTML+='<a href="#" class="itemFamilias" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
            textoHTML+='style="background-color:grey;cursor:default"';
        }else{
            textoHTML+='<a href="#" class="siguienteF itemFamilias" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
        }
        textoHTML+='src="./templates/img/siguienteF.png"></a>';
        $('.familiasHTML').html(textoHTML);
        $('.familiasHTML div').on('click',getProductos);
        $('.familiasHTML')[0].setAttribute('data-pagina',pagina);
        $('.siguienteF').on('click',siguienteF);
        $('.anteriorF').on('click',anteriorF);
    }
    
    
    function pintaProductos(productos,total,pagina){
        var textoHTML='';
        var longitud=14;
        var contador=1;
        
        textoHTML+='<a class="anterior" href="#" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
        if(pagina==1){
            textoHTML+='style="background-color:grey;cursor:default"';
        }
        textoHTML+='src="./templates/img/previo.png"></a>';
        
        $.each(productos, function(i, producto) {
            textoHTML+='<div class="itemProductos" data-descripcion="'+producto['description']+'" data-id="'+producto['id']+'" data-price="'+producto['price']+'" data-product="'+producto['product']+'" href="#" title="'+producto['product']+'" data-gallery="">';
            textoHTML+='<img class="rejillap" src="./images/product/' + producto['id'] + '"/>';
            textoHTML+='<p>'+producto['product']+'</p>';
            textoHTML+='</div>';
            contador++;
        });

        while(contador<longitud){
            textoHTML+='<a class="itemNulosP" href="#" title="Image from Unsplash" data-gallery="">';
            textoHTML+='<img class="rejillap" src="./templates/img/noMore.png"></a>';
            contador++;
        }
        var numElementos=total-pagina*13;
        
        if(numElementos<=0 || numElementos==14){
            textoHTML+='<a href="#" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
            textoHTML+='style="background-color:grey;cursor:default"';
        }else{
            textoHTML+='<a href="#" class="siguiente" title="Image from Unsplash" data-gallery=""><img class="rejillap"';
        }
        textoHTML+='src="./templates/img/siguiente.png"></a>';
        
        $('.productosHTML').html(textoHTML);
        $('.productosHTML')[0].setAttribute('data-pagina',pagina);
        
        
        
        
        var idFam=idFamilia;
        if(typeof productos[0] != 'undefined'){
            idFam=productos[0]['idFamily'].id
        }
        $('.productosHTML')[0].setAttribute('data-idFamily',idFam);
        $('.productosHTML').children().on('click',pintaLineas);
        
        $('.siguiente').on('click',siguiente);
        $('.anterior').on('click',anterior);
    }
    
    $('.productosHTML').on('click',function(e){
        e.preventDefault();
    });
    
    $('.familiasHTML').on('click',function(e){
        e.preventDefault();
    });
    
    function pintaLineas(){
        //Si es un producto, lo pinta
        if($(this)[0].classList.contains('itemProductos')){
            var elementos=$('.table tbody tr');
            var id=$(this).attr('data-id');
            var producto=$(this).attr('data-product');
            var precio=$(this).attr('data-price');
            var descripcion=$(this).attr('data-descripcion');
            descripcion =  limitarTamanoTexto(descripcion,20); // reducirTexto(descripcion,20);
            
            
            var salida=comprobarExistencia(elementos,producto);
            //Si no existe ya en el ticket lo crea
            if(!salida){
                var inicioTR='<tr class="situado" data-id="'+id+'">';
                var fila='<td>'+producto+'</td>';
                fila+='<td>'+descripcion+'</td>';
                
                fila+='<td>1</td>';
            
                fila+='<td>'+precio+'</td>';
                fila+='<td>'+precio+'</td>';
                var finTR='</tr>';
                
                var salida=inicioTR+fila+finTR;
                $('.table tbody').append(salida);
                /*Eliminar la selección del penúltimo*/
                var longitud=$('.table tbody').children().length;
                if(longitud!=1){
                    for(var i=0;i<longitud-1;i++){
                        var anterior=$('.table tbody').children()[i];
                        
                        anterior.removeAttribute("class");
                    }
                }
                
                
                $('.table tbody tr').on('click',aniadirSituado);
            //Si ya existe en el ticket, le suma uno a la cantidad
            }else{
                var cant = salida.cells[2].textContent++;
                cant=salida.cells[2].textContent;
                var price = salida.cells[3].textContent;
                var total=salida.cells[4];
                var multi = String(cant*price);
                if(multi.length>4){
                    multi=multi.substring(0,4);
                }
                total.innerText=multi;
            }
        }
    }
    
    function comprobarExistencia(elementos, palabra){
        for(var i =0;i<elementos.length;i++){
            var td=elementos[i].cells[0].textContent;
            if(td==palabra){
                return elementos[i];
            }
        }
        return false;
    }
    
    function aniadirSituado(){
        var todos = $('.table tbody tr');
        for (var i = 0; i < todos.length; i++) {
            if (todos[i].className == 'situado') {
                var actual = todos[i];
                actual.removeAttribute('class');
            }
        }
        $(this).addClass('situado');
    }
    
    function getProductos(){
        if($(this)[0].classList.contains('itemFamilias')){
            var id=$(this).attr('data-id');
            if(id!=idFamilia){
                getProducts(id);
            }
            
            idFamilia=id;
        }
        
    }
    
    function reducirTexto(texto,max=20){
        var salida=texto.substring(0,max);
        salida+='...';
        return salida;
    }
    
    $(document).on('click',function(){
        var idCliente=0;
        if($('#areaCliente')[0]!=undefined){
            idCliente=$('#areaCliente')[0].getAttribute('data-id');
        }
        console.log(idCliente);
        var lineas = $('table tbody tr');
        
        sendLineas(lineas,idCliente);
        actualizarTotal();
    });
    
    function sendLineas(lineas,idCliente){
        var ticket=Array();
        if(lineas.length>0){
            for(var i=0;i<lineas.length;i++){
                var elemento=lineas[i];
                var id=elemento.getAttribute('data-id');
                var hijos=elemento.cells;
                var cantidad=hijos[2].textContent;
                var precio=hijos[3].textContent;
                var producto=new Producto(id,cantidad,precio);
                ticket.push(producto);
            }
        }
        sendTicket(JSON.stringify(ticket),idCliente);
    }
    
    function sendTicket(ticket,idCliente){
        
        console.log(ticket);
        console.log("cliente en ajax: " + idCliente);
        $.ajax(             
            {
                url: 'index.php?ruta=tpv&accion=updateticketsesion&idClient='+idCliente,
                type: 'post',
                data: ticket,
                dataType: 'json',
                contentType: "application/json",
            }
        ).done(             
            function(json){
                console.log(json);
            }
        )
    }
    
    
    
    function actualizarTotal(){
        var todos = $('.table tbody tr');
        var total=0;
        for (var i = 0; i < todos.length; i++) {
            var precio=parseFloat(todos[i].cells[4].textContent);
            total+=precio;
        }
        total=String(total);
        var posicion=false;
        if(total.includes('.')){
            for(var i=0;i<total.length;i++){
                var letra=total.substring(i,i+1);
                if(letra=='.'){
                    posicion=i;
                }
            }
        }
        
        if(posicion!=false){
            total=total.substring(0,posicion+3);
        }
        
        $('#total-final')[0].innerText=total;
    }

    $('.table tbody tr').on('click',aniadirSituado);

    function siguiente(){
        var pag= parseInt($('.productosHTML')[0].getAttribute('data-pagina'));
        
        var family=$('.productosHTML')[0].getAttribute('data-idFamily');
        if(family==undefined){
            family=idFamilia;
        }
        family=parseInt(family);
        getProducts(family,pag+1);
    }
    
    function anterior(){
        var pag= parseInt($('.productosHTML')[0].getAttribute('data-pagina'));
        var idFamily=$('.productosHTML')[0].getAttribute('data-idFamily');
        if(pag!=1){
            getProducts(idFamily,pag-1);
        }else{
            $('.anterior img')[0].setAttribute('style','background-color:grey;cursor:default')
        }
    }
    
    function siguienteF(){
        var pag= parseInt($('.familiasHTML')[0].getAttribute('data-pagina'));
        var idFamily=$('.productosHTML')[0].getAttribute('data-idFamily');
        getFamilies(pag+1);
    }
    
    function anteriorF(){
        var pag= parseInt($('.familiasHTML')[0].getAttribute('data-pagina'));
        if(pag!=1){
            getFamilies(pag-1);
        }else{
            $('.anterior img')[0].setAttribute('style','background-color:grey;cursor:default')
        }
    }
});