<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'settings/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Settings','url' => base_url().midUrl().$this->midUrl.'settings'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Name','Fee','Start Date','End Date','Address','Phone','Email','Contact Person');
        $this->perPage = 20;
    }

	public function index()
	{

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }


        $url = base_url().midUrl().'settings/deliverytypelist';

        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";

    }

    /********************** Delivery Type List Method Start Here************************/
    public function deliveryTypeList()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        if(user_role() == 'SuperAdmin')
        {

            array_push($this->bCrumb, array('title' => 'Delivery Type','url' => ''));

            $key = $this->api->addapikey();

            $url = $this->apiurl.$this->mbase."GetDeliveryTypeList?key=".$key;

            $url = $this->appendFilter($this->input->post(),$url);

            $result = $this->callAPI('GET',$url);
            $result = json_decode($result);

            $searchOption = array(
                array('label' => 'Franchisee Name', 'value' => 'franchisee_name'),
            );

            $data['encode'] = $this;
            $data['title'] = "Delivery Type | ".$this->pageTitle;
            $data['breadcrumb'] = $this->bCrumb;
            $data['pageheading'] = "Delivery Type";
//            $data['pagination'] = true;
            $data['thead'] = array('Delivery Title','Delivery Price','Delivery Days');
            $data['mbase'] = $this->mbase;
            $data['isbool'] = array('status');
            $data['tbldata'] = $result;
//            $data['filter'] = array('search_option' => $searchOption );

            $data['add'] = array('lable'=> 'Add New Delivery Type','url'=> $this->mbase.'deliveryTypeAdd');
            $data['edit'] = $this->mbase.'deliveryTypeEdit/';
            $data['del'] = $this->mbase.'deliveryTypeDelete/';

            $this->load->view('./comman/data/view',$data);

        }else{
            $data['title'] = "Access Denied | ".$this->pageTitle;
            $this->load->view('./errors/accessdenied',$data);
        }


    }


    public function deliveryTypeAdd()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }
        array_push($this->bCrumb, array('title' => 'Delivery Type','url' => base_url().midUrl().$this->mbase.'deliverytypelist'));
        array_push($this->bCrumb, array('title' => "Add Delivery Type","url" => '', 'icon' => ''));

        $data['title'] = "Add New Delivery Type | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_delivery_type", "type" => "insert", "action" => $this->mbase."deliveryTypeInsert","title" => "Add New Delivery Type", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_delivery_type","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formDeliveryTypeControls() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function deliveryTypeEdit()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }
        array_push($this->bCrumb, array('title' => 'Delivery Type','url' => base_url().midUrl().$this->mbase.'deliverytypelist'));
        array_push($this->bCrumb, array('title' => "Update Delivery Type","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getDeliveryTypeById?key=".$key."&id=".$id;

        $Data = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($Data);
        $data['title'] = "Update Delivery Type | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_update_delivery_type", "type" => "update", "action" => $this->mbase."deliveryTypeUpdate","title" => "Update Delivery Type", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_delivery_type","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formDeliveryTypeControls() );

        $this->load->view('./comman/data/formfull',$data);
    }
//
    public function deliveryTypeInsert()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."insertDeliveryType?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function deliveryTypeUpdate()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateDeliveryType?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function deliveryTypeDelete()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteDeliveryType?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase."/deliverytypelist";
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

    public function formDeliveryTypeControls()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        return array(
            array("name" => "DeliveryTitle", "lable" => "Delivery Title", "placeholder" => "Delivery Title", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "DeliveryPrice", "lable" => "Delivery Price", "placeholder" => "Delivery Price", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "DeliveryDays", "lable" => "Delivery Days", "placeholder" => "Delivery Days", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
            array("name" => "", "lable" => "", "placeholder" => "", "type" => "blank", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
        );
    }
    /********************** Delivery Type List Method End Here************************/
    /********************** Order Via List Method Start Here************************/
    public function OrderViaList()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        if(user_role() == 'SuperAdmin')
        {

            array_push($this->bCrumb, array('title' => 'Order Via','url' => ''));

            $key = $this->api->addapikey();

            $url = $this->apiurl.$this->mbase."GetOrderViaList?key=".$key;

            $url = $this->appendFilter($this->input->post(),$url);

            $result = $this->callAPI('GET',$url);
            $result = json_decode($result);

            $searchOption = array(
                array('label' => 'Order Via', 'value' => 'franchisee_name'),
            );

            $data['encode'] = $this;
            $data['title'] = "Order Via | ".$this->pageTitle;
            $data['breadcrumb'] = $this->bCrumb;
            $data['pageheading'] = "Order Via";
//            $data['pagination'] = true;
            $data['thead'] = array('Order Via');
            $data['mbase'] = $this->mbase;
            $data['isbool'] = array('status');
            $data['tbldata'] = $result;
//            $data['filter'] = array('search_option' => $searchOption );

            $data['add'] = array('lable'=> 'Add New Order Via','url'=> $this->mbase.'orderviaadd');
            $data['edit'] = $this->mbase.'orderviaedit/';
            $data['del'] = $this->mbase.'orderviadelete/';

            $this->load->view('./comman/data/view',$data);

        }else{
            $data['title'] = "Access Denied | ".$this->pageTitle;
            $this->load->view('./errors/accessdenied',$data);
        }


    }


    public function OrderViaAdd()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Order Via','url' => base_url().midUrl().$this->mbase.'ordervialist'));
        array_push($this->bCrumb, array('title' => "Add Order Via","url" => '', 'icon' => ''));

        $data['title'] = "Add New Order Via | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_order_via", "type" => "insert", "action" => $this->mbase."OrderViaInsert","title" => "Add New Order Via", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_order_via","lable" => "Save", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formOrderViaControls() );
        $this->load->view('./comman/data/formfull',$data);
    }

    public function OrderViaEdit()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }
        array_push($this->bCrumb, array('title' => 'Order Via','url' => base_url().midUrl().$this->mbase.'ordervialist'));
        array_push($this->bCrumb, array('title' => "Update Order Via","url" => '', 'icon' => ''));

        $id =  $this->getFirstParam();
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderViaById?key=".$key."&id=".$id;

        $Data = $this->callAPI('GET',$url);

        $data['dataid'] = $id;
        $data['values'] = json_decode($Data);
        $data['title'] = "Update Delivery Type | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_update_order_via", "type" => "update", "action" => $this->mbase."orderViaUpdate","title" => "Update Order Via", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_order_via","lable" => "Update", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->formOrderViaControls() );

        $this->load->view('./comman/data/formfull',$data);
    }
//
    public function OrderViaInsert()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."insertOrderVia?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);
        echo $res;
    }

    public function OrderViaUpdate()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."updateOrderVia?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function OrderViaDelete()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $id =  $this->getFirstParam();

        $pData = array('dataid' => $id);

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteOrderVia?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

//        echo $res;
        $url = base_url().midUrl().$this->mbase."/ordervialist";
//        redirect($url);
        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

    public function formOrderViaControls()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        return array(
            array("name" => "ordervia", "lable" => "Order Via", "placeholder" => "Order Via", "type" => "text", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "", "lable" => "", "placeholder" => "", "type" => "blank", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6"),
        );
    }
    /********************** Order Via List Method End Here************************/

    public function GetLocationListOptions()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetLocationListOptions?key=".$key."&cityid=".$pData['cityid'];

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;

    }
}