<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClassStudent extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'classstudent/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');
		array_push($this->bCrumb, array('title' => 'Class Students','url' => base_url().'index.php/'.$this->mbase));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Session','School','Class','Roll No','Student');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Class Students | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Class Students";
		$data['thead'] = $this->tHead;
		// $data['tbldata'] = $this->Menu_model->getClassStudent();
		$data['add'] = array('lable'=> 'Add New Class Student','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Student_model->getClassStudents());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Student_model->getClassStudents(array('limit'=>$this->perPage));
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
        $totalRec = count($this->Student_model->getClassStudents($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Student_model->getClassStudents($conditions);
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
		array_push($this->bCrumb, array('title' => "Add Class Student","url" => ''));
		
		$data['title'] = "Add Class Student | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_modulemenu", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Class Student", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_modulemenu","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Class Student","url" => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Student_model->getClassStudentById($id);
		$data['title'] = "Update Class Student | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_modulemenu", "type" => "update", "action" => $this->mbase."update","title" => "Update Class Student", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_modulemenu","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
        $this->load->model('Session_model');
        $sessList = $this->Session_model->getSessionList();

        $this->load->model('School_model');
        $schList = $this->School_model->getSchoolList();

		$this->load->model('Branch_model');
		$clasList = $this->Branch_model->getBranchList();
		
		$this->load->model('Student_model');
		$stdList = $this->Student_model->getStudentList();

        $sessionId = $this->session->userdata('session_id');

        $schoolId = $this->session->userdata('school_id');

        if(!empty($this->session->userdata('class_id')))
        {
            $classId = $this->session->userdata('class_id');
        }
        else{
            $classId = null;
        }

		return array(
        array("name" => "session_id", "lable" => "Session" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $sessList , "required" => true, "default" => $sessionId ),
        array("name" => "school_id", "lable" => "School", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $schList, "required" => true, "default" => $schoolId ),
		array("name" => "class_id", "lable" => "Class" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $clasList , "required" => true, "default" => $classId ),
		array("name" => "student_id", "lable" => "Student" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $stdList , "required" => true )
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
        $rollNo = $this->createRollNo($pData);
        $pData['roll_no'] = $rollNo;

		$this->Student_model->insertClassStudent($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Student_model->updateClassStudent($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Student_model->deleteClassStudent($id);
		redirect($this->reURL);
	}
	
}