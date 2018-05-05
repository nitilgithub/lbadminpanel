<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ajaxpagination.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ribbon.css" />
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
        <?php  $this->load->view('./comman/invoice/dashboard-invoice'); ?>
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
 ?>
 <script>
 	//********** Search Function *********//
  function searchFilter(page_num) {
	    page_num = page_num?page_num:0;
	    var keywords = $('#keywords').val();
	    var filter = $('#filter').val();
	    $.ajax({
	        type: 'POST',
	        url: '<?php echo base_url().$this->midUrl.$mbase.$ajaxfunc; ?>/'+page_num,
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
 ?>
<scriprt>

<!--</scriprt><script type="text/javascript">-->
<!--    $(function() {-->
<!---->
<!--        $('#daterange').daterangepicker({-->
<!--            autoUpdateInput: false,-->
<!--            locale: {-->
<!--                cancelLabel: 'Clear'-->
<!--            }-->
<!--        });-->
<!---->
<!--        $('#daterange').on('apply.daterangepicker', function(ev, picker) {-->
<!--            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));-->
<!--        });-->
<!---->
<!--        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {-->
<!--            $(this).val('');-->
<!--        });-->
<!---->
<!--    });-->
<!--</script>-->

</body>
</html>
