<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends Core_controller {
	
	protected $bCrumb = array(); 
	protected $mbase = 'teacher/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Teacher_model');
		array_push($this->bCrumb, array('title' => 'Teachers','url' => base_url().'index.php/'.$this->mbase));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 5;
		$this->tHead = array('Photo','Name','Father Name','Mother Name','Gender','Email','Mobile No');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Teachers | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Teachers";
		$data['thead'] = $this->tHead;
		
		$data['add'] = array('lable'=> 'Add New Teacher','url'=> 'teacher/add');
		$data['imgArry'] = array('photo');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Teacher_model->getTotalTeachers());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Teacher_model->getTeachers(array('limit'=>$this->perPage));
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
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;

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
        $totalRec = count($this->Teacher_model->getTeachers($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Teacher_model->getTeachers($conditions);
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
		array_push($this->bCrumb, array('title' => "Add Teacher","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Teacher | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_page", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Teacher", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_teacher","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Teacher","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Teacher_model->getPageById($id);
		$data['title'] = "Update Teacher | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_page", "type" => "update", "action" => $this->mbase."update","title" => "Update Teacher", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_teacher","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
        $this->load->model('School_model');
        $schList = $this->School_model->getSchoolList();

        $schoolId = $this->session->userdata('school_id');

        $genderGroup = array('Male' => 'm', 'Female' => 'f' );
		return array(
        array("name" => "school_id", "lable" => "School" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $schList, "required" => true, "default" => $schoolId ),
		array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
        array("name" => "father_name", "lable" => "Father Name", "placeholder" => "Father Name", "type" => "text", "class" => "span11", "id" => "", "size" => ""),
        array("name" => "mother_name", "lable" => "Mother Name", "placeholder" => "Mother Name", "type" => "text", "class" => "span11", "id" => "", "size" => ""),
        array("name" => "husband_name", "lable" => "Husband Name", "placeholder" => "Husband Name", "type" => "text", "class" => "span11", "id" => "", "size" => ""),
        array("name" => "wife_name", "lable" => "Wife Name", "placeholder" => "Wife Name", "type" => "text", "class" => "span11", "id" => "", "size" => ""),
        array("name" => "photo", "lable" => "Photo" ,"placeholder" => "Photo", "type" => "file", "class" => "span11", "id" => "", "size" => "" ),
        array("name" => "dob", "lable" => "DOB" ,"placeholder" => "DOB", "type" => "date", "class" => "datepicker span11", "id" => "", "size" => "" ),
		array("name" => "gender", "lable" => "Gender", "type" => "radio", "class" => "span11", "id" => "", "size" => "", "group" => $genderGroup),
		array("name" => "address", "lable" => "Address", "placeholder" => "Address","type" => "textarea", "class" => "span11", "id" => "", "size" => ""),
		array("name" => "email", "lable" => "Email", "placeholder" => "Email", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true ),
		array("name" => "mobile_no", "lable" => "Mobile No.", "placeholder" => "Mobile No.", "type" => "text", "class" => "span11", "id" => "", "size" => "")
		);
	}
	
	public function insert()
	{
	    $teacherData = array();

        $pData = $this->input->post();

        $schoolId = $this->session->userdata('school_id');
        $pData['school_id'] = $schoolId;

        if(!empty($_FILES['photo']['name']))
        {
            $config['upload_path'] = './uploads/teacher-img/';
            $config['allowed_types'] = 'gif|jpg|png';
//            $config['max_size']	= '100';
//            $config['max_width']  = '1024';
//            $config['max_height']  = '768';
            $img_file = $this->do_upload('photo',$config);
            $pData['photo'] = base_url().'uploads/teacher-img/'.$img_file['upload_data']['file_name'];
        }

        $this->load->model('Role_model');
        $res = $this->Role_model->getRoleIdByName('teacher');

        $teacherData['school_id'] = $schoolId;
        $teacherData['user_name'] = $pData['name'];
        $teacherData['password'] = md5($pData['name']);
        $teacherData['status'] = true;
        $teacherData['role_id'] = $res->id;

        $teacherData['teacher_id'] = $this->Teacher_model->insertTeacher($pData);


        $this->load->model('User_model');
        $this->User_model->insertUser($teacherData);

		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);

		$this->Teacher_model->updateTeacher($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Teacher_model->deletePage($id);
		redirect($this->reURL);
	}
	
}