
(function () {
     
    $(document).ready(function() {
        
        /*********VALIDACION ANTES DE PULSAR SUBMIT*******/
        
        $('input[type="email"]').focusout(function() {
            validarInputCorreo($(this));
        });
        
        $('input[type="password"]').focusout(function() {
            validarInputPassword($(this));
        });
        
        $('input[type="text"]').focusout(function() {
            validarInputGenerico($(this));
        });
        
        $('input[type="number"]').focusout(function() {
            validarInputNumber($(this));
        });
        
        $('textarea').focusout(function() {
             validarInputGenerico($(this));
        });
        
        
        /***********VALIDACIÓN FORMULARIO *************/
        $('form').submit(validarFormulario);
        
    });
    
    
}())



/* Validaciones de Formularios*/
// se presupone que los input se pasan como objetos JQUERY

// el true es que el campo es válido el false, inválido.
function validarInputCorreo(input) {
    
    var val_generico = validarInputGenerico(input);
    if(val_generico === false) {
        return false;
    }
    
    var expresion = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    
    if (input.val() !== null && input.val() !== '' && !expresion.test(input.val() ) ) {
        input.next('.error').text('Introduzca una dirección de correo válida');
        input.next('.error').addClass('error_visible');
        
        return false;
    }
            
    input.next('.error').removeClass('error_visible');
    return true;
}

function validarInputGenerico(input) {
    
    var is_required = input.attr('required');
    if (typeof is_required !== typeof undefined && is_required !== false ) {
        
        if (input.val().length == 0) {
            input.next('.error').text('Este campo no puede estar vacio' );
            input.next('.error').addClass('error_visible');
            return false;
        }
    }
    
    var maxlength = input.attr('maxlength');
    if (typeof maxlength !== typeof undefined && maxlength !== false) {
        if (input.val().length > maxlength ) {
            input.next('.error').text('No se permiten más de ' + maxlength + ' carácteres' );
            input.next('.error').addClass('error_visible');
            return false;
        }
    }
    
    var minlength = input.attr('minlength');
    if (typeof minlength !== typeof undefined && minlength !== false) {
        if (input.val().length < minlength ) {
            input.next('.error').text('Se necesitan al menos ' + minlength + ' carácteres' );
            input.next('.error').addClass('error_visible');
            return false;
        }
    }
    
    /*DATA-SECTION*/
    // estos se usan para datos concretos
    
    var data = input.data('validar');
    if (typeof data !== typeof undefined && data !== false) {
        switch (data) {
            case 'postal':
                if(isNaN(input.val() ) ) {
                    input.next('.error').text('No se admiten carácteres no numericos.' );
                    input.next('.error').addClass('error_visible');
                    return false;
                }
                break;
                
            case 'tin':
                var cadena = input.val();
                if(!verificarDNI(cadena) && !verificarNIE(cadena) && !verificarCIF(cadena)) {
                    input.next('.error').text('Formato no válido, introduzca un NIF,NIE o CIF correcto.' );
                    input.next('.error').addClass('error_visible');
                    return false;
                }
                break;
        }
    }
    
    
    input.next().removeClass('error_visible');
    return true;
}

 function validarInputPassword(input) {
   var val_generico = validarInputGenerico(input);
    if(val_generico === false) {
        return false;
    }
    
    var passOriginal = input.parent().prev().children('input[type="password"]'); // con esto comprobaremos si este input tiene un hermano posterior donde repetir la pass
    var passType = passOriginal.attr('type');
    
    // buscamos si hay un campo contraseña anterior, en tal caso es porque ESTE campo es el de repetir contraseña, tenemos que validar que ambas sean iguales
    if (typeof passType !== typeof undefined && passType !== false) {
        //alert('hola');
        if(passOriginal.val() !== input.val() ) {
            input.next('.error').text('Las contraseñas no coinciden');
            input.next('.error').addClass('error_visible');
            return false;
        }
    }
    
    else {
        // en este caso comprobamos, que si cambiamos el texto de la contraseña original, y ya existe texto en repetir, salte la alarma.
        repetirPass =  input.parent().next().children('input[type="password"]'); // con esto comprobaremos si este input tiene un hermano posterior donde repetir la pass
        var passType = repetirPass.attr('type');
        
        if(typeof passType !== typeof undefined && passType !== false) {
                
             if(repetirPass.val() !== null && repetirPass.val() !== '' && repetirPass.val() !== input.val() ) {
                repetirPass.next('.error').text('Las contraseñas no coinciden');
                repetirPass.next('.error').addClass('error_visible');
                return false;
             }
             else {
                repetirPass.next('.error').removeClass('error_visible');
             }
        }
    }

    
    input.next().removeClass('error_visible');
    return true;
}

function validarInputFile(input) {
    
    var is_required = input.attr('required');
    if(typeof is_required !== typeof undefined && is_required !== false && input.val() === '') {
        
        var span = input.parent().next('span.file_error');
        console.log(span);
        span.text('Es necesario subir una foto');
        span.addClass('aparecer');
        return false;
    }
    
    span.removeClass('aparecer');
    return true;
}

function validarInputNumber(input) {
    var value = input.val();
    
    if (isNaN(value) ) {
        input.next('.error').text('');
        input.next('.error').addClass('error_visible');
    }
    
    
    // esto no valida, mas bien cambia el valor del campo para asegurarse de que tiene los decimales adecuados.
    var data = input.data('decimales');
    
    if(typeof data !== typeof undefined && data !== false) {
                var multiplicador = "1";
                
                for (var i = 0; i < data; i++) {
                    multiplicador += "0";
                }
                input.val(Math.floor(value * multiplicador) / multiplicador );
    }
    
    input.next('.error').text('aparecer');
    return true;
}


function validarFormulario(evento) {
    
    var correos = true;
    
    $(this).find('input[type="email"]').each(function() {
        if (validarInputCorreo($(this) ) === false )
            correos = false;
    });
    
    var passwords = true;
    
    $(this).find('input[type="password"]').each(function() {
        if (validarInputPassword($(this) ) === false )
            passwords = false;
    });
    
    var texts = true;
    
    $(this).find('input[type="text"]').each(function() {
        if (validarInputGenerico($(this) ) === false )
            texts = false;
    });
    
     
    var files = true;
    
    $(this).find('input[type="file"]').each(function() {
        if (validarInputFile($(this) ) === false )
            files = false;
    });
    
    if (correos === false || passwords === false || texts === false || files === false) {
        evento.preventDefault();
    }
}

