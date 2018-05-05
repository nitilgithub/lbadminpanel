<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends Core_controller {
		
	protected $bCrumb = array();
	protected $mbase = 'payment/';
	protected $reURL = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Payment_model');
        array_push($this->bCrumb, array('title' => 'Payment','url' => base_url().$this->midUrl.'payment'));
        $this->reURL = base_url().$this->midUrl.$this->mbase;
        $this->tHead = array('Order Id','Client Name','Client Email','Client Mobile','Total Order Amt/Wt','Pending Amount','Payment Reminder');
        $this->perPage = 20;
    }
    /************** Get Pending Invoices List Method Start Here **************/
    protected function pendingInvoiceExtraBtn()
    {
        return (object) array(
            array('name' => 'View Sub Order','title' => 'View Sub Order', 'class' => 'btn-info btn-mini', 'icon-class' => '', 'open'=>'anchor', 'target' => '','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/suborder/' ),
            array('name' => 'Update Payment', 'title' => 'Update Payment', 'class' => 'btn-info btn-mini', 'icon-class' => '', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'payment/updatepayment/' ),
            array('name' => 'Payment Detail', 'title' => 'Payment Detail', 'class' => 'btn-success btn-mini', 'icon-class' => '', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'payment/paymentdetail/' ),
            array('name' => 'Cancel Order', 'title' => 'Cancel Order', 'class' => 'btn-danger btn-mini', 'icon-class' => '', 'open'=>'anchor', 'target' => 'anchor','anchor-class' => '', 'model-class' => '', "view-head" => false, 'anchor-url' => base_url().midurl().'order/cancelorder/' )
        );
    }
    protected  function filterPendingInvoiceData($result)
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
            $pendingAmt = (int) $row->payableamount - (int) $row->paidamount;
            $newRow['pendingamount'] = " ₹ ".$pendingAmt;

            $newRow['totalorderamt_wt'] = " ₹ ".$row->payableamount;


            $key = $this->api->addapikey();
            $url = $this->apiurl.$this->mbase."GetInvoiceReminderCount?key=".$key."&oid=".enc($row->id);
            $total = $this->callAPI('GET',$url);

            $key = $this->api->addapikey();
            $url = $this->apiurl.$this->mbase."GetInvoiceReminderList?key=".$key."&oid=".enc($row->id);
            $result = $this->callAPI('GET',$url);
            $result = json_decode($result);
            if(isset($result->status) && $result->status == 0 )
            {
                $newRow['paymentreminder'] = "-";
            }else{
                $date = $result->addon;
                if(empty($date))
                {
                    $noOfdays="";
                }
                else
                {
                    $start = strtotime(date("Y-m-d"));
                    $end = strtotime($date);
                    $x=trim(ceil(abs($end - $start) / 86400));
                    $noOfdays = "Before <span style='color:red;'>".$x."days</span> ";
                }

                $newRow['paymentreminder'] = "<span style='font-size:12px; line-height:10px;'>".$row->orderstatustext."<br><span style='color:red; line-height:10px;font-weight: bold'>Reminder Detail:</span><br>Total: <span style='color:red;'>".$total."</span> ".$noOfdays."<br>Last Date: ".$date."</span><br><button class='btn btn-primary btn-mini' id='btnRemind' title='".$row->id."'>Remind Me</button>";
            }

            array_push($newResult, (object) $newRow);
        }
        return $newResult;
    }
    public function index()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Pending Order History','url' => ''));
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingInvoicesCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetPendingInvoicesList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        /************* Get Total Pending Amount ***********/
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getPendingAmount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        $penAmount = $this->callAPI('GET',$url);
        $penAmount = json_decode($penAmount);


        $searchOption = array(
            array('label' => 'Name', 'value' => 'empName'),
            array('label' => 'Email', 'value' => 'empEmail'),
            array('label' => 'Phone', 'value' => 'empPhone'),
        );

        $data['encode'] = $this;
        $data['title'] = "Pending Order History | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;

        $data['pageheading'] = "Pending Order History";
        $data['pagination'] = true;
        $data['ajaxfunc'] = 'ajaxPaginationPendingInvoicesData';
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['penAmount'] = $penAmount->totalpendingamount;
        $data['extrabtn'] = $this->pendingInvoiceExtraBtn();
        $data['tbldata'] = ($this->filterPendingInvoiceData($result));
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./payment/view',$data);

    }

    public function ajaxPaginationPendingInvoicesData()
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
        $url = $this->apiurl.$this->mbase."GetPendingInvoicesCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPendingInvoicesList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Pending Order History | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Pending Order History";
        $data['pagination'] = true;
        $data['ajaxfunc'] = 'ajaxPaginationPendingInvoicesData';
        $data['thead'] = $this->tHead;
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
        $data['tbldata'] = ($this->filterPendingInvoiceData($result));
        $data['extrabtn'] = $this->pendingInvoiceExtraBtn();
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /************** Get Pending Invoices List Method End Here **************/

    /************** Get Paid Invoices List Method Start Here **************/
    protected  function paidHead()
    {
        return array('Order Id','Client Name','Client Email','Client Mobile','Total Order Amt/Wt','Paid Amount');
    }

    public function paid()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => 'Paid Order History','url' => ''));
        // Use For Get User Count
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPaidInvoicesCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);

        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();

        $url = $this->apiurl.$this->mbase."GetPaidInvoicesList?key=".$key."&limit=".$this->perPage;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);
        $result = json_decode($result);

        /************* Get Total Pending Amount ***********/
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."getPendingAmount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        $penAmount = $this->callAPI('GET',$url);
        $penAmount = json_decode($penAmount);


        $searchOption = array(
            array('label' => 'Name', 'value' => 'empName'),
            array('label' => 'Email', 'value' => 'empEmail'),
            array('label' => 'Phone', 'value' => 'empPhone'),
        );

        $data['encode'] = $this;
        $data['title'] = "Paid Order History | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;

        $data['pageheading'] = "Paid Order History";
        $data['pagination'] = true;
        $data['ajaxfunc'] = 'ajaxPaginationPaidData';
        $data['thead'] = $this->paidHead();
        $data['mbase'] = $this->mbase;
        $data['penAmount'] = $penAmount->totalpendingamount;
        $data['extrabtn'] = $this->pendingInvoiceExtraBtn();
        $data['tbldata'] = ($this->filterPendingInvoiceData($result));
//        $data['add'] = array('lable'=> 'Add New Subscription','url'=> $this->mbase.'add');
        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $data['filter'] = array('search_option' => $searchOption );
        $this->load->view('./payment/view',$data);

    }

    public function ajaxPaginationPaidData()
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
        $url = $this->apiurl.$this->mbase."GetPaidInvoicesCount?key=".$key;

        $url = $this->appendFilter($this->input->post(),$url);

        //total rows count
        $totalRec = $this->callAPI('GET',$url);

        $config = $this->getConfig($totalRec,$this->mbase,$this->perPage);
        $this->ajax_pagination->initialize($config);

        // Use For Get User Data
        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."GetPaidInvoicesList?key=".$key."&limit=".$this->perPage."&start=".$offset;

        $url = $this->appendFilter($this->input->post(),$url);

        $result = $this->callAPI('GET',$url);

        $result = json_decode($result);
        $data['encode'] = $this;
        $data['title'] = "Paid Order History | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['pageheading'] = "Paid Order History";
        $data['pagination'] = true;
        $data['ajaxfunc'] = 'ajaxPaginationPaidData';
        $data['thead'] = $this->paidHead();
        $data['mbase'] = $this->mbase;
        $data['isactive'] = array('status');
        $data['tbldata'] = ($this->filterPendingInvoiceData($result));
        $data['extrabtn'] = $this->pendingInvoiceExtraBtn();
        $data['cstart'] = $offset+1;
        $data['edit'] = $this->mbase.'edit/';
//        $data['del'] = $this->mbase.'delete/';
//        $data['view'] = $this->mbase.'view/';
        $this->load->view('./comman/data/data-table',$data);
    }
    /************** Get Paid Invoices List Method End Here **************/


    /************ User Order Payment Star Here ***********/
	public function UserPayNow()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        array_push($this->bCrumb, array('title' => "Order Payment","url" => '', 'icon' => ''));

        $oid =  $this->getFirstParam();

        // Get Order User Info
        $key = $this->api->addapikey();
        $url = $this->apiurl."order/getOrderById?key=".$key."&id=".$oid;
        $orderInfo = $this->callAPI('GET',$url);
        $orderInfo = json_decode($orderInfo);


        $orderPayInfo = array();
        $orderPayInfo['OrderId'] = $orderInfo->OrderId;
        $orderPayInfo['OrderTotalAmount'] = $orderInfo->PayableAmount;
        $orderPayInfo['PayableAmount'] = $orderInfo->PaidAmount;
        $orderPayInfo['RemainingAmount'] = $orderInfo->PayableAmount - $orderInfo->PaidAmount;

        $data['title'] = "Order Payment | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['hiddencontrol'] = array( array('name' => 'UserId', 'id' => '' , 'value' => $orderInfo->OrderUserId ));
        $data['mbase'] = $this->mbase;
        $data['values'] = (object) $orderPayInfo;
        $data['readonly'] = array('OrderId','OrderTotalAmount','PayableAmount','RemainingAmount');
        $data['form'] = array("name" => "frm_order_payment", "type" => "update", "action" => $this->mbase."AddOrderPayment","title" => "Order Payment", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "place_user_order","lable" => "Submit", "id" => "", "class" => "btn btn-success pull-right"),
            "controles" => $this->orderPaymentForm() );
        $this->load->view('./payment/paymentform',$data);
    }

    public function AddOrderPayment()
    {
        $response = check_login();
        if(empty($response) && $response != 1  )
        {
            $this->redirectLogin();
        }

        $pData = $this->input->post();

        $ddate=trim($pData['AmountReceivedOn']);
        $date = DateTime::createFromFormat('m/d/Y', $ddate);
        $amtreceiveon=$date->format('Y-m-d');

        $pData['AmountReceivedOn'] = $amtreceiveon;

        $key = $this->api->addapikey();
        $url = $this->apiurl.$this->mbase."AddOrderPayment?key=".$key;
        $res = $this->callAPI('POST',$url,$pData);

        echo $res;
    }
    /************ User Order Payment End Here ***********/
    /************ User Order Payment Form Start Here ***********/
    protected  function orderPaymentForm()
    {
        // Get Payment Mode List
        $key = $this->api->addapikey();
        $url = $this->apiurl."payment/getPaymentModeList?key=".$key;
        $paymentModeList = $this->callAPI('GET',$url);
        $paymentModeList = json_decode($paymentModeList);

        // Get Employees Roles List
        $key = $this->api->addapikey();
        $url = $this->apiurl."user/getEmployeesRole?key=".$key;
        $empRoleList = $this->callAPI('GET',$url);
        $empRoleList = json_decode($empRoleList);

        $reciveByList = array((object) array('id' => 'Delivery Boy', 'name' => 'Delivery Boy'),(object) array('id' => 'Company Account', 'name' => 'Company Account'));

        return array(
            array("name" => "OrderId", "lable" => "Order Id", "placeholder" => "Order Id", "type" => "text", "class" => "span11", "id" => "Order_PickDate", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "OrderTotalAmount", "lable" => "Total Order Amount", "placeholder" => "Total Order Amount", "type" => "text", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true),
            array("name" => "PayableAmount", "lable" => "Amount Paid", "placeholder" => "Amount Paid", "type" => "text", "class" => "span11", "id" => "", "size" => "10", "con-group-class" => "span6" ,"required" => true),
            array("name" => "RemainingAmount", "lable" => "Remaining Amount", "type" => "text","placeholder" => "Remaining Amount(To be paid)", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true),
            array("name" => "AmountPaid", "lable" => "Payment Received", "type" => "text","placeholder" => "Enter Amount Received(In Rupees)", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "AmountReceivedBy", "lable" => "Payment Received By", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ,"required" => true, "options" => $reciveByList),
            array("name" => "RiderId", "lable" => "Delivery Boy Name", "type" => "select", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6 RiderId-div","options" => $empRoleList ),
            array("name" => "AmountReceivedOn", "lable" => "Payment Received On", "type" => "date","placeholder" => "Select Received Date", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span6" ),
            array("name" => "ModeofPayment", "lable" => "Payment Mode", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $paymentModeList, "con-group-class" => "span6" ),
            array("name" => "Remarks", "lable" => "Remarks", "placeholder" => "Remarks", "type" => "textarea", "class" => "span11", "id" => "", "size" => "", "con-group-class" => "span12" ),
        );
    }
    /************ User Order Payment Form End Here ***********/


}
