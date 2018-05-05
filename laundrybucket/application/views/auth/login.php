<!DOCTYPE html>
<html lang="en">
    
<head>
        <title><?php echo !empty($title) ?  $title : 'Admin'; ?></title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/matrix-login.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div id="loginbox">
<!--        	--><?php //echo form_open('',array('id' =>'loginform','class'=>'form-vertical')); ?>
            <form id="loginform" method="post" class="form-vertical" >
				 <div class="control-group normal_text"> <h3><img src="<?= base_url(); ?>assets/img/logo.png" alt="Laundry Bucket" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"> </i></span><input type="text" id="username" name="username" placeholder="Username" value="<?php echo set_value('username'); ?>" size="50" />
<!--                            <input type="hidden" id="rolename" name="rolename"  value="" size="50" />-->
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>" size="50" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lb"><i class="icon-user"></i></span>
                            <select name="role" id="role"  >
                                <option>Login As</option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php echo validation_errors('<div class="error">', '</div>'); ?>
                <div class="error"></div>
                <?php 
                	if(!empty($message))
					{
						echo '<div class="error">'.$message.'</div>';
					}
                 ?>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>
                    <span class="pull-right"><button type="submit" name="btnSubmit" class="btn btn-success" /> Login</button></span>
                </div>
            </form>
<!--            --><?php //echo form_close(); ?>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
                </div>
            </form>
            
        </div>
        
        <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>  
        <!-- <script src="<?= base_url(); ?>assets/js/matrix.login.js"></script>  -->
        <script>
            $( document ).ready(function() {
                $(document).on("focus","#role",function(){

                    var username = $("#username").val();
                    var password = $("#password").val();

                    if(username=="" || password=="")
                    {
                        //alert("Enter Username and Password");
                       // $('.error').append("Please Enter Username and Password");
                    }
                    else
                    {
                        //$("#txtrole").html("");
                        $("#role").find('option').remove();

                        var url = <?php echo "'".base_url().midurl()."user/getuserrole"."'"; ?>

                        $.post(url, {username:username,password:password},
                            function(returnedData){
                                returnedData = JSON.parse(returnedData);
                                var htmlText = "";
                               for(var key = 0; key < returnedData.length; key++)
                                {
                                    var name =  returnedData[key]["roleName"];
                                    var value =  returnedData[key]["roleId"];

                                    htmlText += '<option value="'+value+'" >'+name+'</option>';
                                }
                                $("#role").append(htmlText);
                            }).fail(function(){
                            console.log("error");
                        });

                    }

                });

                $("#loginform").submit(function(e){
                    e.preventDefault();

                    var username = $("#username").val();
                    var password = $("#password").val();

                    if(username=="" || password=="")
                    {
                        // alert("Enter Username and Password");
                        $('.error').append("Please Enter Username and Password");
                    }
                    else {
                        var frmdata = $('#loginform').serialize();
                        var url = <?php echo "'" . base_url() . midurl() . "user/userlogin" . "'"; ?>;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: frmdata,
                            cache: false,
                            success: function (result) {
                                console.log(result);
                                if (result) {

                                    var resurl = <?php echo "'" . base_url() . midurl() . "dashboard" . "'"; ?>;
                                    $(location).attr('href', resurl);
                                } else {
                                    $('.error').html('');
                                    $('.error').append("Please Enter Correct Username and Password");
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </body>

</html>
