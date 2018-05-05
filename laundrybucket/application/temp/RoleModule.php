<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RoleModule extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'rolemodule/';
	protected $reURL = null;
	protected  $tHead = null;
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Role_model');
			array_push($this->bCrumb, array('title' => 'Role Modules','url' => base_url().'index.php/rolemodule'));	
			$this->reURL = base_url().'index.php/'.$this->mbase;
			$this->tHead = array('Role','Module');
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Role Modules | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Role Modules";
		$data['thead'] = $this->tHead;
		$data['tbldata'] = $this->Role_model->getRoleModules();
		$data['add'] = array('lable'=> 'Add New Role Module','url'=> 'rolemodule/add');
		$data['edit'] = 'rolemodule/edit/';
		$data['del'] = 'rolemodule/delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Role Module","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Role Module | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_rolemodule", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Role Module", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_user","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Role Module","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Role_model->getRoleModuleById($id);
		$data['title'] = "Update Role Module | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_rolemodule", "type" => "update", "action" => $this->mbase."update","title" => "Update Role Module", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_school","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		$this->load->model('Role_model');
		$rolList = $this->Role_model->getRoleList();
		
		$this->load->model('Module_model');
		$schList = $this->Module_model->getModuleList();
		
		return array( 
		array("name" => "role_id", "lable" => "Role", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $rolList, "required" => true ),
		array("name" => "module_id", "lable" => "Module" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $schList, "required" => true )
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Role_model->insertRoleModule($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		
		$this->Role_model->updateRoleModule($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Role_model->deleteRoleModule($id);
		redirect($this->reURL);
	}
	
}
