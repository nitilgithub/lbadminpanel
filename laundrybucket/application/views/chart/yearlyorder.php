<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('./comman/head_css'); ?>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/ajaxpagination.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/chart/css/morris.css" />
    <style>
        #bar-chart1{
            background: #ffffff;
            height: 350px;
        }
    </style>
</head>
<body>
<?php
$this->load->view('./comman/header');
$this->load->view('./comman/sidebar_menu');

$selyaer = "2018";
$selservice = 1;
?>

<div id="content">
    <div id="content-header">
        <?php  $this->load->view('./comman/breadcrumb'); ?>
        <h1><?= !empty($pageheading) ? $pageheading : '' ?> <span id="year-head" ></span></h1>
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
                        <?php

                            foreach ($filter as $key => $fil)
                            {
                        ?>
                        <select onchange="getGraphData();" class="select" id="<?= $key ?>" >
                            <?php
                            foreach ($fil as $opt)
                            {
                                if(is_object($opt))
                                {
                                    $opt = (array) $opt;
                                }
                                ?>
                                <option <?= $opt['id'] == $selyaer || $opt['id'] == $selservice ? 'selected' : '' ?>  value="<?php echo !empty($opt['id']) ? $opt['id'] : ''; ?>"><?php echo $opt['name']; ?></option>
                                <?php
                            }

                            ?>
                        </select>
                        <?php
                            }
                        ?>
                        <input type="button" onclick="getGraphData();" value="Submit" class="btn btn-success m-b-10" id="btnsearch" name="btnsearch" >
                    </div>
                    <?php
                }

                $val=2017;
                ?>
                <!-- Search Controls End -->
                <div id="view-data" >
                    <div class="chart_div" id="bar-chart1" ></div>
                <?php
                ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
<script src="https://laundrybucket.co.in/lb-admin/js/jquery.blockUI.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?= base_url(); ?>assets/chart/js/morris/morris.min.js" type="text/javascript"></script>
<script src='<?= base_url(); ?>assets/chart/js/fastclick/fastclick.min.js'></script>
<?php $rw = "Laundry" ?>
<script>
    getGraphData();
    //********** Get Graph Data Function *********//
    function getGraphData() {
        $('#bar-chart1').html('');
        $('#year-head').html('');
        var year = $('#year').val();
        var service = $('#service').val();
        var lab = $('#service option:selected').text();
        var headtxt = lab+' ( '+year+' )';

        $('#year-head').append(headtxt);
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url().midurl().$mbase ?>getYearlyOrderData?service='+service+'&year='+year,
            dataType: "json",
            beforeSend: function () {
                $('#loading-wrp').show();
            },
            success: function (data) {

                var bar = new Morris.Bar({
                    element: 'bar-chart1',
                    resize: true,
                    data: data,
                    barColors: ['#00a65a', '#f56954'],
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: [lab],
                    hideHover: 'auto'
                });

                $('#loading-wrp').fadeOut("slow");
            }
        });
    }
</script>
</body>
</html>
