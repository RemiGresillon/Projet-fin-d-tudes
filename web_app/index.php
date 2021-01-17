<?php 
    include "script/connect.php";
    include "script/scorecards.php";
    $search = "name";
    if(isset($_POST['search'])){  
        $search = $_POST['search'];
    }
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <title> - Comparateur datalayer / scrap - </title>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

</head>

<body>

    <div id="old_cont">
        <div id="menu_container">
            <ul id="menu">
                <li><a class="active" href="index.php"> - Comparateur Datalayer / Scrap - </a></li>
                <li><a class="active" href="script/vizualisation.php"> - Analyse Datalayer - </a></li>
            </ul>
        </div>
    </div>


    <div id="presentation_scorecard">
        <span id="presentation_scorecard_content">
            Scoreboards principaux
        </span>
    </div>

    <div class="container_scorecard">
        <div class="catch_attention">
            <h3>Nombre de produits scrapé</h3>
            <div class="catch_percent">
                <span class="percent">
                    <?php echo $total[0];?>
                </span>
            </div>
        </div>
        <div class="catch_attention">
            <h3>Pourcentage des produits dans le datalayer erronés</h3>
            <div class="catch_percent">
                <span class="percent">
                    <?php echo $result?>
                %</span>
            </div>
        </div>
        <div class="catch_attention">
            <h3>Types de variable du datalayer analysés</h3>
            <div class="catch_percent">
                <span class="percent">
                    <?php echo $total[0];?>
                </span>
            </div>
        </div>
    </div>
    <div class="container_scorecard">
        <div class="catch_attention">
            <h3>Erreur uniques</h3>
            <div class="catch_percent">
                <span class="percent">
                    <?php echo $issue[0];?>
                </span>
            </div>
        </div>       
        <div class="catch_attention">
            <h3>Variable critique (<script> document.write(target) </script>)</h3>
            <div class="catch_percent">
                <span class="percent">
                    <script> document.write(worst) </script>
                </span>
            </div>
        </div>
        <div class="catch_attention">
            <h3>Variable sans erreur</h3>
            <div class="catch_percent">
                <span class="percent">
                    <script>document.write(perfect_stack)</script>
                </span>
            </div>
        </div>
    </div>



    <div class="container_item_">
        <div class="presentation_container_item">
            <span>
                Selecteur de produits
            </span>
        </div>
        <div class="select_key_datalayer">
            <label for="datalayer_select">Choississez une variable du datalayer</label>
            <form class="select_variable" method="post"> 
                <select name="search_test">
                    <option value="URL">Url de la page</option>
                    <option value="name">Nom du produit</option>
                    <option value="ID">ID du produit</option>
                </select>
                <input type="text" name="target_for_search" placeholder=" " size="20">
                <button name="submit">Search</button>
            </form>
        </div>    
        <?php include "script/target_products.php";?>
    </div>


    
    <div class="container_item">
        <div class="presentation_container_item presentation_over">
            <span>
                Chercheur de disparité datalayer/scraping
            </span>
        </div>
        <div class="select_key_datalayer">
            <label for="datalayer_select">Choississez une variable du datalayer</label>
            <form class="select_variable" method="post"> 
                <select name="search">
                    <option value="ID">ID</option>
                    <option value="name">nom</option>
                    <option value="price">prix</option>
                    <option value="brand">marque</option>
                    <option value="category">category</option>
                    <option value="reduction">reduction</option>
                    <option value="variant">variant</option>
                    <option value="pageCategory">pageCategory</option>
                    <option value="pageSubCategory_1">pageSubCategory_1</option>
                    <option value="pageSubCategory_2">pageSubCategory_2</option>
                    <option value="pageSubCategory_3">pageSubCategory_3</option>
                    <option value="pageSubCategory_4">pageSubCategory_4</option>
                    <option value="siteCountry">siteCountry</option>
                </select>
                <button type="submit" name="datalayer_sell" value="select">Rechercher les url</button>
                <?php echo "<div>Dimension actuellement affichée => ".$search."</div>"?>
            </form>
        </div>
        <?php
        $fetching_matching_products = $db -> query(sprintf("SELECT products.URL,products.ID,products.%s FROM products INNER JOIN datalayers ON(products.ID = datalayers.ID) WHERE products.%s = datalayers.%s",$search,$search,$search));
        $fetching_not_matching_products = $db -> query(sprintf("SELECT products.URL,products.ID,products.%s FROM products INNER JOIN datalayers ON(products.ID = datalayers.ID) WHERE products.%s != datalayers.%s",$search,$search,$search));           
        ?>
        <div id="opac"><div id="loader"></div></div>
        <div id="hidden">
            <div class="bloc_good_item">
                <div class="good_item">
                    <h3>URL sans probleme</h3>
                    <div id="container_table">
                    <table>
                        <tr style="font-weight:bold;text-align:center">
                            <td>Lien du produit</td>
                            <td>Scraping</td>
                            <td>Dalayers</td>
                        </tr>
                        <?php include("script/display_good_products.php");?>
                    </table>
                    </div>
                </div>
                <div class="more_info">
                    <span><?php echo "Nombre total de produits: ".$stack_good;?></span> 
                    <button class="extract" id="extract_comparator_good"> Extract </button> 
                </div>
            </div>   
            <div class="bloc_bad_item">
                <div class="bad_item">
                    <h3>URL avec probleme</h3>
                    <div id="container_table">
                    <table>
                        <tr style="font-weight:bold;text-align:center">
                            <td>Lien du produit</td>
                            <td>Scraping</td>
                            <td>Dalayers</td>
                        </tr>
                        <?php include("script/display_bad_products.php");?>
                    </table>
                    </div>
                </div>
                <div class="more_info">
                    <span><?php echo "Nombre total de produits: ".$stack_bad;?></span> 
                    <button class="extract" id="extract_comparator_bad"> Extract </button> 
                </div>
            </div>
            
        </div>
</body>   

<script>
    var element = document.getElementById("hidden");
    element.style.opacity = 0;
    element.style.transition = "opacity 3s";
    setTimeout(() => {
        element.style.opacity = 1;
    }, 1000);

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    document.getElementById('extract_comparator_good').addEventListener('click', function() {
        var data = <?php echo json_encode($array_extract_good);?>;
        let csvContent = "data:text/csv;charset=utf-8,";

        for (var i in data){
          var csvrow = "";
          for (let e=0;e<3;e++){
              csvrow += data[i][e]+";";
          }
          csvrow = csvrow.slice(0,-1);
          csvrow = csvrow + "\r\n";
          csvContent += csvrow;
        }
        var encodeUri = encodeURI(csvContent);
        var testtt = document.getElementById("recup")
        var link = document.createElement("a");
        link.setAttribute("href",encodeUri);
        link.setAttribute("download","good_data.csv");
        document.body.appendChild(link);
        link.click();
    })


    document.getElementById('extract_comparator_bad').addEventListener('click', function() {
        
        var data = <?php echo json_encode($array_extract_bad);?>;
        
        let csvContent = "data:text/csv;charset=utf-8,";

        for (var i in data){
          var csvrow = "";
          for (let e=0;e<3;e++){
              csvrow += data[i][e]+";";
          }
          csvrow = csvrow.slice(0,-1);
          csvrow = csvrow + "\r\n";
          csvContent += csvrow;
        }
        var encodeUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href",encodeUri);
        link.setAttribute("download","bad_data.csv");
        document.body.appendChild(link);
        link.click();
})
</script>

</html>