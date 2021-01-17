<?php
    $data_pie = array();
    $seuil = 5;
    $share_pie_dimension = "variant";
    $rs = $db -> query(sprintf('SELECT count(%s)/2606*100 as share ,%s FROM datalayers GROUP BY %s',$share_pie_dimension,$share_pie_dimension,$share_pie_dimension));
    $obj = $rs -> fetchAll();
    foreach($obj as $row)
        $data_pie[] = $row;
    if (array_key_exists('generator_pie_variable',$_POST)){
        $seuil = $_POST["seuil"];
        $share_pie_dimension = $_POST["search_dimension_pie"];
        $rs = $db -> query(sprintf('SELECT count(%s)/2606*100 as share ,%s FROM datalayers GROUP BY %s',$share_pie_dimension,$share_pie_dimension,$share_pie_dimension));
        $obj = $rs -> fetchAll();
        $data_pie = array();
        foreach($obj as $row)
            $data_pie[] = $row;
    } 
?>