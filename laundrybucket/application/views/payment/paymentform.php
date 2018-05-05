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
    .control-group.span6.RiderId-div{
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
          <h5>Order Payment</h5>
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
                foreach($hiddencontrol as $hcontrol)
                {
               ?>
                    <input type="hidden" name="<?= $hcontrol['name']; ?>" id="<?= $hcontrol['id']; ?>" value="<?= $hcontrol['value'] ?>" >
              <?php
                }
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
							<option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values->$cname) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
							<?php
						}
                	?>
                </select>
				<?php
				}
				elseif($type == 'toggle')
				{
				?>
				<label class="switch <?= $control['class'] ?>"   >
				  <input name="<?= $cname; ?>" id="<?= $control['id']; ?>"  <?= !empty($values->$cname) && $values->$cname == true ? 'checked' : ''; ?>  type="checkbox">
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
				
				<textarea name="<?= $cname; ?>"  <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" class="<?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>" ><?= !empty($values->$cname) ? $values->$cname : ''; ?></textarea>
				<?php				
				}
				elseif ($type == 'date')
                {
                    $datePicker = true;
                ?>
                    <input type="text"  placeholder="<?= $control['placeholder'] ?>"  name="<?= $cname; ?>" id="<?= $control['id']; ?>" value="<?= !empty($values->$cname) ? $values->$cname : ''; ?>" class="datepicker span11">
                <?php
                }
				else {
          	?>	
                <input name="<?= $cname; ?>" <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values->$cname) ? $values->$cname : ''; ?>"  type="<?= $control['type']; ?>" class="<?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>" />
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
    $('.datepicker').datepicker();
</script>
<?php
    }
?>
<script>
    $(document).on("change","#AmountReceivedBy",function(){
        var receiveby=$(this).val();
        if(receiveby=='Delivery Boy')
        {
            $(".RiderId-div").show(50);
        }
        else
        {
            $(".RiderId-div").hide(50);
            $("#RiderId").val("-1");
        }
    });
</script>
</body>
</html>
