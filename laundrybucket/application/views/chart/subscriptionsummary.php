<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ajaxpagination.css" />
</head>
<body>
<?php 
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
?>

<div id="content">
  <div id="content-header">
   <?php  $this->load->view('./comman/breadcrumb'); ?>
    <h1><?= !empty($pageheading) ? $pageheading : '' ?></h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
       <div class="m-b" ></div>
        <div id="view-data" >
            <div id="chart_div" style="width: 1100px; height: 600px;"></div></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // Load the Visualization API and the piechart package.

    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = $.ajax({
            // url: "https://www.laundrybucket.co.in/lb-admin/webservice_subscription_piechart.php",
            url: '<?php echo base_url().midurl().$mbase ?>getSubscriptionSummaryData',
            dataType: "json",
            async: false
        }).responseText;


        var options = {
            title: 'Subcription Summary',
            is3D: true,
        };

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

</script>
</body>
</html>
