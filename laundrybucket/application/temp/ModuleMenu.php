<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModuleMenu extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'modulemenu/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');	
		array_push($this->bCrumb, array('title' => 'Module Menus','url' => base_url().'index.php/modulemenu'));
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Module','Menu');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Module Menus | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Module Menus";
		$data['thead'] = $this->tHead;
		// $data['tbldata'] = $this->Menu_model->getModuleMenus();
		$data['add'] = array('lable'=> 'Add New Module Menu','url'=> 'modulemenu/add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Menu_model->getModuleMenus());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Menu_model->getModuleMenus(array('limit'=>$this->perPage));
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
        $totalRec = count($this->Menu_model->getModuleMenus($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Menu_model->getModuleMenus($conditions);
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
		array_push($this->bCrumb, array('title' => "Add Module Menu","url" => ''));
		
		$data['title'] = "Add Module Menu | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_modulemenu", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Module Menu", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_modulemenu","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Module Menu","url" => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Menu_model->getModuleMenuById($id);
		$data['title'] = "Update Module Menu | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_modulemenu", "type" => "update", "action" => $this->mbase."update","title" => "Update Module Menu", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_modulemenu","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		$this->load->model('Module_model');
		$modList = $this->Module_model->getModuleList();
		
		$this->load->model('Menu_model');
		$menList = $this->Menu_model->getMenuList();
		
		return array( 
		array("name" => "module_id", "lable" => "Module" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $modList, "required" => true ),
		array("name" => "menu_id", "lable" => "Menu", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $menList, "required" => true )
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Menu_model->insertModuleMenu($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Menu_model->updateModuleMenu($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Menu_model->deleteModuleMenu($id);
		redirect($this->reURL);
	}
	
}