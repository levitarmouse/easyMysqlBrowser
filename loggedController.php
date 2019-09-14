<?php

/**
 *
 * @return \levitarmouse\kiss_orm\Mapper
 */
function getConn() {

    $host = $_SESSION['host'];
    $user = $_SESSION['user'];
    $pass = $_SESSION['pass'];
    $port = (isset($_SESSION['port'])) ? $_SESSION['port'] : 3306;
    $db = (isset($_SESSION['db'])) ? $_SESSION['db'] : '';

    $cfg = ['host'=>$host, 'user'=>$user, 'pass'=>$pass, 'db'=>$db];

    $model = new levitarmouse\kiss_orm\GenericEntity(false, $cfg);

//    $time = $model->getMapper()->select('select now()');

//    $conn = mysqli_connect($host, $user, $pass, 'youprpc9_prp');
//    $model->getMapper()->setDbCfg($host, $user, $pass, $db);
    return $model->getMapper();
}


function showDBs() {

    $model = getConn();

    $query = "SHOW DATABASES";

    $collection = $model->select($query);

    if (is_array($collection)) {
        echo('<b>Bases de datos</b><br>');
        echo "<form method='POST'>";
        foreach ($collection as $key => $value) {
            $btn = '<input name="db" type="submit" value="'.$value['Database'].'">';
            echo $btn;
        }
        echo "</form>";
    }
}

function showTables() {

    $model = getConn();

    $db = $_SESSION['db'];

    $query .= "SHOW TABLES";

    $collection = $model->select($query);

    if (is_array($collection)) {
        echo('<b>Tablas</b><br>');
        echo "<form method='POST'>";
        foreach ($collection as $key => $value) {
            $key = 'Tables_in_'.$db;
            $btn = '<input name="table" type="submit" value="'.$value[$key].'">';
            echo $btn;
        }
        echo "<br><br><input type='submit' name='table' value='allTables'><br>";
        echo "</form>";
    }
}

function getAllTables() {

    $model = getConn();
    $query .= "SHOW TABLES";
    $collection = $model->select($query);
    $db = $_SESSION['db'];
    $index = 'Tables_in_'.$db;
    $tables = [];
    foreach ($collection as $key => $value) {
        $tables[] = $value[$index];
    }

    return $tables;
}

function descTables() {
    $table = $_SESSION['table'];
    $model = getConn();

    if ($table != 'allTables') {
        $query .= "DESC ".$table;

        $collection = $model->select($query);

        $desc = json_encode($collection, JSON_PRETTY_PRINT);
        
        echo '<h3>'.$table.'</h3>';
        echo '<pre>';
        echo $desc;
        echo '</pre>';
    } else {

        $allTables = getAllTables();

        foreach ($allTables as $key => $table) {
            $query .= "DESC ".$table;

            $collection = $model->select($query);

            $desc = json_encode($collection, JSON_PRETTY_PRINT);

            echo '<h3>'.$table.'</h3>';
            echo '<pre>';
            echo $desc;
            echo '</pre>';
        }

    }


//    if (is_array($collection)) {
//        echo('<b>Tablas</b><br>');
//        echo "<form method='POST'>";
//        foreach ($collection as $key => $value) {
//            $key = 'Tables_in_'.$db;
//            $btn = '<input name="table" type="submit" value="'.$value[$key].'">';
//            echo $btn;
//        }
//        echo "<br><br><input type='submit' name='table' value='allTables'><br>";
//        echo "</form>";
//    }
}