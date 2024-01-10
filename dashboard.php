<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');
  $_SESSION['table'] = 'users';
  $show_table = 'users';
  $users = $_SESSION['user'];

  // get graph data - purchase order by status
  include('database/po_status_pie_graph.php');

  // get graph data - supplier product count
  include('database/supplier_product_bar_graph.php');

  // get line graph data - delivery history per day
  include('database/delivery_history.php');



 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/css/all.css">
  </head>
  <body id="dashboard_page">
    <div id="main_container">

      <?php include('partials/app-sidebar.php') ?>

      <div class="content_container" id="content_container">

        <?php include('partials/app-topnav.php') ?>
        <div class="content">
          <div class="content_main dashboard_content_main">

            <div class="col-50">
              <figure class="highcharts-figure">
                <div id="container"></div>
                <p class="highcharts-description">
                    Here is the breakdown of the purchase order by the status.
                </p>
              </figure>
            </div>
            <div class="col-50">
              <figure class="highcharts-figure">
                <div id="containerBarChart"></div>
                <p class="highcharts-description">
                    Here is the breakdown of the products assigned to suppliers.
                </p>
              </figure>
            </div>

          </div>

          <div>
            <div id="deliveryHistory">

            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript">
      let graphData = <?= json_encode($results) ?>;
      Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Purchase Orders Status'
        },
        tooltip: {
            // valueSuffix: '%'
            pointFormatter: function(){
              var point = this,
                  series = point.series;
              return `<b>${point.name}</b>: ${point.y}`
            }
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.y}'
                }
                // [{
                //     enabled: true,
                //     distance: 20
                // }, {
                //     enabled: true,
                //     distance: -40,
                //     // format: '{point.y}',
                //     style: {
                //         // fontSize: '1.2em',
                //         // textOutline: 'none',
                //         // opacity: 0.7
                //     },
                //     filter: {
                //         operator: '>',
                //         property: 'percentage',
                //         value: 10
                //     }
                // }]
            }
        },
        series: [
            {
                name: 'Status',
                colorByPoint: true,
                data: graphData
            }
        ]
      });

      let barGraphData = <?= json_encode($bar_chart_data) ?>;
      let barGraphCategories = <?= json_encode($categories) ?>;
      Highcharts.chart('containerBarChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Product Count Assigned To Supplier',
            align: 'left'
        },
        xAxis: {
            categories: barGraphCategories,
            crosshair: true,
            accessibility: {
                description: 'Countries'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Product Count'
            }
        },
        tooltip: {
            // valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Supplier',
                data: barGraphData
            }
          ]
        });

      let lineCategories = <?= json_encode($line_categories) ?>;
      let lineData = <?= json_encode($line_data) ?>;
      console.log(lineCategories);
      console.log(lineData);
      Highcharts.chart('deliveryHistory', {
        chart: {
          type: 'spline'
        },
        title: {
            text: 'Delivery History',
            align: 'left'
        },

        yAxis: {
            title: {
                text: 'Product Delivered'
            }
        },

        xAxis: {
            categories: lineCategories
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
            }
        },

        series: [{
            name: 'Product Delivered',
            data: lineData
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

      });




    </script>

  </body>
  <script type="text/javascript" src="script/index.js"></script>

</html>
