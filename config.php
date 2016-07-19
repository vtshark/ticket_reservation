<?php

function __autoload($className) {

    $className = str_replace("\\","/",$className);
    if (file_exists("class/$className.php")) {
        include "class/$className.php";
    } else {
        //exit ("Файл $className.php не найден!");
    }

}

define("ROOT", "/");
define("IP",$_SERVER['REMOTE_ADDR']);
//define("IP","456");