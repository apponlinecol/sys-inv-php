<?php
session_start(); $url = routes::ctrRout();
$template = ControllerGeneral::ctrRecord('single','template','where id = 1');
$color = '#'.$template['corporate_color'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?=$color?>" name="theme-color">
    <meta name="title" content="Inventories demo">

    <title>Inventories demo</title>
    <link href="<?=$url.'favicon.ico?v='.time()?>" rel="icon" >
    <link href="<?=$url?>assets/node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" >
    <link href="<?=$url?>assets/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link href="<?=$url?>assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" >
    <link href="<?=$url?>assets/node_modules/toastr/build/toastr.min.css" rel="stylesheet" >

    <link href="<?=$url.'assets/css/style.css?v='.time()?>" rel="stylesheet" >

    <script src="<?=$url?>assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="<?=$url?>assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?=$url?>assets/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?=$url?>assets/node_modules/toastr/build/toastr.min.js"></script>

    <style>
        .text_corporate{ color: <?=$color?>; }
        #navbar-toggle .icon-bar { background-color: <?=$color?>; }
        #menu{ background: <?=$color?>; }
    </style>
</head>

<body>

<main id="main">
    <?php
    if( isset( $_GET['route'] ) ) {
        $routes = explode('/', $_GET['route']);
        switch ( $routes[0] ){
            case 'other': break;
            default: include 'modules/'.$routes[0].'.php'; break;
        };
    }else{
        include 'modules/login.php';
    }
    /*switch ( $routes[0] ){
        case $case: $dir; break;
        case 'administrator': include 'modules/login.php'; break;
        case 'exit': include 'modules/exit.php'; break;
        case 'system':
            if( isset( $routes[1] ) ){
                if( isset( $_SESSION['startSesion'] ) && $_SESSION['startSesion'] == 'ok' ) {
                    $user = ControllerGeneral::ctrRecord('single', 'users', 'where id=' . $_SESSION['id']);
                    $menu = ControllerGeneral::ctrRecord('single', 'menu', 'where status=1 and name= "' . str_replace('-', ' ', $routes[1]) . '"');
                    if (!empty($menu)) { $case = str_replace(' ', '-', $menu['name']); }
                    else { $submenu = ControllerGeneral::ctrRecord('single', 'menu_sub', 'where status=1 and name= "' . str_replace('-', ' ', $routes[1]) . '"'); $case = str_replace(' ', '-', $submenu['name']); }
                    switch ( $routes[1] ) {
                        case $case: include 'modules/menu-admin.php'; include 'modules/'.$routes[1].'.php'; break;
                        default: include 'modules/404.php'; break;
                    }
                }else{ include 'modules/404.php'; }
            }else{ include 'modules/404.php'; }
            break;
        default: include 'modules/404.php'; break;
    }*/
    echo '<input id="url" value="'.$url.'" type="hidden">'; ?>
</main>

<script src="<?=$url?>assets/js/script.js"></script>
<?php if( isset( $routes[0] ) ){ echo '<script src="'.$url.'assets/js/'.$routes[0].'.js"></script>'; } ?>

</body>

</html>