<?php

## Connexion à la BDD 

$db= new PDO('mysql:host=127.0.0.1;dbname=elec_db;charset=utf8',"root","");
$error = "";



## Pour débuguer ##
function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

?>