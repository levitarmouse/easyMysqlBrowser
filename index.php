<!DOCTYPE html>
<?php
include_once './lmvendor/levitarmouse/kiss_orm/config/Bootstrap.php';

session_start();

//include_once './login.php';

$loged = isset($_SESSION['logged']) ? $_SESSION['logged'] : 'NO';

if ($loged == 'YES') {
    include_once './loggedController.php';
}

$host = (isset($_REQUEST['host'])) ? trim($_REQUEST['host']) : '';
$user = (isset($_REQUEST['user'])) ? trim($_REQUEST['user']) : '';
$pass = (isset($_REQUEST['pass'])) ? trim($_REQUEST['pass']) : '';
$logout = (isset($_REQUEST['logout'])) ? trim($_REQUEST['logout']) : '';
$db = (isset($_REQUEST['db'])) ? trim($_REQUEST['db']) : '';
$table = (isset($_REQUEST['table'])) ? trim($_REQUEST['table']) : '';
$allTables = (isset($_REQUEST['allTables'])) ? trim($_REQUEST['allTables']) : '';

function login($host, $user, $pass) {
    $conn = mysqli_connect($host, $user, $pass);
    if (!$conn) {
        echo ' ... No se pudo conectar';
    } else {
        echo ' CONECTADO !!!';
        $_SESSION['logged'] = 'YES';
        $_SESSION['host'] = $host;
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
    }
    $connErr = mysqli_errno($conn);
}

$dbSelected = '';
if ($db) {
    $_SESSION['db'] = $db;
    $dbSelected = $db;
} else {
    if ($_SESSION['db']) {
        $dbSelected = $_SESSION['db'];
    }
}

$selectedTable = '';
if ($table) {
    $_SESSION['table'] = $table;
    $selectedTable = $table;
}
if ($allTables) {
    $_SESSION['table'] = $allTables;
    $selectedTable = $allTables;
}

$ingresando = false;
if ($host && $user && $pass) {
    echo 'Ingresando';
    $ingresando = true;
    login($host, $user, $pass);
    $loged = isset($_SESSION['logged']) ? $_SESSION['logged'] : 'NO';
    header("location: http://localhost/emb");
}

if ($logout == 'yes') {
    session_destroy();
    $loged = isset($_SESSION['logged']) ? $_SESSION['logged'] : 'NO';
    header("location: http://localhost/emb");
}


$loginForm = __DIR__.'/login.html';
$salir = __DIR__.'/salir.html';
$menu = __DIR__.'/menu.html';

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body, input {
                font-size: 22px;
                font-family: sans-serif;
            }
        </style>
    </head>
    <body>
        <?php if ($loged != 'YES') {
            $loginHtml = file_get_contents($loginForm);
            echo $loginHtml;
        ?>


        <?php } ?>
        <?php if ($loged == 'YES') {
            $salirHtml = file_get_contents($salir);
            echo $salirHtml;

//            $menuHtml = file_get_contents($menu);

            if (isset($_SESSION['db'])) {
                echo $_SESSION['user'].'@'.$_SESSION['db'].'<br>';
            } else {
                echo $_SESSION['user'].'<br>';
            }

//            echo $menuHtml;

            showDBs();
        } ?>

        <?php if($dbSelected) {
          echo 'Base de datos seleccionada: '.$dbSelected.'<br>';
            showTables();
        }
        ?>

        <?php if($selectedTable) {
          echo 'Tabla seleccionada: '.$dbSelected.'<br>';
            descTables();
        }
        ?>
    </body>
</html>

