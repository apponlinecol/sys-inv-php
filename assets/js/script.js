var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) });
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

$(document).on('change', '.reg, .req_user, .req_supplier, .req_reference', function () {
    if( $(this).val() !== '' ){
        if( $(this).parent().children(':nth-child(3)').hasClass('update_alert') === true ){
            $(this).parent().children(':nth-child(3)').remove();
            $(this).siblings('span').removeClass('input_empty');
            $(this).removeClass('input_empty');
        }else if( $(this).parent().children(':nth-child(2)').hasClass('update_alert') === true ){
            $(this).parent().children(':nth-child(2)').remove();
            $(this).removeClass('input_empty');
        }
    }
});
$(document).on('click', '.pass', function () {
    if( $(this).hasClass('fa-eye') ){ $(this).removeClass('fa-eye').addClass('fa-eye-slash'); $('#password').attr('type','text'); }
    else{ $(this).removeClass('fa-eye-slash').addClass('fa-eye'); $('#password').attr('type','password'); }
});


function requiredSelect2( field ) {
    var response = 'ok';
    $('.'+field).each(function () {
        switch ( $(this).val() ) {
            case '':
                if( $(this).parent().children(':last-child').hasClass('fa-times-circle') !== true ){
                    if( $(this).siblings('span').hasClass('select2-container') ){
                        $(this).siblings('span').addClass('input_empty').after('<i class="fas fa-question-circle update_alert" data-bs-toggle="popover" data-bs-placement="right" title="¡Campo obligatorio!"></i>'); $('[data-bs-toggle="popover"]').popover({trigger: 'hover'});
                    }else{
                        $(this).addClass('input_empty').after('<i class="fas fa-question-circle update_alert" data-bs-toggle="popover" data-bs-placement="right" title="¡Campo obligatorio!"></i>'); $('[data-bs-toggle="popover"]').popover({trigger: 'hover'});
                    }
                }
                response = 'error'; break;
        }
    });
    return response;
}
function validateMail( $email ) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}
function validatePass( $pass ) {
    var passReg = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+])[0-9a-zA-Z!@#$%^&*()_+]{8,}$/;
    return passReg.test( $pass );
}
function validateCellPhone( $phone ) {
    var response = true;
    if( $phone.indexOf('_') > -1 ){ response = false; }
    return response;
}
