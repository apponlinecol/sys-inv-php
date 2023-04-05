<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ControllerGeneral
{
    static public function ctrRecord ( $type, $table, $other )
    {
        $response = ModelGeneral::mdlRecord( $type, $table, $other );
        return $response;
    }
    static public function ctrInsertRow( $table, $header, $data )
    {
        $response = ModelGeneral::mdlInsertRow( $table, $header, $data );
        return $response;
    }
    static public function ctrUpdateRow( $table, $title, $data )
    {
        $response = ModelGeneral::mdlUpdateRow( $table, $title, $data );
        return $response;
    }
    static public function ctrUpdateField( $table, $set, $data )
    {
        $response = ModelGeneral::mdlUpdateField( $table, $set, $data );
        return $response;
    }

    static public function ctrExecuteQuery( $query )
    {
        $response = ModelGeneral::mdlExecuteQuery($query);
        return $response;
    }
    static public function ctrDataTableDynamic( $report1, $report2, $report3 )
    {
        $query = ModelGeneral::mdlRecord( 'single', 'reports', 'where name="'.$report1.'"' );
        $header =  explode('where',$query['query'] );
        $end =  str_replace( '=','', array_slice( explode(' ',$query['query'] ) , -1)[0] );

        switch ( $report2 ){ case 0: $val = $end; break; default: $val = $report2 ;  break; }

        switch ( $query['filter'] ){
            case 1: $query_compose = $query["query"] . $val; break;
            case 2: $query_compose = $query["query"] . $val; break;
            case 3: $query_compose = $query["query"] . ' created > "'.$report2.'" and created < "'.$report3.'" ' ; break;
        }

        $response = ModelGeneral::mdlDataTableDynamic( $header[0] , $query_compose );
        return $response ;
    }

    static public function ctrRemoveRow( $table, $idr ){
        $response = ModelGeneral::mdlRemoveRow( $table, $idr );
        return $response;
    }

    static public function ctrEmailsSending( $ide, $idr, $code )
    {
        $url = routes::ctrRout();
        $template = ModelGeneral::mdlRecord('single','template','where id=1');
        $emails_sending =  ModelGeneral::mdlRecord('single','emails_sending','where id='.$ide );
        $user = ModelGeneral::mdlRecord('single','users','where id="'.$idr.'"');

        if( $user == false ){ $mail = 'nimajho@outlook.com'; $name='Jhonny'; }
        else{ $mail = $user['mail']; $name = ucfirst($user['name']); }

        $html = '
        <div style="width:100%; background:#fff; position:relative; font-family:sans-serif; padding-bottom:20px" >
            <center>
                <img style="padding-top:25px; width:10%" src="'.$url.'sys-inv-php/assets/img/template/logo.png">
                <h4>Bienvenido '.$name.' a AppOnlinecol ¡¡¡</h4>
            </center>
            <div style="position:relative; margin:auto; width:600px; background:white; padding:20px; border:solid 1px #ff5a01;">
                <center>
                    <img style="padding:20px; width:15%" src="'.$url.'sys-inv-php/assets/img/template/activate.png">
                    <h3 style="font-weight:100; color:#424242">'.strtoupper($emails_sending['title']).'</h3>
                    <hr style="border:1px solid #ccc; width: 8px;0%">
                    <h4 style="font-weight:100; color:#424242; padding:0 20px">'.$emails_sending['message'].'</h4>
                    <h2 style="color: #ff5a01">'.$code.'</h2>
                    <a href="'.$url.$emails_sending['link'].$user['mail_encrypt'].'" target="_blank" style="text-decoration:none">
                        <div style="line-height:30px; background:#ff5a01; width:30%; color:white; border-radius: 5px">'.$emails_sending['button'].'</div>
                    </a>
                    <br>
                    <h4 style="font-weight:100; color:#424242; padding:0 20px">Una vez completado, puede comenzar a usar el demo y hacer las pruebas que desee.</h4>
                    <br>
                    <p><small style="font-weight:100; color:#424242">¿No puede ingresar el código? Pegue el siguiente enlace en su navegador:</small></p>
                    <p><small style="font-weight:100; color:#424242; text-decoration:none">'.$url.$emails_sending['link'].$user['mail_encrypt'].'</small></p>
                    <hr style="border:1px solid #ccc; width: 8px;0%">
                    <h5 style="font-weight:100; color:#424242">Estás recibiendo este correo electrónico porque creaste una cuenta en AppOnlinecol. Si no fuiste tú, ignora este correo electrónico.</h5>
                </center>
            </div>
        </div>
        ';

        $e_mail = new PHPMailer(true);
        $e_mail->CharSet = "UTF-8";

        try {
            $e_mail->SMTPDebug = 2;
            $e_mail->isSMTP();
            $e_mail->Host = 'apponlinecol.com';
            $e_mail->SMTPAuth = true;
            $e_mail->Username = $template['mail'];
            $e_mail->Password = $template['pass'];
            $e_mail->SMTPSecure = 'ssl';
            $e_mail->Port = 465;

            $e_mail->setFrom( $template['mail'], 'AppOnlinecol' );
            $e_mail->addAddress( $mail );

            $e_mail->isHTML(true);
            $e_mail->Subject = $emails_sending['subject'];
            $e_mail->Body = $html;
            $e_mail->AltBody = '';
            /*if( $sales_web['shipping_support'] != null ){
                $e_mail->addAttachment( str_replace('../../','../', $sales_web['shipping_support'] )) ;
            }*/

            if( $e_mail->send() ){
                switch ( $code ){
                    case 0: success_mail( $mail, $user['mail_encrypt'] ); break;
                    default: echo'<script> window.location.href= "'.$url.'account-activation/'.$user['mail_encrypt'].'" ; </script>'; break;
                }

            }

        } catch (Exception $e) { echo "Ocurrió un error: {$e_mail->ErrorInfo}";  }

    }

}



function current_date( $type ){
    date_default_timezone_set('America/Bogota');
    switch ($type){
        case "date_time": return date("Y-m-d H:i:s", time()); break;
        case "date": return date("Y-m-d", time()); break;
        case "time": return date("H:i:s", time()); break;
    }
}
function clean_name_file( $text ){
    $accents = [ 'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'Á'=>'a', 'É'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u', 'ñ'=>'n', ' '=>'-'  ];
    $sc = [ '!','#','$','%','&','(',')','=','¡','°','¿','+','-','´','¨','{','}','[',']',';','|','?','/','"','\'' ];
    foreach ( $accents as $key => $row ){ $text = str_ireplace( $key, $row, $text); }
    $text = str_ireplace( $sc ,'', $text);
    return $text;
}

function success( $text ){
    ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? $host = 'http://' : $host = 'https://' ; $url = $host.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; echo'
<script>
    swal.fire({ html: "¡'.$text.'!", icon: "success", showCancelButton: false, confirmButtonText: "ok!", allowOutsideClick: false,
    }).then((result) => { if (result.value) { window.location.href= "'.$url.'" ; } })
</script>
';}
function error( $text ){
    ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? $host = 'http://' : $host = 'https://' ; $url = $host.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; echo'
<script>
    swal.fire({ title: "Ocurrio un error...", html:  "¡'.$text.'!", icon:  "error", showCancelButton: false, confirmButtonText: "ok!", allowOutsideClick: false,
    }).then((result) => { if (result.value) { window.location.href= "'.$url.'" ; } })
</script>
';}
function repeated( $text ){ echo'
<script>
    swal.fire({ title: "Registro repetido...", text:  "¡'.$text.'!", icon:  "warning", showCancelButton: false, confirmButtonText: "ok!", allowOutsideClick: false,
    }).then((result) => { if (result.value) { history.back(); } })
</script>
';}
function success_mail( $mail, $mail_encrypt ){ $url = routes::ctrRout(); echo'
<script>
    swal.fire({ html: "¡Se envió al correo '.$mail.' el código de verificación para que finalice con el proceso de registro!", icon: "success", showCancelButton: false, confirmButtonText: "Ingresar código!", allowOutsideClick: false,
    }).then((result) => { if (result.value) { window.location.href= "'.$url.'account-activation/'.$mail_encrypt.'" ; } })
</script>
';}
function print_pos( $dir ){ $url = routes::ctrRout(); echo'
<script>localStorage.clear(); $(location).attr("href", "'.$url.$dir.'");  </script>
';}

function diff_array( $first, $second ,$match ){
    $available = [];
    for ($i = 0; $i < count($first); $i++) { $equal = false;
        for ($j = 0; $j < count($second) & !$equal; $j++) { if ($first[$i][ $match ] == $second[$j][ $match]) $equal = true; }
        if (!$equal) array_push($available,$first[$i]);
    }
    return $available;
}
function header_active( $tbl ){
    $data = ControllerGeneral::ctrExecuteQuery('SHOW COLUMNS FROM '.$tbl );
    $title = []; foreach ( $data as $row ){
        $typ = explode( '(',$row['Type'] );
        if( $row['Field'] != 'id' && $row['Field'] != 'status' && $typ[0] != 'timestamp' ){ $title[] = $row['Field']; }
    }
    return $title;
}
function insert_row( $tbl ){

    $data = ControllerGeneral::ctrExecuteQuery('SHOW COLUMNS FROM '.$tbl); $typ = [];
    $first = []; foreach ( $data as $row ){
        $typ = explode( '(',$row['Type'] );
        if( $row['Field'] != 'id' && $row['Field'] != 'status' && $typ[0] != 'timestamp' ){
            foreach($_POST as $nombre_campo => $valor){
                if( $nombre_campo == 'Telefonos' || $typ[0] == 'int' || $typ[0] == 'double' || $typ[0] == 'decimal' ) {
                    $val = str_replace(' ','',str_replace('-','',str_replace('_','',$valor))); }
                else if( $nombre_campo == 'Identificacion' ) { $val = str_replace('.','',$valor) ; }
                else { $val = str_replace('-','',$valor) ; }
                if( $row['Field'] == $nombre_campo ) $first[ $row['Field'] ] = $val ;
            }
        }
    }

    $header = []; foreach ( $data as $row ) { $header[] = $row['Field']; }
    $post = []; foreach($_POST as $nombre_campo => $valor){ $post[] = $nombre_campo; }
    $second = [];
    $diff = array_diff( $header, $post );
    foreach ( $diff as $item ){
        foreach ( $data as $row ){
            $typ = explode( '(',$row['Type'] );
            if( $row['Field'] == 'sale' || $row['Field'] == 'quote' || $row['Field'] == 'payment' || $row['Field'] == 'exchange' ){
                $last = ControllerGeneral::ctrRecord( 'single', $row['Field'] , ' order by id desc limit 1' ); $val = $last[ $row['Field']  ]+1;
            }elseif( $typ[0] == 'int' || $typ[0] == 'double' || $typ[0] == 'decimal' ) { $val = 0; } else { $val = null; }
            if( $row['Field'] != 'id' && $row['Field'] != 'status' && $typ[0] != 'timestamp' ){ if( $row['Field'] == $item ) $second[$row['Field']] = $val; }
        }
    }
    return array_merge( $first, $second );

}
function update_row( $tbl ){
    $data = ControllerGeneral::ctrExecuteQuery('SHOW COLUMNS FROM '.$tbl); $typ = [];
    $first = []; foreach ( $data as $row ){
        $typ = explode( '(',$row['Type'] );
        if( $row['Field'] != 'status' && $typ[0] != 'timestamp' ){
            foreach($_POST as $nombre_campo => $valor){
                if( $nombre_campo == 'Telefonos' ) {
                    $val = str_replace(' ','',str_replace('-','',str_replace('_','',$valor))); }
                else if( $typ[0] == 'int' || $typ[0] == 'double' || $typ[0] == 'decimal' ||  $nombre_campo == 'Identificacion' ) { $val = str_replace('.','',$valor) ; }
                else { $val = str_replace('-','',$valor) ; }
                if( $row['Field'] == $nombre_campo ) $first[ $row['Field'] ] = $val ;
            }
        }
    }

    $header = []; foreach ( $data as $row ) { $header[] = $row['Field']; }
    $post = []; foreach($_POST as $nombre_campo => $valor){ $post[] = $nombre_campo; }
    $second = [];
    $diff = array_diff( $header, $post );
    $info = ControllerGeneral::ctrRecord('single', $tbl,'where id='.$_POST['id'] );
    foreach ( $diff as $item ){
        if( $item != 'status' && $item != 'dto' && $item != 'created' && $item != 'modified' && $item != 'last_login'  ){
            $second[ $item ] = $info[$item] ;
        }
    }
    return array_merge( $first, $second );
}
function unique_array($array, $key) {
    $temp_array = [];
    $key_array = [];
    foreach( $array as $i=>$val ) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
    }
    return $temp_array;
}

function valueInLetters($x){
    if ($x<0) { $signo = "Menos ";}
    else      { $signo = "";}
    $x = abs ($x);
    $C1 = $x;
    $G6 = floor($x/(1000000));
    $E7 = floor($x/(100000));
    $G7 = $E7-$G6*10;
    $E8 = floor($x/1000);
    $G8 = $E8-$E7*100;
    $E9 = floor($x/100);
    $G9 = $E9-$E8*10;
    $E10 = floor($x);
    $G10 = $E10-$E9*100;
    $G11 = roUnd(($x-$E10)*100,0);

    $H6 = units($G6);
    if($G7==1 AND $G8==0) { $H7 = "Cien "; }
    else {    $H7 = tens($G7); }
    $H8 = units($G8);
    if($G9==1 AND $G10==0) { $H9 = "Cien "; }
    else {    $H9 = tens($G9); }
    $H10 = units($G10);

    if($G6==0) { $I6=" "; }
    elseif($G6==1) { $I6="Mill�n "; }
    else { $I6="Millones "; }
    if ($G8==0 AND $G7==0) { $I8=" "; }
    else { $I8="Mil "; }
    $I10 = "Pesos ";
    $I11 = "m/c ";
    $C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.$I11;
    return $C3;
}
function units($u){
    if ($u==0)  {$ru = " ";}
    elseif ($u==1)  {$ru = "Un ";}
    elseif ($u==2)  {$ru = "Dos ";}
    elseif ($u==3)  {$ru = "Tres ";}
    elseif ($u==4)  {$ru = "Cuatro ";}
    elseif ($u==5)  {$ru = "Cinco ";}
    elseif ($u==6)  {$ru = "Seis ";}
    elseif ($u==7)  {$ru = "Siete ";}
    elseif ($u==8)  {$ru = "Ocho ";}
    elseif ($u==9)  {$ru = "Nueve ";}
    elseif ($u==10) {$ru = "Diez ";}
    elseif ($u==11) {$ru = "Once ";}
    elseif ($u==12) {$ru = "Doce ";}
    elseif ($u==13) {$ru = "Trece ";}
    elseif ($u==14) {$ru = "Catorce ";}
    elseif ($u==15) {$ru = "Quince ";}
    elseif ($u==16) {$ru = "Dieciseis ";}
    elseif ($u==17) {$ru = "Decisiete ";}
    elseif ($u==18) {$ru = "Dieciocho ";}
    elseif ($u==19) {$ru = "Diecinueve ";}
    elseif ($u==20) {$ru = "Veinte ";}
    elseif ($u==21) {$ru = "Veintiun ";}
    elseif ($u==22) {$ru = "Veintidos ";}
    elseif ($u==23) {$ru = "Veintitres ";}
    elseif ($u==24) {$ru = "Veinticuatro ";}
    elseif ($u==25) {$ru = "Veinticinco ";}
    elseif ($u==26) {$ru = "Veintiseis ";}
    elseif ($u==27) {$ru = "Veintisiente ";}
    elseif ($u==28) {$ru = "Veintiocho ";}
    elseif ($u==29) {$ru = "Veintinueve ";}
    elseif ($u==30) {$ru = "Treinta ";}
    elseif ($u==31) {$ru = "Treinta y Un ";}
    elseif ($u==32) {$ru = "Treinta y Dos ";}
    elseif ($u==33) {$ru = "Treinta y Tres ";}
    elseif ($u==34) {$ru = "Treinta y Cuatro ";}
    elseif ($u==35) {$ru = "Treinta y Cinco ";}
    elseif ($u==36) {$ru = "Treinta y Seis ";}
    elseif ($u==37) {$ru = "Treinta y Siete ";}
    elseif ($u==38) {$ru = "Treinta y Ocho ";}
    elseif ($u==39) {$ru = "Treinta y Nueve ";}
    elseif ($u==40) {$ru = "Cuarenta ";}
    elseif ($u==41) {$ru = "Cuarenta y Un ";}
    elseif ($u==42) {$ru = "Cuarenta y Dos ";}
    elseif ($u==43) {$ru = "Cuarenta y Tres ";}
    elseif ($u==44) {$ru = "Cuarenta y Cuatro ";}
    elseif ($u==45) {$ru = "Cuarenta y Cinco ";}
    elseif ($u==46) {$ru = "Cuarenta y Seis ";}
    elseif ($u==47) {$ru = "Cuarenta y Siete ";}
    elseif ($u==48) {$ru = "Cuarenta y Ocho ";}
    elseif ($u==49) {$ru = "Cuarenta y Nueve ";}
    elseif ($u==50) {$ru = "Cincuenta ";}
    elseif ($u==51) {$ru = "Cincuenta y Un ";}
    elseif ($u==52) {$ru = "Cincuenta y Dos ";}
    elseif ($u==53) {$ru = "Cincuenta y Tres ";}
    elseif ($u==54) {$ru = "Cincuenta y Cuatro ";}
    elseif ($u==55) {$ru = "Cincuenta y Cinco ";}
    elseif ($u==56) {$ru = "Cincuenta y Seis ";}
    elseif ($u==57) {$ru = "Cincuenta y Siete ";}
    elseif ($u==58) {$ru = "Cincuenta y Ocho ";}
    elseif ($u==59) {$ru = "Cincuenta y Nueve ";}
    elseif ($u==60) {$ru = "Sesenta ";}
    elseif ($u==61) {$ru = "Sesenta y Un ";}
    elseif ($u==62) {$ru = "Sesenta y Dos ";}
    elseif ($u==63) {$ru = "Sesenta y Tres ";}
    elseif ($u==64) {$ru = "Sesenta y Cuatro ";}
    elseif ($u==65) {$ru = "Sesenta y Cinco ";}
    elseif ($u==66) {$ru = "Sesenta y Seis ";}
    elseif ($u==67) {$ru = "Sesenta y Siete ";}
    elseif ($u==68) {$ru = "Sesenta y Ocho ";}
    elseif ($u==69) {$ru = "Sesenta y Nueve ";}
    elseif ($u==70) {$ru = "Setenta ";}
    elseif ($u==71) {$ru = "Setenta y Un ";}
    elseif ($u==72) {$ru = "Setenta y Dos ";}
    elseif ($u==73) {$ru = "Setenta y Tres ";}
    elseif ($u==74) {$ru = "Setenta y Cuatro ";}
    elseif ($u==75) {$ru = "Setenta y Cinco ";}
    elseif ($u==76) {$ru = "Setenta y Seis ";}
    elseif ($u==77) {$ru = "Setenta y Siete ";}
    elseif ($u==78) {$ru = "Setenta y Ocho ";}
    elseif ($u==79) {$ru = "Setenta y Nueve ";}
    elseif ($u==80) {$ru = "Ochenta ";}
    elseif ($u==81) {$ru = "Ochenta y Un ";}
    elseif ($u==82) {$ru = "Ochenta y Dos ";}
    elseif ($u==83) {$ru = "Ochenta y Tres ";}
    elseif ($u==84) {$ru = "Ochenta y Cuatro ";}
    elseif ($u==85) {$ru = "Ochenta y Cinco ";}
    elseif ($u==86) {$ru = "Ochenta y Seis ";}
    elseif ($u==87) {$ru = "Ochenta y Siete ";}
    elseif ($u==88) {$ru = "Ochenta y Ocho ";}
    elseif ($u==89) {$ru = "Ochenta y Nueve ";}
    elseif ($u==90) {$ru = "Noventa ";}
    elseif ($u==91) {$ru = "Noventa y Un ";}
    elseif ($u==92) {$ru = "Noventa y Dos ";}
    elseif ($u==93) {$ru = "Noventa y Tres ";}
    elseif ($u==94) {$ru = "Noventa y Cuatro ";}
    elseif ($u==95) {$ru = "Noventa y Cinco ";}
    elseif ($u==96) {$ru = "Noventa y Seis ";}
    elseif ($u==97) {$ru = "Noventa y Siete ";}
    elseif ($u==98) {$ru = "Noventa y Ocho ";}
    else            {$ru = "Noventa y Nueve ";}
    return $ru;
}
function tens($d){
    if ($d==0)  {$rd = "";}
    elseif ($d==1)  {$rd = "Ciento ";}
    elseif ($d==2)  {$rd = "Doscientos ";}
    elseif ($d==3)  {$rd = "Trescientos ";}
    elseif ($d==4)  {$rd = "Cuatrocientos ";}
    elseif ($d==5)  {$rd = "Quinientos ";}
    elseif ($d==6)  {$rd = "Seiscientos ";}
    elseif ($d==7)  {$rd = "Setecientos ";}
    elseif ($d==8)  {$rd = "Ochocientos ";}
    else            {$rd = "Novecientos ";}
    return $rd;
}