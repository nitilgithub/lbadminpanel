<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Controller extends CI_Controller {

	var $pageTitle = "Laundrybucket";
	var $apiurl = "http://127.0.0.1/laundrybucketapi/";
	var $midUrl = null;
    protected $ip;

	public function __construct()
    {
            parent::__construct();
            $this->load->model('Core_Model');
//            $this->midUrl = "index.php/";
            $this->ip = $this->input->ip_address();
    }
	
	public function index()
	{
//        $this->load->model('Api_model');
//	    $apikey = $this->Api_model->addapikey();
//	    echo $apikey;die;
//		$this->load->view('./auth/login');
	}

	public function redirectLogin()
    {
        $url = base_url().midUrl().'auth/login';

        echo "<script>";
        echo "window.location.href = '$url'";
        echo "</script>";
    }

	
	public function resetUrlbCrumb($bCrumb)
	{
		$bCrumb[0]['url'] = '';
		return $bCrumb;
	}
	

	/************** API Call Method Start Here *************/
	public function callAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":

//                curl_setopt($curl, CURLOPT_PUT, 1);
                if ($data)
                    $data_json = json_encode($data);
//                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $headInfo = array('Content-Type: application/json','Content-Length: ' . strlen($data_json));
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headInfo );
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS,$data_json);
                break;
            default:
//                if ($data)
//                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        ini_set('max_execution_time', 500);
        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
	/************** API Call Method End Here *************/

    /**************** Append Filter Value In Url Start Here *************/
    public function appendFilter($filters = array(),$url)
    {
        try
        {
            if(!empty($filters['filter']) && $filters['filter'] != '-1')
            {
                $url = $url."&filter=".$filters['filter'];
            }

            if(!empty($filters['keywords']) && $filters['keywords'] != '-1' )
            {

                $url = $url."&keywords=".$filters['keywords'];
            }

            if(!empty($filters['fromdate']) && $filters['fromdate'] != '' )
            {
                $ddate=trim($filters['fromdate']);
                $date = DateTime::createFromFormat('m/d/Y', $ddate);
                $fromdate=$date->format('Y-m-d');

                $url = $url."&fromdate=".$fromdate;
            }

            if(!empty($filters['todate']) && $filters['todate'] != '' )
            {
                $ddate=trim($filters['todate']);
                $date = DateTime::createFromFormat('m/d/Y', $ddate);
                $todate=$date->format('Y-m-d');

                $url = $url."&todate=".$todate;
            }

            if(!empty($filters['type']) && $filters['type'] != '-1' )
            {
                $url = $url."&type=".$filters['type'];
            }

            if(!empty($filters['franchiseId']))
            {
                $url = $url."&franchiseId=".enc($filters['franchiseId']);
            }

            if(!empty($filters['subs_status']))
            {
                $url = $url."&subs_status=".$filters['subs_status'];
            }

            return $url;
        }catch (Exception $e)
        {
            return $e;
        }
    }
    /**************** Append Filter Value In Url End Here *************/

    /**************** Add Filter Start Here *************/
     public function addFilter($filter,$url)
     {
         try{
             if(!empty($filter) && $filter != '-1')
             {
                 $url = $url."&filter=".$filter;
             }
             return $url;
         }catch (Exception $e)
         {
             return $e;
         }
     }
    /**************** Add Filter End Here *************/

    /**************** Add Keywords Start Here *************/
    public function addKeywords($keyword,$url)
    {
        try{
            if(!empty($keyword))
            {
                $url = $url."&keywords=".$keyword;
            }
            return $url;
        }catch (Exception $e)
        {
            return $e;
        }
    }
    /**************** Add Keywords End Here *************/

	/********** Check And Set Toggle Control Value Methos *********/
	public function checkToggle($dataArry,$val)
	{
		if(array_key_exists($val, $dataArry) && $dataArry[$val] == "on")
		{
			$dataArry[$val] = true;
		}else{
			$dataArry[$val] = FALSE;
		}
		
		return $dataArry;
	}
	
	/********** Encription Methos *********/
	public function enc($str)
	{
		$str = base64_encode($str);
		$str = base64_encode($str);
		return $str;
	}
	
	public function dec($str)
	{
		$str = base64_decode($str);
		$str = base64_decode($str);
		return $str;
	}
	
	/********** Encryption Password Methos *********/
	public function encPass($pass)
	{
		return md5($pass);
	}

	/******************* Upload File **********************/
    function do_upload($name,$config = array())
    {
        if(empty($config['upload_path']))
        {
            $config['upload_path'] = './uploads/';
        }

        if(empty($config['allowed_types']))
        {
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf'; //'gif|jpg|png'
        }

        if(empty($config['max_size']))
        {
            $config['max_size']	= '1000';
//            $config['max_width']  = '1024';
//            $config['max_height']  = '768';
        }


        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($name))
        {
            $error = array('error' => $this->upload->display_errors());
            $error['completed'] = false;
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $data['completed'] = true;
            return $data;
        }
    }

	/*************** Write PHP Excel File ************/
	public function writeExcel($head = array(),$data = array(),$name)
	{
		$cellArry = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('test worksheet');
		//set cell A1 content with some text

		// Make Head Row Excel 		
		foreach($head as $key => $i)
		{
			$this->excel->getActiveSheet()->setCellValue($cellArry[$key].'1', $i);
		}
		
		// Put Data in Excel		
		$start = 2;
		$test = array();
		for($j = 0; $j < sizeof($data) ; $j++)
		{
			foreach($head as $key => $i)
			{
				$this->excel->getActiveSheet()->setCellValue($cellArry[$key].$start, $data[$j][$key]);
			}	
			
			$start += 1;	
		}

        $this->excel->getActiveSheet()
            ->getStyle('A1:Z1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('2682C0');
		//change the font size
		// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
		
		//make the font become bold
		// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false);
		
		//merge cell A1 until D1
		// $this->excel->getActiveSheet()->mergeCells('A1:D1');
		
		//set aligment to center for that merged cell (A1 to D1)
		// $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$filename= $name.'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		             
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}

	/***************** Read PHP Excel File ***************/
    public function readExcel($response = array())
    {
        $data = array();
        if(!empty($response)){

            $file = $response["data"]["file_path"];
            //load the excel library
            $this->load->library('excel');
            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            //get only the Cell Collection
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();


            $data_as_array = $objPHPExcel->getActiveSheet()->toArray();

            $data['header'] = $data_as_array[0];
            unset($data_as_array[0]);
            $data['values'] = $data_as_array;

            unlink($file); // Delete File After read Data From Excel File
        }

        $head = $data['header'][0];

        $resData = array();
        $data_errors= array();
        $lineno = 0;
        foreach ($data['values'] as $data_key => $excel_data) {
            $lineno++;
            $em_data = array();

            for ($col=0; $col < count($data['header']); $col++) {
                $field = $data['header'][$col];
                $field = strtolower($field);
                $field = str_replace(' ','_',$field);
                $value = $excel_data[$col];

                $em_data[$field] = $value;

            }

            array_push($resData,$em_data);
        }

        if(!empty($data_errors)){
            $error = '';
            for ($i=0; $i < sizeof($data_errors); $i++) {
                $error.= $data_errors[$i]."<br>";
            }
            return $error;
//            $this->session->set_flashdata('error',$error);
//            return redirect('attendance/daily');
        }else{
//            $this->session->set_flashdata('success',"Data Uploaded Successfully");
//            return redirect('attendance/daily');
            return $resData;
        }
    }


    /**************** Get Proper Id ***************/
    public function getPropNum($num)
    {
        $num =  str_pad($num, 2, '', STR_PAD_LEFT);
        return $num;
    }

    /***************** Ajax Page Settings **************/
    public function getConfig($totalRec,$baseurl,$parpage = 10)
    {
        $config['target']      = '#view-data';
        $config['base_url']    = $baseurl.'ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $parpage;
        $config['link_func']   = 'searchFilter';
        return $config;
    }

    /**************** Get Login Employee Role Name **************/
     public function getrolename()
    {
        return $this->session->userdata('rolename');
    }

    /**************** Get Login Employee Role Id **************/
    public function getroleid()
    {
        return $this->session->userdata('roleid');
    }

    /**************** Get URL First Parameter **************/
    public function getFirstParam()
    {
        return $this->uri->segment('3');
    }

    /**************** Get URL Second Parameter **************/
    public function getSecondParam()
    {
        return $this->uri->segment('4');
    }



    /**************** Send Admin Mail **************/
    public function sendAdminMail($frommail,$message)
    {
        $fromname= "Laundry Bucket Order";
        $to = 'bucket@laundrybucket.co.in';
        $subject = 'New Order from laundrybucket.co.in';

        return $this->sendMail($frommail,$fromname,$to,$subject,$message);
    }
    /**************** Send User Mail **************/
    public function sendUserMail($to,$message)
    {
        $frommail='orders@laundrybucket.co.in';
        $fromname='Laundry Bucket';
        $subject='Thanks for placing order';

        return $this->sendMail($frommail,$fromname,$to,$subject,$message);

    }
    /***************** Send Mail Method ***************/
    protected function sendMail($frommail,$fromname,$to,$subject,$message)
    {
        //Load email library
        $this->load->library('email');

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://mail.laundrybucket.co.in';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'orders@laundrybucket.co.in';
        $config['smtp_pass']    = '91tGV@t!yP1S';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->email->initialize($config);

        $this->email->from($frommail, $fromname);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        //Send mail
        if($this->email->send())
            return true;
        else
            return false;
    }

    /****************** Send SMS ****************/
    public function sendSMS($mobile,$message)
    {
        $message = urlencode($message);

//        $mobile = $mobile.",8744009933,9718661177";

        $url= "http://alerts.kapsystem.com/api/web2sms.php?workingkey=A28560483f0aa800b63f2d7ddeb3acb5a&to=$mobile&sender=BUCKET&message=".$message;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);

        curl_close($ch);

        if($res)
        {
            return true;
        }else{
            return false;
        }

    }

}
