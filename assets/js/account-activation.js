$(document).on('click', '#btnActivate', function () {
    num = ''; $('.act').each(function() { num += this.value; });
    switch ( num.length ) {
        case 8: $('#formActivate input[name=\'code\']').val( num ); $('#formActivate').submit(); break;
        default: toastr.error('El código no tiene los 8 dígitos completos, ingréselos todos e intentar de nuevo.'); break;
    }
});
$(document).on('click', '#forwardingCode', function () {
    Swal.fire({
        title: "Se reenviará  un nuevo código al correo",
        html: 'Si está de acuerdo de click en <b class="">"Reenviar"</b>, en caso contrario de click en <b class="text-gray">"No, me equivoque"</b>',
        icon: 'warning', showCancelButton: true, confirmButtonText: 'Reenviar', cancelButtonText: "No, me equivoque!",
    }).then((result) => { if (result.value) { $("#formForwarding").submit(); } });
});

$(document).bind('paste', function() {
    $('#n1').css('color','white');
    $(document).on('change', '#n1', function () {
        if( $.isNumeric( $('#n1').val() ) === true ){
            $('#n1').css('color','black');
            var code = this.value.split('');
            $.each( code, function (i, item) {
                $('#n'+(i+1)).val(item).focus();
            })
        }else{
            toastr.warning('El valor que quiere pegar no es un numero');
            $('#n1').val('').css('color','black').focus();
            setTimeout(function () {
                location.reload();
            },1000);
        }
    })
});
$('.act').keyup(function () {
    n = parseInt($(this).attr('idn'))+1;
    $('#n'+n).focus();
});
