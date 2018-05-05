<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/datepicker.css" />
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
    #success-feedback,#error-feedback,#success-remarks,#error-remarks{
        display: none;
    }

    .table-borderd, .table-borderd tr th, .table-borderd tr td{
        border: 1px solid #ccc;
    }
    .td-ce{
        text-align: center !important;
    }
    .txt-cen{
        text-align: center;
    }
    .datepicker.dropdown-menu{
        z-index: 100000 !important;
    }

    .d-t{
        display: table;
        width: 100%;
    }
    .d-tr{
        display: table-row;
    }
    .d-td{
        display: table-cell;
    }
    .d-n{
        display: none;
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
                $this->load->view('./comman/searchfilter');
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
        <?php  $this->load->view('./user/data-table'); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('./user/feedback_model_view',array("emplist" => $emplist)); ?>
<?php $this->load->view('./user/remarks_model_view'); ?>
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


 var callURL = <?= "'".base_url().midurl()."feedback/adduserfeedback'" ?>;

 $("#addUserFeedback").on('submit',function(e){
     e.preventDefault();
     // $('#loading-wrp').show();
     var frmData = $("#addUserFeedback").serializeJSON();
     // console.log(frmData);
     $.ajax({
         url: callURL,
         type: 'post',
         dataType: 'json',
         data: frmData,
         success: function (res) {
             console.log(res.status);
             if(res.status == 1)
             {
                 $('#success-feedback').show();
                 $('#success-message-feedback').html('');
                 $('#success-message-feedback').append(res.message);
                 // $('#loading-wrp').fadeOut("slow");
             }else{
                 $('#success').hide();
             }

             if(res.status == 0)
             {
                 $('#error-feedback').show();
                 $('#error-message-feedback').html('');
                 $('#error-message-feedback').append(res.message);
                 // $('#loading-wrp').fadeOut("slow");
             }else{
                 $('#error').hide();
             }
         }

     });

 });

 $(document.body).on('click','.feedbackModel',function(e) {
     e.preventDefault();
     var userid = $(this).attr("data-id")

     var username = $(this).attr("data-name")

    getUserFeedback(username,userid);

 });

 function getUserFeedback(username,userid)
 {
     $("#user-name-feedback-model").html(username);
     $("#down-excel-btn").html("");
     var url = <?php echo "'".base_url().midurl()."feedback/GetUserFeedbackList"."'"; ?>;

     $.post(url, {userid:userid},
         function(returnedData){
            // console.log(returnedData);
             data = JSON.parse(returnedData);
             var htmlText = "";
             var feedbackData = "";
             var rowdata = "";
             if(data.length > 0)
             {

                 var btnUrl = '<?= base_url().midurl()."feedback/downloadfeedbackexcel/"; ?>'+userid+'/'+username;
                 var excelBtn = '<a href="'+btnUrl+'" class="btn btn-mini btn-info pull-right m-r-7" ><i class="icon-table"></i> Download '+username+' Feedback In Excel Report</a>';
                 $("#down-excel-btn").html(excelBtn);
                 for(var key = 0; key < data.length; key++) {
                     rowdata = data[key];
                     var count = parseInt(key)+parseInt(1) ;
                     var orderid = "";
                     if(rowdata['orderid'] == null)
                     {
                         orderid = "NA";
                     }else{
                         orderid = rowdata['orderid'];
                     }
                     var delLink = "<a style='color:red' href='javascript:void(0)' onclick='deleteUserFeedback(this);' data-uname='"+username+"' data-uid='"+userid+"' data-fid='"+rowdata['id']+"' >Delete</a>";
                     feedbackData = feedbackData+"<tr>" +
                         "<td class='td-ce' >"+count+"</td>" +
                         "<td class='td-ce' >"+orderid+"</td>" +
                         "<td class='td-ce' >"+rowdata['date']+"</td>" +
                         "<td class='td-ce' >"+rowdata['serviceexperience']+"</td>" +
                         "<td class='td-ce' >"+rowdata['customerservicerepresentative']+"</td>" +
                         "<td class='td-ce' >"+rowdata['pickupdeliveryrating']+"</td>" +
                         "<td class='td-ce' >"+rowdata['recommendtofriend']+"</td>" +
                         "<td class='td-ce' >"+rowdata['empName']+"</td>" +
                         "<td>"+rowdata['feedback']+"</td>" +
                         "<td>"+delLink+"</td>" +
                         "</tr>";
                 }
                 htmlText = "<table class='table table-borderd' >" +
                     "<tr>" +
                     "<th>#</th>" +
                     "<th>Order ID</th>" +
                     "<th>Feedback Taken Date</th>" +
                     "<th>Service Experience</th>" +
                     "<th>Customer Service Representative</th>" +
                     "<th>Pickup Delivery Rating</th>" +
                     "<th>Recommend To Friend</th>" +
                     "<th>Feedback Taken By</th>" +
                     "<th>Feedback</th>" +
                     "<th></th>" +
                     "</tr>" +feedbackData+
                     "</table>";
             }else
             {
                 htmlText = "<h4 class='txt-cen' >No Feedback Found!</h4>";
             }


             $('#user-feedback-table').html(htmlText);


         }).fail(function(){
         console.log("error");
     });
 }

 function  deleteUserFeedback(info)
 {
     var uname = $(info).attr('data-uname');
     var uid = $(info).attr('data-uid');
     var id = $(info).attr('data-fid');

     var url = <?php echo "'".base_url().midurl()."feedback/deleteUserFeedback"."'"; ?>;

     $.post(url, {id:id},
         function(returnedData){
             // console.log(returnedData);
             data = JSON.parse(returnedData);
             if(data.status)
             {
                 getUserFeedback(uname,uid);
             }


         }).fail(function(){
         console.log("error");
     });
 }

 $(document.body).on('click','.addfeedbackModel',function(e) {
     e.preventDefault();

     $("#success-feedback").hide();
     $("#error-feedback").hide();

     $("#feedback").val("");

     var userid = $(this).attr("data-id")
     $("#useridfeedback").val(userid);

     var username = $(this).attr("data-name")
     console.log('User Name:'+username);
     $("#feedback-username").val(username);

     $("#user-name-feedback").html(username);

     var url = <?php echo "'".base_url().midurl()."order/GetUserOrderListOptions"."'"; ?>;
     $("#orderid").find('option').remove();
     $.post(url, {userid:userid},
         function(returnedData){

             returnedData = JSON.parse(returnedData);
             var htmlText = "";
             if(returnedData != null ){
                 for(var key = 0; key < returnedData.length; key++)
                 {
                     var name =  returnedData[key]["name"];
                     var value =  returnedData[key]["id"];

                     htmlText += '<option value="'+value+'" >'+name+'</option>';
                 }
             }else{
                 htmlText += '<option value="" >Select Order Id</option>';
             }
             $("#orderid").append(htmlText);
         }).fail(function(){
         console.log("error");
     });
 });

 /**************** User Remarks Script Start Here **************/
 var callURL2 = <?= "'".base_url().midurl()."user/adduserremarks'" ?>;

 $("#addUserRemarks").on('submit',function(e){
     e.preventDefault();
     // $('#loading-wrp').show();
     var frmData = $("#addUserRemarks").serializeJSON();
     // console.log(frmData);
     $.ajax({
         url: callURL2,
         type: 'post',
         dataType: 'json',
         data: frmData,
         success: function (res) {
             console.log(res.status);
             if(res.status == 1)
             {
                 $('#success-remarks').show();
                 $('#success-message-remarks').html('');
                 $('#success-message-remarks').append(res.message);
                 // $('#loading-wrp').fadeOut("slow");
             }else{
                 $('#success').hide();
             }

             if(res.status == 0)
             {
                 $('#error-remarks').show();
                 $('#error-message-remarks').html('');
                 $('#error-message-remarks').append(res.message);
                 // $('#loading-wrp').fadeOut("slow");
             }else{
                 $('#error').hide();
             }
         }

     });

 });

 $(document.body).on('click','.remarksModel',function(e) {
     e.preventDefault();
     var userid = $(this).attr("data-id")

     var username = $(this).attr("data-name")

     getUserRemarks(username,userid);

 });

 function getUserRemarks(username,userid)
 {
     $("#user-name-remarks-model").html(username);

     var url = <?php echo "'".base_url().midurl()."user/GetUserRemarksList"."'"; ?>;

     $.post(url, {userid:userid},
         function(returnedData){
             console.log(returnedData);
             data = JSON.parse(returnedData);
             var htmlText = "";
             var feedbackData = "";
             var rowdata = "";

             if(data.length > 0 && data != "" )
             {
                 for(var key = 0; key < data.length; key++) {
                     rowdata = data[key];
                     var count = parseInt(key)+parseInt(1) ;
                     var orderid = "";
                     if(rowdata['orderid'] == null)
                     {
                         orderid = "NA";
                     }else{
                         orderid = rowdata['orderid'];
                     }

                     var rid = rowdata['id'];

                     var delLink = "<a style='color:red' onclick='deleteUserRemarks(this);' data-uname='"+username+"' data-uid='"+userid+"' data-rid='"+rowdata['id']+"'  href='javascript:void(0)' class='u-re-delete' >Delete</a>";
                     feedbackData = feedbackData+"<tr>" +
                         "<td class='td-ce' >"+count+"</td>" +
                         "<td class='td-ce' >"+rowdata['date']+"</td>" +
                         "<td class='td-ce' >"+rowdata['empName']+"</td>" +
                         "<td>"+rowdata['remarks']+"</td>" +
                         "<td>"+delLink+"</td>" +
                         "</tr>";
                 }
                 htmlText = "<table class='table table-borderd' >" +
                     "<tr>" +
                     "<th>#</th>" +
                     "<th>Date</th>" +
                     "<th>Remarks Add By</th>" +
                     "<th>Remarks</th>" +
                     "<th></th>" +
                     "</tr>" +feedbackData+
                     "</table>";
             }
             else
             {
                 htmlText = "<h4 class='txt-cen' >No Remarks Found!</h4>";
             }


             $('#user-remarks-table').html(htmlText);


         }).fail(function(){
         console.log("error");
     });
 }

 function  deleteUserRemarks(info)
 {
    var uname = $(info).attr('data-uname');
    var uid = $(info).attr('data-uid');
    var rid = $(info).attr('data-rid');

     var url = <?php echo "'".base_url().midurl()."user/deleteUserRemarks"."'"; ?>;

     $.post(url, {id:rid},
         function(returnedData){
             console.log(returnedData);
             data = JSON.parse(returnedData);
             if(data.status)
             {
                 getUserRemarks(uname,uid);
             }


         }).fail(function(){
         console.log("error");
     });
 }

 $(document.body).on('click','.addremarksModel',function(e) {
     e.preventDefault();

     $("#success-remarks").hide();
     $("#error-remarks").hide();

     $("#remarks").val("");

     var userid = $(this).attr("data-id")
     $("#useridremarks").val(userid);

     var username = $(this).attr("data-name")
     // console.log(username);
     $("#user-name-remarks").html(username);
     // $("#user-name-remarks").append(username);


     // $("#feedback-username").val(username);

     var url = <?php echo "'".base_url().midurl()."order/GetUserOrderListOptions"."'"; ?>;
     $("#orderidremark").find('option').remove();
     $.post(url, {userid:userid},
         function(returnedData) {

             returnedData = JSON.parse(returnedData);
             var htmlText = "";
             if(returnedData != null){
             for (var key = 0; key < returnedData.length; key++) {
                 var name = returnedData[key]["name"];
                 var value = returnedData[key]["id"];

                 htmlText += '<option value="' + value + '" >' + name + '</option>';
             }
             }
             else{
                     htmlText += '<option value="" >Select Order Id</option>';
             }
             $("#orderidremark").append(htmlText);
         }).fail(function(){
         console.log("error");
     });
 });
 /**************** User Remarks Script End Here **************/


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
<script>
    var d = new Date();

    var curr_date = d.getDate();
    if(curr_date < 10)
    {
        curr_date = "0"+d.getDate();
    }
    var curr_month = d.getMonth();
    if(curr_month < 10)
    {
        curr_month = "0"+d.getMonth();
    }
    var curr_year = d.getFullYear();
    var fDate = curr_month + "/" + curr_date + "/" + curr_year;

    $('#feedbacktekandate').val(fDate);

    $('#feedbacktekandate').datepicker();
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
