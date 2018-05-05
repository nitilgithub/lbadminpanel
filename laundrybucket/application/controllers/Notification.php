<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends Core_controller {

    protected $bCrumb = array();
    protected $mbase = 'notification/';
    protected $reURL = null;
    protected $tHead = null;

    public function __construct()
    {
        parent::__construct();
        array_push($this->bCrumb, array('title' => 'Notification List','url' => base_url().$this->midUrl.'notification'));
        $this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Title','Description','Date');
        $this->perPage = 20;
    }


    protected function filterNotificationData($result)
    {
        $newResult = array();
        if(!empty($result)) {
            foreach ($result as $row) {
                $newRow = array();
                $newRow['id'] = $row->id;
                $newRow['title'] = $row->title;
                $newRow['description'] = $row->description;
                $newRow['date'] = $row->ndate;


                array_push($newResult, (object)$newRow);
            }
        }
        return $newResult;
    }
    /********************** Get Feedback List Method Start Here************************/
    public function index()
    {
        array_push($this->bCrumb, array('title' => 'Notification','url' => ''));
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetNotificationCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetNotificationList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Service Name', 'value' => 'ServiceName'),
        );

        $data['encode'] = $this;
        $data['title'] = "Notification List | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Notification List";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isdate'] = array('Notificationdate');
        $data['ajaxfunc'] = 'ajaxPaginationNotificationData';
        $data['tbldata'] = $this->filterNotificationData($result);
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationNotificationData()
    {
        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetNotificationCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetNotificationList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Notification List | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Notification List";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isdate'] = array('Notificationdate');
        $data['ajaxfunc'] = 'ajaxPaginationNotificationData';
        $data['tbldata'] = $this->filterNotificationData($result);
        $data['cstart'] = $offset+1;
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /********************** Get Notification List Method End Here************************/

    /********************** Add Notification Start***************************************/

    public function pushnotification()
    {
        array_push($this->bCrumb, array('title' => "Send Push Notification","url" => '', 'icon' => ''));

        $data['title'] = "Send Push Notification to all App users | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_push", "type" => "insert", "action" => $this->mbase."insert","title" => "Send Push Notification to all App users", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_push","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControls() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function formControls()
    {
        return array(
            array("name" => "title", "lable" => "Title", "placeholder" => "Enter Title", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "text", "lable" => "Message", "placeholder" => "Enter Message", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" )

        );
    }

    public function insert()
    {
        $res = "";
            $pData = $this->input->post();
            $key = $this->api->addapikey();
            $url = $this->apiurl."notification/insert?key=".$key;
            $res = $this->callAPI('POST',$url,$pData);



        echo $res;
    }



}