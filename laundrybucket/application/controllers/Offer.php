<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends Core_controller {

    protected $bCrumb = array();
    protected $mbase = 'offer/';
    protected $reURL = null;
    protected $tHead = null;

    public function __construct()
    {
        parent::__construct();
        array_push($this->bCrumb, array('title' => 'Offer','url' => base_url().$this->midUrl.'offer'));
        $this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name','Status');
        $this->perPage = 20;
    }
    /********************** Get Offers List Method Start Here************************/
    protected  function OfferHeadList()
    {
        return array('Offer Code','Order Type','Offer Value','Offer Value Unit','Validity','Start Date','End Date','Status','Description');
    }

    protected  function filterOfferData($result)
    {

        $newEmpList = array();
        foreach ($result as $row)
        {
            $newrow = array();
            $newrow['id'] = $row->id;
            $newrow['offercode'] = $row->offercode;
            $newrow['ordertype']=$row->ordertype;
            $newrow['offervalue']=$row->offervalue;
            $newrow['offervalueunit']=$row->offervalueunit;
            $newrow['validity']=$row->validity;
            $newrow['startdate']=$row->startdate;

            $newrow['enddate']=$row->enddate;

            $currentdate=date("Y-m-d");

            if($currentdate <= $row->enddate){
                $newrow['status']='ACTIVE';
                if($row->status != 1)
                {
                    $newrow['notify'] = 1;
                    $newrow['notifmessage'] = "Enjoy the new offer ".$row->offercode." on ".$row->servicename." with ".$row->offervalueunit." ".$row->offervalueunit." discount. Hurry up!";
                }else{
                    $newrow['notify'] = 0;
                }
            }else{

                $newrow['status']='EXPIRED';
                $newrow['notify'] = 0;
            }

            $newrow['description']=$row->description;

            $sdate=DateTime::createFromFormat('Y-m-d', $row->startdate)->format('Y-m-d');
            $edate=DateTime::createFromFormat('Y-m-d', $row->enddate)->format('Y-m-d');

            array_push($newEmpList, (object) $newrow );
            }

            return $newEmpList;

    }


    /**
     *
     */
    public function index()
    {
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOfferCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetOfferList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Name', 'value' => 'ordertype')
//            array('label' => 'Email', 'value' => 'empEmail'),
//            array('label' => 'Phone', 'value' => 'empPhone'),
        );

        $data['encode'] = $this;
        $data['title'] = "Offers | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Offers";
        $data['pagination'] = true;
        $data['thead'] = $this->OfferHeadList();
        $data['mbase'] = $this->mbase;
//        $data['isactive'] = array('status');
        $data['isdate'] = array('startdate','enddate');
        $data['ajaxfunc'] = 'ajaxPaginationOfferData';
        $data['tbldata'] = $this->filterOfferData($result);
        if(user_role() == 'SuperAdmin') {
            $data['add'] = array('lable' => 'Add New Offer', 'url' => $this->mbase . 'addoffer');
            $data['edit'] = $this->mbase . 'editoffer/';
            $data['del'] = $this->mbase . 'deleteoffer/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationOfferData()
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
        $url = $this->apiurl.$this->mbase."GetOfferCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetOfferList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Offers | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Offers";
        $data['pagination'] = true;
        $data['thead'] = $this->OfferHeadList();
        $data['mbase'] = $this->mbase;
        $data['isdate'] = array('startdate','enddate');
//        $data['isactive'] = array('status');
        $data['ajaxfunc'] = 'ajaxPaginationOfferData';
        $data['tbldata'] = $this->filterOfferData($result);
        $data['cstart'] = $offset+1;

        if(user_role() == 'SuperAdmin') {
            $data['edit'] = $this->mbase . 'editoffer/';
            $data['del'] = $this->mbase . 'deleteoffer/';
        }

//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }

    public function addOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Add New Offer","url" => '', 'icon' => ''));

        $data['title'] = "Add New Offer | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
        $data['form'] = array("name" => "frm_add_new_offer", "type" => "insert", "action" => $this->mbase."insertOffer","title" => "Add New Offer", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_offer","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsOffer() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function editOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Update Combo Offer","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOfferById?key=".$key."&id=".$id;

        $comboOfferData = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($comboOfferData);
        $data['title'] = "Update Offer | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
        $data['form'] = array("name" => "frm_update_offer", "type" => "update", "action" => $this->mbase."updateOffer","title" => "Update Offer", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_offer","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsOffer() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function formControlsOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $key = $this->api->addapikey();
        $url = $this->apiurl."service/GetServiceList?key=".$key;
        $orderType = $this->callAPI('GET',$url);
        $orderType = json_decode($orderType);

        $offValUnit = array((object) array('id' => 'flat', 'name' => 'FLAT'),(object) array('id' => 'percent', 'name' => 'IN PERCENT(%)'));

        return array(
            array("name" => "OfferCode", "lable" => "Offer Code", "placeholder" => "Offer Code", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "OrderTypeId", "lable" => "Order Type", "placeholder" => "Order Type", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $orderType ),
            array("name" => "OfferValue", "lable" => "Offer Value", "placeholder" => "Offer Value", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "OfferUnit", "lable" => "Offer Value Unit", "placeholder" => "Offer Value Unit", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6", "options" => $offValUnit ),
            array("name" => "Validity", "lable" => "Time Validity(in days)", "placeholder" => "Time Validity(in days)", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "StartDate", "lable" => "Offer Start Date", "placeholder" => "Offer Start Date", "type" => "date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "OfferDescription", "lable" => "Offer Description", "placeholder" => "Offer Description", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );
    }
//
    public function insertOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $numDays = $pData['Validity'];
        $sdate = $pData['StartDate'];

        $date = new DateTime(date("Y-m-d",strtotime($sdate)));
        $date->modify('+'.$numDays.' day');
        $expDATE = $date->format('Y-m-d');

        $xdate = DateTime::createFromFormat('m/d/Y', $pData['StartDate']);
        $offerstart=$xdate->format('Y-m-d');

        $pData['StartDate'] = $offerstart;
        $pData['ExpiryDate'] = $expDATE;

        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insertOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function updateOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function deleteOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;

        $url = base_url().midUrl().$this->mbase;
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

    /********************** Get Offers List Method End Here************************/
    /*********************** Get Combo offer list Method Start Here ************************/

    protected  function ComboOfferHeadList()
    {
        return array('Offer Name','Offer Amount','Purchase Validity','Offer Validity','Start Date','End Date','Status','Description','Image','Activation');
    }

    protected  function filterComboOfferData($result)
    {
        $newEmpList = array();
        foreach ($result as $row)
        {
            $newrow = array();
            $newrow['id'] = $row->id;
            $newrow['offername'] = $row->offername;
            $newrow['offeramount']=$row->offeramount;
            $newrow['purchasevalidity']=$row->purchasevalidity;
            $newrow['offervalidity']=$row->offervalidity;
            $newrow['startdate']=$row->startdate;
            $newrow['enddate']=$row->enddate;
            $newrow['status']=$row->status;
            $newrow['description']=$row->description;
            $newrow['image']=$row->image;
            $newrow['activation']=$row->status;


            array_push($newEmpList, (object) $newrow );
        }
        return $newEmpList;



    }


    public function comboofferlist()
    {
        array_push($this->bCrumb, array('title' => 'Combo Offers','url' => ''));

        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetComboOfferCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetComboOfferList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Name', 'value' => 'ordertype')
//            array('label' => 'Email', 'value' => 'empEmail'),
//            array('label' => 'Phone', 'value' => 'empPhone'),
        );

        $data['encode'] = $this;
        $data['title'] = "Combo Offers | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Combo Offers";
        $data['pagination'] = true;
        $data['thead'] = $this->ComboOfferHeadList();
        $data['mbase'] = $this->mbase;
//        $data['isactive'] = array('status');
        $data['isactive']=array('activation');
        $data['imgArry']=array('offerPic');
        $data['ajaxfunc'] = 'ajaxPaginationComboOfferData';
        $data['tbldata'] = $this->filterComboOfferData($result);
        if(user_role() == 'SuperAdmin') {
            $data['add'] = array('lable' => 'Add New Combo Offer', 'url' => $this->mbase . 'addComboOffer');
            $data['edit'] = $this->mbase . 'editComboOffer/';
            $data['del'] = $this->mbase . 'deleteComboOffer/';
        }
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);
    }

    public function ajaxPaginationComboOfferData()
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
        $url = $this->apiurl.$this->mbase."GetComboOfferCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetComboOfferList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Offers | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Offers";
        $data['pagination'] = true;
        $data['thead'] = $this->ComboOfferHeadList();
        $data['mbase'] = $this->mbase;
//        $data['isactive'] = array('status');
        $data['imgArry']=array('offerPic');
        $data['ajaxfunc'] = 'ajaxPaginationComboOfferData';
        $data['tbldata'] = $this->filterComboOfferData($result);
        $data['cstart'] = $offset+1;
       if(user_role() == 'SuperAdmin') {
           $data['edit'] = $this->mbase . 'editComboOffer/';
           $data['del'] = $this->mbase . 'deleteComboOffer/';
         }
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }

    public function addComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Add New Offer","url" => '', 'icon' => ''));

        $data['title'] = "Add New Offer | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
        $data['form'] = array("name" => "frm_add_new_offer", "type" => "insert", "action" => $this->mbase."insertComboOffer","title" => "Add New Offer", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_offer","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsComboOffer() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function editComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Update Combo Offer","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getComboOfferById?key=".$key."&id=".$id;

        $comboOfferData = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($comboOfferData);
        $data['title'] = "Update Combo Offer | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['mbase'] = $this->mbase;
        $data['form'] = array("name" => "frm_update_offer", "type" => "update", "action" => $this->mbase."updateComboOffer","title" => "Update Combo Offer", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_offer","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formControlsComboOffer() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function formControlsComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        return array(
            array("name" => "offerName", "lable" => "Offer Name", "placeholder" => "Offer Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "offerDescription", "lable" => "Offer Description", "placeholder" => "Offer Description", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "amount", "lable" => "Offer Amount", "placeholder" => "Offer Amount", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "validity", "lable" => "Offer Validity(in days)", "placeholder" => "Validity", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "startDate", "lable" => "Offer Start Date", "placeholder" => "Offer Start Date", "type" => "date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "purchaseValidity", "lable" => "Time Validity(in days)", "placeholder" => "Offer Validity", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "offerPic", "lable" => "Upload Image", "placeholder" => "Upload Image", "type" => "file", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );
    }
//
    public function insertComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $numDays = $pData['validity'];
        $sdate = $pData['startDate'];

        $date = new DateTime(date("Y-m-d",strtotime($sdate)));
        $date->modify('+'.$numDays.' day');
        $expDATE = $date->format('Y-m-d');

        $xdate = DateTime::createFromFormat('m/d/Y', $pData['startDate']);
        $offerstart=$xdate->format('Y-m-d');

        $pData['startDate'] = $offerstart;
        $pData['expireDate'] = $expDATE;

        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."insertComboOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function updateComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateComboOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function deleteComboOffer()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteComboOffer?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;

        $url = base_url().midUrl().$this->mbase."comboofferlist";
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }
    /*********************** Get Combo Offers Method End Here ************************/

    public function uploadReceipt()
    {

        if(!empty($_FILES['file']['name']))
        {
            $file = $this->do_upload('file');

            if($file['completed'] == false){
                echo json_encode(array('status' => 0, 'message' => $file['error']));
            }else{
                echo json_encode(array('status' => 1, 'file' => $file));
            }
        }else{
            echo json_encode(array('status' => 0, 'message' => 'Please Select File!'));
        }
    }

}