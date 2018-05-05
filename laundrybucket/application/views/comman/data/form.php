<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-wysihtml5.css" />
</head>
<body>

<?php 
	
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
	
 	if(empty($readonly))
	{
		$readonly = array();
	}
?>

<div id="content">
<div id="content-header">
  <?php  $this->load->view('./comman/breadcrumb'); ?>
  <h1><?= $form['title']; ?></h1>
</div>
<div class="container-fluid">
  <hr>
  <?php
  // echo json_encode($control['options']);
  ?>
  <div class="row-fluid">
    <div class="span6">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><?= $form['title']; ?>-Info</h5>
        </div>
        <div class="widget-content nopadding">
        	<?php
        		$actionUrl = null;
        		if(!empty($form['action']))
				{
					$actionUrl = base_url().'index.php/'.$form['action'];
				}
        	?>
          <form enctype="multipart/form-data" name="<?= $form['name']; ?>" action="<?= $actionUrl; ?>" id="<?= $form['id']; ?>" method="post" class="<?= $form['class']; ?>">
			<?php
      			if(!empty($values) && !empty($values->id) )
				{
					?>
					<input type="hidden" name="id" value="<?= $values->id; ?>" />
					<?php
				}
      		?>	          	
          	<?php foreach($form['controles'] as $control) {
          		$cname= $control['name'];
			 ?>
      		<div class="control-group">
      			<label for="<?= $cname; ?>" class="control-label"><?= $control['lable'] ?> :</label>
      			<div class="controls">
          	<?php
          		$type = $control['type'];
          	    if($type == 'select')
				{
				?>
                <select  name="<?= $cname; ?>" id="<?= $control['id']; ?>" class="<?= $control['class'] ?>"  >
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
				?>
                <input type="file" name="<?= $cname; ?>" <?= array_key_exists('required', $control) && $control['required'] == 'true' ? 'required' : '' ?> id="<?= $control['id']; ?>" class="<?= $control['class'] ?>" />
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
                ?>
                    <input type="text" data-date="01-02-2013" data-date-format="dd-mm-yyyy" value="01-02-2013" class="datepicker span11">
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
	// $('.textarea_editor').wysihtml5();
</script>
<script type="text/javascript">
  $('select').select2();
</script>
</body>
</html>
