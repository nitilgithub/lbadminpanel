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
    .f-lef{
        float: left !important;
    }
    .m-l-20{
        margin-left: 20px !important;
    }
    .w-230{
        width: 230px;
        float: left;
    }
    .d-n{
        display: none !important;
    }
    .d-b{
        display: block !important;
    }
    .rd-cov{
        padding-top: 7px;
    }
    .rd-cov input[type='radio']{
        float: left;
        margin-right: 5px;
    }
    .rd-cov label{
        float: left;
    }
    .apply{
        color: #5cb85c;
        margin-left: 10px;
        line-height: 30px;
    }
    .expired{
        color: #d9534f;
        margin-left: 10px;
        line-height: 30px;
    }
    .lblgst{
        float: left;
        padding-top: 4px;
        margin-right: 10px;
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

    $fileUpload = true;
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
          <h5><?= $userInfo->UserFirstName." ".$userInfo->UserLastName." ".$form['title']." Under ".$userInfo->franchiseename; ?></h5>
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
          	<?php foreach($form['controles'] as $control) {
          		$cname= $control['name'];
          		if(empty($control['id']))
                {
                    $control['id'] = $cname;
                }

                $type = $control['type'];
			 ?>
      		<div class="control-group <?= $control['con-group-class']  ?>">

                <?php
                if($type == 'blank')
                {
                    ?>
                    <label style="line-height: 30px" class="control-label">&nbsp;</label>
                    <?php
                }else{
                    ?>
                    <label for="<?= $cname; ?>" class="control-label"><?= $control['lable'] ?> :</label>
                    <?php
                }
                ?>

                <div class="controls">
          	<?php

          	    if($type == 'select')
				{
				?>
                <select  name="<?= $cname; ?>" id="<?= $control['id']; ?>" class="<?= $control['class'] ?>"  >
                	<option value="-1" >Select <?= $control['lable'] ?></option>
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
				elseif($type == 'toggle')
				{
				?>
				<label class="switch <?= $control['class'] ?>"   >
				  <input name="<?= $cname; ?>" id="<?= $control['id']; ?>"  <?= !empty($values) && $values->$cname == true ? 'checked' : ''; ?>  type="checkbox">
				  <span class="slider round"></span>
				</label>
				<?php
				}
                elseif($type == 'checkbox')
                {
                    ?>
                    <label class="switch <?= $control['class'] ?>"   >
                        <input name="<?= $cname; ?>" id="<?= $control['id']; ?>"  <?= !empty($values) && $values->$cname == true ? 'checked' : ''; ?>  type="checkbox">
                        <span class="slider round"></span>
                    </label>
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

				<textarea style="width: 770px !important;" name="<?= $cname; ?>"  <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" class="<?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>" ><?= !empty($values) ? $values->$cname : ''; ?></textarea>
				<?php
				}
				elseif ($type == 'date')
                {
                ?>
                    <input type="text"  name="<?= $cname; ?>" <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>" class="datepicker <?= $control['class'] ?>" placeholder="<?= $control['placeholder'] ?>"  />
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
                elseif($type == 'discount'){
            ?>
                    <input name="<?= $cname; ?>" <?= in_array($cname, $readonly) ? 'readonly' : '' ?> <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="text" class="span2 f-lef" placeholder="<?= $control['placeholder'] ?>" />
                    <select  name="discounttype" id="discounttype" class="f-lef" style="width: 152px" >
                        <option value="-1" >Select Discount Type</option>
                        <?php

                        $discountType = array((object) array('id' => 'flat', 'name' => 'Flat'),(object) array('id' => 'parcentage', 'name' => 'Percent (%)'));
                        foreach($discountType as $option)
                        {
                            ?>
                            <option <?= 'flat' == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input name="discountamount" id="discountamount" readonly <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="text" class="span3 m-l-20" placeholder="Total Discount Amount" />
            <?php
                }
                elseif($type == 'offer'){
                    ?>
                    <select  name="<?= $cname; ?>" id="<?= $control['id']; ?>" class="span4 f-lef"  >
                        <option value="-1" >Select <?= $control['lable'] ?></option>
                        <?php
                        foreach($control['options'] as $option)
                        {
                            ?>
                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input name="offeramount" id="offeramount" readonly <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="text" class="span3 f-lef m-l-20" placeholder="Total Offer Amount" />
                    <span id="offerStatusMessage" ></span>
                    <?php
                }
                elseif($type == 'tax'){
                    ?>
                    <div class="w-230 rd-cov" >
                    <input type="radio" id="inclusive-rd" class="rd-tax-type"  name="taxtype" value="inclusive" />
                    <label id="inclusive-lb" >Inclusive</label>
                    </div>
                    <div class="w-230 rd-cov" >
                        <input type="radio" id="exclusive-rd" class="rd-tax-type"  name="taxtype" value="exclusive" />
                    <label id="exclusive-lb" >Exclusive</label>
                    </div>
                    <?php
                }
                elseif($type == 'gst'){
                    ?>
                    <input name="inclusivetax" id="inclusivetax" readonly <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="text" class="d-b <?= $control['class'] ?>" placeholder="GST Tax Amount" />
                    <input name="exclusivetax" style="float: left;" id="exclusivetax" readonly <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" value="<?= !empty($values) ? $values->$cname : ''; ?>"  type="text" class="d-n <?= $control['class'] ?>" placeholder="GST Tax Amount" />
                    <?php
                }
                elseif($type == 'blank')
                {
                    ?>
                    <div>
                    </div>
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
            
            <div class="form-actions">
              <button type="submit" class="<?= $form['submit']['class'] ?>"><?= $form['submit']['lable'] ?></button>
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
                console.log(res.status);
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
  // $('select').select2("-1", null);
</script>
<script>
    // $('.datepicker').datepicker();
</script>
<script>

    /*********** Subscription Type Change Start Here ***********/
    $('#subid').change(function(e) {
        e.preventDefault();

        var id = this.value;
        // console.log(id);
        var urlPath = '<?= base_url().midurl().$mbase ?>getSubTypeInfo';

        $.ajax({
            url: urlPath,
            dataType: 'json',
            data: {id:id},
            success: function(res){

                // console.log(res);
                var d = new Date();
                var numDays = parseInt(res.subs_validity);
                d.setDate(d.getDate()+numDays);

                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();
                var expDate = curr_month + "/" + curr_date + "/" + curr_year;

                // console.log(d);
                $('#totalweight').val(res.subs_wt);
                $('#expdate').val(expDate);

                var tAmount = res.subs_cost;
                tAmount = parseFloat(tAmount).toFixed(2);

                $('#totalamount').val(tAmount);


                $("#discount").val(0);
                $("#discounttype").val('flat');
                $("#discountamount").val(parseFloat(0).toFixed(2));
                $("#offeramount").val(parseFloat(0).toFixed(2));
                $("#offercodeid").val(-1);
                $("#inclusivetax").val('');
                $("#exclusivetax").val('');
                $("#payableamount").val('');
                $("#taxableamount").val('');
                $("#offerStatusMessage").html("");

                $("#discount").focus();

                $("input[name='taxtype']").prop('checked', false);

            }
        });

    });
    /*********** Subscription Type Change End Here ***********/
    /*********** Discount Type Change Start Here ***********/
    $('#discount').blur(function(e) {
        var dis = $("#discount").val();
        dis = parseFloat(dis).toFixed(2);
        var disType = $("#discounttype").val();

        var disAmount = 0;
        if(disType == "parcentage")
        {
            var tAmount = $("#totalamount").val();

            disAmount = (dis * tAmount) / 100;

        }else{

            disAmount = dis;
        }

        disAmount = parseFloat(disAmount).toFixed(2);
        $('#discountamount').val(disAmount);

        var res = checkTaxRd();
        if(res != false)
        {
            var taxType = res[1];
            setTaxAmount(taxType);
        }


    });

    $('#discounttype').change(function(e) {
        e.preventDefault();
        var disType = this.value;
        var dis = $("#discount").val();
        dis = parseFloat(dis).toFixed(2);
        var disAmount = 0;

        if(disType == "parcentage")
        {
            var tAmount = $("#totalamount").val();

            disAmount = (dis * tAmount) / 100;


        }else{

            disAmount = dis;
        }

        disAmount = parseFloat(disAmount).toFixed(2);
        $('#discountamount').val(disAmount);

        var res = checkTaxRd();
        if(res != false)
        {
            var taxType = res[1];
            setTaxAmount(taxType);
        }

        // var paybleAmount = $("#totalamount").val();
        // var offAmount = $("#offeramount").val();
        //
        // paybleAmount = paybleAmount - disAmount ;
        // paybleAmount = parseFloat(paybleAmount).toFixed(2);

        // $("#payableamount").val(paybleAmount);
    });

    /*********** Discount Type Change End Here ***********/

    /*********** Offer Type Change Start Here ***********/
    $('#offercodeid').change(function(e) {
        e.preventDefault();

        var id = this.value;
        // console.log(id);
        var urlPath = '<?= base_url().midurl().$mbase ?>getOfferInfo';

        $.ajax({
            url: urlPath,
            dataType: 'json',
            data: {id:id},
            success: function(res){

                // console.log(res);
                var offExpDate = res.ExpiryDate;
                var offType = res.OfferUnit;
                var d = new Date();

                var curr_date = d.getUTCDate();
                var curr_month = d.getUTCMonth() + 1;
                if(curr_month<10)
                {
                    curr_month='0'+curr_month
                }
                var curr_year = d.getFullYear();
                var toDate = curr_year + "-" + curr_month + "-" + curr_date;

                if(offExpDate > toDate )
                {

                    $("#offerStatusMessage").html("Offer Applied");
                    $("#offerStatusMessage").addClass("apply");
                    $("#offerStatusMessage").removeClass("expired");

                    if(offType == "percent")
                    {
                        var tAmount = $("#totalamount").val();
                        var offParVal = parseFloat(res.OfferValue);
                        var offAmount = 0 ;
                        offAmount = (offParVal * tAmount) / 100;
                        offAmount = parseFloat(offAmount).toFixed(2);

                        $("#offeramount").val(offAmount);

                        var res = checkTaxRd();
                        if(res != false)
                        {
                            var taxType = res[1];
                            setTaxAmount(taxType);
                        }

                        // var paybleAmount = $("#totalamount").val();
                        // var disAmount = $("#discountamount").val();
                        // var excTax = $("#exclusivetax").val();

                        // paybleAmount = paybleAmount - disAmount - offAmount;
                        // paybleAmount = parseFloat(paybleAmount).toFixed(2);

                        // $("#payableamount").val(paybleAmount);

                    }else{
                        var offVal = parseFloat(res.OfferValue).toFixed(2);
                        $("#offeramount").val(offVal);

                        var res = checkTaxRd();
                        if(res != false)
                        {
                            var taxType = res[1];
                            setTaxAmount(taxType);
                        }

                        // var paybleAmount = $("#totalamount").val();
                        // var disAmount = $("#discountamount").val();
                        // var excTax = $("#exclusivetax").val();

                        // paybleAmount = paybleAmount - disAmount - offVal ;
                        // paybleAmount = parseFloat(paybleAmount).toFixed(2);

                        // $("#payableamount").val(paybleAmount);
                    }

                }else{

                    $("#offerStatusMessage").html("Offer Expired");
                    $("#offerStatusMessage").removeClass("apply");
                    $("#offerStatusMessage").addClass("expired");

                    offAmount = parseFloat(0).toFixed(2);

                    $("#offeramount").val(offAmount);

                }

            }
        });

    });

    /*********** Offer Type Change End Here ***********/

    /*********** Tax Type Change Start Here ***********/
    // Change Radio Button Tax Type
    $("input[type='radio']").change(function(){

        var taxType = $(this).val();
        setTaxAmount(taxType);

    });

    // Change Click on label Inclusive
    $("#inclusive-lb").click(function(){
        $("#inclusive-rd").prop('checked', true);
        var taxType = 'inclusive';
        setTaxAmount(taxType);
    });

    // Change Click on label Exclusive
    $("#exclusive-lb").click(function(){
        $("#exclusive-rd").prop('checked', true);
        var taxType = 'exclusive';
        setTaxAmount(taxType);
    });

    /*************** Function For Calculate Tax Amount Start Here **************/
    function setTaxAmount(taxType)
    {
        var disAmount = $("#discountamount").val();
        disAmount = parseFloat(disAmount).toFixed(2);

        var offAmount = $("#offeramount").val();
        offAmount = parseFloat(offAmount).toFixed(2);

        var tAmount = $("#totalamount").val();

        // var disAmount = $("#discountamount").val();
        // disAmount = parseFloat(disAmount).toFixed(2);
        //
        // var offAmount = $("#offeramount").val();
        // offAmount = parseFloat(offAmount).toFixed(2);
        // var tAmount = $("#totalamount").val();

        if(taxType == "inclusive")
        {

            $("#inclusivetax").removeClass("d-n");
            $("#inclusivetax").addClass("d-b");
            $("#inclusivetax").addClass("span3");

            $("#exclusivetax").addClass("d-n");
            $("#exclusivetax").removeClass("d-b");
            $("#exclusivetax").removeClass("span3");

            var temTootalAmount = tAmount - disAmount - offAmount ;
            temTootalAmount = parseFloat(temTootalAmount).toFixed(2);

            // var taxAbleAmount = 0.82 * temTootalAmount;
            // var taxAmount = temTootalAmount - taxAbleAmount;

            // taxAmount = parseFloat(taxAmount).toFixed(2);
            var taxAmount = (18/118) * temTootalAmount;
            taxAmount = parseFloat(taxAmount).toFixed(2);
            $("#inclusivetax").val(taxAmount);
            $("#exclusivetax").val(parseFloat(0).toFixed(2));

            $("#payableamount").val(temTootalAmount);

            var taxAbleAmount = temTootalAmount - taxAmount;
            taxAbleAmount = parseFloat(taxAbleAmount).toFixed(2);
            $("#taxableamount").val(taxAbleAmount);
            $(".lblgst").show();

        }
        else if (taxType == "exclusive")
        {

            $("#inclusivetax").addClass("d-n");
            $("#inclusivetax").removeClass("d-b");
            $("#inclusivetax").removeClass("span3");

            $("#exclusivetax").addClass("d-b");
            $("#exclusivetax").removeClass("d-n");
            $("#exclusivetax").addClass("span3");

            taxAbleAmount = tAmount - disAmount - offAmount;

            taxAmount = 0.18 * taxAbleAmount;

            taxAmount = parseFloat(taxAmount).toFixed(2);

            $("#exclusivetax").val(taxAmount);
            $("#inclusivetax").val(parseFloat(0).toFixed(2));

            var payAbleAmount  = taxAbleAmount + +taxAmount;
            payAbleAmount = parseFloat(payAbleAmount).toFixed(2);

            $("#payableamount").val(payAbleAmount);

            $(".lblgst").show();

            taxAbleAmount = parseFloat(taxAbleAmount).toFixed(2);
            $("#taxableamount").val(taxAbleAmount);

        }else{

            $("#exclusivetax").val(parseFloat(0).toFixed(2));
            $("#inclusivetax").val(parseFloat(0).toFixed(2));

            $("#inclusivetax").addClass("d-b");
            $("#inclusivetax").removeClass("d-n");

            $("#exclusivetax").addClass("d-n");
            $("#exclusivetax").removeClass("d-b");
            $(".lblgst").hide();
        }
    }
    /*************** Function For Calculate Tax Amount End Here **************/
    /*************** Function For Check Tax Radio Selected or Not Start Here **************/
    function checkTaxRd()
    {
        if($('input[name=taxtype]:checked').val())
        {
            var taxType = $('input[name=taxtype]:checked').val();
            res = true;
            return [res,taxType];
        }else{
            return false;
        }
        // console.log(res);
        // return res;
    }
    /*************** Function For Check Tax Radio Selected or Not End Here **************/
    /************ Set Amount Zero on Page Load ************/
    $("#discount").val(0);
    $("#discountamount").val(parseFloat(0).toFixed(2));
    $("#offeramount").val(parseFloat(0).toFixed(2));

</script>

</body>
</html>
