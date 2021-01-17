<script> 

window.onload = function test() {

  document.getElementById('Selector').addEventListener('click', function() {
        var data = <?php echo json_encode($data); ?> ;
        var dimension = [];
        var overallRating = [];
        var overallRatingCount = [];
        var price = [];
        for (var i in data) {
            dimension.push(data[i]["<?php echo $Vizualisation_rating ?>"]);
            overallRating.push(data[i]['AVG(overallRating)']);
            overallRatingCount.push(data[i]['SUM(overallRatingCount)']);
            price.push(data[i]['AVG(price)']);
        }

        var chartdata = {
            labels: dimension,
            datasets: [{
                label: 'test graphiques',
                backgroundColor: '#5d7bc2',
                borderColor: '#EEEEEE',
                hoverBackgroundColor: '#fff',
                hoverBorderColor: '#GGGGGG',
                data: <?php echo $search_metric ?> ,
                order: 0,
                fill: false,
                radius: 0
            }]
        };

        document.getElementById('container_chart').innerHTML = "<canvas id='graphCanvas'></canvas>";


        var graphTarget = document.getElementById('graphCanvas')

        var options = {
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }

        var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: options
        });
    })

    document.getElementById('extract_csv').addEventListener('click', function() {

        var data = <?php echo json_encode($data);?>;

        let csvContent = "data:text/csv;charset=utf-8,";

        for (var i in data){
          var csvrow = data[i]["<?php echo $Vizualisation_rating ?>"]+";";
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
        link.setAttribute("download","chart_data.csv");
        document.body.appendChild(link);
        link.click();

    })






    document.getElementById('generator_pie').addEventListener('click', function() {
        var data = <?php echo json_encode($data_pie) ?>;
        var seuil = <?php echo $seuil;?>;
        var dimension = [];
        var count_dimension = [];
        var other_percent = 0;
        for (var i in data) {
            if (data[i][0] > seuil){
              dimension.push(data[i]["<?php echo $share_pie_dimension;?>"]);
              count_dimension.push(parseFloat(data[i][0]).toFixed(2));
            } else { 
              other_percent += parseFloat(data[i][0]);
            }
        }
        if (other_percent != 0){
          dimension.push("Other");
          count_dimension.push(other_percent.toFixed(2));
        } 

        var color = []

      function getRandomColor() {
          var letters = '0123456789ABCDEF';
          var color = '#';
          for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
          }
          return color;
        }

        for (var i in dimension){
            color.push(getRandomColor());
        }

        
        document.getElementById('container_pie').innerHTML = "<canvas id='graphCanvas_pie'></canvas>";

        var graphTarget = document.getElementById('graphCanvas_pie').getContext("2d");

        var myChart = new Chart(graphTarget, {
        type: 'pie',
        data: {
          labels: dimension,
          datasets: [{
            backgroundColor: color,
            data: count_dimension
          }]
        }
      });

    })




    document.getElementById('extract_pie_csv').addEventListener('click', function() {

      var data = <?php echo json_encode($data_pie);?>;

      let csvContent = "data:text/csv;charset=utf-8,";
      for (var i in data){
        var csvrow = "";
        for (let e=0;e<2;e++){
            csvrow += data[i][e]+";";
        }
        csvrow = csvrow.slice(0,-1);
        csvrow = csvrow + "\r\n";
        csvContent += csvrow;
      }
      var encodeUri = encodeURI(csvContent);
      var link = document.createElement("a");
      link.setAttribute("href",encodeUri);
      link.setAttribute("download","chart_pie_data.csv");
      document.body.appendChild(link);
      link.click();

      })

}
</script>