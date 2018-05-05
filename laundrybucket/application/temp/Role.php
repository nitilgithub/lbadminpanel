<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends Core_controller {
		
	protected $bCrumb = array();	
	protected $mbase = 'role/';
	protected $reURL = null;
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Role_model');
			array_push($this->bCrumb, array('title' => 'Roles','url' => base_url().'index.php/role'));	
			$this->reURL = base_url().'index.php/'.$this->mbase;
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Roles | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Roles";
		$data['thead'] = array('Name','Title','PR Read','PR Write','PR Update','PR Delete','IP');
		$data['isbool'] = array('pr_read','pr_write','pr_update','pr_delete');
		$data['tbldata'] = $this->Role_model->getRoles();
		$data['add'] = array('lable'=> 'Add New Role','url'=> 'role/add');
		$data['edit'] = 'role/edit/';
		$data['del'] = 'role/delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Role","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Role | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_role", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Role", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_user","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Role","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array();
		$data['values'] = $this->Role_model->getRoleById($id);
		$data['title'] = "Update Role | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_role", "type" => "update", "action" => $this->mbase."update","title" => "Update Role", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_role","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		return array( 
		array("name" => "name", "lable" => "Name" , "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true ),
		array("name" => "title", "lable" => "Title", "placeholder" => "Title", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
		array("name" => "pr_read", "lable" => "Parmission Read", "type" => "toggle", "class" => "", "id" => "", "size" => ""),
		array("name" => "pr_write", "lable" => "Parmission Write", "type" => "toggle", "class" => "", "id" => "", "size" => ""),
		array("name" => "pr_update", "lable" => "Parmission Update", "type" => "toggle", "class" => "", "id" => "", "size" => ""),
		array("name" => "pr_delete", "lable" => "Parmission Delete", "type" => "toggle", "class" => "", "id" => "", "size" => "")
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$pData = $this->checkToggle($pData,'pr_read');
		$pData = $this->checkToggle($pData,'pr_write');
		$pData = $this->checkToggle($pData,'pr_update');
		$pData = $this->checkToggle($pData,'pr_delete');
		
		$this->Role_model->insertRole($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		
		$id = $pData['id'];
		unset($pData['id']);
		
		$pData = $this->checkToggle($pData,'pr_read');
		$pData = $this->checkToggle($pData,'pr_write');
		$pData = $this->checkToggle($pData,'pr_update');
		$pData = $this->checkToggle($pData,'pr_delete');
		
		$this->Role_model->updateRole($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Role_model->deleteRole($id);
		redirect($this->reURL);
	}
	
}
