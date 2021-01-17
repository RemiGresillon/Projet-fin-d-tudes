<?php
            $search_option_desc = "DESC";
            $search_option_select_desc = "AVG(price)";
            $Vizualisation_rating = "variant";
            $search_metric = "price";
            $query = sprintf('SELECT AVG(price),SUM(overallRatingCount),AVG(overallRating), %s FROM products WHERE overallRatingCount > 0 GROUP BY %s ORDER BY %s %s', $Vizualisation_rating, $Vizualisation_rating,$search_option_select_desc,$search_option_desc);
            $rs = $db->query(sprintf('SELECT AVG(price),SUM(overallRatingCount),AVG(overallRating), %s FROM products WHERE overallRatingCount > 0 GROUP BY %s ORDER BY %s %s', $Vizualisation_rating, $Vizualisation_rating,$search_option_select_desc,$search_option_desc));
            $obj = $rs->fetchAll();
            $data = array();
            foreach ($obj as $row){
                $data[] = $row;
                }
            if (array_key_exists('generate',$_POST)){
              $search_metric = $_POST["search_metric"];
              $search_option_select_desc = $_POST["search_option_select_desc"];
              $search_option_desc = $_POST["search_option_desc"];
              $Vizualisation_rating = $_POST["search_dimension"];
              $query = sprintf('SELECT AVG(price),SUM(overallRatingCount),AVG(overallRating), %s FROM products WHERE overallRatingCount > 0 GROUP BY %s ORDER BY %s %s', $Vizualisation_rating, $Vizualisation_rating,$search_option_select_desc,$search_option_desc);
              $rs = $db->query(sprintf('SELECT AVG(price),SUM(overallRatingCount),AVG(overallRating), %s FROM products WHERE overallRatingCount > 0 GROUP BY %s ORDER BY %s %s', $Vizualisation_rating, $Vizualisation_rating,$search_option_select_desc,$search_option_desc));
              $obj = $rs->fetchAll();
              $data = array();
              foreach ($obj as $row){
                  $data[] = $row;
                  }
            }
            

?>

