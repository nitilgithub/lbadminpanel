<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends Core_controller {
		
	protected $bCrumb = array();
	protected $mbase = 'subject/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Subject_model');
			array_push($this->bCrumb, array('title' => 'Subjects','url' => base_url().'index.php/'.$this->mbase));
			$this->reURL = base_url().'index.php/'.$this->mbase;
			$this->tHead = array('Name');
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Subjects | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Subjects";
		$data['thead'] = $this->tHead;
		$data['tbldata'] = $this->Subject_model->getSubjects();
		$data['add'] = array('lable'=> 'Add New Subject','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.'edit/';
		$data['del'] = $this->mbase.'delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Subject","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Subject | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_module", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Subject", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_module","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Subject","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array();
		$data['values'] = $this->Subject_model->getSubjectById($id);
		$data['title'] = "Update Subject | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_module", "type" => "update", "action" => $this->mbase."update","title" => "Update Subject", "id" => "", "class" => "form-horizontal",
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
		
		$this->Subject_model->insertSubject($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		
		$id = $pData['id'];
		unset($pData['id']);
		
		$this->Subject_model->updateSubject($pData,$id);
		redirect($this->reURL);	
	}	

	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Subject_model->deleteSubject($id);
		redirect($this->reURL);
	}
}
