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

    .lbl{
        width: 120px;
        text-align: right;
        line-height: 30px;
    }

    .lbl-l{
        width: 120px;
        line-height: 30px;
    }

    .frm-sub-title{
        margin-left: 15px;
    }

    .lbl-ord{
        width: 150px;
        line-height: 30px;
    }

    .btn-fix{
        width: 60px;
    }

    #tableAddRow tr th,#tableAddRow tr td{
        width: 130px;
    }
    .m-t-0{
        margin-top: 0 !important;
    }
    .sel-list{
        width: 150px;
        margin: 0 5px;
    }
    .f-w-12{
        font-size: 12px;
    }
    .w-12{
        width: 12px !important;
    }

    .opt-wrp{
        float: left;
        margin-right: 5px;
    }
    .f-row{
        text-align: left;
    }
    .f-row th{
        padding-left: 7px;
    }
    #tableAddRow tr:first-child{
        border: none;
        line-height: 25px;
        background: #444;
        color: #ffffff;
    }
    #tableAddRow tr{
        border-bottom: 1px solid #CCCCCC;
        border-top: 1px solid #CCCCCC;
        line-height: 40px;
    }
    .tbl-order-total-wrp{
        width: 600px;
        margin: 20px auto;
    }
    .tbl-order-total-wrp tr{
        line-height: 50px;
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
      <form enctype="multipart/form-data" name="<?= $form['name']; ?>"  id="<?= $form['id']; ?>" method="post" class="<?= $form['class']; ?>">
    <div class="span12">
      <div class="widget-box">

        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        <!--          <h5>-->
            <?php
            //= $userInfo->UserFirstName." ".$userInfo->UserLastName <!--'s Place Order(New)/Check Status of Existing Order</h5>-->
            ?>

          <h5>Customer Details</h5>
        </div>
        <div class="widget-content nopadding">
                  <div class="control-group">
                      <table>
                          <tr>
                              <td>
                                  <h5 class="frm-sub-title" >Customer Details</h5>
                                  <table>
                                      <tr>
                                          <td><label class="lbl">Customer ID :</label></td>
                                          <td><input type="text" class="span11" placeholder="Customer ID" /></td>

                                          <td><label class="lbl">Customer Name :</label></td>
                                          <td><input type="text" class="span11" placeholder="Customer Name" /></td>

                                          <td><label class="lbl">Address :</label></td>
                                          <td><input type="text" class="span11" placeholder="Address" /></td>

                                          <td><label class="lbl">Phone No. :</label></td>
                                          <td><input type="text" class="span11" placeholder="Phone No." /></td>
                                      </tr>
                                  </table>
                              </td>
                              <td>
                                  <button type="button" class="btn btn-success" style="width: 110px;margin-right: 20px;" >Full Details</button>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <h5 class="frm-sub-title" >Order Details</h5>
                                  <table style="margin-left: 30px;" >
                                      <tr>
                                          <td><label class="lbl-l">Order ID :</label></td>
                                          <td><label class="lbl-l">Order Status :</label></td>
                                          <td><label class="lbl-l">Franchisee :</label></td>
                                          <td><label class="lbl-l">Remarks :</label></td>
                                          <td><label class="lbl-l" style="width: 140px;" >Offer Code Applied :</label></td>
                                      </tr>
                                      <tr>

                                          <td><input type="text" class="span11" placeholder="Order ID" /></td>

                                          <td><input type="text" class="span11" placeholder="Order Status" /></td>

                                          <td><input type="text" class="span11" placeholder="Franchisee" /></td>

                                          <td><input type="text" class="span11" placeholder="Remarks" /></td>

                                          <td><input type="text" class="span11" placeholder="Offer Code Applied" /></td>

                                      </tr>
                                      <tr>
                                          <td><label class="lbl-l">Order Via :</label></td>
                                          <td><label class="lbl-l">Delivery Type :</label></td>
                                      </tr>
                                      <tr>
                                          <td><input type="text" class="span11" placeholder="Order Via" /></td>

                                          <td><input type="text" class="span11" placeholder="Delivery Type" /></td>
                                      </tr>
                                  </table>

                              </td>
                              <td>
                                <button type="button" class="btn btn-success" style="width: 110px;margin-right: 20px;" >Edit</button>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <h5 class="frm-sub-title" >Order Pickup/Delivery Details</h5>
                                  <table style="margin-left: 30px;width: calc(100% - 30px);" >
                                      <tr>
                                          <td><label class="lbl-ord">Pickup Date :</label></td>
                                          <td><label class="lbl-ord">Delivery up Due Date :</label></td>
                                          <td><label class="lbl-ord">Pickup/Drop Address :</label></td>
                                      </tr>
                                      <tr>

                                          <td><input type="text" class="span11 datepicker" placeholder="Pickup Date" /></td>

                                          <td><input type="text" class="span11 datepicker" placeholder="Delivery up Due Date" /></td>

                                          <td><input type="text" class="span11" placeholder="Pickup/Drop Address" /></td>

                                      </tr>
                                  </table>
                              </td>
                              <td>
                                  <button type="button" class="btn btn-success" style="width: 110px;margin-right: 20px;" >Edit</button>
                              </td>
                          </tr>
                          <tr>
                                <td>
                                    <table style="margin-left: 30px;width: calc(100% - 30px);" >
                                        <tr>
                                            <td><label class="lbl-ord">Actual Pickup Date :</label></td>
                                            <td><label class="lbl-ord">Picked Up By :</label></td>
                                            <td><label class="lbl-ord">Actual Delivery Date :</label></td>
                                            <td><label class="lbl-ord">Delivered By :</label></td>
                                        </tr>
                                        <tr>

                                            <td><input type="text" class="span11 datepicker" placeholder="Actual Pickup Date" /></td>

                                            <td><input type="text" class="span11" placeholder="Picked Up By" /></td>

                                            <td><input type="text" class="span11 datepicker" placeholder="Actual Delivery Date" /></td>

                                            <td><input type="text" class="span11" placeholder="Delivered By" /></td>

                                        </tr>
                                    </table>
                                </td>
                          </tr>
                      </table>







                      <h5 class="frm-sub-title" >Payment Details</h5>
                      <table>
                          <tr>
                              <td><label class="lbl">Payment Status :</label></td>
                              <td>
                                  <select class="sel-list"  id="service-category-drop-down-1"  name="servicecatid[]"  >
                                      <option value="-1" >Select Category</option>
                                      <?php
                                      foreach($paymentStatusList as $option)
                                      {
                                          ?>
                                          <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                          <?php
                                      }
                                      ?>
                                  </select>
                              </td>

                              <td><button type="button" class="btn btn-success">Payment Details</button></td>

                          </tr>
                      </table>

                      <div>
                        <h5 class="frm-sub-title" >Order Details</h5>
                          <div style="overflow-x: scroll" >
                            <div style="width: 1980px;" >
                        <table id="tableAddRow" >
                            <tr class="f-row" >
                                <th>Service</th>
                                <th>Category</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Total Items</th>
                                <th>MRP</th>
                                <th>Sale</th>
                                <th>Offer Code</th>
                                <th>Calculated Discount</th>
                                <th>Extra Discount</th>
                                <th>Calculated Amount</th>
                                <th>Extra Charges</th>
                                <th>Extra Charges Type</th>
                                <th>Remarks</th>
                                <th><a href="javascript:void(0)" class="addBtn btn btn-mini btn-primary btn-fix" ><span class="glyphicon glyphicon-plus" id="addBtn_0"></span>Add New Item</a></th>
                            </tr>
                            <tr>
                                <td>
                                    <select class="sel-list" id="service-drop-down-1"  onchange="setOfferCode(this);" name="serviceid[]"  >
                                        <option value="-1" >Select Service</option>
                                        <?php
                                        foreach($serviceList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="sel-list"  id="service-category-drop-down-1"  name="servicecatid[]"  >
                                        <option value="-1" >Select Category</option>
                                        <?php
                                        foreach($serviceCatList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select  class="sel-list" id="item-drop-down-1" name="serviceitemid[]"  >
                                        <option value="-1" >Select Item</option>
                                        <?php
                                        foreach($serviceItemList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select  class="sel-list"  id="qantity-drop-down-1" name="qantity[]"  >
                                        <option value="-1" >Select Qantity</option>
                                        <?php
                                        foreach($quantityList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select  class="sel-list"  id="total-items-drop-down-1" name="totalitems[]"  >
                                        <option value="-1" >Select Total Items</option>
                                        <?php
                                        foreach($quantityList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="text" class="span11" name="mrp[]" placeholder="MRP" value="0.00" /></td>
                                <td><input type="text" class="span11" name="sale[]" placeholder="Sale" value="0.00" /></td>
                                <td>
                                <select  class="sel-list"  id="offer-drop-down-1"  name="offerid[]"  >
                                <option value="-1" >Select Offer Code</option>
                                <?php
                                        foreach($offerCodeList as $option)
                                        {
                                            ?>
                                <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                <?php
                                        }
                                        ?>
                                </select>
                                </td>
                                <td><input type="text" class="span11" name="totaldiscount[]" placeholder="Calculated Discount" value="0.00" /></td>
                                <td><input type="text" class="span11" name="extradiscount[]" placeholder="Extra Discount" value="0.00" /></td>
                                <td><input type="text" class="span11" name="calculatedamount[]" placeholder="Calculated Amount" value="0.00" /></td>
                                <td><input type="text" class="span11" name="extracharges[]" placeholder="Extra Charges" value="0.00" /></td>
                                <td>
                                    <select  class="sel-list"  id="extracharges-drop-down-1"  name="extrachargestype[]"  >
                                        <option value="-1" >Select Extra Charges Type</option>
                                        <?php
                                        foreach($extraChargeList as $option)
                                        {
                                            ?>
                                            <option <?= !empty($control['default']) && $control['default'] == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="width: 700px;" ><input type="text" class="span11" name="remarks[]" placeholder="Remarks" /></td>
                                <td>
<!--                                    <a href="javascript:void(0)" onclick="removeRow(this);" class="addBtnRemove btn btn-mini btn-danger btn-fix" ><span class="glyphicon glyphicon-minus" id="addBtnRemove_0"></span> Delete Item</a>-->
                                </td>
                            </tr>
                        </table>
                            </div>
                         </div>
                      </div>
                      <div>
                          <table class="tbl-order-total-wrp" >
                              <tr>
                                  <td><label class="lbl-ord">Sub Total :</label></td><td><input type="text" value="0.00" readonly="readonly" ></td>
                              </tr>
                              <tr>
                                  <td><label class="lbl-ord">Total Discount :</label></td><td><input type="text" value="0.00" readonly="readonly" ></td>
                              </tr>
                              <tr><td><a href="#" class="btn btn-mini btn-success" >Apply Extra Discount</a></td></tr>
                              <tr>
                                  <td><label class="lbl-ord">Add Extra Charges :</label></td>
                                  <td>
                                      <select>
                                          <option value="No">No</option>
                                          <option value="Yes">Yes</option>
                                      </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td><label class="lbl-ord">Offer Code :</label></td>
                                  <td>
                                      <select>
                                          <option value="No">No</option>
                                          <option value="Yes">Yes</option>
                                      </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td><label class="lbl-ord" style="width: 220px" >Total Taxable Amount :</label></td><td><input type="text" value="0.00" readonly="readonly" ></td>
                              </tr>
                              <tr>
                                  <td><label class="lbl-ord" style="width: 220px" >Inclusive Tax Amount (18% GST) :</label></td><td><input type="text" value="0.00" readonly="readonly" ></td>
                              </tr>
                              <tr>
                                  <td><label class="lbl-ord" style="width: 220px" >Total Payable Amount :</label></td><td><input type="text" value="0.00" readonly="readonly" ></td>
                              </tr>
                          </table>
                      </div>
                  </div>

                  <div class="form-actions">
                      <button type="submit" class="btn btn-success">Save</button>
                      <button type="reset" class="btn btn-danger">Cancel</button>
                  </div>


        </div>
      </div>
    </div>
      </form>
  </div>
</div>
</div>

<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
<!-- <script src="<?= base_url(); ?>assets/js/wysihtml5-0.3.0.js"></script>  -->

<!-- <script src="<?= base_url(); ?>assets/js/bootstrap-wysihtml5.js"></script>  -->
<script>

    //var frmid = <?//= $form['id'] ?>//,callURL = <?//= "'".$actionUrl."'" ?>//;
    var frmid = "";
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

        $('.addBtn').on('click', function () {
            //var trID;
            //trID = $(this).closest('tr'); // table row ID
            addTableRow();
        });

        var i = 2;
        function addTableRow()
        {
            var serviceDropDown = $('#service-drop-down-1').html();
            var serviceCateDropDown = $('#service-category-drop-down-1').html();
            var serviceItemDropDown = $('#item-drop-down-1').html();
            var qantityDropDown = $('#qantity-drop-down-1').html();
            var totalItemDropDown = $('#total-items-drop-down-1').html();
            var offerCodeDropDown = $('#offer-drop-down-1').html();
            var extChargeTypeDropDown = $('#extracharges-drop-down-1').html();

            var tempTr = $('' +
                '<tr>' +
                '<td><select class="sel-list" name="serviceid[]" onchange="setOfferCode(this);" id="service-drop-down-'+ i +'" >'+serviceDropDown+'</select></td>' +
                '<td><select class="sel-list" name="servicecatid[]" id="service-category-drop-down-'+ i +'" >'+serviceCateDropDown+'</select></td>' +
                '<td><select class="sel-list" name="serviceitemid[]" id="item-drop-down-'+ i +'" >'+serviceItemDropDown+'</select></td>' +
                '<td><select class="sel-list" name="qantity[]" id="qantity-drop-down-'+ i +'" >'+qantityDropDown+'</select></td>' +
                '<td><select class="sel-list" name="totalitems[]" id="total-items-drop-down-'+ i +'" >'+totalItemDropDown+'</select></td>' +
                '<td><input type="text" id="mrp-' + i + '" name="mrp[]" value="0.00" placeholder="MRP" class="span11" /></td>' +
                '<td><input type="text" id="sale-' + i + '" name="sale[]" value="0.00" placeholder="Sale" class="span11" /></td>' +
                '<td><select class="sel-list" name="offerid[]" id="offer-drop-down-'+ i +'" >'+offerCodeDropDown+'</select></td>' +
                '<td><input type="text" id="totaldiscount-' + i + '" name="totaldiscount[]" value="0.00" placeholder="Calculated Discount" class="span11" /></td>' +
                '<td><input type="text" id="extradiscount-' + i + '" name="extradiscount[]" value="0.00" placeholder="Extra Discount" class="span11" /></td>' +
                '<td><input type="text" id="calculatedamount-' + i + '" name="calculatedamount[]" value="0.00" placeholder="Calculated Amount" class="span11" /></td>' +
                '<td><input type="text" id="extracharges-' + i + '" name="extracharges[]" value="0.00" placeholder="Extra Charges" class="span11" /></td>' +
                '<td><select class="sel-list" name="extrachargestype[]" id="extracharges-drop-down-'+ i +'" >'+extChargeTypeDropDown+'</select></td>' +
                '<td><input type="text" id="remrks-' + i + '" class="span11" name="remarks[]" placeholder="Remarks" /></td>' +
                '<td><a href="javascript:void(0)" onclick="removeRow(this);" class="addBtnRemove btn btn-mini btn-danger btn-fix" ><span class="glyphicon glyphicon-minus" id="addBtn_' + i + '"></span>Delete Item</a></td>' +
                '</tr>' +
                '');

            $("#tableAddRow").append(tempTr)
            i++;
        }

        function removeRow(cRow)
        {
            $(cRow).closest('tr').remove();
        }

        /************** Function For Offer Code *************/
        function setOfferCode(info)
        {
            var id = info.id;
            var selService = $("#"+id).val();
            console.log(selService);
        }

</script>
<script>
    $('.datepicker').datepicker();
</script>
</body>
</html>
