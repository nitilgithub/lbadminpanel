<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserRole extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'user/';
	protected $reURL = null;
	protected $tHead = null;
    protected $isDate = null;
    protected $isBool = null;
    protected $extraBtns = null;
	
	public function __construct()
    {
        parent::__construct();

    }
	
	public function index()
	{

    }

    /****************** Get User Roles Star Here   ******************/
    public function getuserrole()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getuserrole?key=".$key;
        echo $url;
//        $res = $this->callAPI('POST',$url,$pData);
//
//        echo $res;

    }
    /****************** Get User Roles End Here  ******************/
}