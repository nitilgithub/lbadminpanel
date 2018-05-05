<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ajaxpagination.css" />
<style>
    #frmsearch label{
        float: left;
        line-height: 27px;
        font-weight: bold;
        margin-right: 3px;
    }
    #frmsearch input[type='text']{
        float: left;
        margin-right: 10px;
    }
    .center{
        text-align: center !important;
    }
</style>
</head>
<body>
<?php 
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
	if(!isset($filter))
    {
        $filter = "";
    }
    if(!isset($ajaxfunc))
    {
        $ajaxfunc = "ajaxPaginationData";
    }
    if(!isset($datefilter) || $datefilter != true)
    {
        $datefilter = false;
    }
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
  	   if(!empty($add))
	   {
  	   ?>
       <div class="m-b-10 pull-right" >
       	<a href="<?= base_url().midurl().$add['url']; ?>" class="btn btn-info m-b-10" ><?= $add['lable'] ?></a>
       </div>
		<?php
	   }
		?>  
		<?php
		 	if(!empty($pagination) && $pagination == TRUE)
			{
				if(empty($mbase))
				{
					$mbase = null;
				}
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
        ?>
        <!-- Search Controls End -->
        <?php
			}
		 ?>
          <?php
            if($datefilter)
            {
          ?>
          <div class="post-search-panel">
              <form name="frmsearch" id="frmsearch" method="post" >
              <label for="fromdate" >From Date: </label>
              <input type="text" id="fromdate" name="fromdate" placeholder="Select From Date" />
              <label for="todate" >To Date: </label>
              <input type="text" id="todate" name="fromdate" placeholder="Select To Date" />
              <label for="todate" >Order Type: </label>
              <select class="select" id="type" >
                  <option value="laundry">Laundry</option>
                  <option value="dryclean">Dryclean</option>
                  <option selected value="all">All</option>
              </select>
              <input type="button" onclick="searchFilter();" value="Search" class="btn btn-success m-b-10" id="btnsearch" name="btnsearch" >
              <input type="button" onclick="window.location.href=''" value="Reset" class="btn btn-danger m-b-10" id="btnreset" name="btnreset" >
              </form>
          </div>
          <?php
            }
          ?>
        <div id="view-data" >
        <?php  $this->load->view('./rate/ratelist'); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>

 <script>
 //********** Comfirme Deletion *********//
 	function ConfirmDelete()
	{
	  	if (confirm("Are you sure you want to delete?") == true) {
        	return true;
	    } else {
	        return false;
	    }
	}
 </script>

 <?php
 	if(!empty($pagination) && $pagination == TRUE)
	{
		if(empty($mbase))
		{
			$mbase = null;
		}
		if($datefilter)
        {
 ?>
 <script>
 	//********** This Method Working On Page Link Click With Date Filter *********//
  function searchFilter(page_num) {
	    page_num = page_num?page_num:0;
	    var fromdate = $('#fromdate').val();
	    var todate = $('#todate').val();
	    var type = $('#type').val();
	    $.ajax({
	        type: 'POST',
	        url: '<?php echo base_url().midurl().$mbase.$ajaxfunc; ?>/'+page_num,
	        data:'page='+page_num+'&fromdate='+fromdate+'&todate='+todate+'&type='+type,
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

 <?php
        }else{
?>
<script>
    //********** This Method Working On Page Link Click With Search Filter*********//
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
<?php
        }
	}
 ?>
<scriprt>
<?php
    if($datefilter)
    {
?>
<script>
    $('#fromdate').datepicker();
    $('#todate').datepicker();
</script>
<?php
    }
?>

</body>
</html>
