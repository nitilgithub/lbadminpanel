<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Place extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'place/';
	protected $reURL = null;
	
	public function __construct()
    {
    		$mbase = '';
						
            parent::__construct();
            $this->load->model('Place_model');
			array_push($this->bCrumb, array('title' => 'Places','url' => base_url().'index.php/place'));
			$this->reURL = base_url().'index.php/'.$this->mbase;
    }
	
	public function index()
	{	
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this; 
		$data['title'] = "Places | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Places";
		$data['thead'] = array('Country','Destination','Place','Status');
		$data['isbool'] = array('status');
		$data['tbldata'] = $this->Place_model->getPlaces();
		$data['add'] = array('lable'=> 'Add New Place','url'=> 'place/add');
		$data['edit'] = 'place/edit/';
		$data['del'] = 'place/delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Place","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Place | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_place", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Place", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_place","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Place","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['values'] = $this->Place_model->getPlaceById($id);
		$data['title'] = "Update Place | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_place", "type" => "update", "action" => $this->mbase."update","title" => "Update Place", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_place","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	
	public function formControls()
	{
        $conuntryList = $this->Place_model->getCountryList();

        $desList = $this->Place_model->getDesList();
		
		return array(
        array("name" => "country_id", "lable" => "Country", "placeholder" => "Country", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $conuntryList),
        array("name" => "destination_id", "lable" => "Destination", "placeholder" => "Destination", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $desList),
        array("name" => "place_name", "lable" => "Name" ,"placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true ),
        array("name" => "about_place", "lable" => "About Place", "placeholder" => "About Place", "type" => "textarea", "class" => "span11", "id" => "", "size" => ""),
		array("name" => "status", "lable" => "Status" ,"placeholder" => "Status", "type" => "toggle", "class" => "", "id" => "", "size" => "" )
		);
	}

	public function insert()
	{
		$pData = $this->input->post();
		
		$pData = $this->checkToggle($pData,'status');
		
		$this->Place_model->insertPlace($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		 
		$pData = $this->checkToggle($pData,'status');
		unset($pData['id']);
		
		$this->Place_model->updatePlace($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->School_model->deleteSchool($id);
		redirect($this->reURL);
	}
}