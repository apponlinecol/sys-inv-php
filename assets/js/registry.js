$(document).on('click', '#accept', function () {
    $("#first").prop('checked', true);
});
$(document).on('click', '#btnRegistry', function () {
    switch ( requiredSelect2( 'reg' ) ) {
        case 'ok':
            if( !validateMail( $('#formRegistry input[name=\'mail\']').val() ) ) { toastr.error('<b>No digito un correo electrónico</b>, necesita un correo electrónico valido para registrarce en el sistema.'); return; }
            if( !validatePass( $('#formRegistry input[name=\'password\']').val() ) ) { $('#valPass').css('color','red'); return; }
            if( $("#first").is(':checked')) { } else { toastr.error('Debe aceptar las políticas de privacidad de la información para registrarse, verifique las <b>“Políticas y Condiciones”</b> y acepte para poder registrarse.'); return; }
            $('#formRegistry').submit(); break;
    }
});