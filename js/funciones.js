/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */





function precioEuropeo(precio) {
    precio+= " €";
    return precio.replace('.' , ',');
}



function limitarTamanoTexto(texto, longitud = 90) {
    
    if (texto === null)
        return null;
    
    if (texto.length > longitud)  {
        var proximoEspacioIndex =  texto.indexOf(' ', longitud);        
        if (proximoEspacioIndex !== -1) {
            return texto.substr(0, proximoEspacioIndex) + "...";
        }                     
    }    
    return texto; 
}

function verificarDNI(cadena) {
    var letra = new Array("T", "R", "W", "A", "G", "M", "Y", "F", "P", "D", "X", "B", "N", "J", "Z", "S", "Q", "V", "H", "L", "C", "K", "E");
    
    
    var numero =  cadena.substr(0, cadena.length - 1);
    if (!isNaN(numero) ) { // si es un numero
        if(cadena.charAt(cadena.length - 1).toUpperCase() === letra[numero % 23]) {
            return true;
        }
    }
    
    return false;
}

function verificarNIE(cadena) {

    var numeroInicial;
    
    switch(cadena.charAt(0).toUpperCase() ) {
         case 'X':
            numeroInicial = 0;
            break; 
            
        case 'Y':
            numeroInicial = 1;
            break; 
            
        case 'Z':
            numeroInicial = 2;
            break; 
            
        default:
            return false;
    }

    return verificarDNI(numeroInicial + cadena.substr(1));
}

function verificarCIF(cadena) {
    // las 3 ultimas letras son para el formato antiguo
    var tipoOrganizacion = new Array("A", "B", "C", "D", "E", "F", "G", "H", "N", "P", "Q", "S", "K" , "L", "M", "J", "R", "W", "U" , "V");
    var solo_letras = new Array ( "K", "P", "Q" , "S"); // si la primera letra esta aquí, el ultimo digito sera una letra
    var solo_numero = new Array("A", "B", "E" , "H"); // si la primera letra esta aquí, el ultimo digito sera un número
    
    // tras hacer los calculos, esta sera una de las posibles letras para el último dígito.
    var letra_resultado = new Array("J", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J");
    
    
        // comprobamos que la letra inicial sea válida
    var organizacion = cadena.charAt(0).toUpperCase();
    
    if (tipoOrganizacion.includes(organizacion) ) {
        
        var numerosIntermedios = cadena.substr(1, cadena.length - 2);
        
        // comprobamos que efectivamente sean numeros
        if(!isNaN(numerosIntermedios)) {
            var pares_impares = obtenerParesImpares(numerosIntermedios);
            // se suman los digitos pares
            var operacion_pares = sumarNumerosArray(pares_impares[0]);
            
            //Para los dígitos de la posiciones impares, se tiene que multiplicar por 2 y entonces sumar los dígitos del resultado, y luego sumar todos los resultados.
            var operacion_impares = operacionImparesCIF(pares_impares[1]);
            
            var sumaTotal = operacion_pares + operacion_impares;
            var sumaTotalCadena = sumaTotal.toString();
            
            var unidades_suma = sumaTotalCadena.charAt(sumaTotalCadena.length - 1);
            
            //falla aqui cuando el resultado de unidades_suma da 0;
            var unidades_operado = 10 - unidades_suma;
            
            
            if (unidades_operado === 10) {
                unidades_operado = 0;
            }
            
            var digito_control = cadena.charAt(cadena.length - 1).toUpperCase();
            
            if(solo_letras.includes(organizacion) ) {
                if (digito_control == letra_resultado[unidades_operado]) {
                    return true;
                }
            }
            
            else if (solo_numero.includes(organizacion)) {
                
                if (digito_control == unidades_operado) {
                    return true;
                }
            }
            
            else {
                if (digito_control == unidades_operado || digito_control == letra_resultado[unidades_operado]) {
                    return true;
                }
            }
            
        }
    }

    return false;
}

function obtenerParesImpares(cadena) {
    var pares = new Array();
    var impares = new Array();
    
    for(var i = 0; i < cadena.length; i++) {
        var letra = cadena.charAt(i);
        
        if( (i + 1) % 2 === 0 ) { // comprobamos si la posicion es par, le sumamos 1 para indicar que la primera posicion contara como el número 1
            pares.push(letra);
        }
        
        else {
            impares.push(letra);
        }
        
    }
    
    return new Array(pares, impares);
}

function sumarNumerosArray(lista) {
    var suma = 0;
        
    for(var i = 0; i < lista.length; i++) {
        var numero = parseInt(lista[i]);
        if (!isNaN(numero)) {
            suma += numero;
        }
    }
    
    return suma;
}


// basicamente hay que multiplicar cada elemento del array por 2 y luego sumar cada digito de cada multiplicacion, despues se suman toda slas sumas.
function operacionImparesCIF(lista) {
   var total = 0;
   for(var i = 0; i < lista.length; i++) {
        var numero = parseInt(lista[i]);
        
        if (!isNaN(numero)) {
            nuevoNumero = numero * 2;
            numeroArray = nuevoNumero.toString().split('');
            total += sumarNumerosArray(numeroArray);
        }
    }
    return total;
}

// fecha es un STRING el método devolvera false si la fecha no es válida o un objeto Date si lo es.
function validarCadenaFecha(fecha) {
    
    var diasMes = new Array(31, 28, 31 , 30, 31, 30, 31, 31, 30, 31, 30, 31);
    
    var fechaArray;
    
    if(fecha.indexOf('-') !== -1) {
        fechaArray = fecha.split('-');
    }
    
    else if (fecha.indexOf('/') !== -1) {
        fechaArray = fecha.split('/');
    }
    
    // comprobamos que el array tenga 3 elementos númericos y que el objeto devuelto sea efectivamente un Array
    if(typeof fechaArray === typeof undefined || fechaArray === false || fechaArray.length !== 3)  {
       return false; 
    }
    
    var dia;
    var mes;
    var anio;
    
    // el año esta ubicado en primera posición
    if(fechaArray[0].length === 4) {
        dia = Number(fechaArray[2]);
        anio = Number(fechaArray[0]);
    }
    
    // año ubicado en la ultima posición
    else if(fechaArray[2].length === 4) {
        dia = Number(fechaArray[0]);
        anio = Number(fechaArray[2]);
    }
    
    else {
        return false;
    }
    
    // restamos 1 por el array y por el objeto Date
    mes = Number(fechaArray[1]) - 1;
    
    if(!Number.isInteger(dia) || !Number.isInteger(mes) || !Number.isInteger(anio)) {
        return false;
    }
    
    if(mes < 0 || mes > 11) {
        return false;
    }
    
    // BISIESTO
    if (mes === 1 && anio % 4 === 0 && (anio % 100 !== 0 || anio % 400 === 0)) {
        if(dia > 29 || dia <= 0) {
            return false;
        }
    }
    
    else {
        if (dia <= 0 ||  dia > diasMes[mes]) {
            return false;
        }
    }
    
    // si llegamos aquí la fecha es válida
    return new Date(anio, mes, dia);
}

/*function checkInputHasDataAttribute(input) {
    input.each(function() {
      $.each(this.attributes, function() {
        // this.attributes is not a plain object, but an array
        // of attribute nodes, which contain both the name and value
        console.log();
        if(this.specified) {
          console.log(this.name, this.value);
        }
      });
    });
}*/

    
    
