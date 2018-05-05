<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incharge extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'incharge/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Incharge_model');	
		array_push($this->bCrumb, array('title' => 'Incharges','url' => base_url().'index.php/'.$this->mbase));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('School','Class','Teacher');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Incharges | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Incharges";
		$data['thead'] = $this->tHead;
		// $data['tbldata'] = $this->Incharge_model->getIncharges();
		$data['add'] = array('lable'=> 'Add New Incharge','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Incharge_model->getIncharges());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Incharge_model->getIncharges(array('limit'=>$this->perPage));
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
        $totalRec = count($this->Incharge_model->getIncharges($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Incharge_model->getIncharges($conditions);
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
		array_push($this->bCrumb, array('title' => "Add Incharge","url" => ''));
		
		$data['title'] = "Add Incharge | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_incharge", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Incharge", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_incharge","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Incharge","url" => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Incharge_model->getInchargeById($id);
		$data['title'] = "Update Incharge | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_incharge", "type" => "update", "action" => $this->mbase."update","title" => "Update Incharge", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_incharge","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		$this->load->model('School_model');
		$schList = $this->School_model->getSchoolList();
		
		$this->load->model('Branch_model');
		$clsList = $this->Branch_model->getBranchList();

        $this->load->model('Teacher_model');
        $teaList = $this->Teacher_model->getTeacherList();

        $schoolId = $this->session->userdata('school_id');

		return array( 
		array("name" => "school_id", "lable" => "School" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $schList, "required" => true, "default" => $schoolId ),
		array("name" => "class_id", "lable" => "Class", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $clsList, "required" => true ),
		array("name" => "teacher_id", "lable" => "Incharge", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $teaList, "required" => true )
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Incharge_model->insertIncharge($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Incharge_model->updateIncharge($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Incharge_model->deleteIncharge($id);
		redirect($this->reURL);
	}
	
}