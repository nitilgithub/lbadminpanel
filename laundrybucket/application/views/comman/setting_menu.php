<?php
$stMenuArr = array(
    (object) array("name" => "Delivery Type", "url" => "settings/deliverytypelist"),
    (object) array("name" => "Order Via", "url" => "settings/ordervialist"),
);
if(!empty($stMenuArr))
{
    ?>
        <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-cog"></i> <span class="text">Settings</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
    <?php
//    $stMenuArr = $this->session->userdata('stMenuArr');
//          $menuIcons = $this->session->userdata('menuIcons');
          $count = 1;
          foreach ($stMenuArr as $stMenu)
          {
    ?>
            <li><a class="sAdd" title="" href="<?= base_url().midurl().$stMenu->url ?>">
                    <i class="icon <?= !empty($menuIcons[$stMenu->name]) ? $menuIcons[$stMenu->name]  : ''; ?>"></i>
    <?= $stMenu->name; ?>
                </a>
            </li>
    <?php
    if($count != sizeof($stMenuArr))
              {
                  echo '<li class="divider"></li>';
              }
              $count += 1;
          }
          ?>
              </ul>
            </li>
        <?php
}
?>