<?php

class ControllerUsers{

    static public function registry()
    {
        if( isset( $_POST['mail'] ) ) {
            $code =  substr(str_shuffle( '1234567890' ), 0, 8);
            $items = [
                'name' => ucwords($_POST['name']),
                'surname' => ucwords($_POST['surname']),
                'role' => 1,
                'mail' => $_POST['mail'],
                'mail_encrypt' => md5( $_POST['mail'] ),
                'code_encrypt' => md5( $code ),
                'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
            ];
            $response = ControllerGeneral::ctrInsertRow('users', header_active('users'), $items);

            is_numeric($response) ? $reply = 'ok' : $reply = $response;

            switch ( $reply ){
                case 'ok': ControllerGeneral::ctrEmailsSending(1,$response,$code); break;
                case 'repeated': repeated('El correo '.$_POST['mail'].' ya se encuentra registrado, ingrese al sistema'); break;
                default: error('Comuníquese con nosotros para ayudarle con su registro.'); break;
            }
        }
    }
    static public function activate(){
        if( isset( $_POST['code'] ) ){
            $user = ModelGeneral::mdlRecord('single','users','where mail_encrypt="'.$_POST['mail'].'"');
            if( !empty($user) ){
                $url = routes::ctrRout();
                switch ($user['status']){
                    case 0:
                        if( $user['code_encrypt'] == md5( $_POST['code'] ) ){
                            $reply = ModelGeneral::mdlUpdateField('users','status', [ 'id'=>$user['id'], 'set'=>1 ] );
                            switch ($reply){
                                case 'ok':echo'<script>
                                    swal.fire({ html: "¡Su cuenta ya se encuentra activa, ya puede ingresar al sistema y puede hacer las pruebas que desee!", icon: "success", showCancelButton: false, confirmButtonText: "ok!", allowOutsideClick: false,
                                    }).then((result) => { if (result.value) { window.location.href= "'.$url.'" ; } })
                                </script>';break;
                            }

                        }else{
                            echo '<div class="alert alert-warning text-center">El <b>código</b> ingresado tiene algún error, verifique de nuevo el <b>correo</b> e ingrese el código correcto e intente de nuevo.</div>';
                        }; break;
                    default: echo '<script> setTimeout( function() { window.location = "'.$url.'"; },1) </script>'; break;
                }
            }else{
                error('Se modifico algo de la url; vuelva al correo y de click en \"Abra AppOnlinecol\" o de click o copie y pegue en el navegador el \"enlace\"');
            }
        }
    }
    static public function entry()
    {
        if( isset( $_POST['mail'] ) ){
            $mail = str_replace ( ' ','',$_POST['mail']);
            if( !preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i",$mail) ){ echo '<div class="alert alert-warning text-center"><b>No digito un correo electrónico valido</b>, necesita el correo electrónico registrado para ingresar al sistema.</div>'; }
            else{
                $data_user = ModelGeneral::mdlRecord( 'single', 'users', 'where mail="'.$mail.'"' );
                if( empty($data_user) ){
                    echo '<br><div class="alert alert-danger text-center"><b>¡El usuario no existe!</b>, intentelo de nuevo o registrese.</div>';
                }
                elseif ( password_verify( $_POST['password'], $data_user['password'] ) ) {
                    switch ( $data_user['status'] ){
                        case 1:
                            $lastLogin = ModelGeneral::mdlUpdateField( 'users', 'last_login', [ 'set' => current_date("date_time"), 'id' => $data_user['id'] ] );
                            switch ( $lastLogin ){
                                case 'ok':
                                    $_SESSION['startSesion'] = 'ok';
                                    $_SESSION['id'] = $data_user['id'];
                                    $_SESSION['role'] = $data_user['role'];
                                    switch ( $data_user['role'] ){
                                        case 1: echo '<script> window.location = "dashboard"; </script>'; break;
                                        case 2: echo '<script> window.location = "customer"; </script>'; break;
                                    } break;
                            } break;
                        default: echo '<div class="alert alert-info text-center">"<b>¡Aún falta validar su cuenta!</b><br>verifique el correo que registro y busque el correo de validación que le enviamos, puede que este en correo no deseado y desde ese correo actívela."</div>';break;
                    }
                }
                else{
                    echo '<br><div class="alert alert-danger text-center"><b>¡Contraseña errada!</b>, inténtelo de nuevo o de click en <b>Recuperar contraseña</b> sino la recuerda.</div>';
                }
            }

        }

        /*if( preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i",$mail) && preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/', $_POST['password'] ) ){
            $data_user = ModelsGeneral::mdlRecord('single','users',"where username = '$mail'" );
            if( empty($data_user) ){
                echo '<br><div class="alert bg-gradient-danger text-center"><b>¡Usuario no existe!</b>, intentelo de nuevo o comuniquese con el administrador...</div>';
            }elseif ( password_verify( $_POST['password'], $data_user['password'] ) ) {
                switch ( $data_user['status'] ){
                    case 1:
                        $lastLogin = ModelsGeneral::mdlUpdateFieldUnique( 'users', 'last_login', [ 'set' => current_date("date_time"), 'id' => $data_user['id'] ] );
                        switch ( $lastLogin ){
                            case 'ok': $_SESSION['startSesion'] = 'ok'; $_SESSION['id'] = $data_user['id']; $_SESSION['role'] = $data_user['role'];
                                switch ( $data_user['role'] ){
                                    case 1: echo '<script> window.location = "system/dashboard"; </script>'; break;
                                    case 2: echo '<script> window.location = "system/sales";</script>'; break;
                                } break;
                        }
                        break;
                    default: echo '<br><div class="alert alert-danger">El usuario no está activado, solicítele a su jefe directo la activación</div>'; break;
                }
            }else{
                echo '<br><div class="alert bg-gradient-warning"><b>¡Contraseña errada!</b>, intentelo de nuevo o de click en olvide mi contraseña...</div>';
            }
        }*/

    }

    static public function UpdatePass( $id )
    {

        if( isset( $_POST['update_password'] ) ){

            $data = [
                'id' => $id,
                'set' => password_hash( $_POST['update_password'], PASSWORD_DEFAULT ),
            ];

            $response = ControllerGeneral::mdlUpdateFieldUnique('users','password',$data);

            switch ( $response ){
                case 'ok':
                    echo success();
                    break;
                default:
                    echo error();
                    break;
            }

        }

    }
    static public function UpdateInsertUsers()
    {

        if( isset( $_POST['usu_id'] ) ){

            $items = [
                'sex' => $_POST['usu_sex'],
                'name' => $_POST['usu_name'],
                'username' => $_POST['usu_username'],
                'role' => $_POST['usu_role'],
                'site' => $_POST['usu_site'],
                'email' => $_POST['usu_mail'],
                'address' => $_POST['usu_address'],
                'phone' => $_POST['usu_phone'],
                'id_card' => $_POST['usu_identification'],
            ];

            if( $_POST['usu_id'] != ''){
                $user = ControllerGeneral::ctrRecord('single','users','where id = '.$_POST['usu_id'] );
                $data = array_merge($items,["password" => $user['password'],'id' => $user['id']] );
                $response = ControllerGeneral::ctrUpdateRow('users',$data);
            }else{
                $data = array_merge($items,["password" => password_hash('Default1234', PASSWORD_DEFAULT)] );
                $response = ControllerGeneral::ctrInsertRow('users',$data);
            }

            switch ( $response ){
                case 'ok':
                    echo success();
                    break;
                default:
                    echo error();
                    break;
            }

        }

        if( isset( $_POST['profile_id'] ) ){

            $user = ModelsGeneral::mdlRecord('single','users','where id = '.$_POST['profile_id'] );

            if( $_POST['profile_pass'] != '' ){
                $pass = password_hash($_POST['profile_pass'], PASSWORD_DEFAULT);
            }else{
                $pass = $user['password'];
            }

            $data = [
                'id' => $user['id'],
                'sex' => $user['sex'],
                'name' => $user['name'],
                'username' => $user['username'],
                'password' => $pass,
                'role' => $user['role'],
                'site' => $user['site'],
                'email' => $_POST['profile_mail'],
                'address' => $_POST['profile_address'],
                'phone' => $_POST['profile_phone'],
                'id_card' => $user['id_card'],
            ];

            $response = ModelsGeneral::mdlUpdateRow('users',$data);

            switch ( $response ){
                case 'ok':
                    echo success();
                    break;
                default:
                    echo error();
                    break;
            }

        }

    }
    static public function ChangeStatusUser($user, $sta)
    {

        /*$data = array(
            'set' => current_date("date_time"),
            'id' => $data_user['id']
        );*/

        $respuesta =  ModelsGeneral::mdlUpdateFieldUnique('','','');
        return $respuesta;

    }

}

