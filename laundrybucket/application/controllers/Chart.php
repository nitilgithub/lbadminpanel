<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'chart/';
	protected $reURL = null;
	protected $tHead = null;
	protected $isDate = null;
	protected $isBool = null;

	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Order Summary Chart','url' => base_url().midurl().'chart'));
		$this->reURL = base_url().midurl().$this->mbase;
    }

    /****************** Get All Orders Star Here ******************/
	public function index()
	{

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Subscription Summary Chart | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Subscription Summary Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/subscriptionsummary',$data);

    }

    /****************** Get All Orders End Here ******************/
    /****************** Total Subscription Chart Start Here ******************/
    public function totalSubscriptions()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Total User Subscription','url' => ''));
        $searchOption = array(array('label' => '2018', 'value' => '2018'),array('label' => '2017', 'value' => '2017'),array('label' => '2016', 'value' => '2016'),array('label' => '2015', 'value' => '2015'));

        $data['encode'] = $this;
        $data['title'] = "Total User Subscription | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Total User Subscription Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/totalsubscriptions',$data);

    }
    public function getTotalSubscriptionsData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getTotalSubscriptionsData?key=".$key."&year=".$year;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);
        $table = array();

        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => $row->no_of_subs));
        }

        echo json_encode($table);
    }
    /****************** Total Subscription Chart End Here ******************/
    /****************** Subscription Summary Chart Start Here ******************/
    public function subscriptionSummary()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Subscription Summary','url' => ''));
        $searchOption = array(array('label' => 'Name', 'value' => 'subs_name'),array('label' => 'Plan Type', 'value' => 'planType'),array('label' => 'Service Type', 'value' => 'Subs_ServiceType'));

        $data['encode'] = $this;
        $data['title'] = "Subscription Summary Chart | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Subscription Summary Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/subscriptionsummary',$data);

    }

    public function getSubscriptionSummaryData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getSubscriptionSummaryData?key=".$key;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);
        $table = array();
        $table['cols'] = array(
            //Labels for the chart, these represent the column titles
            array('id' => '', 'label' => 'Subscription', 'type' => 'string'),
            array('id' => '', 'label' => 'No of Subscriptions', 'type' => 'number')
        );

        $rows = array();
        foreach($result as $row)
        {
            //$table['rows'][] = array('c' => array(array('v' => $row["Months"]), array('v' => $row["no_of_subs"])));
            $temp = array();

            //Values
            $temp[] = array('v' => (string) $row->subs_name);
            $temp[] = array('v' => (float) $row->no_of_subs);
            $rows[] = array('c' => $temp);
            // $result->free();
            $table['rows'] = $rows;
        }
        echo json_encode($table);
    }
    /****************** Subscription Summary Chart End Here ******************/

    /****************** Yearly Collection Chart Start Here ******************/
    public function yearlyCollection()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Yearly Collection','url' => ''));

        $key = $this->api->addapikey();
        $url = $this->apiurl."subscription/getServicesList?key=".$key;
        $result = $this->callAPI('GET',$url);
        $serviceList = json_decode($result);

        $searchOption = array(array('name' => 'Select Year', 'id' => ''),array('name' => '2018', 'id' => '2018'),array('name' => '2017', 'id' => '2017'),array('name' => '2016', 'id' => '2016'),array('name' => '2015', 'id' => '2015'));
//        $searchOption2 = array(array('label' => 'Select Service Type', 'value' => ''),array('label' => 'Laundry', 'value' => '1'));
        array_unshift($serviceList, (object) array('name' => 'Select Service Type', 'id' => null));

        $data['encode'] = $this;
        $data['title'] = "Yearly Collection | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Yearly Collection Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('year' => $searchOption, 'service' => $serviceList  );
        $this->load->view('./chart/yearlycollection',$data);

    }
    public function getYearlyCollectionData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $service = $this->input->get('service');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getYearlyCollectionData?key=".$key."&year=".$year."&service=".$service;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();

        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => $row->ota));
        }

        echo json_encode($table);
    }
    /****************** Yearly Collection Chart End Here ******************/

    /****************** Yearly Order Chart Start Here ******************/
    public function yearlyOrder()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Yearly Orders','url' => ''));

        $key = $this->api->addapikey();
        $url = $this->apiurl."subscription/getServicesList?key=".$key;
        $result = $this->callAPI('GET',$url);
        $serviceList = json_decode($result);

        $searchOption = array(array('name' => 'Select Year', 'id' => ''),array('name' => '2018', 'id' => '2018'),array('name' => '2017', 'id' => '2017'),array('name' => '2016', 'id' => '2016'),array('name' => '2015', 'id' => '2015'));
//        $searchOption2 = array(array('label' => 'Select Service Type', 'value' => ''),array('label' => 'Laundry', 'value' => '1'));
        array_unshift($serviceList, (object) array('name' => 'Select Service Type', 'id' => null));

        $data['encode'] = $this;
        $data['title'] = "Yearly Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Yearly Orders Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('year' => $searchOption, 'service' => $serviceList  );
        $this->load->view('./chart/yearlyorder',$data);

    }
    public function getYearlyOrderData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $service = $this->input->get('service');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getYearlyOrderData?key=".$key."&year=".$year."&service=".$service;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();

        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => (int) $row->no_of_orders));
        }

        echo json_encode($table);
    }
    /****************** Yearly Order Chart End Here ******************/
    /****************** New User added in Chart Start Here ******************/
    public function newUserAdded()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'New User Added','url' => ''));

        $searchOption = array(array('label' => '2018', 'value' => '2018'),array('label' => '2017', 'value' => '2017'),array('label' => '2016', 'value' => '2016'),array('label' => '2015', 'value' => '2015'));

        $data['encode'] = $this;
        $data['title'] = "New User Added | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "New User Added Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/newuseradded',$data);

    }
    public function getNewUserAddedData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getNewUserAddedData?key=".$key."&year=".$year;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();

        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => (int) $row->no_of_user));
        }

        echo json_encode($table);
    }
    /****************** New User added in Chart End Here ******************/
    /****************** Reprocess orders in Chart Start Here ******************/
    public function reprocessOrderMonthly()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Total Reprocess Orders','url' => ''));

        $searchOption = array(array('label' => '2018', 'value' => '2018'),array('label' => '2017', 'value' => '2017'),array('label' => '2016', 'value' => '2016'),array('label' => '2015', 'value' => '2015'));

        $data['encode'] = $this;
        $data['title'] = "Total Reprocess Orders | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Total Reprocess Orders Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/reprocessordermonthly',$data);

    }
    public function getReprocessOrderMonthlyData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getReprocessOrderMonthlyData?key=".$key."&year=".$year;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();
        if(!isset($result->status) || $result->status != 0 )
        {
        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => (int) $row->no_of_orders));
        }
        }
        echo json_encode($table);
    }
    /****************** Reprocess orders in Chart End Here ******************/
    /****************** Orders From City in Chart Start Here ******************/
    public function orderFromCity()
    {

        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Orders From Particular City Per Month','url' => ''));

        $searchOption = array(array('label' => '2018', 'value' => '2018'),array('label' => '2017', 'value' => '2017'),array('label' => '2016', 'value' => '2016'),array('label' => '2015', 'value' => '2015'));

        $data['encode'] = $this;
        $data['title'] = "Orders From Particular City Per Month | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Orders From Particular City Per Month Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./chart/orderfromcity',$data);

    }
    public function getOrderFromCityData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getOrderFromCityData?key=".$key."&year=".$year;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();
        if(!isset($result->status) || $result->status != 0 )
        {
            foreach($result as $row)
            {
                array_push($table,array('y' => $row->Months, 'a' => (int) $row->no_of_orders));
            }
        }
        echo json_encode($table);
    }
    /****************** Orders From City in Chart End Here ******************/
    /****************** Yearly Collection New Existing Chart Start Here ******************/
    public function yearlyCollectionUser()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Yearly Collection (New/Existing Customers)','url' => ''));

        $key = $this->api->addapikey();
        $url = $this->apiurl."subscription/getServicesList?key=".$key;
        $result = $this->callAPI('GET',$url);
        $serviceList = json_decode($result);

        $searchOption = array(array('name' => 'Select Year', 'id' => ''),array('name' => '2018', 'id' => '2018'),array('name' => '2017', 'id' => '2017'),array('name' => '2016', 'id' => '2016'),array('name' => '2015', 'id' => '2015'));
//        $searchOption2 = array(array('label' => 'Select Service Type', 'value' => ''),array('label' => 'Laundry', 'value' => '1'));
        array_unshift($serviceList, (object) array('name' => 'Select Service Type', 'id' => null));

        $data['encode'] = $this;
        $data['title'] = "Yearly Collection (New/Existing Customers) | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Yearly Collection (New/Existing Customers) Chart";
        $data['mbase'] = $this->mbase;
        $data['tbldata'] = array() ;//$result;
        $data['filter'] = array('year' => $searchOption, 'service' => $serviceList  );
        $this->load->view('./chart/yearlycollectionuser',$data);

    }

    public function getYearlyCollectionUserData()
    {
//        $response = check_login();
//        if(empty($response) && $response != 1  )
//        {
//            $this->redirectLogin();
//        }

        $year = $this->input->get('year');
        $service = $this->input->get('service');
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getYearlyCollectionUserData?key=".$key."&year=".$year."&service=".$service;
        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $table = array();

        foreach($result as $row)
        {
            array_push($table,array('y' => $row->Months, 'a' => (int) $row->nota, 'b' => (int)$row->eota ));
        }

        echo json_encode($table);
    }
    /****************** Yearly Collection New Existing Chart End Here ******************/

}