/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function(){
    
    var currentPage;
    var currentFamily;
    var word_search;
    var next_page_product;
    
    
    $(document).ready(function() {
        
        
        getFamilies();
        getProducts();
        //asignamos el evento de la paginacion al div  de las paginas, porque las paginas seran borradas en cada petición.
        $('.family_pagination').click(familyPaginationClick);
                
        $('#more_products').click(masProductos);
        $('#all_button').click(function(){
            if (currentFamily != 0 || (word_search != null && word_search !== '') ) {
                $('.products_items').html('');                        
                getProducts();
                $('#product_filter').html("Todos los productos");               
                asignarClaseSelectedFamilia();
            }
        });
        
        $('.buscador_productos input').keyup(eventoEnterBuscadorProductos);        
        $('.buscador_productos span').click(eventoClickBuscadorProductos);
    });
    
    
    
    
    // este evento se encarga de hacer una peticion al servidor cuando hacemos clicks en los enlaces de la paginaciond e familias.
    function familyPaginationClick(e) {
        e.preventDefault();
            // El elemento puede ser un <i>(por los iconos) o un enlace, hay que comprobar que etiqueta es para acceder a la propia etiqueta o al padre.
            var elemento = e.target.tagName.toLowerCase() === 'i' ? e.target.parentElement : e.target;                                
            var newPage = $(elemento).data('page-number');  // lo casteo a jquery para poder usar la funcion data           
            // tenemos que asegurarnos de que la pagina no sea igual para no hace rpeticiones al servidor innecesarias.
            if (newPage != currentPage) {
                getFamilies(newPage);
            }          
    }
             
    // este metodo devolvera las familias y la paginacion consultando al servidor.
    function getFamilies(page = 1) {       
        $.ajax(             
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    page: page,
                    ruta: 'ajax',
                    accion: 'getfamilies'
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
                currentPage = page; // actualizamos la pagina actual si la operación ha sido exitosa                
                getFamiliesDone(json.familias, json.paginas, json.total, json.rol === 'Administrador');
            }
          )
    }
        
    // operacion si la peticion al servidor sobre familias, fue un exito.
    function getFamiliesDone(families, htmlPaginas, total,  isAdmin) {
                                       
        //foreach de Jquery
        var htmlFamilias = ''; // fabricaremos el html de todas las familias.
        $.each(families, function(i, family) {
                        
            htmlFamilias += "<div data-id ='" + family['id'] + "' class = 'family'>";
            htmlFamilias += "<div class = 'family_imagen'>"; 
                                   
            htmlFamilias += "<img src='./images/family/" + family['id'] + "' />";
            
            if (isAdmin) {
                htmlFamilias += "<div class='family_icons'>";
            
                //editar
               htmlFamilias += "<a href = './?ruta=producto&accion=editfamily&id=" + family['id'] + "'>"; 
               htmlFamilias += "<i class='fa fa-pencil-alt'></i>"; 
               htmlFamilias += "</a>";

               //borrar
               htmlFamilias += "<a class='delete_family' data-id='"+family['id']+"' data-family = '" + family['family'] + "' href = './?ruta=producto&accion=removefamily&id=" + family['id'] + "'>"; 
               htmlFamilias += "<i class='fa  fa-trash-alt'></i>"; 
               htmlFamilias += "</a>"

               htmlFamilias += "</div>";
            }
            
            
            htmlFamilias += "</div>";            
            htmlFamilias += "<h3>" + family['family'] + "</h3>";            
            htmlFamilias += "</div>";          
        });
        
        $('.families_items').html(htmlFamilias);
        
        if(total != $('.families_items').children().length) {
            $('.family_pagination').html(htmlPaginas); 
        }
        
        
        
        // asignamos confirmación en borrado.
        $('.delete_family').each(function() {
            $(this).click(function(e) {
                e.preventDefault();
                var actual=e.currentTarget;
                var id=actual.getAttribute('data-id');
                var ruta='./?ruta=producto&accion=removefamily&id='+id;
                var familia=actual.getAttribute('data-family');
                var texto="Se va a borrar la familia: "+familia;
                
                
                mostrarTextoF(id,texto,e,ruta);
            });
            
            
            
        })
        
        //evento para mostrar los productos de la familia concreta
        $('.family img, .family h3').each(function () {
            $(this).click(function(){
                var capaFamilia = $(this).closest('.family');
                var idFamilia = capaFamilia.data('id');
                
                if((typeof idFamilia) !== 'undefined' && idFamilia != currentFamily) {
                    $('.products_items').html('');
                    getProducts(idFamilia);
                    asignarClaseSelectedFamilia(capaFamilia);
                    
                    //asignamos mensaje                                                 
                    var nombreFamilia = capaFamilia.find('h3').text();
                    $('#product_filter').html("Productos de la familia: <span>" + nombreFamilia + "</span>");                                                            
                }
            });
        });
    }
    
    
    // este metodo devolvera las familias y la paginacion consultando al servidor.
    function getProducts(familia = 0, pagina = 1) { // si familia es 0 coge todos los productos.     
        $.ajax(                
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    familia: familia,
                    page: pagina,
                    ruta: 'ajax',
                    accion: 'getproducts'
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
               //ponemos la siguiente pagina, porq es la q tendremos que buscar si volvemos a hacer click en el boton.               
               console.log(json);
               next_page_product = pagina + 1;
               currentFamily = familia;
               word_search = null;
               getProductsDone(json.productos, json.total, json.rol === 'Administrador');
            }
          )
    }
    
    // este metodo devolvera las familias y la paginacion consultando al servidor.
    function getProductsSearch(text, pagina = 1) { // si familia es 0 coge todos los productos.     
        $.ajax(                
            {
                url: 'index.php',
                type: 'get',
                data: { 
                    texto: text,
                    page: pagina,
                    ruta: 'ajax',
                    accion: 'getproductssearch'
                },
                dataType: 'json' 
            }
        ).done(             
            function(json){
               //ponemos la siguiente pagina, porq es la q tendremos que buscar si volvemos a hacer click en el boton.             
               next_page_product = pagina + 1;
               currentFamily = 0;
               word_search = text;
               getProductsDone(json.productos, json.total, json.rol === 'Administrador');
            }
          )
    }
    
    // si la operacion de obtener productos es buena, activaremos esta funcion
    function getProductsDone(products, totalProductCount, isAdmin) {        
        var listaProductos = $('.products_items');
        $.each(products, function(i, product) {
            
            var capaProducto = $('<div>' , { 'class' : 'product',  'style' : 'display: none'});
            
            var htmlCapa = "<div class = 'product_imagen'>";            
            htmlCapa += "<img src='./images/product/" + product['id'] + "' />";
            
            if (isAdmin) {
                htmlCapa += "<div class='product_icons'>";
            
                //editar
               htmlCapa += "<a href = './?ruta=producto&accion=editproduct&id=" + product['id'] + "'>"; 
               htmlCapa += "<i class='fa fa-pencil-alt'></i>"; 
               htmlCapa += "</a>";

               //borrar
               htmlCapa += "<a class='delete_product' data-id='"+product['id']+"' data-product= '" + product['product'] + "' href = './?ruta=producto&accion=removeproduct&id=" + product['id'] + "'>"; 
               htmlCapa += "<i class='fa  fa-trash-alt'></i>"; 
               htmlCapa += "</a>"

               htmlCapa += "</div>";
            }
            
            
            htmlCapa += "</div>";            
            htmlCapa += "<h3>" + product['product'] + "</h3>";
            
            htmlCapa += "<div class='fila_producto'>";
            htmlCapa += "<span class='producto_familia'>" + product['idFamily']['family'] + "</span>";
            htmlCapa += "<span class='precio'>" + precioEuropeo(product['price']) + "</span>";
            htmlCapa += "</div>";
            
            
            var descripcion = product['description'] === null ? 'No se ha añadido descripción para este producto' 
                                                              : limitarTamanoTexto(product['description']);
            
            htmlCapa += "<p>" + descripcion   + "</p>";
           
            
            capaProducto.html(htmlCapa);
            
            // confirmación borrar producto.
            capaProducto.find('.delete_product').click(function(e) {
                e.preventDefault()
                var actual=e.currentTarget;
                var id=actual.getAttribute('data-id');
                var ruta='./?ruta=producto&accion=removeproduct&id='+id;
                var producto=actual.getAttribute('data-product');
                var texto="Se va a borrar el producto: "+producto;
                
                
                mostrarTextoP(id,texto,e,ruta);
            });
            
            capaProducto.appendTo(listaProductos).fadeIn('fast');                        
        });        
        // si el número de hijos de la capa de productos es = al total de productos de la base de datos, ocultara el boton.                
        ocultarBotonMasProductos(listaProductos.children().length == totalProductCount);                
    }
    
    function masProductos() {
                
        if (word_search !== null && word_search !== '') {
            getProductsSearch(word_search, next_page_product);
        }        
        else {                        
            getProducts(currentFamily, next_page_product);
        }
    }
    
    function ocultarBotonMasProductos(ocultar) {
        var boton = $('#more_products');        
        if (ocultar) {
            if (boton.css('display')!=='none') {
                boton.fadeOut();
            }
        }
        //sino lo oculta lo muestra.
        else {
            if (boton.css('display')==='none') {
                boton.fadeIn();
            }
        }
    }
    
    // familia es un objeto jquery.
    function asignarClaseSelectedFamilia(capa_familia = null) {
        var familias = $('.family');
        familias.removeClass('seleccionada');
        if(capa_familia !== null) {
            capa_familia.addClass('seleccionada')
        }
    }
    
    
    function eventoClickBuscadorProductos() {
        var texto = jQuery.trim( $(this).closest('.buscador_productos').find('input').val() );        
        if (texto !== null && texto !== '') {
            currentFamily = 0;
            $('.products_items').html('');
            getProductsSearch(texto);
            asignarClaseSelectedFamilia();
            $('#product_filter').html("Productos por busqueda: <span>" + texto + "</span>");  
        }
    }
    
    function eventoEnterBuscadorProductos(e) {                
        if(e.keyCode == 13) {
                var texto = jQuery.trim($(this).val());
                if (texto !== null && texto !== '') {
                    currentFamily = 0;
                    $('.products_items').html('');
                    getProductsSearch(texto);
                    asignarClaseSelectedFamilia();
                    $('#product_filter').html("Productos por busqueda: <span>" + texto + "</span>");  
                }
        }
    }

    function mostrarTextoF(id,txt,evento, ruta) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog"></div>');
        var texto = textoMostrarF(txt);
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

    function textoMostrarF(texto) {
        var salida = "";
        salida += "<h2>Se va a borrar una familia:</h2>";
        salida += "<h3>Esto conlleva el que se borren<br>todos sus productos.</h3>"
        salida += "<p>" + texto + "</p>";
        salida += "<button class='aceptar-dialog'>Aceptar</button>";
        salida += "<button class='borrar-unidad'>Cancelar</button>";
        return salida;
    }
    
    function mostrarTextoP(id,txt,evento, ruta) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog"></div>');
        var texto = textoMostrarP(txt);
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

    function textoMostrarP(texto) {
        var salida = "";
        salida += "<h2>Se va a borrar un Producto:</h2>";
        salida += "<h3>Esto conlleva la eliminación permanente</h3>"
        salida += "<p>" + texto + "</p>";
        salida += "<button class='aceptar-dialog'>Aceptar</button>";
        salida += "<button class='borrar-unidad'>Cancelar</button>";
        return salida;
    }
    
    
    
}())











//AJAX EXAMPLE
/*        $.ajax(
            //paso 1 hago la llamada
            {
                url: 'index.php',//ruta relativa
                type: 'get',//post
                data: { 
                    dato1: 'valor1', 
                    dato2: 'valor2',
                    ruta: 'ajax',
                    accion: 'pruebaAjax'
                },
                dataType: 'json' //!!!importante: convierte la respuesta en un objeto JS
            }//parámetro json con todos las datos para la petición
        ).done(
            //paso 3 recibo la respuesta, 5 segundos después
            function(json){
                //var json = JSON.parse(txtjson);
                //alert(json.respuesta);
                console.log(json.lista);
            }//función que procesa el resultado
        ).fail(
            //paso 3 no recibo la respuesta, hay un error
            function(){
                console.log('error en la peticion')
            }//función que procesa el error
        ).always(
            //paso 4, se ejecuta siempre try catch finally
            function(){
                //alert('siempre');
            }//función que se ejecuta siempre
        );
   */