<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'unittest/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Unit_model');
		array_push($this->bCrumb, array('title' => 'Unit Test','url' => base_url().'index.php/'.$this->mbase));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Subject','Unit','Total Mark','Pass Mark');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Unit Test | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Unit Tests";
		$data['thead'] = $this->tHead;

		$data['add'] = array('lable'=> 'Add New Unit Test','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Unit_model->getUnitTests());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Unit_model->getUnitTests(array('limit'=>$this->perPage));
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
        $totalRec = count($this->Unit_model->getUnitTests($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Unit_model->getUnitTests($conditions);
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
		array_push($this->bCrumb, array('title' => "Add Unit Test","url" => ''));
		
		$data['title'] = "Add Unit Test | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_unittest", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Unit Test", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_unittest","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Unit Test","url" => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Unit_model->getUnitTestById($id);
		$data['title'] = "Update Unit Test | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_unittest", "type" => "update", "action" => $this->mbase."update","title" => "Update Unit Test", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_unittest","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		$this->load->model('Subject_model');
		$subList = $this->Subject_model->getsubjectList();

		$unitList = $this->Unit_model->getUnitList();
		
		return array( 
		array("name" => "subject_id", "lable" => "Subject" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $subList, "required" => true ),
		array("name" => "unit_id", "lable" => "Unit", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $unitList, "required" => true ),
        array("name" => "total_mark", "lable" => "Total Mark" ,"placeholder" => "Total Mark", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true ),
        array("name" => "pass_mark", "lable" => "Pass Mark" ,"placeholder" => "Pass Mark", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true )
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Unit_model->insertUnitTest($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Unit_model->updateUnitTest($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Unit_model->deleteUnitTest($id);
		redirect($this->reURL);
	}
	
}