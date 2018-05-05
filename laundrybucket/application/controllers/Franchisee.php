<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchisee extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'franchisee/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Franchisee','url' => base_url().$this->midUrl.'franchisee'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name','Fee','Start Date','End Date','Address','Phone','Email','Contact Person');
        $this->perPage = 20;
    }
	/********************** Get Franchisee List Method Start Here************************/
	public function index()
	{

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFranchiseeCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetFranchiseeList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Franchisee Name', 'value' => 'franchisee_name'),
        );

        $data['encode'] = $this;
        $data['title'] = "Franchisee | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Franchisee";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;

        if(user_role() == 'SuperAdmin')
        {
            $data['add'] = array('lable'=> 'Add New Franchisee ','url'=> $this->mbase.'add');
            $data['edit'] = $this->mbase.'edit/';
            $data['del'] = $this->mbase.'delete/';
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
        $url = $this->apiurl.$this->mbase."GetFranchiseeCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFranchiseeList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Franchisee | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Franchisee";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        if(user_role() == 'SuperAdmin')
        {
            $data['edit'] = $this->mbase.'edit/';
            $data['del'] = $this->mbase.'delete/';
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

    public function add()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Add New Franchisee","url" => '', 'icon' => ''));

        $data['title'] = "Add New Franchisee | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_service", "type" => "insert", "action" => $this->mbase."insert","title" => "Add New Franchisee", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_franchisee","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
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

        array_push($this->bCrumb, array('title' => "Update Franchisee","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getFranchiseeById?key=".$key."&id=".$id;

        $servData = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($servData);
        $data['title'] = "Update Franchisee | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_update_service", "type" => "update", "action" => $this->mbase."update","title" => "Update Franchisee", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_franchisee","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControls() );

        $this->load->view('./comman/data/formfull',$data);
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

        $sdate = $pData['startdate'];
        $pData['startdate'] = date('Y-m-d',strtotime($sdate));

        $edate = $pData['enddate'];
        $pData['enddate'] = date('Y-m-d',strtotime($edate));

        $url = $this->apiurl.$this->mbase."insertFranchisee?key=".$key;

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

        $sdate = $pData['startdate'];
        $pData['startdate'] = date('Y-m-d',strtotime($sdate));

        $edate = $pData['enddate'];
        $pData['enddate'] = date('Y-m-d',strtotime($edate));

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateFranchisee?key=".$key;

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
        $url = $this->apiurl.$this->mbase."deleteFranchisee?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase;
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

    public function formControls()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        return array(
            array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "fee", "lable" => "Fee", "placeholder" => "Fee", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "startdate", "lable" => "Start Date", "placeholder" => "Start Date", "type" => "date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "enddate", "lable" => "End Date", "placeholder" => "End Date", "type" => "date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "address", "lable" => "Address", "placeholder" => "Address", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "phone", "lable" => "Phone", "placeholder" => "Phone", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "email", "lable" => "Email", "placeholder" => "Email", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "contactperson", "lable" => "Contact Person", "placeholder" => "Contact Person", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),

        );
    }
    /********************** Get Franchisee List Method End Here************************/

}