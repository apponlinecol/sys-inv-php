<?php error_reporting(E_ALL); ini_set('display_errors', 1); $system_name = []; $system_name = explode(" ", $template["system_name"]); ?>

<style>
    .body_login, .login-box, .login-card-body, input.form-control:focus{ border-color:<?=$color?>; box-shadow: 0 0 7px <?=$color?>; }
    input.form-control{ border-color:<?=$color?>; }
    .login-title, .input-icon, i.thin{ color:<?=$color?>; }
    .btn-entry{ background: <?=$color?>; color: #ffffff; }
    .btn-entry:hover{ opacity: 0.5; }
    a, a:hover {
        color:<?=$color?>;
    }

</style>

<div id="body_login">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body ">
                <div class="login-title text-center m-4"><?=$system_name[0]?><b><?=$system_name[1]?></b> </div>
                <form id="form_login" method="post" autocomplete="off">
                    <?php $login = new ControllerUsers(); $login -> entry(); ?>

                    <div class="input-icon m-2">
                        <input class="form-control" name="mail" placeholder="Correo electrónico" autofocus />
                        <i class="fas thin fa-mail-bulk" data-bs-toggle="tooltip" data-bs-placement="left" title="Correo electrónico" ></i>
                    </div>
                    <div class="input-icon m-2">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Contraseña" autofocus />
                        <i class="fas thin fa-eye pass" data-bs-toggle="tooltip" data-bs-placement="left" title="Contraseña" ></i>
                    </div>
                    <div class="text-center p-1">
                        <a href="#recover_password" class="h7">¿Recuperar contraseña? </a><br>
                        <button type="submit" class="btn btn-entry btn-sm mt-3">Ingresar</button>
                    </div>

                </form>
                <hr>
                <div class="d-flex">
                    <div class="ms-auto h7">¿No tiene una cuenta registrada? | <a href="<?=$url.'registry'?>"><b>Registrarse</b></a> </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- modals-->
<div class="modal fade" id="recover_password">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header bg-gradient-gray">
                <h5 class="modal-title">Recuperar contraseña</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="login-card-body p-0">
                    <form id="form_recovery" method="post" autocomplete="off">

<!--                        --><?php //$recovery = new ControllerUsers(); $recovery -> ctrRecoverPass(); ?>

                        <div class="input-group">
                            <input class="form-control" name="mail_recovery" autofocus placeholder="Correo" />
                            <div class="input-group-append"> <div class="input-group-text"> <i class="far fa-envelope"></i> </div> </div>
                        </div>

                        <div class="input-group">
                            <input class="form-control" name="username_recovery" placeholder="Usuario" />
                            <div class="input-group-append"> <div class="input-group-text"> <i class="fas fa-user"></i> </div> </div>
                        </div>

                    </form>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" id="btn_recovery_pass">Recuperar</button>
            </div>

        </div>
    </div>
</div>