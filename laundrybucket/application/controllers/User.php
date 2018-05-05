<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'user/';
	protected $reURL = null;
	protected $tHead = null;
    protected $isDate = null;
    protected $isBool = null;
    protected $extraBtns = null;
	
	public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');	
		array_push($this->bCrumb, array('title' => 'Users','url' => base_url().midurl().'user'));
		$this->reURL = base_url().midurl().$this->mbase;
        $this->tHead = array('User Id','User Name','User City','User Registration Date');
        $this->isDate = array('userregistrationdate');
        $this->perPage = 20;

        $this->extraBtns = (object) array(
            array('name' => 'Order History','title' => 'Order History', 'class' => 'btn-info btn-mini', 'icon-class' => 'icon-time', 'open'=>'anchor', 'target' => '','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/userorderhistory/' ),
            array('name' => 'Order Dashboard', 'title' => 'Order Dashboard', 'class' => 'btn-info btn-mini', 'icon-class' => 'icon-dashboard', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/userorderdashboard/' ),
            array('name' => 'Create Order', 'title' => 'Create Order', 'class' => 'btn-success btn-mini', 'icon-class' => 'icon-plus', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/createuserorder/' ),
            array('name' => 'Create User Subscription', 'title' => 'Create User Subscription', 'class' => 'btn-success btn-mini', 'icon-class' => 'icon-plus', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'subscription/buysubscription/' ),
            array('name' => 'Subscription History', 'title' => 'Subscription History', 'class' => 'btn-info btn-mini', 'icon-class' => 'icon-plus', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false ),
            array('name' => 'Wallet', 'title' => 'Wallet', 'class' => 'btn-warning btn-mini', 'icon-class' => 'icon-money', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false ),
//            array('name' => 'Remarks', 'title' => 'Remarks', 'class' => 'btn-warning btn-mini', 'icon-class' => 'icon-money', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/userorderhistory/' ),
//            array('name' => 'Feedback', 'title' => 'Feedback', 'class' => 'btn-warning btn-mini', 'icon-class' => 'icon-money', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'feedback/userfeedback/' ),
            array('name' => 'Remarks', 'title' => 'Remarks', 'class' => 'btn-primary btn-mini', 'icon-class' => 'icon-pencil', 'open'=>'model', 'target' => 'user/remarks_model_view','anchor-class' => '', 'model-class' => '', "view-head" => false ),
            array('name' => 'Feedback', 'title' => 'Feedback', 'class' => 'btn-inverse btn-mini', 'icon-class' => 'icon-phone-sign', 'open'=>'model', 'target' => 'user/feedback_model_view','anchor-class' => '', 'model-class' => '', "view-head" => false ),
        );
    }

    protected  function filterOfferData($result)
    {

        $newList = array();
        foreach ($result as $row)
        {
            $newrow = array();
            $newrow['id'] = $row->id;
            $newrow['userid'] = $row->userid;
            $newrow['usertype']=$row->usertype;
            $newrow['useremail']=$row->useremail;
            $newrow['username']=$row->username;
            $newrow['userdob']=$row->userdob;
            $newrow['usersex']=$row->usersex;

            $newrow['usercity']=$row->usercity;
            $newrow['userstate']=$row->userstate;
            $newrow['userzip']=$row->userzip;
            $newrow['userverifiedstatus']=$row->userverifiedstatus;
            $newrow['useremailverifiedstatus']=$row->useremailverifiedstatus;
            $newrow['userregistrationdate']=$row->userregistrationdate;

            $key = $this->api->addapikey();
            $url = $this->apiurl."order/UserOrderListOption?key=".$key."&userid=".enc($row->id);
            $result = $this->callAPI('GET',$url);
            $result = json_decode($result);
            if(!empty($result))
            {
                $newrow['useroptionlist']= $result;
            }

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
        $url = $this->apiurl."user/getusercount?key=".$key;
        $url = $this->appendFilter($this->input->post(),$url);
        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl."user/getuserlist?key=".$key."&limit=".$this->perPage;
        $url = $this->appendFilter($this->input->post(),$url);
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'User Id', 'value' => 'UserId'), array('label' => 'User Type', 'value' => 'UserType'),array('label' => 'User Email', 'value' => 'UserEmail'),array('label' => 'User First Name', 'value' => 'UserFirstName'), array('label' => 'User Last Name', 'value' => 'UserLastName'), array('label' => 'User City', 'value' => 'UserCity'), array('label' => 'User State', 'value' => 'UserState'), array('label' => 'User Zip', 'value' => 'UserZip'), array('label' => 'User Phone', 'value' => 'UserPhone'), array('label' => 'User Address', 'value' => 'UserAddress'));

        $key = $this->api->addapikey();
        $url = $this->apiurl."employee/getEmployeeListOption?key=".$key;
        $empList = $this->callAPI('GET',$url);
        $empList = json_decode($empList);

        $data['encode'] = $this;
        $data['title'] = "Users | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Users";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['extrabtn'] = $this->extraBtns;
        $data['btnExpExcel'] = (object) array('lable'=> 'Download User List In Excel Report','url'=> $this->mbase.'genrateusersexcel');
        $data['emplist'] = $empList;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./user/view',$data);
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
        $url = $this->apiurl."user/getusercount?key=".$key;
        $url = $this->appendFilter($this->input->post(),$url);
        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getuserlist?key=".$key."&limit=".$this->perPage."&start=".$offset;
        $url = $this->appendFilter($this->input->post(),$url);
        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Users | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Users";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $page+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $data['extrabtn'] = $this->extraBtns;
        $this->load->view('./user/data-table',$data);
    }
	/************************* Get User Excel Method Start Here **************************/
    public function usersexcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getusercount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl."user/getuserlist?key=".$key."&limit=".$this->perPage;

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(array('label' => 'User Type', 'value' => 'UserType'),array('label' => 'User Email', 'value' => 'UserEmail'),array('label' => 'User First Name', 'value' => 'UserFirstName'),array('label' => 'User Last Name', 'value' => 'UserLastName'));
        $filters = array(
            (object) array( 'title' => 'From Date' ,'name' => 'from_date', 'id' => '', 'class' => '', 'type' => 'datepicker', 'placeholder' => 'From Date' ),
            (object) array('title' => 'To Date' ,'name' => 'to_date', 'id' => '', 'class' => '', 'type' => 'datepicker', 'placeholder' => 'To Date' ),
        );

        $data['encode'] = $this;
        $data['title'] = "Filter Users & Export Excel | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Filter Users & Export Excel";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataUserExcel';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['filter2'] = $filters;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['btnExpExcel'] = (object) array('lable'=> 'Download Excel Report','url'=> $this->mbase.'genrateusersexcel');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
//        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);
    }

    public function ajaxPaginationDataUserExcel()
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
        $url = $this->apiurl."user/getusercount?key=".$key;

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getuserlist?key=".$key."&limit=".$this->perPage."&start=".$offset;
        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Users | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Users";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['ajaxfunc'] = 'ajaxPaginationDataUserExcel';
        $data['isbool'] = array('status');
        $data['isdate'] = $this->isDate;
        $data['tbldata'] = $result;
        $data['cstart'] = $page+1;
        $data['add'] = array('lable'=> 'Add New User','url'=> $this->mbase.'add');
        $data['btnExpExcel'] = (object) array('lable'=> 'Download Excel Report','url'=> $this->mbase.'genrateusersexcel');
        $data['edit'] = $this->mbase.'edit/';
        $data['del'] = $this->mbase.'delete/';
        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }

    public function genrateUsersExcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $excHead = array(
            'User Id',
            'User Type',
            'Email','Name',
            'Country',
            'State',
            'City',
            'Pin Code',
            'Address',
            'DOB',
            'Gender',
            'Registration Date',
            'Phone',
            'Alternative Phone',
            'FAX',
            'Franchisee Name',
            'Remarks',
        );
        $excData = array();
        $excName = "UserList-".date("d-m-Y");

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getuserlistExcelReport?key=".$key;
        $result = $this->callAPI('GET',$url);
        ini_set('memory_limit', '-1');
        $result = json_decode($result);

        foreach ($result as $row)
        {
            array_push($excData,array(
                    !empty($row->UserId) ? $row->UserId : 'NA',
                    !empty($row->UserType) ? $row->UserType : 'NA',
                    !empty($row->UserEmail) ? $row->UserEmail : 'NA',
                    !empty($row->username) ? $row->username : '',
                    !empty($row->UserCountry) ? $row->UserCountry : 'NA',
                    !empty($row->UserState) ? $row->UserState : 'NA',
                    !empty($row->UserCity) ? $row->UserCity : 'NA',
                    !empty($row->UserZip) ? $row->UserZip : 'NA',
                    !empty($row->UserAddress) ? $row->UserAddress : 'NA',
                    !empty($row->UserDOB) ? date('d-m-Y',strtotime($row->UserDOB)) : 'NA',
                    !empty($row->UserSex) ? $row->UserSex : 'NA',
                    !empty($row->UserRegistrationDate) ? date('d-m-Y',strtotime($row->UserRegistrationDate)) : 'NA',
                    !empty($row->UserPhone) ? $row->UserPhone : 'NA',
                    !empty($row->UserPhone2) ? $row->UserPhone2 : 'NA',
                    !empty($row->UserFax) ? $row->UserFax : 'NA',
                    !empty($row->franchiseename) ? $row->franchiseename : 'NA',
                    !empty($row->remarks) ? $row->remarks : 'NA',
                )
            );
        }

        $this->writeExcel($excHead,$excData,$excName);
    }

	/************************* Get User Excel Method End Here **************************/
    public function test()
    {
        $this->load->view('./test');
    }
	public function add()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Add User","url" => '', 'icon' => ''));
		
		$data['title'] = "Add User | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
		$data['form'] = array("name" => "frm_add_user", "type" => "insert", "action" => $this->mbase."insert","title" => "Add User", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_user","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $this->formControls() );
		$this->load->view('./user/formfull',$data);
	}
	
	public function edit()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

		array_push($this->bCrumb, array('title' => "Update User","url" => '', 'icon' => ''));	
		
		$id =  $this->uri->segment('3');

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getUserById?key=".$key."&id=".$id;

        $res = $this->callAPI('GET',$url);

//		$data['readonly'] = array('password');

        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/GetCityListOptions?key=".$key;
        $cityList = $this->callAPI('GET',$url);
        $cityList = json_decode($cityList);

        $key = $this->api->addapikey();
        $url = $this->apiurl."franchisee/GetFranchiseeListOption?key=".$key;
        $franList = $this->callAPI('GET',$url);
        $franList = json_decode($franList);

        $res = json_decode($res);

        $locid = $res->UserLocation;
        $ucity = $res->UserCity;

        $cityID = "";
        foreach ($cityList as $city)
        {
            if($city->name == $ucity)
            {
                $cityID = $city->id;
            }
        }

        $res->UserCity = $cityID;


        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/GetLocationListOptions?key=".$key."&cityid=".$cityID;
        $locList = $this->callAPI('GET',$url);
        $locList = json_decode($locList);

        $knowList = array((object) array('id' => 1, 'name' => 'Google Search Engine'),(object) array('id' => 2, 'name' => 'Facebook'),(object) array('id' => 3, 'name' => 'Pamphlet'),(object) array('id' => 4, 'name' => 'Reference'),(object) array('id' => 5, 'name' => 'Others'));
//        $franList = array((object) array('id' => 1, 'name' => 'Laundry Bucket'),(object) array('id' => 2, 'name' => 'Olive County'));

        $frmcontrol =array(
        array("name" => "UserFirstName", "lable" => "First Name", "placeholder" => "First Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "UserLastName", "lable" => "Last Name", "placeholder" => "Last Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "UserEmail", "lable" => "Email", "placeholder" => "Email", "type" => "email", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
		array("name" => "UserPhone", "lable" => "Mobile", "placeholder" => "Mobile", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
        array("name" => "UserCity", "lable" => "UserCity", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $cityList, "con-group-class" => "span6" ),
        array("name" => "UserLocation", "lable" => "User Location", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $locList, "con-group-class" => "span6", "default" => $locid ),
        array("name" => "UserAddress", "lable" => "Address", "placeholder" => "Address", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
//        array("name" => "UserCity", "lable" => "City", "placeholder" => "City (Noida, Gaziabaad)", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
//        array("name" => "UserState", "lable" => "State", "placeholder" => "State (UP Only)", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
		array("name" => "UserReferenceRemarks", "lable" => "How user know about us?", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $knowList, "con-group-class" => "span6" ,"required" => true ),
		array("name" => "franchiseId", "lable" => "Franchise", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $franList, "con-group-class" => "span6" )
		);

        $data['dataid'] = $id;
		$data['values'] = $res; //$this->User_model->getUserById($id);
		$data['title'] = "Update User | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
		$data['form'] = array("name" => "frm_update_user", "type" => "update", "action" => $this->mbase."update","title" => "Update User", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_user","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
		"controles" => $frmcontrol );
		$this->load->view('./user/formfull',$data);
	}
	
	public function formControls()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl."settings/GetCityListOptions?key=".$key;
        $cityList = $this->callAPI('GET',$url);
        $cityList = json_decode($cityList);

        $key = $this->api->addapikey();
        $url = $this->apiurl."franchisee/GetFranchiseeListOption?key=".$key;
        $franList = $this->callAPI('GET',$url);
        $franList = json_decode($franList);

		$knowList = array((object) array('id' => 1, 'name' => 'Google Search Engine'),(object) array('id' => 2, 'name' => 'Facebook'),(object) array('id' => 3, 'name' => 'Pamphlet'),(object) array('id' => 4, 'name' => 'Reference'),(object) array('id' => 5, 'name' => 'Others'));
//        $franList = array((object) array('id' => 1, 'name' => 'Laundry Bucket'),(object) array('id' => 2, 'name' => 'Olive County'));

		return array(
		array("name" => "UserFirstName", "lable" => "First Name", "placeholder" => "First Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
		array("name" => "UserLastName", "lable" => "Last Name", "placeholder" => "Last Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
		array("name" => "UserEmail", "lable" => "Email", "placeholder" => "Email", "type" => "email", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
		array("name" => "UserPhone", "lable" => "Mobile", "placeholder" => "Mobile", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
		array("name" => "UserPassword", "lable" => "Password", "placeholder" => "Password", "type" => "password", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
		array("name" => "confirm_password", "lable" => "Confirm Password", "placeholder" => "Confirm Password", "type" => "password", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
        array("name" => "UserCity", "lable" => "UserCity", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $cityList, "con-group-class" => "span6" ),
        array("name" => "UserLocation", "lable" => "User Location", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => array(), "con-group-class" => "span6" ),
        array("name" => "UserAddress", "lable" => "Address", "placeholder" => "Address", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
//        array("name" => "UserCity", "lable" => "City", "placeholder" => "City (Noida, Gaziabaad)", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
//        array("name" => "UserState", "lable" => "State", "placeholder" => "State (UP Only)", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
		array("name" => "UserReferenceRemarks", "lable" => "How user know about us?", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $knowList, "con-group-class" => "span6" ,"required" => true ),
		array("name" => "franchiseId", "lable" => "Franchise", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $franList, "con-group-class" => "span6" )
		);
	}

	public function insert()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

	    $res = "";
		$pData = $this->input->post();

		if($pData['UserPassword'] != $pData['confirm_password'] )
        {
            $res = array( 'status' => 0, 'message' => 'Password & Confirm Password not match');
            $res = json_encode($res);
        }else{

		unset($pData['confirm_password']);

        $pData['UserPassword'] = md5($pData['UserPassword']);
        $pData['UserZip'] = 125055;
        $pData['UserFax'] = 016666-290399;
        $pData['UserCountry'] = "India";
        $pData['UserType'] = "websiteuser";
        $pData['CreatedBy'] = user_id();
        $pData['UserIP'] = $this->ip;
        $pData['UserRegistrationDate'] = date('Y-m-d');

        $pData['UserCity'] = $pData['cityname'];
        unset($pData['cityname']);

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/insert?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        }

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

        $pData['UserCity'] = $pData['cityname'];
        unset($pData['cityname']);

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/update?key=".$key;

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

		$id =  $this->uri->segment('3');

		$pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/delete?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
		redirect($this->reURL);
	}
    /****************** Login User Star Here   ******************/
	public function userlogin()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/userlogin?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        $res = json_decode($res);

//        echo json_encode($res);die;
        $response = false;
        if(!empty($res) && $res->status)
        {
            $sdata = (array)  $res->sesdata;
            $this->session->set_userdata($sdata);
            $response = true;
        }
        echo $response;
    }
    /****************** Login User End Here   ******************/
    /****************** Get User Roles Star Here   ******************/
    public function getuserrole()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getuserrole?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;

    }
    /****************** Get User Roles End Here  ******************/

    public function adduserremarks()
    {
        $pData = $this->input->post();

        $pData['userid'] = dec($pData['userid']);
        $pData['createdby'] = user_id();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/adduserremarks?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function GetUserRemarksList()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetUserRemarksList?key=".$key."&userid=".$pData['userid'];

        $res = $this->callAPI('GET',$url);

        echo $res;

    }

    public function deleteUserRemarks()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteUserRemarks?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;

    }
}