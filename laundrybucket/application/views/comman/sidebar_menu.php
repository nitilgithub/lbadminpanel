<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>

    <?php $uri = $this->uri->segment('1'); ?>
    <ul>
<!--        <li class="--><?//= $uri=='dashboard' ? 'active' : '' ?><!--" ><a href="--><?//= base_url(); ?><!--index.php/dashboard"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>-->
        <?php
        $menus =  menus();
        $menuIcons = menuIcons();

        foreach($menus as $m)
        {
            $mURI = str_replace('/', '', $m->url);

            if(isset($m->child_menus) && !empty($m->child_menus))
            {
            ?>
                 <li class="submenu <?= $uri==$mURI ? 'open' : '' ?>" ><a href="javascript:void(0)" ><i class="icon <?= !empty($menuIcons[$m->name]) ? $menuIcons[$m->name]  : ''; ?>"></i> <span><?= !empty($m->name) ? $m->name : ''; ?></span></a>
                     <ul>
                         <?php

                            foreach ($m->child_menus as $cm)
                            {
                                $cURI = str_replace('/', '', $cm['url']);
                         ?>
                             <li><a href="<?= base_url().midurl().$cm['url'] ?>"><?= !empty($cm['name']) ? $cm['name'] : ''; ?></a></li>
                         <?php
                            }
                         ?>
                     </ul>
                 </li>
            <?php
            }else{
            ?>
                 <li class="<?= $uri==$mURI ? 'active' : '' ?>" ><a href="<?= base_url().midurl().$m->url ?>"><i class="icon <?= !empty($menuIcons[$m->name]) ? $menuIcons[$m->name]  : ''; ?>"></i> <span><?= !empty($m->name) ? $m->name : ''; ?></span></a> </li>
            <?php
            }
        }
        ?>
    </ul>

<!--   <ul>-->
<!--    <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>Home</span></a> </li>-->
<!--    <li class="submenu" > <a href="charts.html"><i class="icon icon-signal"></i> <span>Order Summary Chart</span></a>-->
<!--        <ul>-->
<!--            <li><a href="error403.html">Total Subscription</a></li>-->
<!--            <li><a href="error404.html">Subscription Summary</a></li>-->
<!--            <li><a href="error405.html">Yearly Collection</a></li>-->
<!--            <li><a href="error500.html">Yearly Orders</a></li>-->
<!--            <li><a href="error500.html">New User Added Per Month</a></li>-->
<!--            <li><a href="error500.html">Total Reprocess Orders</a></li>-->
<!--            <li><a href="error500.html">Orders From Particular (City Per Month)</a></li>-->
<!--            <li><a href="error500.html">Yearly Collection (New vs Existing User)</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--    <li class="submenu" > <a href="javascript:void(0)"><i class="icon icon-inbox"></i> <span>Users</span></a>-->
<!--        <ul>-->
<!--            <li><a href="error403.html">Add Users</a></li>-->
<!--            <li><a href="--><?php //echo base_url()."index.php/user" ?><!--">Users List (New*)</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--    <li class="submenu" ><a href="javascript:void(0)"><i class="icon icon-th"></i> <span>Orders</span></a>-->
<!--        <ul>-->
<!--            <li><a href="--><?php //echo base_url()."index.php/order" ?><!--">All Orders (New*)</a></li>-->
<!--            <li><a href="error404.html">Creat New Orders (New*)</a></li>-->
<!--            <li><a href="error404.html">Today Pickup</a></li>-->
<!--            <li><a href="error404.html">Today Deliveries</a></li>-->
<!--            <li><a href="error404.html">Pending Pickup</a></li>-->
<!--            <li><a href="error404.html">Pending Deliveries</a></li>-->
<!--            <li><a href="error404.html">Cancelled Orders</a></li>-->
<!--            <li><a href="error404.html">Combo Offer Orders</a></li>-->
<!--            <li><a href="error404.html">All Orders (Old)</a></li>-->
<!--            <li><a href="error404.html">Filtered Orders</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--    <li class="submenu" ><a href="javascript:void(0)"><i class="icon icon-fullscreen"></i> <span>Subscriptions</span></a>-->
<!--        <ul>-->
<!--            <li><a href="--><?php //echo base_url()."index.php/subscription" ?><!--">Subscription List</a></li>-->
<!--            <li><a href="error404.html">Add Subscription</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>User Subscriptions</span> <span class="label label-important">3</span></a>-->
<!--      <ul>-->
<!--        <li><a href="form-common.html">Latest Inactive</a></li>-->
<!--        <li><a href="form-validation.html">Activated</a></li>-->
<!--      </ul>-->
<!--    </li>-->
<!--    <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Contact Us Query</span></a></li>-->
<!--    <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Get Orders Excel</span></a></li>-->
<!--    <li> <a href="#"><i class="icon icon-file"></i> <span>Get Users Excel</span> <span class="label label-important">5</span></a></li>-->
<!--    <li> <a href="#"><i class="icon icon-info-sign"></i> <span>Rate List</span> <span class="label label-important">4</span></a></li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Service Category</span></a>-->
<!--        <ul>-->
<!--            <li><a href="form-common.html">Service Category</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--       <li class="submenu"><a href="#"><i class="icon icon-th-list"></i> <span>Services</span></a>-->
<!--        <ul>-->
<!--            <li><a href="form-common.html">Services</a></li>-->
<!--        </ul>-->
<!--    </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Services Item</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Services Item</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Payment Details</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Pending Invoices</a></li>-->
<!--           <li><a href="form-common.html">Paid Invoices</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Feedback Status</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">View Customer Feedback</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Employee Roles</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Employee Roles List</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Employee Registration</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Employee List</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Offers</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Offers List</a></li>-->
<!--           <li><a href="form-common.html">Combo Offers List</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--       <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i><span>Notifications</span></a>-->
<!--       <ul>-->
<!--           <li><a href="form-common.html">Send Push Notification</a></li>-->
<!--           <li><a href="form-common.html">Notification List</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!--  </ul>-->

<!--    <ul>-->
<!--        <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>-->
<!--        <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>-->
<!--        <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>-->
<!--        <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>-->
<!--        <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>-->
<!--        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="label label-important">3</span></a>-->
<!--            <ul>-->
<!--                <li><a href="form-common.html">Basic Form</a></li>-->
<!--                <li><a href="form-validation.html">Form with Validation</a></li>-->
<!--                <li><a href="form-wizard.html">Form with Wizard</a></li>-->
<!--            </ul>-->
<!--        </li>-->
<!--        <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>-->
<!--        <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>-->
<!--        <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label label-important">5</span></a>-->
<!--            <ul>-->
<!--                <li><a href="index2.html">Dashboard2</a></li>-->
<!--                <li><a href="gallery.html">Gallery</a></li>-->
<!--                <li><a href="calendar.html">Calendar</a></li>-->
<!--                <li><a href="invoice.html">Invoice</a></li>-->
<!--                <li><a href="chat.html">Chat option</a></li>-->
<!--            </ul>-->
<!--        </li>-->
<!--        <li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>-->
<!--            <ul>-->
<!--                <li><a href="error403.html">Error 403</a></li>-->
<!--                <li><a href="error404.html">Error 404</a></li>-->
<!--                <li><a href="error405.html">Error 405</a></li>-->
<!--                <li><a href="error500.html">Error 500</a></li>-->
<!--            </ul>-->
<!--        </li>-->
<!--        <li class="content"> <span>Monthly Bandwidth Transfer</span>-->
<!--            <div class="progress progress-mini progress-danger active progress-striped">-->
<!--                <div style="width: 77%;" class="bar"></div>-->
<!--            </div>-->
<!--            <span class="percent">77%</span>-->
<!--            <div class="stat">21419.94 / 14000 MB</div>-->
<!--        </li>-->
<!--        <li class="content"> <span>Disk Space Usage</span>-->
<!--            <div class="progress progress-mini active progress-striped">-->
<!--                <div style="width: 87%;" class="bar"></div>-->
<!--            </div>-->
<!--            <span class="percent">87%</span>-->
<!--            <div class="stat">604.44 / 4000 MB</div>-->
<!--        </li>-->
<!--    </ul>  -->

</div>
<!--sidebar-menu-->