<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-wysihtml5.css" />
<style>
    .row-fluid [class*="span"]{
        margin-left: 0;
    }
    .row-fluid [class*="span"]:nth-child(even){
        float: right;
    }
    .control-group.span6{
        width: 50%;
    }
    .control-group.span12{
        width: 100%;
    }
    .control-group .controls textarea{
        width: calc(100% - 30px) !important;
    }
    #success,#error{
        display: none;
    }
</style>
</head>
<body>

<?php 
	
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
	
 	if(empty($readonly))
	{
		$readonly = array();
	}

	$fileUpload = false;
	$datePicker = false;
?>

<div id="content">
<div id="content-header">
  <?php  $this->load->view('./comman/breadcrumb'); ?>
  <h1><?= $form['title']; ?></h1>
</div>

<div class="container-fluid">
  <hr>
    <!-- Success Alert Show Start Here  -->
    <div id="success" class="alert alert-success">
        <button class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong> <span id="success-message" ></span>
    </div>
    <!-- Success Alert Show End Here  -->

    <!-- Error Alert Show Start Here  -->
    <div id="error" class="alert alert-error">
        <button class="close" data-dismiss="alert">×</button>
        <strong>Error!</strong> <span id="error-message" ></span>
    </div>
    <!-- Error Alert Show End Here  -->
  <?php
  // echo json_encode($control['options']);
  ?>
  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?= $userInfo->UserFirstName." ".$userInfo->UserLastName ?>'s Place New Order</h5>
        </div>
        <div class="widget-content nopadding">
        	<?php
        		$actionUrl = null;
        		if(!empty($form['action']))
				{
					$actionUrl = base_url().midurl().$form['action'];
				}
				if(empty($form['id']))
                {
                    $form['id'] = $form['name'];
                }

        	?>
          <form enctype="multipart/form-data" name="<?= $form['name']; ?>"  id="<?= $form['id']; ?>" method="post" class="<?= $form['class']; ?>">
			<?php
      			if(!empty($dataid))
				{
					?>
					<input type="hidden" name="dataid" value="<?= $dataid; ?>" />
					<?php
				}
      		?>
            <?php
            if(isset($hiddencontrol) && !empty($hiddencontrol))
            {

               ?>
                    <input type="hidden" name="<?= $hiddencontrol['name']; ?>" id="<?= $hiddencontrol['id']; ?>" value="<?= $hiddencontrol['value'] ?>" >
              <?php

            }

            ?>

              <div class="row-fluid" >
          	<?php foreach($form['controles'] as $control) {
          		$cname= $control['name'];
          		if(empty($control['id']))
                {
                    $control['id'] = $cname;
                }
			 ?>

      		<div class="control-group <?= $control['con-group-class']  ?>">
      			<label for="<?= $cname; ?>" class="control-label"><?= $control['lable'] ?> :</label>
      			<div class="controls">
          	<?php
          		$type = $control['type'];
          	    if($type == 'select')
				{
				?>
                <select <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?>  name="<?= $cname; ?>" id="<?= $control['id']; ?>" class="<?= $control['class'] ?>"  >
                	<option value="" >Select <?= $control['lable'] ?></option>
                	<?php
                		foreach($control['options'] as $option)
						{
							?>
							<option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
							<?php
						}
                	?>
                </select>
				<?php
				}
                elseif($type == 'address')
                {
                    ?>
                    <div>
                    <select <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?>  name="<?= $cname; ?>" id="<?= $control['id']; ?>" class="<?= $control['class'] ?>"  >
                    <option value="" >Select <?= $control['lable'] ?></option>
                    <?php
                    foreach($control['options'] as $option)
                    {
                        ?>
                        <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                        <?php
                    }
                    ?>
                    </select>
                    <a href="#myModal" data-toggle="modal" >Add New</a>
                    </div>
                    <?php
                }
				elseif($type == 'toggle')
				{
				?>
				<label class="switch <?= $control['class'] ?>"   >
				  <input name="<?= $cname; ?>" id="<?= $control['id']; ?>"  <?= !empty($values) && $values->$cname == true ? 'checked' : ''; ?>  type="checkbox">
				  <span class="slider round"></span>
				</label>
				<?php
				}
				elseif($type == 'file')
				{
                    $fileUpload = true;
				?>
                    <div class="uploader" id="uniform-undefined">
                        <input type="hidden" id="filepath" name="<?= $cname; ?>" value="" >
                        <input type="file" style="opacity: 0;" name="file" <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="file"  class="<?= $control['class'] ?>" />
                        <span class="filename" id="filename" >No file selected</span>
                        <span class="action">Choose File</span>
                    </div>

				<?php
				}
				elseif($type == 'radio')
				{
				    $group = $control['group'];
				    if(!empty($group))
                    {
                        foreach ($group as $title => $value)
                        {
				?>
				<label>
                  <input type="radio"  name="<?= $cname; ?>" value="<?= $value ?>" />
                  <?= $title ?>
                </label>
				<?php
                        }
                    }
				}
				elseif($type == 'textarea')
				{	
				?>
				
				<textarea name="<?= $cname; ?>"  <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" class="<?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>" ><?= !empty($values) ? $values->$cname : ''; ?></textarea>
				<?php				
				}
				elseif ($type == 'date')
                {
                    $datePicker = true;
                ?>
                    <input type="text" name="<?= $cname; ?>" id="<?= $control['id']; ?>"  <?= in_array($cname, $readonly) ? 'readonly' : '' ?>  value="<?= !empty($values) ? $values->$cname : ''; ?>" class="datepicker span11">
                <?php
                }
				else {
          	?>	
                <input name="<?= $cname; ?>" <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="<?= $control['type']; ?>" class="<?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>" />
            <?php
             }
				echo "</div>  </div>";
				 }
			 ?>
            </div>
            <div class="row-fluid" >
            <div class="span12" >
            <div class="form-actions">
              <button type="submit" class="<?= $form['submit']['class'] ?>"><?= $form['submit']['lable'] ?></button>
            </div>
            </div>
            </div>
          </form>
            <!-- Add New Address Model Start Here -->
            <div id="myModal" class="modal hide">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h3>Add New User Address</h3>
                </div>
                <div class="modal-body">
                    <form method="post" id="addUserAddress" name="addUserAddress" >
                        <input type="hidden" name="UserId" value="<?= enc($userInfo->UserId) ?>" >
                        <textarea style="width: calc(100% - 15px);" name="Address" placeholder="Address" ></textarea>
                        <input type="submit" class="btn btn-success pull-right" value="Save" >
                    </form>
                </div>
            </div>
            <!-- Add New Address Model End Here -->
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
<!-- <script src="<?= base_url(); ?>assets/js/wysihtml5-0.3.0.js"></script>  -->

<!-- <script src="<?= base_url(); ?>assets/js/bootstrap-wysihtml5.js"></script>  -->
<script>

    var frmid = <?= $form['id'] ?>,callURL = <?= "'".$actionUrl."'" ?>;

    // console.log(frmid.id);
    // console.log(callURL);

    $("#"+frmid.id).on('submit',function(e){
        e.preventDefault();
        $('#loading-wrp').show();
        var frmData = $("#"+frmid.id).serializeJSON();
        // console.log(frmData);
        $.ajax({
            url: callURL,
            type: 'post',
            dataType: 'json',
            data: frmData,
            success: function (res) {
                // console.log(res.status);
                if(res.status == 1)
                {
                    $('#success').show();
                    $('#success-message').html('');
                    $('#success-message').append(res.message);
                    $('#loading-wrp').fadeOut("slow");
                }else{
                    $('#success').hide();
                }

                if(res.status == 0)
                {
                    $('#error').show();
                    $('#error-message').html('');
                    $('#error-message').append(res.message);
                    $('#loading-wrp').fadeOut("slow");
                }else{
                    $('#error').hide();
                }
            }

        });

    });



    $("#addUserAddress").on('submit',function(e){
        e.preventDefault();
        $('#loading-wrp').show();
        var frmData = $("#addUserAddress").serializeJSON();
        var URL = <?= "'".base_url().midurl()."order/addUserAddress"."'"; ?>;
        $.ajax({
            url: URL,
            type: 'post',
            dataType: 'json',
            data: frmData,
            success: function (res) {
                // console.log(res.status);
                if(res.status == 1)
                {
                    $('#success').show();
                    $('#success-message').html('');
                    $('#success-message').append(res.message);
                    $('#myModal').modal('hide');
                    $('#loading-wrp').fadeOut("slow");
                }else{
                    $('#success').hide();
                }

                if(res.status == 0)
                {
                    $('#error').show();
                    $('#error-message').html('');
                    $('#error-message').append(res.message);
                    $('#loading-wrp').fadeOut("slow");
                }else{
                    $('#error').hide();
                }
            }

        });
    });
</script>
<script>
	// $('.textarea_editor').wysihtml5();
</script>
<script type="text/javascript">
  $('select').select2("-1", null);
</script>
<?php
    if($fileUpload)
    {
?>
<script>
    $('#file').change(function(e) {
        e.preventDefault();
        $('#loading-wrp').show();

        var formData = new FormData(this);
        var file = document.getElementById('file');
        var fileName = document.getElementById('file').name;

        var uploadFile = file.files[0];

        var upPath = '<?= base_url().midurl().$mbase ?>uploadReceipt';
        formData.append('file', uploadFile, uploadFile.name);

        var xhr = new XMLHttpRequest();

        // Open the connection.
        xhr.open('POST', upPath, true);


        // Set up a handler for when the request finishes.
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = xhr.response;
                res = JSON.parse(res);

                if(res.status == true)
                {
                    var fileInfo = res.file;
                    var fileUploadData = fileInfo.upload_data;

                    $('#filename').html('');
                    $('#filename').append(fileUploadData.file_name);

                    $('#filepath').val(fileUploadData.full_path);

                    $('#success-message').html('');
                    $('#success-message').append('File Uploaded!');

                    $('#success').fadeIn("slow");
                    $('#error').fadeOut("slow");

                    $('#loading-wrp').fadeOut("slow");
                }else{
                    var err = res.message;
                    $('#error-message').html('');
                    $('#error-message').append(err);
                    $('#error').fadeIn("slow");
                    $('#success').fadeOut("slow");

                    $('#loading-wrp').fadeOut("slow");
                }

            } else {

                $('#error-message').html('');
                $('#error-message').append('Error occur during file upload, Pleas try again!');
                $('#error').fadeIn("slow");
                $('#success').fadeOut("slow");

                $('#loading-wrp').fadeOut("slow");
            }
        };

        // Send the Data.
        xhr.send(formData);

    });

</script>
<?php
    }
?>
<?php
    if($datePicker)
    {
?>
<script type="text/javascript">
    /************ Set Current Date On Pickup Date **************/
    var tdate = new Date();
    var todate = tdate.getDate();
    var tomonth = tdate.getMonth();
    var toyear = tdate.getFullYear();

    if(todate<10)
    {
        todate='0'+todate
    }

    if(tomonth<10)
    {
        tomonth='0'+tomonth
    }
    var toDate = tomonth + "/" + todate + "/" + toyear;
    $('#pickupdate').val(toDate);

    /************ Set Delivery Date On Page Load **************/
    var d = new Date();
    var numDays = 3;
    d.setDate(d.getDate()+numDays);

    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    var curr_year = d.getFullYear();

    if(curr_date<10)
    {
        curr_date='0'+curr_date;
    }

    if(curr_month<10)
    {
        curr_month='0'+curr_month;
    }

    var expDate = curr_month + "/" + curr_date + "/" + curr_year;
    $('#deliverydate').val(expDate);


    /******Load Date Picker Method *****/
    $('#pickupdate').datepicker();
    // $('#deliverydate').datepicker();


    /*********** Delivery Type Change Start Here ***********/
    $('#deliverytype').change(function(e) {
        e.preventDefault();

        var id = this.value;
        // console.log(id);
        var urlPath = '<?= base_url().midurl().$mbase ?>getDeliveryTypeInfo';

        $.ajax({
            url: urlPath,
            dataType: 'json',
            data: {id:id},
            success: function(res){

                console.log(res);
                var d = new Date();
                var numDays = parseInt(res.DeliveryDays);
                d.setDate(d.getDate()+numDays);

                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();

                if(curr_date<10)
                {
                    curr_date='0'+curr_date;
                }

                if(curr_month<10)
                {
                    curr_month='0'+curr_month;
                }

                var expDate = curr_month + "/" + curr_date + "/" + curr_year;
                $('#deliverydate').val(expDate);

            }
        });

    });

    /*********** Delivery Type Change End Here ***********/
    /******************* Delivery Type Change On Date Change **************/

    $( document ).on( 'keydown', function ( e ) {
        if ( e.keyCode === 27 ) { // ESC
            $('#myModal').modal('hide');
        }
    });
</script>
<?php
    }
?>
</body>
</html>
