function AbrirAlerta(msg, alto, ancho, typemodal) {
    var modal = (typemodal != undefined) ? typemodal: false;
    $.fancybox('<div class="content_msg">'+msg+'</div>', {
        'modal': modal,
        'height' : alto,
        'width'  : ancho,
        'autoSize' : false
    });
}

function FancyCerrar(){
    $.fancybox.close(true);
}

//FUNCION QUE VALIDA QUE SOLO NUMEROS O LETRAS SEAN INGRESADAS   
    function CaracterValidar(e,tipo){
        tecla = (document.all) ? e.keyCode : e.which;
        //Tecla de retroceso para borrar, siempre la permite
        switch (tipo){
            case 0:
                if (tecla == 8 || tecla == 32 || tecla == 0) return true;
                // Patron de entrada para letras
                patron =/[A-Za-z]/; 
                break;
            case 1:
                if (tecla == 8 || tecla == 0) return true;
                // Patron de entrada para numeros
                patron = /\d/;  
                break;
            case 2:
                if (tecla == 8 || tecla == 0 || tecla == 46) return true;
                // Patron de entrada para numeros con punto
                patron = /\d/;
                break; 
            default:
            	if (tecla == 8 || tecla == 32 || tecla == 0) return true;
                // Patron de entrada para letras
                patron =/[A-Za-z]/; 
            	break;
        }
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }
