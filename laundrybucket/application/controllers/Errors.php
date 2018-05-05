<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
	public function index()
	{
		if(!empty($this->session->userdata('user_name')))
		{
			$data = array('heading' => 'OOPS', 'message' => 'This page not exists');
			$this->load->view('error404');
		}else{
			$this->load->view('error404_1');
		}
	}
	
}