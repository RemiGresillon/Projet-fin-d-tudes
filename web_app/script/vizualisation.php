<?php
   include "connect.php";
?>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="../css/style.css" type="text/css">
      <title> - Analyse Datalayer - </title>
      <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
      <script type="text/javascript" src="../js/app.js"></script>
   </head>
   <body>
      <div id="old_cont">
         <div id="menu_container">
            <ul id="menu">
               <li><a class="active" href="../index.php"> - Comparateur Datalayer / Scrap - </a></li>
               <li><a class="active" href="vizualisation.php"> - Analyse Datalayer - </a></li>
            </ul>
         </div>
      </div>

      

      <div class="container_item_ content_panel first_graph">
         <h2 style="width:400px;margin:5px 0px 10px 50px;">Analyse notes client par produits</h2>
         <label id="graph_option" for="datalayer_select">Variable à analyser avec les notes des clients</label>
         <span id='test_css'>
         <form class="select_variable" method="post">
         <?php include "load_data.php" ?>
            <label for="datalayer_select">Dimension: </label>
            <select name="search_dimension" id="datalayer_selected">
               <option value="variant">variant</option>
               <option value="name">nom</option>
               <option value="brand">marque</option>
               <option value="reduction">reduction</option>
               <option value="pageSubCategory_1">Sous-Catégorie 1</option>
               <option value="pageSubCategory_2">Sous-Catégorie 2</option>
               <option value="pageSubCategory_3">Sous-Catégorie 3</option>
               <option value="pageSubCategory_4">Sous-Catégorie 4</option>
            </select>
            <label for="datalayer_select"> | Metric: </label>
            <select name="search_metric" id="datalayer_select">
               <option value="price">price</option>
               <option value="overallRating">overallRating</option>
               <option value="overallRatingCount">overallRatingCount</option>
            </select>
            <label for="datalayer_select"> | Order: </label>
            <select name="search_option_desc" id="datalayer_select">
               <option value="DESC">Desc</option>
               <option value="ASC">Asc</option>
            </select>
            <select name="search_option_select_desc" id="datalayer_select">
               <option value="AVG(price)">price</option>
               <option value="AVG(overallRating)">overallRating</option>
               <option value="SUM(overallRatingCount)">overallRatingCount</option>
            </select>
            
            <button name="generate" class="change_variable" id="Select">Change Variables</button>
         </form>
         <div class="Selector">
            <button name="generator" id="Selector">Generate</button>
            <button name="generator_pie" id="extract_csv">Extract</button>
         </div>
         </span>         
         <div id="container_chart"></div>
      </div>



      <div class="container_item_ content_panel graph">
         <h2 style="width:400px;margin:5px 0px 10px 50px;">Répartition des dimensions</h2>
         <label id="graph_option" for="datalayer_select">Variable à analyser avec les notes des clients</label>
         <form class="select_variable" method="post">
         <?php include "load_data_pie.php" ?>
            <label for="datalayer_select">Dimension: </label>
            <select name="search_dimension_pie" id="datalayer_select">
               <option value="variant">variant</option>
               <option value="brand">marque</option>
               <option value="reduction">reduction</option>
               <option value="pageSubCategory_1">Sous-Catégorie 1</option>
               <option value="pageSubCategory_2">Sous-Catégorie 2</option>
               <option value="pageSubCategory_3">Sous-Catégorie 3</option>
            </select>
            <label>| Seuil pour variable autre: </label>
            <input type="number" name="seuil" placeholder="default 5" required min="1" max="100" id="myPercent" size="30"/>
            <button name="generator_pie_variable" class="change_variable" id="generator_pie_variable">Change Variables</button>
         </form>  
         <div class="Selector">
            <button name="generator_pie" id="generator_pie">Generate</button>
            <button name="generator" id="extract_pie_csv">Extract</button>
         </div>
         <div id="container_pie"><div>
      </div>



   </body>

   <?php include 'chart.php';?>

      
</html>