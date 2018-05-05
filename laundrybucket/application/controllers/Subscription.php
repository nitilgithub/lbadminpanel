<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'subscription/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Subscriptions','url' => base_url().$this->midUrl.'subscription'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Subscription Name','Subscription Service','Subscription Garment','Subscription Cost','Subscription Weight','Validity','Extra Weight Cost','Subscription Max Pickup','Extra Pickup Cost','Remark');
        $this->perPage = 20;
    }
	/********************** Get Subscription List Method Start Here************************/
	public function index()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetSubscriptionCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetSubscriptionList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Subscription Name', 'value' => 'subs_name'),
            array('label' => 'Subscription Service', 'value' => 'Subs_ServiceType'),
            array('label' => 'Subscription Garment', 'value' => 'Subs_GarmentType'),
            array('label' => 'Subscription Cost', 'value' => 'subs_cost'),
            array('label' => 'Subscription Weight', 'value' => 'subs_wt'),
            array('label' => 'Validity', 'value' => 'subs_validity'),
            array('label' => 'Extra Weight Cost', 'value' => 'subs_extra_wt_cost'),
            array('label' => 'Subscription Max Pickup', 'value' => 'subs_maxpickup'),
            array('label' => 'Extra Pickup Cost', 'value' => 'subs_extra_pickup_cost'),
            array('label' => 'Remark', 'value' => 'Remark'),
        );

        $data['encode'] = $this;
        $data['title'] = "Subscription | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
        if(user_role() == 'SuperAdmin') {
            $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }
//        $data['view'] = $this->mbase.'view/';

        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./subscription/view',$data);

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
        $url = $this->apiurl.$this->mbase."GetSubscriptionCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetSubscriptionList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;

        if(user_role() == 'SuperAdmin') {
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./subscription/data-table',$data);
    }
    /********************** Get Subscription List Method End Here************************/
	/*********************** Latest Inactive User Subscription Method Start Here ************************/
    public function latestInactive()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Inactive Subscriptions','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubscriptionCount?key=".$key;

        $pData = $this->input->post();
        $pData['subs_status'] = 'inactive';

        $url = $this->appendFilter($pData,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getUserSubscriptionList?key=".$key."&limit=".$this->perPage;

        $pData = $this->input->post();
        $pData['subs_status'] = 'inactive';

        $url = $this->appendFilter($pData,$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $searchOption = array(
            array('label' => 'Subscription Name', 'value' => 'subs_name'),
            array('label' => 'Subscription Service', 'value' => 'Subs_ServiceType'),
            array('label' => 'Subscription Garment', 'value' => 'Subs_GarmentType'),
            array('label' => 'Subscription Cost', 'value' => 'subs_cost'),
            array('label' => 'Subscription Weight', 'value' => 'subs_wt'),
            array('label' => 'Validity', 'value' => 'subs_validity'),
            array('label' => 'Extra Weight Cost', 'value' => 'subs_extra_wt_cost'),
            array('label' => 'Subscription Max Pickup', 'value' => 'subs_maxpickup'),
            array('label' => 'Extra Pickup Cost', 'value' => 'subs_extra_pickup_cost'),
            array('label' => 'Remark', 'value' => 'Remark'),
        );

        $data['encode'] = $this;
        $data['title'] = "Inactive Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Inactive Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->userSubTHead();
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $this->filterUserSubResult($result);
        $data['ajaxfunc'] = 'ajaxPaginationLatestInactiveData';

        if(user_role() == 'SuperAdmin') {
            $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }

//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./subscription/view',$data);
    }

    public function ajaxPaginationLatestInactiveData()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubscriptionCount?key=".$key;

        $pData = $this->input->post();
        $pData['subs_status'] = 'inactive';

        $url = $this->appendFilter($pData,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubscriptionList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $pData = $this->input->post();
        $pData['subs_status'] = 'inactive';

        $url = $this->appendFilter($pData,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Inactive Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Inactive Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->userSubTHead();
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $this->filterUserSubResult($result);
        $data['cstart'] = $offset+1;
        $data['ajaxfunc'] = 'ajaxPaginationLatestInactiveData';
        if(user_role() == 'SuperAdmin') {
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./subscription/data-table',$data);
    }
	/*********************** Latest Inactive User Subscription Method End Here ************************/
	/*********************** Latest Active User Subscription Method Start Here ************************/
	protected  function userSubTHead()
    {
        return array('User Id','Client Name','Subscription','Subscribe Date','Start Date','End Date','Validity','Used Weight','Remaining Weight','Used Pickup','Remaining Pickup','Status');
    }

    protected function userSubIsDate()
    {
        return array('subscribedate','startdate','enddate');
    }

    protected function  filterUserSubResult($result)
    {
        $newResult = array();

        foreach ($result as $row)
        {
            $newRow = array();
            $newRow['id'] = $row->id;
            $newRow['userid'] = $row->userid;
            $newRow['clientname'] = $row->clientname;
            $newRow['subscription'] = $row->subscription;
            $newRow['subscribedate'] = $row->subscribedate;
            $newRow['startdate'] = $row->startdate;
            $newRow['enddate'] = $row->enddate;

            $date1=date_create($row->startdate);
            $date2=date_create($row->enddate);

            $diff=date_diff($date1,$date2);
            $validity=$diff->format("%a days");

            $newRow['validity'] = $validity;
            $newRow['usedweight'] = $row->usedweight;
            $newRow['subscriptionweight'] = $row->subscriptionweight;
            $newRow['usedpickup'] = $row->max_pickup;

            $newRow['remainingpickup'] = (int) $row->subs_maxpickup - (int) $row->max_pickup;
            $newRow['status'] = $row->subscriptionstatus;


            if($row->subscriptionweight == 'Unlimited' || $row->subscriptionweight == 'unlimited')
            {
                $newRow['remainingweight'] = 'Unlimited';
            }else{
                $sweight = (int) $row->subscriptionweight;
                $usedweight = (int) $row->usedweight;
                $newRow['remainingweight'] = $sweight - $usedweight;
            }

            array_push($newResult, (object)$newRow);
        }
        return $newResult;
    }
    public function active()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Activated Subscriptions','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubscriptionCount?key=".$key;

        $pData = $this->input->post();
        $pData['subs_status'] = 'activated';

        $url = $this->appendFilter($pData,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getUserSubscriptionList?key=".$key."&limit=".$this->perPage;

        $pData = $this->input->post();
        $pData['subs_status'] = 'activated';

        $url = $this->appendFilter($pData,$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $searchOption = array(
            array('label' => 'Subscription Name', 'value' => 'subs_name'),
            array('label' => 'Subscription Service', 'value' => 'Subs_ServiceType'),
            array('label' => 'Subscription Garment', 'value' => 'Subs_GarmentType'),
            array('label' => 'Subscription Cost', 'value' => 'subs_cost'),
            array('label' => 'Subscription Weight', 'value' => 'subs_wt'),
            array('label' => 'Validity', 'value' => 'subs_validity'),
            array('label' => 'Extra Weight Cost', 'value' => 'subs_extra_wt_cost'),
            array('label' => 'Subscription Max Pickup', 'value' => 'subs_maxpickup'),
            array('label' => 'Extra Pickup Cost', 'value' => 'subs_extra_pickup_cost'),
            array('label' => 'Remark', 'value' => 'Remark'),
        );

        $data['encode'] = $this;
        $data['title'] = "Activated Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Activated Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->userSubTHead();
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $this->filterUserSubResult($result);
        $data['ajaxfunc'] = 'ajaxPaginationActiveData';
        if(user_role() == 'SuperAdmin') {
            $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./subscription/view',$data);

    }

    public function ajaxPaginationActiveData()
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
        $url = $this->apiurl.$this->mbase."getUserSubscriptionCount?key=".$key;

        $pData = $this->input->post();
        $pData['subs_status'] = 'activated';

        $url = $this->appendFilter($pData,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubscriptionList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $pData = $this->input->post();
        $pData['subs_status'] = 'activated';

        $url = $this->appendFilter($pData,$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Activated Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Activated Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->userSubTHead();
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $this->filterUserSubResult($result);
        $data['cstart'] = $offset+1;
        $data['ajaxfunc'] = 'ajaxPaginationActiveData';

        if(user_role() == 'SuperAdmin') {
            $data['edit'] = $this->mbase . 'edit/';
            $data['del'] = $this->mbase . 'delete/';
        }
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./subscription/data-table',$data);
    }

    public function buysubscription()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Buy Subscription","url" => '', 'icon' => ''));

        $uid =  $this->uri->segment('3');
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/"."GetUserById?key=".$key."&id=".$uid;
        $userInfo = $this->callAPI('GET',$url);
        $userInfo = json_decode($userInfo);

        $data['title'] = "Buy Subscription | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['hiddencontrol'] = array('name' => 'userid', 'id' => '' , 'value' => $uid );
        $data['mbase'] = $this->mbase;
        $data['userInfo'] = $userInfo;
        $data['readonly'] = array('totalweight','expdate','totalamount','discountamount','offeramount','payableamount','taxableamount');
        $data['form'] = array("name" => "frm_buy_subscription", "type" => "insert", "action" => $this->mbase."insertbuysubscription","title" => "Buy Subscription", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_buy_subscription","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsBuySubscription() );
        $this->load->view('./subscription/buysubscription',$data);
    }

    public function insertbuysubscription()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();
        $pData['userid'] = dec($pData['userid']);
        $pData['createdby'] = user_id();
        $pData['startdate'] = date('Y-m-d');
        $pData['expdate'] = date('Y-m-d',strtotime($pData['expdate']));
        unset($pData['taxtype']);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getUserSubStatus?key=".$key."&startdate=".$pData['startdate']."&expdate=".$pData['expdate']."&userid=".$pData['userid'];
        $uSubStatus = $this->callAPI('GET',$url);

        if($uSubStatus == 1 )
        {
            $res = array('status' => 0, 'message' => "User Have Allready Subscription");
            $res = json_encode($res);
        }else{
            $key = $this->api->addapikey();
            $url = $this->apiurl.$this->mbase."insertBuySubscription?key=".$key;
            $res = $this->callAPI('POST',$url,$pData);
        }
        echo $res;
    }

    public function formControlsBuySubscription()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getSubscriptionListOption?key=".$key;
        $result = $this->callAPI('GET',$url);
        $subList = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl."offer/getOfferListOption?key=".$key."&OrderTypeId=9";
        $result = $this->callAPI('GET',$url);
        $offerCodeList = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl."franchisee/GetFranchiseeListOption?key=".$key;
        $result = $this->callAPI('GET',$url);
        $franList = json_decode($result);

        return array(
            array("name" => "subid", "lable" => "Select Subscription", "placeholder" => "Subscription Name", "type" => "select", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span12" ,"required" => true, "options" => $subList ),
            array("name" => "totalweight", "lable" => "Total Laundry Weight", "placeholder" => "Total Laundry Weight", "type" => "text", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span12"),
            array("name" => "expdate", "lable" => "Subscription Expiry Date", "placeholder" => "Subscription Expiry Date", "type" => "date", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span12"),
            array("name" => "totalamount", "lable" => "Total Amount", "placeholder" => "Total Amount", "type" => "text", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "", "lable" => "", "placeholder" => "Total Amount", "type" => "blank", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span6" ),
//            array("name" => "franchiseeid", "lable" => "Franchisee", "type" => "select", "class" => "span8", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $franList ),
            array("name" => "discount", "lable" => "Discount", "placeholder" => "Discount", "type" => "discount", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
            array("name" => "offercodeid", "lable" => "Offer Codes", "placeholder" => "Offer Codes", "type" => "offer", "class" => "span5", "id" => "", "size" => "", "con-group-class" => "span12", "options" => $offerCodeList ),
            array("name" => "remarks", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
            array("name" => "tax", "lable" => "GST (18%)", "type" => "tax", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
            array("name" => "taxableamount", "lable" => "Taxable Amount", "placeholder" => "Taxable Amount", "type" => "text", "class" => "span3", "id" => "", "size" => "", "con-group-class" => "span12" ),
            array("name" => "", "lable" => "GST Amount", "placeholder" => "GST Amount", "type" => "gst", "class" => "span3", "id" => "", "size" => "", "con-group-class" => "span12" ),
            array("name" => "payableamount", "lable" => "Payable Amount", "placeholder" => "Payable Amount", "type" => "text", "class" => "span3", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );
    }

	/*********************** Latest Active User Subscription Method End Here ************************/
	public function add()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Add New Subscription","url" => '', 'icon' => ''));

		$data['title'] = "Add New Subscription | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_subscription", "type" => "insert", "action" => $this->mbase."insert","title" => "Add New Subscription", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_user","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/formfull',$data);
	}

	public function edit()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Update Subscription","url" => '', 'icon' => ''));

		$id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getSubscriptionById?key=".$key."&id=".$id;

        $subsData = $this->callAPI('GET',$url);
        $data['dataid'] = $id;
		$data['values'] = json_decode($subsData);
		$data['title'] = "Update Subscription | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_subscription", "type" => "update", "action" => $this->mbase."update","title" => "Update Subscription", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_school","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/formfull',$data);
	}

    public function formControls()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

//		$this->load->model('Role_model');
        $knowList = array((object) array('id' => 1, 'name' => 'Google Search Engine'),(object) array('id' => 2, 'name' => 'Facebook'),(object) array('id' => 3, 'name' => 'Pamphlet'),(object) array('id' => 4, 'name' => 'Reference'),(object) array('id' => 5, 'name' => 'Others'));
        $franList = array((object) array('id' => 1, 'name' => 'Laundry Bucket'),(object) array('id' => 2, 'name' => 'Olive County'));

        return array(
            array("name" => "subs_name", "lable" => "Name", "placeholder" => "Subscription Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "Subs_ServiceType", "lable" => "Service Type", "placeholder" => "Like Wash & Iron", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "Subs_GarmentType", "lable" => "Garment Type", "placeholder" => "Like Apparels and Household", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "subs_cost", "lable" => "Subscription Cost ( ₹ )", "placeholder" => "Subscription Cost", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "subs_wt", "lable" => "Subscription Weight ( kg )", "placeholder" => "Subscription Weight", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "subs_extra_wt_cost", "lable" => "Extra Weight Cost (Per kg)", "placeholder" => "Extra Weight Cost", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "subs_maxpickup", "lable" => "Maximum Pickup", "placeholder" => "Subscription Maximum Pickup", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "subs_extra_pickup_cost", "lable" => "Extra Cost (per pickup)", "placeholder" => "Extra Pickup Cost", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "subs_validity", "lable" => "Validity (in days)", "placeholder" => "Validity", "type" => "number", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "Remark", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );
    }
//
	public function insert()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		$pData = $this->input->post();
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insert?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
	}

	public function update()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."update?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
	}

	public function delete()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."delete?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
        $url = base_url().midUrl().$this->mbase;
        redirect($url);
	}

	public function getSubTypeInfo()
    {
        $id = $this->input->get('id');
        $id = enc($id);
//        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getSubscriptionById?key=".$key."&id=".$id;
        $subsData = $this->callAPI('GET',$url);
        echo $subsData;
    }

    public function getOfferInfo()
    {
        $id = $this->input->get('id');
        $id = enc($id);
//        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl."offer/getOfferById?key=".$key."&id=".$id;
        $offerData = $this->callAPI('GET',$url);
        echo $offerData;
    }
}