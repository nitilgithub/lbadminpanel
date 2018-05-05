<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends Core_controller {
		
	protected $bCrumb = array();
	protected $mbase = 'unit/';
	protected $reURL = null;
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Unit_model');
			array_push($this->bCrumb, array('title' => 'Units','url' => base_url().'index.php/unit'));
			$this->reURL = base_url().'index.php/'.$this->mbase;
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Units | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Units";
		$data['thead'] = array('Name','IP');
		$data['tbldata'] = $this->Unit_model->getUnits();
		$data['add'] = array('lable'=> 'Add New Unit','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.'edit/';
		$data['del'] = $this->mbase.'delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Unit","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Unit | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_module", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Unit", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_module","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Unit","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array();
		$data['values'] = $this->Unit_model->getUnitById($id);
		$data['title'] = "Update Unit | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_module", "type" => "update", "action" => $this->mbase."update","title" => "Update Unit", "id" => "", "class" => "form-horizontal",
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
		
		$this->Unit_model->insertUnit($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		
		$id = $pData['id'];
		unset($pData['id']);
		
		$this->Unit_model->updateUnit($pData,$id);
		redirect($this->reURL);	
	}	

	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Unit_model->deleteUnit($id);
		redirect($this->reURL);
	}
}
