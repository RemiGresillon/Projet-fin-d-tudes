<?php 


    $issue = $db -> query('SELECT COUNT(DISTINCT products.ID) FROM products INNER JOIN datalayers ON (products.ID = datalayers.ID) WHERE products.name != datalayers.name OR products.price != datalayers.price OR products.brand != datalayers.brand OR products.category != datalayers.category OR products.reduction != datalayers.reduction');
    $total = $db -> query('SELECT COUNT(DISTINCT ID) FROM products');
    $issue = $issue -> fetch();
    $total = $total -> fetch();



    $issue = $db -> query('SELECT COUNT(DISTINCT products.ID) FROM products INNER JOIN datalayers ON (products.ID = datalayers.ID) WHERE products.name != datalayers.name OR products.price != datalayers.price OR products.brand != datalayers.brand OR products.category != datalayers.category OR products.reduction != datalayers.reduction');
    $total = $db -> query('SELECT COUNT(DISTINCT ID) FROM products');
    $issue = $issue -> fetch();
    $total = $total -> fetch();
    $result = number_format($issue[0]/$total[0]*100,2,',','');


    $total = $db -> query('SELECT COUNT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "datalayers"');
    $total = $total -> fetch();


    $issue = $db -> query('SELECT COUNT(DISTINCT products.ID) FROM products INNER JOIN datalayers ON (products.ID = datalayers.ID) WHERE products.name != datalayers.name OR products.price != datalayers.price OR products.brand != datalayers.brand OR products.category != datalayers.category OR products.reduction != datalayers.reduction');
    $total = $db -> query('SELECT COUNT(DISTINCT ID) FROM products');
    $issue = $issue -> fetch();
    $total = $total -> fetch();



    $data_test = "efz";
    $datalayer_variable = ['ID','name','price','brand','category','reduction','variant','pageCategory','pageSubCategory_1','pageSubCategory_2','pageSubCategory_3','pageSubCategory_4','siteCountry'];
    $data_add = [];
    $data_err = [];
    foreach($datalayer_variable as $value)
        array_push($data_add,[$value,sprintf("SELECT COUNT(products.ID) FROM products INNER JOIN datalayers ON(products.ID = datalayers.ID) WHERE products.%s != datalayers.%s",$value,$value)]);
    foreach($data_add as $value)
        array_push($data_err, [$value[0],$db -> query($value[1]) -> fetch()]);?>



    <script>
        var test = <?php echo json_encode($data_err);?>; 
        var worst = 0 
        var target = ""
        var perfect_stack = 0
        for (var i in test){
            if (parseInt(test[i][1][0]) == 0){
                perfect_stack += 1
            }
            if (parseInt(test[i][1][0]) > worst){        
                worst = test[i][1][0]  
                var target = test[i][0]
            } else { continue }
        }
    </script>
<?php
?>