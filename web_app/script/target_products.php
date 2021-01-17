<?php
    $target_for_search = "";
    if(isset($_POST['submit'])){  
    $search_test = $_POST['search_test'];
    $target_for_search = $_POST['target_for_search'];
    if ($target_for_search != ""){
        if ($search_test == "URL"){
            $fetch_datalayer = $db -> query(sprintf("SELECT * FROM datalayers WHERE ID = (SELECT ID FROM products WHERE URL = '%s')",$target_for_search));
            $fetch_products = $db -> query(sprintf("SELECT * FROM products WHERE %s LIKE '%s'",$search_test,$target_for_search));
        }else{
            $fetch_datalayer = $db -> query(sprintf("SELECT * FROM datalayers WHERE %s LIKE '%s'",$search_test,$target_for_search));
            $fetch_products = $db -> query(sprintf("SELECT * FROM products WHERE %s LIKE '%s'",$search_test,$target_for_search));
        }
        $fetch_datalayer = $fetch_datalayer -> fetch();
        $fetch_products = $fetch_products-> fetch();
        if ($fetch_products[$search_test] != null){
        echo "<div id='finder'><table cellspacing='0' cellpadding='0'class='nope'>
                <tr style='font-weight:bold;text-align:center;'>
                    <td>Lien du produit</td>
                    <td>Scraping</td>
                    <td>Dalayers</td>
                </tr>";                       
        $table = ['ID','name','price','brand','category','reduction','variant','pageCategory','pageSubCategory_1','pageSubCategory_2','pageSubCategory_3','pageSubCategory_4','siteCountry'];
        foreach($table as $value)
        echo    "<tr style='text-align:center'>
                    <td>".$value."</td>
                    <td>".$fetch_products["$value"]."</td>
                    <td>".$fetch_datalayer["$value"]."</td>
                </tr>";
        echo "</table></div>"; 
        } else {echo " Ce produit n'est pas présent dans la base de données";}
        } else {echo "Veuillez renseigner un élément de recherche";} 
        }                       
?>