<?php 
$conPrefix = '';
include 'includes/session.php'; 
if(strtolower($_SESSION['userType']) == 'sales executive' || strtolower($_SESSION['userType']) == "shop executive"){
  include 'timezone.php'; 
  $today = date('Y-m-d');
  $year = date('Y');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }
  
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
    <style>
        #customers {font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;}
        #customers td, #customers th {border: 1px solid #ddd;padding: 8.3px;color: white;font-weight: 800;}
        #customers tr:nth-child(odd){background-color: #a4a4a4;}
        #customers tr:nth-child(even){background-color: #868686;}
        #customers tr:hover {background-color: #c1ab32;}
        #customers th {padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #4CAF50;color: white;}
        .container-fluid { margin-top: 100px}
        .bsp_row-underline {content: "";display: block;border-bottom: 2px solid #3798db; margin-bottom: 20px}
        .bsp_deal-text {margin-left: -10px;font-size: 25px;margin-bottom: 10px;color: #000;font-weight: 700}
        .bsp_view-all {margin-right: -10px;font-size: 14px; margin-top: 10px}
        .bsp_image {width: 100% !important;height: 95px !important;border: 1px solid #d9d7d7;border-radius: 5px;}
        .bsp_big-image { box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0);border-radius: 5px; margin-top: 0px;}
        .bsp_padding-0 { padding: 3px}
        .bsp_bbb_item { padding: 5px;background-color: #fff;box-shadow: 1px 2px 2px 0px #3798db ;border-radius: 5px;}
        .bsp_bbb_item:hover { padding: 5px;background-color: #d5d5d5;box-shadow: 1px 2px 2px 0px #b5b5b5 ;border-radius: 5px;}
        .bsp_card-text { color: blue}
        .textBox {width: 116px;height: 30px;overflow: hidden;padding: 5px;position: relative;color: #3798db;font-weight: 800; }
        .textBox1 {width: 116px;   height: 28px; overflow: hidden; padding: 4px;   position: relative; color: #30751d;}
        .textBox span, .textBox1 span{position: absolute;white-space: nowrap;transform: translateX(0);transition: 2s;}
        .textBox:hover span {transform: translateX(calc(125px - 120%));}
        .textBox1:hover span { transform: translateX(calc(125px - 120%)); }
        
    </style>
<div class="wrapper">

  	<?php include 'includes/navbar.php'; ?>
  	<?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard :<span style="color: gray;"> <?php echo $user['fname'].' '.$user['lname']; ?> - <?php echo $_SESSION['userType']; ?></span>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                     <?php 
                        $sql = "SELECT id,brandName,brand_logo FROM tbl_brands WHERE status='Active' and deleted='No' ORDER BY brandName ASC";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $id = $row['id'];
                            $brandName = $row['brandName'];
                            $brandLogo = $row['brand_logo'];
                            if($brandLogo == '' || $brandLogo == ' '){
                                $productImage = "images/broken_image.png";
                            }else{
                                $productImage = "images/brand/thumb".$brandLogo;
                            }
                            echo "<div class='col-md-2 col-xs-3 bsp_padding-0'>
                                    <div class='bsp_bbb_item'>
                                        <ul class='list-group' style='margin-bottom: 1%;'>
                                            <div style='text-align: center;border: 1px solid #e8e8e8;;width: 100%;100px;'>
                                                <img src='$productImage' style='width:100%; height:100px;'>
                                            </div>
                                            <a href='orderPanel.php?id=".$id."'><li class='' style='text-align: center;list-style: none;'>$brandName</li></a>
                                        </ul>
                                    </div>
                            </div>";
                        }
                        ?>
            </div>        
        
    </section>
    </div>
      <!-- right col -->
    
  	<?php 
  	include 'includes/footer.php'; 
  	}else{
        header('location:user-home.php');
    }
  	?>

</div>
<!-- ./wrapper -->

<!-- Chart Data -->
<?php
  $and = 'AND YEAR(distribute_dateTime) = '.$year;
  $months = array();
  $ontime = array();
  $late = array();
  for( $m = 1; $m <= 12; $m++ ) {
    //$sql = "SELECT * FROM tbl_users WHERE MONTH(distribute_dateTime) = '$m' AND re_status !='' $and";
    //$oquery = $conn->query($sql);
    //array_push($ontime, $oquery->num_rows);

    //$sql = "SELECT * FROM tbl_users WHERE MONTH(distribute_dateTime) = '$m' AND re_status ='' $and";
    //$lquery = $conn->query($sql);
    //array_push($late, $lquery->num_rows);

    //$num = str_pad( $m, 2, 0, STR_PAD_LEFT );
   // $month =  date('M', mktime(0, 0, 0, $m, 1));
    //array_push($months, $month);
  }

  $months = json_encode($months);
  $late = json_encode($late);
  $ontime = json_encode($ontime);

?>
<!-- End Chart Data -->
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChart = new Chart(barChartCanvas)
  var barChartData = {
    labels  : <?php echo $months; ?>,
    datasets: [
      {
        label               : 'Distribute',
        fillColor           : 'rgba(210, 214, 222, 1)',
        strokeColor         : 'rgba(210, 214, 222, 1)',
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : <?php echo $late; ?>
      },
      {
        label               : 'Adjustment',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : <?php echo $ontime; ?>
      }
    ]
  }
  barChartData.datasets[1].fillColor   = '#00a65a'
  barChartData.datasets[1].strokeColor = '#00a65a'
  barChartData.datasets[1].pointColor  = '#00a65a'
  var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 5,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
  }

  barChartOptions.datasetFill = false
  var myChart = barChart.Bar(barChartData, barChartOptions)
  document.getElementById('legend').innerHTML = myChart.generateLegend();
});
</script>
<script>
$(function(){
  $('#select_year').change(function(){
    window.location.href = 'home.php?year='+$(this).val();
  });
});
</script>
</body>
</html>
