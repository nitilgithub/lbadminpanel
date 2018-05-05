<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends Core_controller {
	
	protected $bCrumb = array(); 
	protected $mbase = 'package/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Package_model');
		array_push($this->bCrumb, array('title' => 'Packages','url' => base_url().'index.php/package'));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Cover','Package Name','Category','Price','Stars','Verified Status');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Package | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Packages";
		$data['thead'] = $this->tHead;

		$rolePer = $this->session->userdata('role_permission');
        $rolePer  = $rolePer['package'];

        if($rolePer['read'])
        {
            $data['add'] = array('lable'=> 'Add New Package','url'=> 'package/add');
        }

		$data['imgArry'] = array('cover');

        if($rolePer['update']) {
            $data['edit'] = $this->mbase . $this->edit;
        }

        if($rolePer['delete']) {
            $data['del'] = $this->mbase . $this->delete;
        }
		//total rows count
        $totalRec = count($this->Package_model->getTotalPackages());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Package_model->getPackages(array('limit'=>$this->perPage));
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = 1;
		
		$this->load->view('./comman/data/view',$data);
	}
	
 	
	public function ajaxPaginationData()
	{
        $conditions = array();
		
        $data['encode'] = $this;
		$data['thead'] = $this->tHead;

        $rolePer = $this->session->userdata('role_permission');

        if($rolePer['update']) {
            $data['edit'] = $this->mbase . $this->edit;
        }

        if($rolePer['delete']) {
            $data['del'] = $this->mbase . $this->delete;
        }

        $data['imgArry'] = array('photo');
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        //total rows count
        $totalRec = count($this->Package_model->getStudents($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Package_model->getStudents($conditions);
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = $page+1;
        
        //load the view
        $this->load->view('./comman/data/data-table', $data, false);
    }

	protected function getConfig($totalRec)
	{
		$config['target']      = '#view-data';
        $config['base_url']    = base_url().$this->mbase.'ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
		return $config; 
	}
 	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Package","url" => '', 'icon' => ''));

		$this->load->model('Place_model');
		$data['placelist'] = $this->Place_model->getPlaceList();

		$data['title'] = "Add Package | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_package", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Package", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_package","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/package/test',$data);
	}

    public function addpackage()
    {
        array_push($this->bCrumb, array('title' => "Add Package","url" => '', 'icon' => ''));

        $this->load->model('Place_model');
        $data['placelist'] = $this->Place_model->getPlaceList();

        $data['includes'] = $this->Package_model->getIncludes();
        $data['excludes'] = $this->Package_model->getExcludes();
        $data['payoptions'] = $this->Package_model->getPaymentOptions();

        $data['title'] = "Add Package | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_package", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Package", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_package","lable" => "Save", "id" => "", "class" => "btn btn-success"),
            "controles" => $this->formControls() );
        $this->load->view('./comman/package/add_package',$data);
    }
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Student","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Student_model->getPageById($id);
		$data['title'] = "Update Student | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_page", "type" => "update", "action" => $this->mbase."update","title" => "Update Student", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_student","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/package/form',$data);
	}
	
	public function formControls()
	{
        $genderGroup = array('Male' => 'm', 'Female' => 'f' );
		return array(
		array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
        array("name" => "father_name", "lable" => "Father Name", "placeholder" => "Father Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
        array("name" => "mother_name", "lable" => "Mother Name", "placeholder" => "Mother Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
        array("name" => "photo", "lable" => "Photo" ,"placeholder" => "Photo", "type" => "file", "class" => "span11", "id" => "", "size" => "" ),
        array("name" => "dob", "lable" => "DOB" ,"placeholder" => "DOB", "type" => "date", "class" => "datepicker span11", "id" => "", "size" => "" ),
		array("name" => "gender", "lable" => "Gender", "type" => "radio", "class" => "span11", "id" => "", "size" => "", "group" => $genderGroup),
		array("name" => "address", "lable" => "Address", "placeholder" => "Address","type" => "textarea", "class" => "span11", "id" => "", "size" => ""),
		array("name" => "email", "lable" => "Email", "placeholder" => "Email", "type" => "text", "class" => "span11", "id" => "", "size" => ""),
		array("name" => "mobile_no", "lable" => "Mobile No.", "placeholder" => "Mobile No.", "type" => "text", "class" => "span11", "id" => "", "size" => "")
		);
	}
	
	public function insert()
	{
        $userData = array();

        $pData = $this->input->post();

        $schoolId = $this->session->userdata('school_id');
        $pData['school_id'] = $schoolId;

        $admissionNo = $this->createAdmissinoNo($schoolId);
        $pData['admission_no'] = $admissionNo;

        $userData['school_id'] = $schoolId;
        $userData['user_name'] = $admissionNo;
        $userData['password'] = md5($admissionNo);
        $userData['status'] = true;

        $this->load->model('Role_model');
        $res = $this->Role_model->getRoleIdByName('student');

        $userData['role_id'] = $res->id;

        if(!empty($_FILES['photo']['name']))
        {
            $config['upload_path'] = './uploads/student-img/';
            $config['allowed_types'] = 'gif|jpg|png';
//            $config['max_size']	= '100';
//            $config['max_width']  = '1024';
//            $config['max_height']  = '768';
            $img_file = $this->do_upload('photo',$config);
            $pData['photo'] = base_url().'uploads/student-img/'.$img_file['upload_data']['file_name'];
        }

        $userData['student_id'] = $this->Student_model->insertStudent($pData);

        $this->load->model('User_model');
        $this->User_model->insertUser($userData);

		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);

		$this->Package_model->updateStudent($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Package_model->deletePage($id);
		redirect($this->reURL);
	}


}