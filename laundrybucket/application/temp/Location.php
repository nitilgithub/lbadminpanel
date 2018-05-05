<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends Core_controller {
		
	protected $bCrumb = array();
	protected $mbase = 'location/';
	protected $reURL = null;
	protected $tHead = null;

	public function __construct()
    {
            parent::__construct();
            $this->load->model('Location_model');
			array_push($this->bCrumb, array('title' => 'Location','url' => base_url().'index.php/'.$this->mbase));
			$this->reURL = base_url().'index.php/'.$this->mbase;
			$this->tHead = array('Country','Cover','Location','Latitude','Longitude','Status');
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Location | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Location";
		$data['thead'] = $this->tHead;
        $data['isbool'] = array('status');
        $data['imgArry'] = array('cover');
		$data['tbldata'] = $this->Location_model->getLocations();
		$data['add'] = array('lable'=> 'Add New Location','url'=> $this->mbase.'add');
		$data['edit'] = $this->mbase.'edit/';
		$data['del'] = $this->mbase.'delete/';
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Location","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Location | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
        $data['readonly'] = array('latitude','longitude');
		$data['form'] = array("name" => "frm_add_location", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Location", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_location","lable" => "Save", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/location/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Location","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('latitude','longitude');
		$data['values'] = $this->Location_model->getLocationById($id);
		$data['title'] = "Update Location | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_location", "type" => "update", "action" => $this->mbase."update","title" => "Update Location", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_location","lable" => "Update", "id" => "", "class" => "btn btn-success"),
		"controles" => $this->formControls() );
		$this->load->view('./comman/location/form',$data);
	}
	
	public function formControls()
	{
        $this->load->model('Place_model');

        $conuntryList = $this->Place_model->getCountryList();
        $desList = $this->Place_model->getDesList();
        $placeList = $this->Place_model->getPlaceList();

		return array(
        array("name" => "country_id", "lable" => "Country", "placeholder" => "Country", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $conuntryList),
        array("name" => "destination_id", "lable" => "Destination", "placeholder" => "Destination", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $desList),
        array("name" => "place_id", "lable" => "Place", "placeholder" => "Place", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $placeList),
		array("name" => "location_name", "lable" => "Location Name", "placeholder" => "Location Name", "type" => "text", "class" => "span11", "id" => "location_name", "size" => "", "required" => true),
		array("name" => "latitude", "lable" => "Latitude", "placeholder" => "Latitude", "type" => "text", "class" => "span11", "id" => "latitude", "size" => "", "required" => true ),
		array("name" => "longitude", "lable" => "Longitude", "placeholder" => "Longitude", "type" => "text", "class" => "span11", "id" => "longitude", "size" => "", "required" => true),
        array("name" => "cover", "lable" => "Cover Image" ,"placeholder" => "", "type" => "file", "class" => "span11", "id" => "", "size" => "" ),
        array("name" => "status", "lable" => "Status" ,"placeholder" => "Status", "type" => "toggle", "class" => "", "id" => "", "size" => "" ),
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();

        $pData = $this->checkToggle($pData,'active');

		$this->Session_model->insertSession($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();

        $pData = $this->checkToggle($pData,'status');

		$id = $pData['id'];
		unset($pData['id']);
		
		$this->Location_model->updateLocation($pData,$id);
		redirect($this->reURL);	
	}	

	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Session_model->deleteSession($id);
		redirect($this->reURL);
	}
}
