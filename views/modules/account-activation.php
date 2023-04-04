<?php if( !isset($routes[1]) || $routes[1]=='' ){ echo '<script> setTimeout( function() { window.location = "'.$url.'"; },1) </script>'; } ?>

<style>
    .body_login, .login-box, .login-card-body, input.form-control:focus{ border-color:<?=$color?>; box-shadow: 0 0 7px <?=$color?>; }
    input.form-control{ border-color:<?=$color?>; }
    .login-title, .input-icon, small, i.thin{ color:<?=$color?>; }
    .btn-entry{ background: <?=$color?>; color: #ffffff; }
    .btn-entry:hover{ opacity: 0.5; }
    a, a:hover {
        color:<?=$color?>;
    }
    input.form-control {
        padding: 0;
        width: 30px;
        height: 40px;
        text-align: center;
    }
</style>


<div id="body_login">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-title text-center m-4"> Activación de cuenta </div>
                <form id="formActivate" method="post" autocomplete="off">
                    <input name="code" type="hidden" />
                    <input name="mail" type="hidden" value="<?=$routes[1]?>" />
                    <?php $activate = new ControllerUsers(); $activate -> activate(); ?>
                </form>
                <p class="text-center">Ingrese acá el código enviado a su correo:</p>
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
                <div class="text-center p-1">
                    <button type="button" class="btn btn-entry mt-4" id="btnActivate">Activar cuenta</button>
                </div>
            </div>
        </div>
    </div>
</div>