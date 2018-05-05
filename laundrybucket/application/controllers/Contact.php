<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'contact/';
	protected $reURL = null;
	protected $tHead = null;
    protected $extraBtns = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Contact Us Query','url' => base_url().midUrl().'contact'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name','Email','Phone','Enquiry Type','Message','Date','Remarks');
        $this->perPage = 20;

        $this->extraBtns = (object) array(
            array('name' => 'Add Remarks','title' => 'Add Remarks', 'class' => 'btn-info btn-mini', 'icon-class' => 'icon-plus', 'open'=>'anchor', 'target' => '','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'' ),
        );
    }
	/********************** Get Contact Us List Method Start Here************************/
	public function index()
	{

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getContactQueryCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getContactQueryList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Name', 'value' => 'name'),
            array('label' => 'Email', 'value' => 'email'),
            array('label' => 'Phone', 'value' => 'phone'),
            array('label' => 'Enquiry Type', 'value' => 'enquirytype'),
            array('label' => 'Message', 'value' => 'message'),
            array('label' => 'Remarks', 'value' => 'Remarks'),
        );

        $data['encode'] = $this;
        $data['title'] = "Contact Us Query | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Contact Us Query";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
//        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['extrabtn'] = $this->extraBtns;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./contact/view',$data);

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
        $url = $this->apiurl.$this->mbase."getContactQueryCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getContactQueryList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Contact Us Query | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Contact Us Query";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
//        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['extrabtn'] = $this->extraBtns;
        $this->load->view('./contact/data-table',$data);
    }
    /********************** Get Contact Us List Method End Here************************/

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
}