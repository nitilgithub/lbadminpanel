<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'order/';
	protected $reURL = null;
	protected $tHead = null;
	protected $isDate = null;
	protected $isBool = null;

	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Orders','url' => base_url().midurl().'order'));
		$this->reURL = base_url().midurl().$this->mbase;
        $this->tHead = array('Receipt ID','Order Pickup Date','Pickup Address','Order City');
        $this->isDate = array('orderpickupdate');
        $this->perPage = 20;
//        echo check_login();die;
    }

    /****************** Get All Orders Star Here ******************/
	public function index()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

//        $oid = 1;
//        $pickdate = '29-01-2018';
//        $picktime = '10AM-01PM';
//        $name = 'Bheem';
//        $phone = '9729039244';
//        $txtmsg="Laundry Bucket Order Placed Id $oid . Date $pickdate . Pickup $picktime . Client $name . Ph $phone .";
//
//        echo $this->sendSMS($phone,$txtmsg);die;

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOrderCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOrderList?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));




        $data['encode'] = $this;
        $data['title'] = "All Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "All Orders ";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationData';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['datefilter'] = true;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
//        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationData()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOrderCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);


        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOrderList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationData';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get All Orders End Here ******************/
    /****************** Get Today Orders Star Here ******************/
    public function toDayPickUpOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayPickUpOrderCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetTodayPickUpOrder?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Today Pickup Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Today Pickup Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataTodayPickUp';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataTodayPickUp()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $filter = $this->input->post('filter');
        $keyword = $this->input->post('keywords');


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayPickUpOrderCount?key=".$key;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayPickUpOrder?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataToday';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Today Orders End Here ******************/
    /****************** Get Today Deliver Orders Star Here ******************/
    public function toDayDeliverOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Today Deliver Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayDeliverOrderCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetTodayDeliverOrder?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Today Deliver Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Today Deliver Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataTodayDeliver';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataTodayDeliver()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $filter = $this->input->post('filter');
        $keyword = $this->input->post('keywords');


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayDeliverOrderCount?key=".$key;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetTodayDeliverOrder?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataTodayDeliver';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Today Deliver Orders End Here ******************/
    /****************** Get Pending PickUp Orders Star Here ******************/
    public function pendingPickUpOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Pending Pickup Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingPickUpOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetPendingPickUpOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Pending Pickup Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Pending Pickup Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataPendingPickUp';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataPendingPickUp()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $filter = $this->input->post('filter');
        $keyword = $this->input->post('keywords');


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingPickUpOrdersCount?key=".$key;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingPickUpOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataPendingPickUp';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Pending PickUp Orders End Here ******************/
    /****************** Get Pending Deliveries Orders Star Here ******************/
    public function pendingDeliveriesOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Pending Deliveries Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingDeliveriesOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetPendingDeliveriesOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Pending Deliveries Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Pending Deliveries Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataPendingDeliveries';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataPendingDeliveries()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingDeliveriesOrdersCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingDeliveriesOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataPendingDeliveries';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Pending Deliveries Orders End Here ******************/
    /****************** Get Canceled Orders Star Here ******************/
    public function canceledOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Canceled Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetCanceledOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetCanceledOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Canceled Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Canceled Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataCanceled';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['datefilter'] = true;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
//        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataCanceled()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetCanceledOrdersCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetCanceledOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataCanceled';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Canceled Orders End Here ******************/
    /****************** Get Combo Offer Orders Star Here ******************/
    public function comboOfferOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Combo Offer Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetComboOfferOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetComboOfferOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Combo Offer Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Combo Offer Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataComboOffer';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['datefilter'] = true;
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetComboOfferOrdersCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetComboOfferOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataComboOffer';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Combo Offer Orders End Here ******************/
    /****************** Get All Old Orders Star Here ******************/
    public function allOldOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "All Old Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetAllOldOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetAllOldOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $data['encode'] = $this;
        $data['title'] = "All Old Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "All Old Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataAllOld';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['datefilter'] = true;
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataAllOld()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetAllOldOrdersCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetAllOldOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataAllOld';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get All Old Orders End Here ******************/
    /****************** Get Filtered Orders Star Here ******************/
    public function filteredOrders()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Filtered Orders","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetFilteredOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $data['encode'] = $this;
        $data['title'] = "Filtered Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Filtered Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataFiltered';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['datefilter'] = true;
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataFiltered()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrdersCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataFiltered';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /****************** Get Filtered Orders End Here ******************/
    /****************** Get Filtered Orders Star Here ******************/
    public function ordersExcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Filter Orders & Export Excel","url" => '', 'icon' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrdersCount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetFilteredOrders?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Filter Orders & Export Excel | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Filter Orders & Export Excel";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataOrdersExcel';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['btnExpExcel'] = (object) array('lable'=> 'Download Excel Report','url'=> $this->mbase.'genrateordersexcel');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataOrdersExcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $filter = $this->input->post('filter');
        $keyword = $this->input->post('keywords');


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrdersCount?key=".$key;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFilteredOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataOrdersExcel';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['btnExpExcel'] = (object) array('lable'=> 'Download Excel Report','url'=> $this->mbase.'genrateordersexcel');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }

    public function genrateOrdersExcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $excHead = array('Receipt Id','Order PickUp Date','PickUp Address','Order City');
        $excData = array();
        $excName = "Test";

        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetOrderList?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        foreach ($result as $row)
        {
            array_push($excData,array(
                    !empty($row->receiptid) ? $row->receiptid : '',
                    !empty($row->orderpickupdate) ? $row->orderpickupdate : '',
                    !empty($row->pickupaddress) ? $row->pickupaddress : '',
                    !empty($row->ordercity) ? $row->ordercity : ''
                )
            );
        }

        $this->writeExcel($excHead,$excData,$excName);
    }

    /****************** Get Filtered Orders End Here ******************/

    /**************** User Order Dashboard Method Start Here ***************/
    public function userOrderDashboard()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "User Orders Dashboard","url" => '', 'icon' => ''));
        $id =  $this->uri->segment('3');


        // Get Order User Info
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$id;

        $userInfo = $this->callAPI('GET',$url);

        // Get Order Info
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetUserOrders?key=".$key."&id=".$id;

        $orderInfo = $this->callAPI('GET',$url);

        $data['encode'] = $this;
        $data['title'] = "User Orders Dashboard | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders Dashboard";
        $data['pagination'] = true;
        $data['thead'] = array('Order ID','Order Date','Order Total Amount','Order Paid Amount','Order Remarks');
        $data['mbase'] = $this->mbase;
//        $data['ajaxfunc'] = 'ajaxPaginationDataUserOrderHistory';
        $data['isbool'] = array('status');
        $data['userInfo'] = json_decode($userInfo);
        $data['orderInfo'] = json_decode($orderInfo);
        $data['isdate'] = $this->isDate;
//        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'userorderedit/';
//        $data['del'] = $this->mbase.'delete/';
        $data['paynow'] = 'payment/userpaynow/';
        $this->load->view('./comman/invoice/view-invoice',$data);
    }
    /**************** User Order Dashboard Method Start Here ***************/

    /**************** User Order History Method Start Here ***************/

    public function userOrderHistory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "User Orders History","url" => '', 'icon' => ''));

        $id =  $this->uri->segment('3');

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetUserOrdersCount?key=".$key."&id=".$id;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetUserOrders?key=".$key."&id=".$id."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "User Orders History | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "User Orders History";
        $data['pagination'] = true;
        $data['thead'] = array('Order ID','Order Date','Order Total Amount','Order Paid Amount','Order Remarks');
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataUserOrderHistory';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Order','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationDataUserOrderHistory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        $filter = $this->input->post('filter');
        $keyword = $this->input->post('keywords');


        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetAllOldOrdersCount?key=".$key;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetAllOldOrders?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->addFilter($filter,$url);

        $url = $this->addKeywords($keyword,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataUserOrderHistory';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /**************** User Order History Method End Here ***************/

    public function add()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Add Order","url" => '', 'icon' => ''));

		$data['title'] = "Add Order | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_order", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Order", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_order","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/formfull',$data);
	}

    /************ User Place Order Start Here ***********/
    public function UserOrderPlace()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Place User Order","url" => '', 'icon' => ''));

        // Get Order Info
//        $id =  $this->uri->segment('3');
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetOrderById?key=".$key."&id=".$id;
//        $orderInfo = $this->callAPI('GET',$url);
//        $orderInfo = json_decode($orderInfo);

        // Get Order User Info
        $uid =  $this->uri->segment('3');
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$uid;
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        // Get User Order Remarks
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetUserOrderRemark?key=".$key."&oid=".$id."&uid=".$uid;
//        $userOrderRemark = $this->callAPI('GET',$url);
//        $userOrderRemark = json_decode($userOrderRemark);

        // Get Pickup Time List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderPickupTime?key=".$key;
        $pickTimeList = $this->callAPI('GET',$url);
        $pickTimeList = json_decode($pickTimeList);

        // Get Employees Roles List
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getEmployeesRole?key=".$key;
        $empRoleList = $this->callAPI('GET',$url);
        $empRoleList = json_decode($empRoleList);

        // Get Order Status List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderStatusList?key=".$key;
        $orderStatusList = $this->callAPI('GET',$url);
        $orderStatusList = json_decode($orderStatusList);

        // Get Franchise List

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getFranchiseList?key=".$key;
        $franchiseList = $this->callAPI('GET',$url);
        $franchiseList = json_decode($franchiseList);
        array_unshift($franchiseList,(object) array('id' => 1, 'name' => 'Laundry Bucket'));


        $processTypeList = array((object) array('id' => 'fresh', 'name' => 'Fresh Order'),(object) array('id' => 'reprocess', 'name' => 'Reprocess Order'));

        $cityList = array((object) array('id' => 'Noida', 'name' => 'Noida'),(object) array('id' => 'Greater Noida', 'name' => 'Greater Noida'), (object) array('id' => 'Gaziabad', 'name' => 'Gaziabad'),(object) array('id' => 'East Delhi', 'name' => 'East Delhi'),(object) array('id' => 'Other', 'name' => 'Other'));

        $frmControll = array(
            array("name" => "Order_PickDate", "lable" => "Pickup Date", "placeholder" => "Pickup Date", "type" => "date", "class" => "span11", "id" => "Order_PickDate", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "Order_PickTime", "lable" => "Pickup Time", "placeholder" => "Pickup Time", "type" => "select", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true,"options" => $pickTimeList),
            array("name" => "PickupAddress", "lable" => "Pickup Address", "placeholder" => "Pickup Address", "type" => "textarea", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span12" ,"required" => true),
            array("name" => "OrderStatusId", "lable" => "Order Status", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true,"options" => $orderStatusList),
            array("name" => "RiderId", "lable" => "Order Picked By", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6","options" => $empRoleList, "required" => true ),
            array("name" => "OrderReceiptId", "lable" => "Order Receipt No", "placeholder" => "Order Receipt No", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "OrderReceiptPic", "lable" => "Order Receipt Pic", "type" => "file", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $cityList ),
            array("name" => "franchiseId", "lable" => "Franchise", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $franchiseList, "con-group-class" => "span6" ),
            array("name" => "OrderProcessType", "lable" => "Order Process Type", "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $processTypeList, "con-group-class" => "span6" ),
            array("name" => "Remarks", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );

        $data['title'] = "Place User Order | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['hiddencontrol'] = array('name' => 'OrderUserId', 'id' => '' , 'value' => $uid );

        $data['mbase'] = $this->mbase;
        $data['userInfo'] = $userInfo;
        $data['form'] = array("name" => "frm_place_user_order", "type" => "insert", "action" => $this->mbase."AddUserOrder","title" => "Place User Order", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "place_user_order","lable" => "Place Order", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $frmControll );
        $this->load->view('./orders/userorder',$data);
    }

    public function AddUserOrder()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$pData['OrderUserId'];
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        $pData['OrderCity'] = $userInfo->UserCity;
        $pData['CreatedByName'] = $this->session->userdata('empemail');

        $pData['OrderUserId'] = dec($pData['OrderUserId']);
        if(empty($pData['OrderReceiptPic']))
        {
            unset($pData['OrderReceiptPic']);
        }

        $pData['Order_Via'] = 'website';
        $pData['CreatedBy'] = "admin";

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."PlaceUserOrder?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }
    /************ User Place Order End Here ***********/

    /************ User Order Edit Start Here ***********/
	public function UserOrderEdit()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Update User Order","url" => '', 'icon' => ''));

        // Get Order Info
		$id =  $this->uri->segment('3');
		$key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOrderById?key=".$key."&id=".$id;
        $orderInfo = $this->callAPI('GET',$url);
        $orderInfo = json_decode($orderInfo);

        // Get Order User Info
        $uid =  $this->uri->segment('4');
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$uid;
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        // Get User Order Remarks
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetUserOrderRemark?key=".$key."&oid=".$id."&uid=".$uid;
//        $userOrderRemark = $this->callAPI('GET',$url);
//        $userOrderRemark = json_decode($userOrderRemark);

		// Get Pickup Time List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderPickupTime?key=".$key;
        $pickTimeList = $this->callAPI('GET',$url);
        $pickTimeList = json_decode($pickTimeList);

        // Get Employees Roles List
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getEmployeesRole?key=".$key;
        $empRoleList = $this->callAPI('GET',$url);
        $empRoleList = json_decode($empRoleList);

        // Get Order Status List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderStatusList?key=".$key;
        $orderStatusList = $this->callAPI('GET',$url);
        $orderStatusList = json_decode($orderStatusList);

        // Get Franchise List

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getFranchiseList?key=".$key;
        $franchiseList = $this->callAPI('GET',$url);
        $franchiseList = json_decode($franchiseList);
        array_unshift($franchiseList,(object) array('id' => 1, 'name' => 'Laundry Bucket'));


        $processTypeList = array((object) array('id' => 'fresh', 'name' => 'Fresh Order'),(object) array('id' => 'reprocess', 'name' => 'Reprocess Order'));

        $cityList = array((object) array('id' => 'Noida', 'name' => 'Noida'),(object) array('id' => 'Greater Noida', 'name' => 'Greater Noida'), (object) array('id' => 'Gaziabad', 'name' => 'Gaziabad'),(object) array('id' => 'East Delhi', 'name' => 'East Delhi'),(object) array('id' => 'Other', 'name' => 'Other'));

        $frmControll = array(
            array("name" => "Order_PickDate", "lable" => "Pickup Date", "placeholder" => "Pickup Date", "type" => "date", "class" => "span11", "id" => "Order_PickDate", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "Order_PickTime", "lable" => "Pickup Time", "placeholder" => "Pickup Time", "type" => "select", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true,"options" => $pickTimeList),
            array("name" => "PickupAddress", "lable" => "Pickup Address", "placeholder" => "Pickup Address", "type" => "textarea", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span12" ,"required" => true),
            array("name" => "OrderStatusId", "lable" => "Order Status", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true,"options" => $orderStatusList),
            array("name" => "RiderId", "lable" => "Order Picked By", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6","options" => $empRoleList, "required" => true ),
            array("name" => "OrderReceiptId", "lable" => "Order Receipt No", "placeholder" => "Order Receipt No", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "OrderReceiptPic", "lable" => "Order Receipt Pic", "type" => "file", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $cityList ),
            array("name" => "franchiseId", "lable" => "Franchise", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $franchiseList, "con-group-class" => "span6" ),
            array("name" => "OrderProcessType", "lable" => "Order Process Type", "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $processTypeList, "con-group-class" => "span6" ),
            array("name" => "Remarks", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );

		$data['title'] = "Update User Order | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['dataid'] = $id;
        $data['mbase'] = $this->mbase;
		$data['userInfo'] = $userInfo;
		$data['values'] = $orderInfo;
		$data['form'] = array("name" => "frm_update_user_order", "type" => "update", "action" => $this->mbase."UpdateUserOrder","title" => "Update User Order", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_user_order","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $frmControll );
		$this->load->view('./orders/userorder',$data);
	}

    public function UpdateUserOrder()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();
        if(empty($pData['OrderReceiptPic']))
        {
            unset($pData['OrderReceiptPic']);
        }


        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."UpdateUserOrder?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }
    /************ User Order Edit End Here ***********/

    /************ Order Edit Start Here ***********/
//    public function Edit()
//    {
//        array_push($this->bCrumb, array('title' => "Update User","url" => '', 'icon' => ''));
//
//        $id =  $this->uri->segment('3');
//        $id = $this->dec($id);
//
//        $data['readonly'] = array('password');
//        $data['values'] = $this->User_model->getUserById($id);
//        $data['title'] = "Update User | ".$this->pageTitle;
//        $data['breadcrumb'] = $this->bCrumb;
//        $data['form'] = array("name" => "frm_update_school", "type" => "update", "action" => $this->mbase."update","title" => "Update User", "id" => "", "class" => "form-horizontal",
//            "submit" => array("name" => "update_school","lable" => "Update", "id" => "", "class" => "btn btn-success"),
//            "controles" => $this->formControls() );
//        $this->load->view('./comman/data/form',$data);
//    }
    /************ Order Edit End Here ***********/
    public function formControls()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

//        $this->load->model('Role_model');
        $knowList = array((object) array('id' => 1, 'name' => 'Google Search Engine'),(object) array('id' => 2, 'name' => 'Facebook'),(object) array('id' => 3, 'name' => 'Pamphlet'),(object) array('id' => 4, 'name' => 'Reference'),(object) array('id' => 5, 'name' => 'Others'));
        $franList = array((object) array('id' => 1, 'name' => 'Laundry Bucket'),(object) array('id' => 2, 'name' => 'Olive County'));
        $cityList = array((object) array('id' => 'Noida', 'name' => 'Noida'),(object) array('id' => 'Greater Noida', 'name' => 'Greater Noida'), (object) array('id' => 'Gaziabad', 'name' => 'Gaziabad'),(object) array('id' => 'East Delhi', 'name' => 'East Delhi'),(object) array('id' => 'Other', 'name' => 'Other'));

        return array(
            array("name" => "cust_email", "lable" => "Customer Email", "placeholder" => "Customer Email Id", "type" => "email", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "cust_mobile", "lable" => "Mobile", "placeholder" => "Customer Mobile No.", "type" => "text", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true),
            array("name" => "cust_alt_mobile", "lable" => "Alternate Mobile", "placeholder" => "Customer Another Mobile No.", "type" => "text", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true),
            array("name" => "first_name", "lable" => "Customer First Name", "placeholder" => "Customer First Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "last_name", "lable" => "Customer Last Name", "placeholder" => "Customer Last Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "pickup_address", "lable" => "Pickup Address", "placeholder" => "Pickup Address", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ,"required" => true),
            array("name" => "city", "lable" => "City", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $cityList ),
            array("name" => "know_about", "lable" => "How user know about us?", "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $knowList, "con-group-class" => "span6" ),
            array("name" => "franchise", "lable" => "Franchise", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $franList, "con-group-class" => "span6" )
        );
    }

    public function uploadReceipt()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        if(!empty($_FILES['file']['name']))
        {
            $file = $this->do_upload('file');

            if($file['completed'] == false){
                echo json_encode(array('status' => 0, 'message' => $file['error']));
            }else{
                echo json_encode(array('status' => 1, 'file' => $file));
            }
        }else{
            echo json_encode(array('status' => 0, 'message' => 'Please Select File!'));
        }
    }

    /***************************** User Create Order *******************************/

    public function CreateUserOrder()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Create User Order","url" => '', 'icon' => ''));

        // Get Order Info
//        $id =  $this->uri->segment('3');
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetOrderById?key=".$key."&id=".$id;
//        $orderInfo = $this->callAPI('GET',$url);
//        $orderInfo = json_decode($orderInfo);

        // Get Order User Info
        $uid =  $this->uri->segment('3');
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$uid;
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        // Get User Order Remarks
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetUserOrderRemark?key=".$key."&oid=".$id."&uid=".$uid;
//        $userOrderRemark = $this->callAPI('GET',$url);
//        $userOrderRemark = json_decode($userOrderRemark);

        // Get Pickup Time List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderPickupTime?key=".$key;
        $pickTimeList = $this->callAPI('GET',$url);
        $pickTimeList = json_decode($pickTimeList);

        // Get Employees Roles List
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getEmployeesRole?key=".$key;
        $empRoleList = $this->callAPI('GET',$url);
        $empRoleList = json_decode($empRoleList);

        // Get Order Status List
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderStatusList?key=".$key;
        $orderStatusList = $this->callAPI('GET',$url);
        $orderStatusList = json_decode($orderStatusList);

        // Get Franchise List

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getFranchiseList?key=".$key;
        $franchiseList = $this->callAPI('GET',$url);
        $franchiseList = json_decode($franchiseList);
        array_unshift($franchiseList,(object) array('id' => 1, 'name' => 'Laundry Bucket'));


        $processTypeList = array((object) array('id' => 'fresh', 'name' => 'Fresh Order'),(object) array('id' => 'reprocess', 'name' => 'Reprocess Order'));

        $cityList = array((object) array('id' => 'Noida', 'name' => 'Noida'),(object) array('id' => 'Greater Noida', 'name' => 'Greater Noida'), (object) array('id' => 'Gaziabad', 'name' => 'Gaziabad'),(object) array('id' => 'East Delhi', 'name' => 'East Delhi'),(object) array('id' => 'Other', 'name' => 'Other'));


        $data['title'] = "Create User Order | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['hiddencontrol'] = array('name' => 'OrderUserId', 'id' => '' , 'value' => $uid );

        $data['mbase'] = $this->mbase;
        $data['userInfo'] = $userInfo;
        $data['readonly'] = array('deliverydate');

        $data['form'] = array("name" => "frm_place_user_order", "type" => "insert", "action" => $this->mbase."AddUserOrder","title" => "Create User Order", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "place_user_order","lable" => "Create Order", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formCreateOrderControls() );
        $this->load->view('./orders/userorder',$data);
    }

    public function UserOrderDetail()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Create User Order","url" => '', 'icon' => ''));

        // Get Order Info
//        $id =  $this->uri->segment('3');
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetOrderById?key=".$key."&id=".$id;
//        $orderInfo = $this->callAPI('GET',$url);
//        $orderInfo = json_decode($orderInfo);

        // Get Order User Info
        $uid =  $this->uri->segment('3');
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$uid;
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        // Get User Order Remarks
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."GetUserOrderRemark?key=".$key."&oid=".$id."&uid=".$uid;
//        $userOrderRemark = $this->callAPI('GET',$url);
//        $userOrderRemark = json_decode($userOrderRemark);

        // Get Pickup Time List
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."getOrderPickupTime?key=".$key;
//        $pickTimeList = $this->callAPI('GET',$url);
//        $pickTimeList = json_decode($pickTimeList);

        // Get Employees List
        $key = $this->api->addapikey();
        $url = $this->apiurl."employee/getEmployeeListOption?key=".$key;
        $empList = $this->callAPI('GET',$url);
        $empList = json_decode($empList);

        // Get Order Status List
//        $key = $this->api->addapikey();
//        $url = $this->apiurl.$this->mbase."getOrderStatusList?key=".$key;
//        $orderStatusList = $this->callAPI('GET',$url);
//        $orderStatusList = json_decode($orderStatusList);

        // Get Franchise List

//        $key = $this->api->addapikey();
//        $url = $this->apiurl."user/getFranchiseList?key=".$key;
//        $franchiseList = $this->callAPI('GET',$url);
//        $franchiseList = json_decode($franchiseList);
//        array_unshift($franchiseList,(object) array('id' => 1, 'name' => 'Laundry Bucket'));


        // Get Services List
        $key = $this->api->addapikey();
        $url = $this->apiurl."service/GetServiceList?key=".$key;
        $serviceList = $this->callAPI('GET',$url);
        $serviceList = json_decode($serviceList);

        $newSerList = array();
        foreach ($serviceList as $list)
        {
            if($list->name != "Subscription")
            {
                array_push($newSerList, (object) array('id' => $list->id, 'name' => $list->name ) );
            }
        }

        // Get Services Category List
        $key = $this->api->addapikey();
        $url = $this->apiurl."service/getServiceCategoryList?key=".$key;
        $serviceCatList = $this->callAPI('GET',$url);
        $serviceCatList = json_decode($serviceCatList);

        // Get Services Item List
        $key = $this->api->addapikey();
        $url = $this->apiurl."service/getServiceItemList?key=".$key;
        $serviceItemList = $this->callAPI('GET',$url);
        $serviceItemList = json_decode($serviceItemList);

        // Get Subscription List
        $key = $this->api->addapikey();
        $url = $this->apiurl."Subscription/getUserSubsListOption?key=".$key;
        $subscriptionList = $this->callAPI('GET',$url);
        $subscriptionList = json_decode($subscriptionList);


        $quantityList = array();
        for($i=1;$i <= 30; $i++ )
        {
            array_push($quantityList, (object) array('id' => $i, 'name' => $i));
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl."offer/getOfferListOption?key=".$key;
        $result = $this->callAPI('GET',$url);
        $offerCodeList = json_decode($result);


        $extraChargeList = array((object) array('id' => 'transport', 'name' => 'Transport'),);

        $paymentStatusList = array((object) array('id' => 'unpaid', 'name' => 'UNPAID'),(object) array('id' => 'paid', 'name' => 'PAID'), (object) array('id' => 'inprocess', 'name' => 'INPROCESS'));


        $data['title'] = "Create User Order | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['hiddencontrol'] = array('name' => 'OrderUserId', 'id' => '' , 'value' => $uid );

        $data['mbase'] = $this->mbase;
        $data['userInfo'] = $userInfo;
        $data['readonly'] = array('deliverydate');

        $data['serviceList'] = $newSerList;
        $data['serviceCatList'] = $serviceCatList;
        $data['serviceItemList'] = $serviceItemList;
        $data['subscriptionList'] = $subscriptionList;
        $data['quantityList'] = $quantityList;
        $data['offerCodeList'] = $offerCodeList;
        $data['extraChargeList'] = $extraChargeList;
        $data['paymentStatusList'] = $paymentStatusList;
        $data['empList'] = $empList;

        $data['form'] = array("name" => "frm_place_user_order", "type" => "insert", "action" => $this->mbase."AddUserOrder","title" => "Create User Order", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "place_user_order","lable" => "Create Order", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formCreateOrderControls() );
        $this->load->view('./orders/orderdetail',$data);
    }



    public function formCreateOrderControls()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/GetDeliveryTypeListOptions?key=".$key;
        $result = $this->callAPI('GET',$url);
        $devTypeList = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl."franchisee/GetFranchiseeListOption?key=".$key;
        $result = $this->callAPI('GET',$url);
        $franList = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/GetOrderViaListOptions?key=".$key;
        $result = $this->callAPI('GET',$url);
        $orderViaList = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl."service/GetServiceList?key=".$key;
        $result = $this->callAPI('GET',$url);
        $OrderTypeList = json_decode($result);

        $userid = $this->getFirstParam();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getIUserAddressListOption?key=".$key."&userid=".$userid;
        $result = $this->callAPI('GET',$url);
        $userAddressList = json_decode($result);

        return array(
            array("name" => "pickupdate", "lable" => "Pickup Date", "placeholder" => "Pickup Date", "type" => "date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "deliverydate", "lable" => "Delivery Date", "placeholder" => "Delivery Date", "type" => "date", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true),
            array("name" => "deliverytype", "lable" => "Delivery Type",  "type" => "select", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true, "options" => $devTypeList, "default" => 1),
            array("name" => "remarks", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "address", "lable" => "Address", "placeholder" => "Address", "type" => "address", "class" => "span9", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true, "options" => $userAddressList),
            array("name" => "franchisee", "lable" => "Franchisee", "placeholder" => "Franchisee", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true , "options" => $franList),
            array("name" => "ordervia", "lable" => "Order Via", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $orderViaList ),
            array("name" => "ordertype", "lable" => "Order Type", "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $OrderTypeList, "con-group-class" => "span6" ),
        );
    }

    public function getDeliveryTypeInfo()
    {
        $id = $this->input->get('id');
        $id = enc($id);
//        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/getDeliveryTypeById?key=".$key."&id=".$id;
        $Data = $this->callAPI('GET',$url);
        echo $Data;
    }

    public function addUserAddress()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();
        $pData['UserId'] = dec($pData['UserId']);

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/addUserAddress?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function GetUserOrderListOptions()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."UserOrderListOption?key=".$key."&userid=".$pData['userid'];

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;

    }
}