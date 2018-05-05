<?php
if(!function_exists('pr'))
{
    function pr($arry)
    {
        echo "<pre>";
        print_r($arry);
        echo "</pre>";
    }
}

if(!function_exists('menuIcons'))
{
    function menuIcons()
    {
        return array(
            'Home' => 'icon-home',
            'Order Summary Chart' => 'icon-bar-chart',
            'Users' => 'icon-group',
            'Orders' => 'icon-tags',
            'Subscriptions' => 'icon-list-alt',
            'User Subscriptions' => 'icon-list-ol',
            'Contact Us Query' => 'icon-comments-alt',
            'Get Orders Excel' => 'icon-download-alt',
            'Get Users Excel' => 'icon-copy',
            'Rate List' => 'icon-list-ul',
            'Service Category' => 'icon-th-large',
            'Services' => 'icon-columns',
            'Services Item' => 'icon-table',
            'Payment Details' => 'icon-money',
            'Feedback Status' => 'icon-comments',
            'Employee' => 'icon-tasks',
            'Employee Registration' => 'icon-list-alt',
            'Offers' => 'icon-tag',
            'Notifications' => 'icon-envelope',
            'Franchisee' => 'icon-align-left',
        );
    }
}

if(!function_exists('menus'))
{
    function menus()
    {

        $ordersumary = (object) array(
            array('name' => 'Total Subscription', 'url' => 'chart/totalsubscriptions'),
            array('name' => 'Subscription Summary', 'url' => 'chart/subscriptionsummary'),
            array('name' => 'Yearly Collection', 'url' => 'chart/yearlycollection'),
            array('name' => 'Yearly Orders', 'url' => 'chart/yearlyorder'),
            array('name' => 'New User Added Per Month', 'url' => 'chart/newuseradded'),
            array('name' => 'Total Reprocess Orders', 'url' => 'chart/reprocessordermonthly'),
            array('name' => 'Orders From Particular (City Per Month)', 'url' => 'chart/orderfromcity'),
            array('name' => 'Yearly Collection (New vs Existing User)', 'url' => 'chart/yearlycollectionuser'),
        );

        $userchildmenu = (object) array(
            array('name' => 'Add Users', 'url' => 'user/add'),
            array('name' => 'Users List', 'url' => 'user'),
            array('name' => 'Download Users Excel', 'url' => 'user/usersexcel'),
        );

        $orderchildmenu = (object) array(
            array('name' => 'All Orders ', 'url' => 'order'),
            array('name' => 'Creat New Orders', 'url' => 'order/add'),
            array('name' => 'Today Pickup', 'url' => 'order/todaypickuporders'),
            array('name' => 'Today Deliveries', 'url' => 'order/todaydeliverorders'),
            array('name' => 'Pending Pickup', 'url' => 'order/pendingpickuporders'),
            array('name' => 'Pending Deliveries', 'url' => 'order/pendingdeliveriesorders'),
            array('name' => 'Cancelled Orders', 'url' => 'order/canceledorders'),
            array('name' => 'Combo Offer Orders', 'url' => 'order/comboofferorders'),
            array('name' => 'All Orders (Old)', 'url' => 'order/alloldorders'),
            array('name' => 'Filtered Orders', 'url' => 'order/filteredorders'),
            array('name' => 'Download Orders Excel', 'url' => 'order/ordersexcel'),
        );


        if(user_role() == 'SuperAdmin') {
            $subchildmenu = (object) array(
            array('name' => 'Subscription List', 'url' => 'subscription'),
//            array('name' => 'Add Subscription', 'url' => 'subscription/add'),
            array('name' => 'Latest Inactive Users', 'url' => 'subscription/latestinactive'),
            array('name' => 'Activated Users', 'url' => 'subscription/active'),
//            array('name' => 'Buy Subscription', 'url' => 'subscription/buysubscription')
            );
        }else{
            $subchildmenu = (object) array(
            array('name' => 'Subscription List', 'url' => 'subscription'),
//            array('name' => 'Add Subscription', 'url' => 'subscription/add'),
            array('name' => 'Latest Inactive Users', 'url' => 'subscription/latestinactive'),
            array('name' => 'Activated Users', 'url' => 'subscription/active'),
            );
        }


        $userChildMenu = (object) array(
            array('name' => 'Latest Inactive', 'url' => 'subscription/latestinactive'),
            array('name' => 'Activated', 'url' => 'subscription/active'),
        );

        $serviceCateChildMenu = (object) array(
            array('name' => 'Service Category', 'url' => 'service/category'),
        );

        $serviceChildMenu = (object) array(
            array('name' => 'Service Category', 'url' => 'service/category'),
            array('name' => 'Services List', 'url' => 'service'),
            array('name' => 'Services Item', 'url' => 'service/item'),
        );

        $serviceItemChildMenu = (object) array(
            array('name' => 'Services Item', 'url' => 'service/item'),
        );

        $paymentChildMenu = (object) array(
            array('name' => 'Pending Invoices', 'url' => 'payment'),
            array('name' => 'Paid Invoices', 'url' => 'payment/paid'),
        );

        $feedbackChildMenu = (object) array(
            array('name' => 'View Customer Feedback', 'url' => 'feedback'),
        );

        $offerChildMenu = (object) array(
            array('name' => 'Offers List', 'url' => 'offer/'),
            array('name' => 'Combo Offers List', 'url' => 'offer/comboofferlist'),
        );

        $notiChildMenu = (object) array(
            array('name' => 'Send Push Notification', 'url' => 'notification/pushnotification'),
            array('name' => 'Notification List', 'url' => 'notification/list'),
        );

        $employeeChildMenu = (object) array(
            array('name' => 'Employee Roles', 'url' => 'employee/roles'),
            array('name' => 'Employee Registration', 'url' => 'employee'),
        );

        $franChildMenu = (object) array(
            array('name' => 'Franchisee List', 'url' => 'franchisee'),
            array('name' => 'Create Franchisee', 'url' => 'franchisee/add'),
        );



        return array(
            (object) array('name' => 'Home','url' => 'dashboard' ),
            (object) array('name' => 'Order Summary Chart','url' => 'order/sumarychart', 'child_menus' => $ordersumary),
            (object) array('name' => 'Users','url' => 'user', 'child_menus' => $userchildmenu ),
            (object) array('name' => 'Orders','url' => 'order', 'child_menus' => $orderchildmenu ),
            (object) array('name' => 'Subscriptions','url' => 'subscription', 'child_menus' => $subchildmenu ),
//            (object) array('name' => 'User Subscriptions','url' => 'subscription/users', 'child_menus' => $userChildMenu ),
            (object) array('name' => 'Contact Us Query','url' => 'contact' ),
//            (object) array('name' => 'Get Orders Excel','url' => 'order/ordersexcel' ),
//            (object) array('name' => 'Get Users Excel','url' => 'user/usersexcel' ),
            (object) array('name' => 'Rate List','url' => 'rate' ),
//            (object) array('name' => 'Service Category','url' => 'service/category', 'child_menus' => $serviceCateChildMenu  ),
            (object) array('name' => 'Services','url' => 'service', 'child_menus' => $serviceChildMenu ),
//            (object) array('name' => 'Services Item','url' => 'service/serviceitem','child_menus' => $serviceItemChildMenu ),
            (object) array('name' => 'Payment Details','url' => 'payment', 'child_menus' => $paymentChildMenu ),
            (object) array('name' => 'Feedback Status','url' => 'feedback', 'child_menus' => $feedbackChildMenu ),
            (object) array('name' => 'Employee','url' => 'employee', 'child_menus' => $employeeChildMenu ),
//            (object) array('name' => 'Employee Roles','url' => 'employee/roles' ),
//            (object) array('name' => 'Employee Registration','url' => 'employee/registration' ),
            (object) array('name' => 'Offers','url' => 'offers', 'child_menus' => $offerChildMenu ),
            (object) array('name' => 'Notifications','url' => 'notification', 'child_menus' => $notiChildMenu ),
            (object) array('name' => 'Franchisee','url' => 'franchisee', 'child_menus' => $franChildMenu ),

        );
    }
}

if(!function_exists('midurl'))
{
    function midurl()
    {
        return "";//index.php/
    }
}

if(!function_exists('enc'))
{
    function enc($str)
    {
        $str = base64_encode($str);
        $str = base64_encode($str);
        return $str;
    }
}

if(!function_exists('dec'))
{
    function dec($str)
    {
        $str = base64_decode($str);
        $str = base64_decode($str);
        return $str;
    }
}

/************************** Get Login User Infromation Methods Start Here  ************************/
/**************** Check User Login **************/
if(!function_exists('check_login'))
{
    function check_login()
    {
        $CI =& get_instance();
        $res = false;
        if($CI->session->userdata('user_login_status'))
        {
            $res = true;
        }

        return $res;
    }
}
/**************** Get Login User Role **************/
if(!function_exists('user_is_login'))
{
    function user_is_login()
    {
        $CI =& get_instance();
        $res = false;
        if($CI->session->userdata('user_login_status'))
        {
            $res = true;
        }
        return $res;
    }
}
/**************** Get Login User Role **************/
if(!function_exists('user_role'))
{
    function user_role()
    {
        $CI =& get_instance();
        return $CI->session->userdata('rolename'); //SuperAdmin, Admin, Sales Manager, Marketing Manager, Operator, Rider, Moderator, Franchise, Team Leader, Manager
    }
}


/**************** Get Login User Role Id **************/
if(!function_exists('user_role_id'))
{
    function user_role_id()
    {
        $CI =& get_instance();
        return $CI->session->userdata('roleid');
    }
}

/**************** Get Login User Id **************/
if(!function_exists('user_id'))
{
    function user_id()
    {
        $CI =& get_instance();
        return $CI->session->userdata('empid');
    }
}

/**************** Get Login User Name **************/
if(!function_exists('user_name'))
{
    function user_name()
    {
        $CI =& get_instance();
        return $CI->session->userdata('empname');
    }
}

/**************** Get Login User Email **************/
if(!function_exists('user_email'))
{
    function user_email()
    {
        $CI =& get_instance();
        return $CI->session->userdata('empemail');
    }
}

/**************** Get Login User Phone **************/
if(!function_exists('user_phone'))
{
    function user_phone()
    {
        $CI =& get_instance();
        return $CI->session->userdata('empphone');
    }
}
/************************** Get Login User Infromation Methods End Here  ************************/

/******************* Comman Date filter ****************/
//$orderTypeList = (object) array(
//    array('id' => 'laundry', 'name' => 'Laundry'),
//    array('id' => 'dryclean', 'name' => 'Dryclean'),
//    array('id' => 'all', 'name' => 'All'),
//);
//
//$filter = (object) array(
//    'title' => 'Filter Orders',
//    'type' => 'date' ,
//    'cantrols' => (object) array(
//        array('label' => 'From Date','name' => 'from_date','id' => 'from_date', 'class' => '', 'type' => 'date'),
//        array('label' => 'To Date','name' => 'to_date','id' => 'to_date', 'class' => '', 'type' => 'date'),
//        array('label' => 'Order Type','name' => 'order_type','id' => 'order_type', 'class' => '', 'type' => 'select','options' => $orderTypeList),
//    )
//);