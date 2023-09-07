<?php

include_once 'php/views/head.php';
include_once 'php/views/navbar.php';

if (isset($_GET['module'])) {
    switch ($_GET['module']) {
        case 'alumnos':
            include_once 'php/views/horarios_alumnos.php';
            break;
        case 'familias':
            include_once 'php/views/horarios_familias.php';
            break;
        case 'admisiones':
            include_once 'php/views/admisiones.php';
            break;

        case 'prefectas':
            include_once 'php/views/prefectas.php';
            break;


        default:
            include_once 'php/views/horarios_familias.php';
            break;
    }
} else {
    include_once 'php/views/horarios_familias.php';
}


include_once 'php/views/endpage.php';
include_once 'php/views/footer.php';
