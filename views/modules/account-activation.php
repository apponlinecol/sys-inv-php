<?php
if( !isset($routes[1]) || $routes[1]=='' ){ echo '<script> setTimeout( function() { window.location = "'.$url.'"; },1) </script>'; }
else{
    $user = ControllerGeneral::ctrRecord('single','users','where mail_encrypt="'.$routes[1].'"');
    if( !empty($user) && $user['status'] == 1 ){ echo '<script> setTimeout( function() { window.location = "'.$url.'"; },1) </script>'; }
}
?>

<style>
    .login-box, .login-card-body, input.form-control:focus{ border-color:<?=$color?>; box-shadow: 0 0 7px <?=$color?>; }
    .login-title, .input-icon, i.thin{ color:<?=$color?>; }
    .btn-entry{ background: <?=$color?>; color: #ffffff; }
    input.form-control{ border-color:<?=$color?>; }
    a, a:hover { color:<?=$color?>; }
    input.form-control {
        padding: 0;
        width: 30px;
        height: 40px;
        text-align: center;
    }
    .h7 button {
         color:<?=$color?>;
         text-decoration: none;
         font-size: .98em;
     }
    .h7 button:hover {
        color:<?=$color?>;
        opacity: 0.5;
    }
</style>


<div id="body_login">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-title text-center m-4"><b> Confirma tu registro </b></div>
                <p class="text-center">Hemos enviado un código de verificación por correo electrónico a <b><?=$user['mail']?></b>. Ingréselo a continuación para confirmar su registro:</p>
                <div class="row">
                    <div class="col-1 ms-3 me-2"> <input id="n1" type="text" class="form-control act" idn="1" autofocus/> </div>
                    <div class="col-1 me-2"> <input id="n2" class="form-control act" idn="2" maxlength="1" /> </div>
                    <div class="col-1 me-2"> <input id="n3" class="form-control act" idn="3" maxlength="1" /> </div>
                    <div class="col-1 me-2"> <input id="n4" class="form-control act" idn="4" maxlength="1" /> </div>
                    <div class="col-1 me-2"> <input id="n5" class="form-control act" idn="5" maxlength="1" /> </div>
                    <div class="col-1 me-2"> <input id="n6" class="form-control act" idn="6" maxlength="1" /> </div>
                    <div class="col-1 me-2"> <input id="n7" class="form-control act" idn="7" maxlength="1" /> </div>
                    <div class="col-1"> <input id="n8" class="form-control act" idn="8" maxlength="1" /> </div>
                </div>
                <form id="formActivate" method="post" autocomplete="off" >
                    <input name="code" type="hidden" />
                    <input name="mail" type="hidden" value="<?=$routes[1]?>" />
                    <?php $activate = new ControllerUsers(); $activate -> activate(); ?>
                </form>
                <div class="text-center p-1">
                    <button type="button" class="btn btn-entry mt-4" id="btnActivate">Confirmar</button>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h7">Regresar a <a href="<?=$url?>"><b>Iniciar sesión</b></a> </div>
                    <?php if( !empty($user) ){echo'
                    <div class="h7">¿No tiene código de verificación? | <button class="btn btn-link" type="button" id="forwardingCode"><b>Renviar código</b></button> </div>
                    ';}?>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formForwarding" method="post" autocomplete="off" >
    <input name="mail_encrypt" type="hidden" value="<?=$routes[1]?>" />
    <?php $forwarding = new ControllerUsers(); $forwarding -> forwarding(); ?>
</form>