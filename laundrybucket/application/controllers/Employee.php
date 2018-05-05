<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'employee/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Employees','url' => base_url().$this->midUrl.'employee'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name','Status');
        $this->perPage = 20;
    }
	/********************** Get Subscription List Method Start Here************************/
    protected  function empHeadList()
    {
        return array('Name','Email','Phone','Role');
    }

    protected  function filterEmpData($result)
    {
        $newEmpList = array();
        foreach ($result as $row)
        {
            $newrow = array();
            $newrow['id'] = $row->id;
            $newrow['name'] = $row->name;
            $newrow['email'] = $row->email;
            $newrow['phone'] = $row->phone;

            $key = $this->api->addapikey();
            $url = $this->apiurl.$this->mbase."getEmployeeRolesListByEmpId?key=".$key."&id=".enc($row->id);

            $url = $this->appendFilter($this->input->post(),$url);

            $roleResult = $this->callAPI('GET',$url);
            $roleResult = json_decode($roleResult);
            $trole = "";
            $i = 1;
            foreach ($roleResult as $r)
            {
                if($i == 1)
                {
                    $trole = $r->rolename;
                }
                elseif(sizeof($roleResult) == $i)
                {
                    $trole = $trole.$r->rolename;
                }else{
                    $trole = $trole.$r->rolename." - ";
                }
                $i = $i + 1;
            }

            $newrow['role'] = $trole;


            array_push($newEmpList, (object) $newrow );
        }
        return $newEmpList;
    }


	public function index()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetEmployeeCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetEmployeeList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Name', 'value' => 'empName'),
            array('label' => 'Email', 'value' => 'empEmail'),
            array('label' => 'Phone', 'value' => 'empPhone'),
        );

        $data['encode'] = $this;
        $data['title'] = "Employees | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Employees";
        $data['pagination'] = true;
        $data['thead'] = $this->empHeadList();
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
        $data['ajaxfunc'] = 'ajaxPaginationEmpData';
        $data['tbldata'] = $this->filterEmpData($result);
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationEmpData()
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
        $url = $this->apiurl.$this->mbase."GetEmployeeCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetEmployeeList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Employees | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Employees";
        $data['pagination'] = true;
        $data['thead'] = $this->empHeadList();
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
        $data['ajaxfunc'] = 'ajaxPaginationEmpData';
        $data['tbldata'] = $this->filterEmpData($result);
        $data['cstart'] = $offset+1;
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /********************** Get Subscription List Method End Here************************/
	/*********************** Get Employees Role Method Start Here ************************/
    public function roles()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Employee Roles','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getEmployeeRolesCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."getEmployeeRolesList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);


        $searchOption = array(
            array('label' => 'Name', 'value' => 'roleName'),
            array('label' => 'Status', 'value' => 'status'),
        );

        $data['encode'] = $this;
        $data['title'] = "Employee Roles | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Employee Roles";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
//        $data['isdate'] = $this->userSubIsDate();
        $data['tbldata'] = $result;
        $data['ajaxfunc'] = 'ajaxPaginationRolesData';
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);
    }

    public function ajaxPaginationRolesData()
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
        $url = $this->apiurl.$this->mbase."getEmployeeRolesCount?key=".$key;


        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getEmployeeRolesList?key=".$key."&limit=".$this->perPage."&start=".$offset;


        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);

        $data['encode'] = $this;
        $data['title'] = "Inactive Subscriptions | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Inactive Subscriptions";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
        $data['tbldata'] = $result;
        $data['cstart'] = $offset+1;
        $data['ajaxfunc'] = 'ajaxPaginationRolesData';
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
	/*********************** Get Employees Role Method End Here ************************/

}