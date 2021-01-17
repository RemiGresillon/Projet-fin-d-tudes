<?php          
    $stack_good = 0;
    $array_extract_good = array();
    while($products = $fetching_matching_products -> fetch()){
        array_push($array_extract_good,$products);
        $stack_good += 1;
        $fetch_test = $db -> query(sprintf("SELECT %s FROM datalayers WHERE ID = %s",$search,$products["ID"]));
        $fetch_test = $fetch_test -> fetch();
        ?>
        <div class="product_name">
            <tr>
                <?php echo '<td><a class="active_p" href= ',$products["URL"],'>Lien du produit</a></td>'?>
                <td><?php echo $products[$search]?></td>
                <td><?php echo $fetch_test[$search]?></td>
            </tr>
        </div><p></p>
    <?php
}?>