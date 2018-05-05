<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('./comman/head_css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/uniform.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ajaxpagination.css" />
</head>
<body>
<?php 
	$this->load->view('./comman/header');
	$this->load->view('./comman/sidebar_menu');
?>

<div id="content">
  <div id="content-header">
   <?php  $this->load->view('./comman/breadcrumb'); ?>
    <h1>Access Denied</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
       <div class="m-b" ></div>
        <div id="view-data" >
            <img style="width: 100%;height: 500px;" src="<?= base_url()."assets/img/accessdenied.png"; ?>">
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('./comman/footer'); ?>
<?php $this->load->view('./comman/footer_js'); ?>
</body>
</html>
