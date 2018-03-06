$(document).ready(function () {
    var pantalla = $('.pantalla_calculadora');
    $('.numero').on('click', function (e) {
        var pulsado = $(this).attr('data-numero');
        var anteriorValor = pantalla.val();
        pantalla.val(anteriorValor + pulsado);
        //Si el primer valor es 0, que no escriba más o que lo quite y ponga el nuevo pulsado
        if (anteriorValor.length == 1 && anteriorValor == '0') {
            if (pulsado == '0') {
                pantalla.val(anteriorValor.substr(0, longitud));
            } else {
                pantalla.val(anteriorValor.substr(0, longitud));
                pantalla.val(pulsado);
            }
        }
        //No permitir escribir más de 9 dígitos
        if (anteriorValor.length >= 9) {
            mostrarTexto('No se pueden escribir más números');
            var longitud = anteriorValor.length;
            pantalla.val(anteriorValor.substr(0, longitud));
        }

    });

    $('.accion').on('click', function () {
        var pulsado = $(this).attr('data-accion');
        var anteriorValor = pantalla.val();
        if (anteriorValor != '') {
            if (pulsado == 'borrar') {
                var longitud = anteriorValor.length;
                pantalla.val(anteriorValor.substr(0, longitud - 1));
            }
            if (pulsado == 'borrarTodo') {
                var longitud = anteriorValor.length;
                pantalla.val('');
            }
        }
        if (pulsado == 'punto') {
            if (!anteriorValor.includes('.')) {
                if (anteriorValor.length == 0) {
                    pantalla.val('0.');
                } else {
                    pantalla.val(anteriorValor + '.');
                }
            }
            if (anteriorValor.length >= 9) {
                mostrarTexto('No se pueden escribir más números');
                var longitud = anteriorValor.length;
                pantalla.val(anteriorValor.substr(0, longitud));
            }
        }
        if (pulsado == 'cantidad') {
            if (!anteriorValor.includes('.')) {
                var posicionLinea = $('tr.situado')[0].children[2];
                posicionLinea.innerText = anteriorValor;
                posicionLinea.setAttribute("class", "cambiado");
                cambiarTotal();
                if(posicionLinea.textContent=="0"){
                    posicionLinea.parentElement.remove();
                }
                
            } else {
                mostrarTexto('No se pueden mostrar decimales');
            }
            pantalla.val('');
        }
        if (pulsado == 'precio') {
            if (anteriorValor != "") {
                var posicion=false;
                if(anteriorValor.includes('.')){
                    for(var i=0;i<anteriorValor.length;i++){
                        var letra=anteriorValor.substring(i,i+1);
                        if(letra=='.'){
                            posicion=i;
                        }
                    }
                }
                
                if(posicion!=false){
                    anteriorValor=anteriorValor.substring(0,posicion+3);
                }
                var numero = parseFloat(anteriorValor);
                var posicionLinea = $('tr.situado')[0].children[3];
                posicionLinea.innerText = anteriorValor;
                posicionLinea.setAttribute("class", "cambiado");
                cambiarTotal();
            }
            pantalla.val('');
        }
        if (pulsado == 'borrarProducto') {
            var posicionLinea = $('tr.situado')[0];
            posicionLinea.remove();
            var lineas=$('.table tbody tr');
            if(lineas[0]!=undefined){
                lineas[lineas.length-1].classList.add('situado');
            }
        }
    });

    function mostrarTexto(txt) {
        var dialog = $('<div class="dialog"></div>');
        var boxDialog = $('<div class="boxDialog"></div>');
        var texto = textoMostrar(txt);
        $('body').append(dialog);
        $('.dialog').append(boxDialog);
        $('.boxDialog').append(texto);
        $('.aceptar-dialog').on('click', function () {
            var hola = 'hola';
            $('.dialog').remove();
        });

    }

    function textoMostrar(texto) {
        var salida = "";
        salida += "<h2>Se ha producido un error</h2>";
        salida += "<p>" + texto + "</p>";
        salida += "<button class='aceptar-dialog'>Aceptar</>";
        return salida;
    }

    function cambiarTotal() {
        var total = $('tr.situado')[0].children[4];
        var cantidad = $('tr.situado')[0].children[2].textContent;
        var precio = $('tr.situado')[0].children[3].textContent;
        var multi = cantidad * precio;
        /*multi.substring(0,4);*/
        total.innerText = multi;
    }
    
    $(document).keypress(function(e){
        if($('.dialog')[0]==undefined){
            var pulsado=e.which;
            var pantalla=$('.pantalla_calculadora');
            var pulsadoString=String.fromCharCode(pulsado);
            var anteriorValor = pantalla.val();
            if (anteriorValor.length <= 1 && anteriorValor == '0') {
                if (pulsadoString == '0') {
                    pantalla.val(anteriorValor.substr(0, longitud));
                } else {
                    pantalla.val(anteriorValor.substr(0, longitud));
                    pantalla.val(pulsadoString);
                }
                
            }else if (anteriorValor.length >= 9) {
                mostrarTexto('No se pueden escribir más números');
                var longitud = anteriorValor.length;
                pantalla.val(anteriorValor.substr(0, longitud));
            }else if(pulsado==46){
                if (!anteriorValor.includes('.')) {
                    if (anteriorValor.length == 0) {
                        pantalla.val('0.');
                    } else {
                        pantalla.val(anteriorValor + '.');
                    }
                }
            }else{
                if(pulsado>=48 && pulsado <=57){
                    pantalla.val(anteriorValor+pulsadoString);
                }
            }
        }
    });
    
    $(document).keyup(function(e){
        if($('.dialog')[0]==undefined){
            var pulsado=e.which;
            var pantalla=$('.pantalla_calculadora');
            var anteriorValor = pantalla.val();
            if(pulsado==8){
                nuevovalor=anteriorValor.substring(0,anteriorValor.length-1);
                pantalla.val(nuevovalor);
            }
        }
    });

    $(document).on('click',function(){
        setTimeout(temporizador,2000);
            
    });
    
    function temporizador(){
        $('.cambiado').removeAttr('class');
    }
});
