<?php error_reporting(E_ALL); ini_set('display_errors', 1); $system_name = []; $system_name = explode(" ", $template["system_name"]); ?>

<style>
    .body_login, .login-box, .login-card-body, input.form-control:focus{ border-color:<?=$color?>; box-shadow: 0 0 7px <?=$color?>; }
    input.form-control{ border-color:<?=$color?>; }
    .login-title, .input-icon, small, i.thin{ color:<?=$color?>; }
    .btn-entry{ background: <?=$color?>; color: #ffffff; }
    .btn-entry:hover{ opacity: 0.5; }
    a, a:hover {
        color:<?=$color?>;
    }
</style>


<div id="body_login">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-title text-center m-4"> Registro </div>
                <form id="formRegistry" method="post" autocomplete="off">
                    <div class="input-icon m-2">
                        <input class="form-control reg" type="text" name="name" placeholder="Nombres" autofocus />
                        <i class="fas thin fa-user-alt" data-bs-toggle="tooltip" data-bs-placement="left" title="Nombres" ></i>
                    </div>
                    <div class="input-icon m-2">
                        <input class="form-control reg" type="text" name="surname" placeholder="Apellidos" autofocus />
                        <i class="fas thin fa-user-tie" data-bs-toggle="tooltip" data-bs-placement="left" title="Apellidos" ></i>
                    </div>
                    <div class="input-icon m-2">
                        <input class="form-control reg" name="mail" placeholder="Correo electrónico" autofocus />
                        <i class="fas thin fa-mail-bulk" data-bs-toggle="tooltip" data-bs-placement="left" title="Correo electrónico" ></i>
                    </div>
                    <div class="input-icon m-2">
                        <input class="form-control reg" type="password" id="password" name="password" placeholder="Contraseña" autofocus />
                        <i class="fas thin fa-eye pass" data-bs-toggle="tooltip" data-bs-placement="left" title="Contraseña" ></i>
                    </div>
                    <div class="text-center h7 ps-3 pe-3" ><small id="valPass">(La contraseña debe tener mínimo: 8 caracteres, una mayúscula, un número y un carácter especial.)</small></div>

                    <div class="checkBox pt-2">
                        <div class="first">
                            <div class="text-center pointer">
                                <a data-bs-toggle="modal" data-bs-target="#modalPolitics" class="hand">
                                    <i class="fas fa-pen-square grey_dark"></i>
                                    <span class="grey_dark h7">Politicas y Condiciónes</span>
                                </a>
                            </div>
                            <div class="">
                                <input type="checkbox" id="first" name="first">
                                <label for="first" id="label_second" class="h7">Al registrarse, ¿usted acepta nuestras políticas de privacidad y uso de la información?</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-1">
                        <button type="button" class="btn btn-entry btn-sm mt-3" id="btnRegistry">Registrarse</button>
                    </div>

                </form>
                <hr>
                <div class="d-flex ">
                    <div class="h7 ms-auto">¿Tiene una cuenta registrada? | <a href="<?=$url?>"><b>Ingresar</b></a> </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="pt-5 mt-5">
    <?php $registry = new ControllerUsers(); $registry -> registry(); ?>
</div>
<!-- modals-->
<div class="modal fade" id="modalPolitics">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><b>POLÍTICA DE TRATAMIENTO Y PROTECCIÓN DE DATOS PERSONALES</b></h6> <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <small><?=$template['politics']?></small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="accept">Acepto</button>
            </div>
        </div>
    </div>
</div>