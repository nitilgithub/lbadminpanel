<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Core_controller {

	var $data = array('title' => "Laundry Bucket" );
    protected $bCrumb = array();
    protected $mbase = 'user/';
    protected $reURL = null;
    protected $tHead = null;
	
	public function __construct()
    {
            parent::__construct();
            // $this->load->model('Auth_model');
            $this->tHead = array('UserFirstName','UserLastName','UserCity','userregistrationdate');
            $this->perPage = 20;
    }

	public function index()
	{
//		$this->load->view('./dashboard', $this->data);
        $url = base_url().midurl()."user";
        redirect($url);
	}




}
