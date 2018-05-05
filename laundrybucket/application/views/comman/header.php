<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle">
    	<i class="icon icon-user"></i>  <span class="text">WELCOME  <?= !empty(user_name()) ? strtoupper(user_name())  :""  ?></span><b class="caret"></b></a>
      <ul class="dropdown-menu">
<!--        <li><a href="--><?//= base_url(); ?><!--index.php/auth/profile"><i class="icon-user"></i> My Profile</a></li>-->
<!--        <li class="divider"></li>-->
<!--        <li><a href="--><?//= base_url(); ?><!--index.php/auth/changepassword"><i class="icon-key"></i> Change Password</a></li>-->
<!--        <li class="divider"></li>-->
        <li><a href="<?= base_url().midurl(); ?>auth/logout"><i class="icon-share-alt"></i> Log Out</a></li>
      </ul>
    </li>
    <?php

        if(user_role() == "SuperAdmin")
        {
            $this->load->view('./comman/setting_menu');
        }
    ?>
    <li class=""><a title="" href="<?= base_url().midurl(); ?>auth/logout"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
<!--  <input type="text" placeholder="Search here..."/>-->
<!--  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>-->
</div>
<!--close-top-serch-->