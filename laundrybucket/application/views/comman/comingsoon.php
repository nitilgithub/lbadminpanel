<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
</head>
<body>

<?php 
	
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
 
?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?= base_url().'index.php/dashboard' ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Coming Soon</a> </div>
    <h1>Coming Soon</h1>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Coming Soon</h5>
          </div>
          <div class="widget-content">
            <div class="error_ex">
              <h1>Coming Soon!</h1>
              <h3>Work in process</h3>
              <p>Our Team working on it.It will coming soon.</p>
              <a class="btn btn-warning btn-big"  href="<?= base_url(); ?>index.php/dashboard">Back to Home</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
</body>
</html>
