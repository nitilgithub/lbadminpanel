<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends Core_controller {
		
	protected $bCrumb = array();
	protected $mbase = 'module/';	
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Module_model');	
			array_push($this->bCrumb, array('title' => 'Modules','url' => base_url().'index.php/module'));
			$this->reURL = base_url().'index.php/'.$this->mbase;
			$this->tHead = array('Name');
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Modules | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Modules";
		$data['thead'] = $this->tHead;
		$data['tbldata'] = $this->Module_model->getModules();
		$data['add'] = array('lable'=> 'Add New Module','url'=> 'module/add');
		$data['edit'] = 'module/edit/';
		$data['del'] = 'module/delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Module","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Module | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_module", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Module", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_module","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Module","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array();
		$data['values'] = $this->Module_model->getModuleById($id);
		$data['title'] = "Update Module | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_module", "type" => "update", "action" => $this->mbase."update","title" => "Update Module", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_module","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		return array( 
		array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true)
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Module_model->insertModule($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		
		$id = $pData['id'];
		unset($pData['id']);
		
		$this->Module_model->updateModule($pData,$id);
		redirect($this->reURL);	
	}	

	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Module_model->deleteModule($id);
		redirect($this->reURL);
	}
}
