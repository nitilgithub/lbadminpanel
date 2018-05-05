<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'service/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Service','url' => base_url().$this->midUrl.'service'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name');
        $this->perPage = 20;
    }
	/********************** Get Service List Method Start Here************************/
	public function index()
	{

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetServiceCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetServiceList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Service Name', 'value' => 'ServiceName'),
        );

        $data['encode'] = $this;
        $data['title'] = "Services | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Services";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;

        if(user_role() == 'SuperAdmin')
        {
            $data['add'] = array('lable'=> 'Add New Service ','url'=> $this->mbase.'addservice');
            $data['edit'] = $this->mbase.'editservice/';
            $data['del'] = $this->mbase.'deleteservice/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
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
        $url = $this->apiurl.$this->mbase."GetServiceCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetServiceList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Services | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Services";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        if(user_role() == 'SuperAdmin')
        {
            $data['edit'] = $this->mbase.'editservice/';
            $data['del'] = $this->mbase.'deleteservice/';
        }

//        $data['view'] = $this->mbase.'view/';

        $this->load->view('./comman/data/data-table',$data);
    }

    public function formControlsService()
    {
        return array(
            array("name" => "ServiceName", "lable" => "Service Name", "placeholder" => "Service Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ,"required" => true),

        );
    }

    public function addService()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Add New Service","url" => '', 'icon' => ''));

        $data['title'] = "Add New Service | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_service", "type" => "insert", "action" => $this->mbase."insertservice","title" => "Add New Service", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_service","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsService() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function editService()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Update Service","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceById?key=".$key."&id=".$id;

        $servData = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($servData);
        $data['title'] = "Service Category | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_update_service", "type" => "update", "action" => $this->mbase."updateservice","title" => "Update Service", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_service","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsService() );
        $this->load->view('./comman/data/formfull',$data);
    }
//
    public function insertService()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insertService?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function updateService()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateService?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function deleteService()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteService?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase;
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }
    /********************** Get Service List Method End Here************************/
	/*********************** Get Service Category Method Start Here ************************/
    public function category()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Service Category','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceCategoryCount?key=".$key;

        $pData = $this->input->post();

        $url = $this->appendFilter($pData,$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getServiceCategoryList?key=".$key."&limit=".$this->perPage;

        $pData = $this->input->post();
        $pData['subs_status'] = 'inactive';

        $url = $this->appendFilter($pData,$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $searchOption = array(
            array('label' => 'Service Name', 'value' => 'ServiceCatName'),
        );

        $data['encode'] = $this;
        $data['title'] = "Service Category | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Service Category";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
//        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $result;
        $data['ajaxfunc'] = 'ajaxPaginationCategoryData';
        if(user_role() == 'SuperAdmin')
        {
            $data['add'] = array('lable'=> 'Add New Service Category','url'=> $this->mbase.'addcategory');
            $data['edit'] = $this->mbase.'editcategory/';
            $data['del'] = $this->mbase.'deletecategory/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);
    }

    public function ajaxPaginationCategoryData()
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
        $url = $this->apiurl.$this->mbase."getServiceCategoryCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceCategoryList?key=".$key."&limit=".$this->perPage."&start=".$offset;


        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Service Category | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Service Category";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['ajaxfunc'] = 'ajaxPaginationCategoryData';
        if(user_role() == 'SuperAdmin')
        {
            $data['edit'] = $this->mbase.'editcategory/';
            $data['del'] = $this->mbase.'deletecategory/';
        }

//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }

    public function addCategory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Service Category","url" => base_url().midurl().'service/category', 'icon' => ''));
        array_push($this->bCrumb, array('title' => "Add New Service Category","url" => '', 'icon' => ''));

        $data['title'] = "Add New Service Category | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_category", "type" => "insert", "action" => $this->mbase."insertcategory","title" => "Add New Category", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_user","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsCategory() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function editCategory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Service Category","url" => base_url().midurl().'service/category', 'icon' => ''));
        array_push($this->bCrumb, array('title' => "Update Service Category","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceCategoryById?key=".$key."&id=".$id;

        $catData = $this->callAPI('GET',$url);
        $data['dataid'] = $id;
        $data['values'] = json_decode($catData);
        $data['title'] = "Service Category | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_update_category", "type" => "update", "action" => $this->mbase."updateCategory","title" => "Update Service Category", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_category","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsCategory() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function formControlsCategory()
    {
        return array(
            array("name" => "ServiceCatName", "lable" => "Service Category_Name", "placeholder" => "Service Category_Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ,"required" => true),

        );
    }
//
    public function insertCategory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insertCategory?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function updateCategory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateCategory?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function deleteCategory()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteCategory?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase.'category';
//        redirect($url);
//        exit();
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

	/*********************** Get Service Category Method End Here ************************/
	/*********************** Get Service Item Method Start Here ************************/
	protected  function itemHeadList()
    {
        return array('Name','Standard Rate','Premium Rate','Price Unit','Item','Service','Service Category');
    }

    protected  function filterItemData($result)
    {
        $newResult = array();

        foreach ($result as $row)
        {
            $newRow = array();
            $newRow['id'] = $row->id;
            $newRow['name'] = $row->name;
            $newRow['standardrate'] = $row->standardrate;
            $newRow['premiumrate'] = $row->premiumrate;
            $newRow['priceunit'] = $row->priceunit;
            $newRow['servicecategory'] = $row->servicecategory;
            $newRow['service'] = $row->service;
            if(!empty($row->item))
            {
                $newRow['item'] = "https://cdn.laundrybucket.co.in/images/".$row->item;
            }else{

                $newRow['item'] = "";
            }
            array_push($newResult, (object) $newRow );
        }
        return $newResult;
    }

    public function item()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Service Items','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceItemCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getServiceItemList?key=".$key."&limit=".$this->perPage;

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
        $data['title'] = "Service Items | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Service Items";
        $data['pagination'] = true;
        $data['thead'] = $this->itemHeadList();
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = $this->filterItemData($result);
        $data['imgArry'] = array('item');
        $data['ajaxfunc'] = 'ajaxPaginationItemData';
        if(user_role() == 'SuperAdmin')
        {
            $data['add'] = array('lable'=> 'Add New Service Item','url'=> $this->mbase.'additem');
            $data['edit'] = $this->mbase.'edititem/';
            $data['del'] = $this->mbase.'deleteitem/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationItemData()
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
        $url = $this->apiurl.$this->mbase."getServiceItemCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceItemList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Service Items | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Service Items";
        $data['pagination'] = true;
        $data['thead'] = $this->itemHeadList();
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array();
        $data['tbldata'] = $this->filterItemData($result);
        $data['cstart'] = $offset+1;
        $data['ajaxfunc'] = 'ajaxPaginationItemData';
        $data['imgArry'] = array('item');
        if(user_role() == 'SuperAdmin')
        {
            $data['edit'] = $this->mbase.'edititem/';
            $data['del'] = $this->mbase.'deleteitem/';
        }
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
	/*********************** Get Service Item Method End Here ************************/
	public function addItem()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Add New Service Item","url" => '', 'icon' => ''));

		$data['title'] = "Add New Service Item | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
		$data['form'] = array("name" => "frm_add_service_item", "type" => "insert", "action" => $this->mbase."insertitem","title" => "Add New Service Item", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_service_item","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControlsItem() );
		$this->load->view('./comman/data/formfull',$data);
	}

	public function editItem()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Update Service Item","url" => '', 'icon' => ''));

		$id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceItemById?key=".$key."&id=".$id;

        $serviceItemData = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
		$data['values'] = json_decode($serviceItemData);
		$data['title'] = "Update Service Item | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_service_item", "type" => "update", "action" => $this->mbase."updateitem","title" => "Update Service Item", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_service_item","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControlsItem() );
		$this->load->view('./comman/data/formfull',$data);
	}

    public function formControlsItem()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServicePriceUnitList?key=".$key;
        $url = $this->appendFilter($this->input->post(),$url);
        $result = $this->callAPI('GET',$url);
        $priceUnit = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceOptionList?key=".$key;
        $url = $this->appendFilter($this->input->post(),$url);
        $result = $this->callAPI('GET',$url);
        $serviceUnit = json_decode($result);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getServiceCategoryOptionList?key=".$key;
        $url = $this->appendFilter($this->input->post(),$url);
        $result = $this->callAPI('GET',$url);
        $serviceCategoryUnit = json_decode($result);

        return array(
            array("name" => "ItemName", "lable" => "Item Name", "placeholder" => "Item Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "PriceUnit", "lable" => "Price Unit", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $priceUnit ),
            array("name" => "StandardRate", "lable" => "Standard Rate", "placeholder" => "Standard Rate", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "PremiumRate", "lable" => "Premium Rate", "placeholder" => "Premium Rate", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "ServiceId", "lable" => "Service", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6","options" => $serviceUnit ),
            array("name" => "ServiceCatId", "lable" => "Category", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6","options" => $serviceCategoryUnit ),
            array("name" => "item_img", "lable" => "Upload Image", "placeholder" => "Upload Image", "type" => "file", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),

        );
    }
//
	public function insertItem()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		$pData = $this->input->post();
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insertServiceItem?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
	}

	public function updateItem()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateServiceItem?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
	}

	public function deleteItem()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteServiceItem?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase."item";
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
	}
    public function uploadReceipt()
    {

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
}