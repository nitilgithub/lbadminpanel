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
                <?php

                ?>
                <?php

                ?>
                <!-- Search Controls Start -->
                <?php
                if(!empty($filter)) {
                    ?>
                    <div class="post-search-panel">
                        <select class="select" id="filter" >
                            <option value="">Search By</option>
                            <?php
                            foreach ($filter['search_option'] as $opt)
                            {

                                ?>
                                <option value="<?php echo $opt['value']; ?>"><?php echo $opt['label']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!--            <input type="text" id="daterange" placeholder="Select Two Dates --><?//= !empty($pageheading) ? $pageheading : '' ?><!-- ..." onkeyup="searchFilter()"/>-->
                        <input type="text" id="keywords" placeholder="Search Here <?= !empty($pageheading) ? $pageheading : '' ?> ..." onkeyup="searchFilter()"/>
                        <input type="button" onclick="searchFilter();" value="Search" class="btn btn-success m-b-10" id="btnsearch" name="btnsearch" >
                    </div>
                    <?php
                }

                if(isset($filter2) && !empty($filter2))
                {
                    foreach ($filter2 as $fill)
                    {
                        if($fill->type == 'datepicker')
                        {
                            ?>
                            <input type="text" data-date="01-02-2013" data-date-format="dd-mm-yyyy" value="01-02-2013" class="datepicker">
                            <?php
                        }
                    }
                    ?>
                    <input type="button" onclick="searchFilter();" value="Search" class="btn btn-success m-b-10" id="btnsearch" name="btnsearch" >
                    <?php
                }
                ?>
                <!-- Search Controls End -->
                <?php

                ?>
                <div id="view-data" >
                    <div id="chart_div" style="width: 1100px; height: 600px;"></div></div>
                <?php
                //        $this->load->view('./chart/data-table');
                ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    //********** Search Function *********//
    function searchFilter(page_num) {
        page_num = page_num?page_num:0;
        var keywords = $('#keywords').val();
        var filter = $('#filter').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url().midurl().$mbase.$ajaxfunc; ?>/'+page_num,
            data:'page='+page_num+'&keywords='+keywords+'&filter='+filter,
            beforeSend: function () {
                $('#loading-wrp').show();
            },
            success: function (html) {
                $('#view-data').html(html);
                $('#loading-wrp').fadeOut("slow");
            }
        });
    }
</script>
<script type="text/javascript">

    // Load the Visualization API and the piechart package.

    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = $.ajax({
            url: "https://www.laundrybucket.co.in/lb-admin/webservice_subscription_piechart.php",
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
