<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'feedback/';
	protected $reURL = null;
	protected $tHead = null;
	
	public function __construct()
    {
        parent::__construct();
		array_push($this->bCrumb, array('title' => 'Feedback','url' => base_url().$this->midUrl.'feedback'));
		$this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Order Id','Client Name','Client Email','Client Mobile','Address','Rating','Product Experience','Customer Service Representative','Recommend To Friend','Comment','Feedback Date');
        $this->perPage = 20;
    }

    protected function filterFeedbackData($result)
    {
        $newResult = array();

        foreach ($result as $row)
        {
            $newRow = array();
            $newRow['id'] = $row->id;
            $newRow['orderid'] = $row->orderid;
            $newRow['clientname'] = $row->clientname;
            $newRow['clientemail'] = $row->clientemail;
            $newRow['clientmobile'] = $row->clientmobile;
            $newRow['address'] = $row->address;

            $star="";
            $rcount = $row->rating;
            for($i=1;$i<=$rcount;$i++)
            {
                $star=$star."<i class='icon-star' style='color: #f89406' ></i>";
            }
            $newRow['rating'] = $star;

            $que1 = $row->question1;
            $pexp = "";
            if($que1 < 3)
            {
                $pexp= "Very Dissatisfied";
            }
            else if($que1 >= 3 && $que1 <= 6){
                $pexp="Neutral";
            }
            else{
                $pexp="Satisfied";
            }
            $newRow['productexperience'] = $pexp;


            $que2 = $row->question2;
            $csr = "";
            if($que2 < 3)
            {
                $csr = "Very Dissatisfied";
            }
            else if($que2 >= 3 && $que2 <= 6)
            {
                $csr = "Neutral";
            }
            else{
                $csr = "Satisfied";
            }

            $newRow['customerservicerepresentative'] = $csr;


            $que3 = $row->question3;
            $rtf = "";
            if($que3 < 3)
            {
                $rtf = "Not at All";
            }
            else if($que3 >= 3 && $que3 <= 6)
            {
                $rtf = "Neutral";
            }
            else{
                $rtf = "Extremely Likely";
            }
            $newRow['recommendtofriend'] = $rtf;

            $newRow['comment'] = $row->comment;
            $newRow['feedbackdate'] = $row->feedbackdate;
            array_push($newResult, (object) $newRow);
        }
        return $newResult;
    }
	/********************** Get Feedback List Method Start Here************************/
	public function index()
	{
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Customer\'s Feedback','url' => ''));
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFeedbackCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetFeedbackList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        $searchOption = array(
            array('label' => 'Service Name', 'value' => 'ServiceName'),
        );

        $data['encode'] = $this;
        $data['title'] = "Customer's Feedback | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Customer's Feedback";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isdate'] = array('feedbackdate');
        $data['ajaxfunc'] = 'ajaxPaginationFeedbackData';
        $data['tbldata'] = $this->filterFeedbackData($result);
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./comman/data/view',$data);

    }

    public function ajaxPaginationFeedbackData()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

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
        $url = $this->apiurl.$this->mbase."GetFeedbackCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetFeedbackList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Customer's Feedback | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Customer's Feedback";
        $data['pagination'] = true;
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isdate'] = array('feedbackdate');
        $data['ajaxfunc'] = 'ajaxPaginationFeedbackData';
        $data['tbldata'] = $this->filterFeedbackData($result);
        $data['cstart'] = $offset+1;
//        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /********************** Get Feedback List Method End Here************************/

    /********************** User Feedback List Method Start Here************************/

    public function adduserfeedback()
    {
        $pData = $this->input->post();

        $pData['userid'] = dec($pData['userid']);
        $pData['feedbacktakendate'] = date("Y-m-d",strtotime($pData['feedbacktakendate']));
        $pData['createdby'] = user_id();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."adduserfeedback?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }

    public function GetUserFeedbackList()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetUserFeedbackList?key=".$key."&userid=".$pData['userid'];

        $res = $this->callAPI('GET',$url);

        echo $res;

    }
    /********************** User Feedback List Method End Here************************/

    public function deleteUserFeedback()
    {
        $pData = $this->input->post();

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."deleteUserFeedback?key=".$key;

        $res = $this->callAPI('POST',$url,$pData);

        echo $res;

    }

    public function downloadFeedbackExcel()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $uid = $this->getFirstParam();
        $uname = $this->getSecondParam();

        $excHead = array(
            'User Id',
            'Name',
            'Phone',
            'Order Id',
            'Feedback Date',
            'Service Experience',
            'Customer Service Representative',
            'Pickup & Delivery Rating',
            'Recommend to Friend',
            'Feedback',
        );
        $excData = array();
        $excName = str_replace("%20","-",$uname)."-Feedback-".date("d-m-Y");

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."downloadFeedbackExcel?key=".$key."&userid=".$uid;
        $result = $this->callAPI('GET',$url);
        ini_set('memory_limit', '-1');
        $result = json_decode($result);

        foreach ($result as $row)
        {
            array_push($excData,array(
                    !empty($row->userid) ? $row->userid : 'NA',
                    !empty($row->username) ? $row->username : 'NA',
                    !empty($row->UserPhone) ? $row->UserPhone : 'NA',
                    !empty($row->orderid) ? $row->orderid : 'NA',
                    !empty($row->feedbacktakendate) ? date('d-m-Y',strtotime($row->feedbacktakendate)) : 'NA',
                    !empty($row->serviceexperience) ? $row->serviceexperience : 'NA',
                    !empty($row->customerservicerepresentative) ? $row->customerservicerepresentative : 'NA',
                    !empty($row->pickupdeliveryrating) ? $row->pickupdeliveryrating : 'NA',
                    !empty($row->recommendtofriend) ? $row->recommendtofriend : 'NA',
                    !empty($row->feedback) ? $row->feedback : 'NA',
                )
            );
        }

        $this->writeExcel($excHead,$excData,$excName);

    }
}