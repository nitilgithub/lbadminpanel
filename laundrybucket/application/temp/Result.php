<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends Core_controller {
	
	protected $bCrumb = array(); 
	protected $mbase = 'result/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	protected $viewReportCard = null;
	protected $downloadReportCard = null;

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Result_model');
		array_push($this->bCrumb, array('title' => 'Results','url' => base_url().'index.php/'.$this->mbase));	
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Roll No','Name','Father Name');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
		$this->viewReportCard = $this->reURL.'viewreportcard/';
		$this->downloadReportCard = $this->reURL.'downloadPDF/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Results | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Results";
		

		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;

		$data['viewReportCard'] = $this->viewReportCard;
		$data['downloadReportCard'] = $this->downloadReportCard;

		if(!empty($this->session->userdata('user_role')) && $this->session->userdata('user_role') != 'student' )
        {
            $data['add'] = array('lable'=> 'Add Result','url'=> 'result/add');
            $data['link'] = array('lable'=> 'Download Class Students Report','url'=> 'result/downloadReport');
        }

		/*********** Filter Start Here **********/
        $this->load->model('Branch_model');
        $classList = $this->Branch_model->getBranchList();

        $this->load->model('Unit_model');
        $unitList = $this->Unit_model->getUnitList();

        if(!empty($this->session->userdata('class_id')))
        {
            $classId = $this->session->userdata('class_id');
        }
        else{
            $classId = null;
        }

        $filter = array(
            array("name" => "class_id", "lable" => "Class" , "type" => "select", "class" => "", "id" => "", "size" => "", "options" => $classList , "required" => true, "default" => $classId ),
            array("name" => "unit_id", "lable" => "Unit", "type" => "select", "class" => "", "id" => "", "size" => "","options" => $unitList, "required" => true ),
            array("name" => "submit", "lable" => "Search", "type" => "button", "class" => "", "id" => ""),
        );

        /*********** Filter Start End **********/

        if(!empty($this->session->userdata('user_role')) && $this->session->userdata('user_role') == 'student' )
        {
//            unset($filter[0]);
            $filter = array();
            $this->tHead = array('Unit','Percentage','Grade','Total Obtained Mark');
            $studentId = $this->session->userdata('student_id');
            $resData = $this->Result_model->getStudentResult($studentId);

            $data['edit'] = null;
            $data['del'] = null;
        }
        else{

            //total rows count
            $totalRec = count($this->Result_model->getTotalResults());

            //pagination configuration
            $config = $this->getConfig($totalRec);
            $this->ajax_pagination->initialize($config);

            if(isset($_POST['submit-filter']))
            {
                $classId = $this->input->post('class_id');
                $unitId = $this->input->post('unit_id');
                $resData = array();

                $this->load->model('Subject_model');
                $classSubjList = $this->Subject_model->getClassSubjectByClassId($classId);
                foreach($classSubjList as $subj)
                {
                    array_push($this->tHead,$subj->subject);
                }

                $resResult = $this->Result_model->getResults(array('limit'=>$this->perPage));

                if(!empty($resResult))
                {
                    foreach ($resResult as $i)
                    {
                        $classStdId = $i->class_student_id;
                        $resMark = $this->Result_model->getSubjectMarks($classId,$classStdId);
                        $marksData = array();
                        $stdInfo  = array('id'=>$i->id,'roll_no' => $i->roll_no, 'name' => $i->name, 'father_name' => $i->father_name, 'percentage' => $i->percentage, 'grade' => $i->grade, 'total_marks' => $i->total_obtained_mark );
                        foreach ($resMark as $r)
                        {
                            $stdInfo[strtolower($r->subject)] = $r->obtained_mark;
                        }
                        array_push($resData, (object) $stdInfo );
                    }
                }
                array_push($this->tHead, 'Percentage', 'Grade','Total Marks');
            }
            else{
                $resData = array();
            }
        }

        $data['filter'] = $filter;


        $data['thead'] = $this->tHead;
        $data['tbldata'] = $resData;
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = 1;

		$this->load->view('./comman/result/view',$data);
	}
	
 	
	public function ajaxPaginationData()
	{
        $conditions = array();
		
        $data['encode'] = $this;
		$data['thead'] = $this->tHead;    
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;   
		
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        //total rows count
        $totalRec = count($this->Result_model->getResults($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Result_model->getResults($conditions);
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = $page+1;
        
        //load the view
        $this->load->view('./comman/result/data-table', $data, false);
    }

	protected function getConfig($totalRec)
	{
		$config['target']      = '#view-data';
        $config['base_url']    = base_url().$this->mbase.'ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
		return $config; 
	}
 	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Page","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Result | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_result", "enctype" => true, "type" => "insert", "action" => $this->mbase."insert","title" => "Add Result", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_result","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Page","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Result_model->getPageById($id);
		$data['title'] = "Update Page | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_page", "enctype" => true, "type" => "update", "action" => $this->mbase."update","title" => "Update Page", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_page","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
        $this->load->model('Branch_model');
        $clasList = $this->Branch_model->getBranchList();

        $this->load->model('Unit_model');
        $unitList = $this->Unit_model->getUnitList();

        if(!empty($this->session->userdata('class_id')))
        {
            $classId = $this->session->userdata('class_id');
        }
        else{
            $classId = null;
        }

		return array(
        array("name" => "class_id", "lable" => "Class" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $clasList , "required" => true, "default" => $classId ),
        array("name" => "unit_id", "lable" => "Unit" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $unitList , "required" => true ),
		array("name" => "excel_file", "lable" => "File", "type" => "file", "class" => "", "id" => "", "size" => "", "required" => true)
		);
	}
	
	public function insert()
	{

        if(!empty($_FILES['excel_file']['name'])){
            $excel_file = $this->do_upload('excel_file');

            if($excel_file['completed'] == false){
//                $this->session->set_flashdata('error',$excel_file->error);
//                return redirect(base_url().'attendance/daily');
                echo json_encode($excel_file->error);
            }else{
                $response['data']= array(
                    'name'=>$excel_file['upload_data']['file_name'],
                    'url'=>$excel_file['upload_data']['file_path'],
                    'file_path'=>$excel_file['upload_data']['full_path'],
                );
//                echo json_encode($response);
            }
        }else{
//            $this->session->set_flashdata('error',"Select a file to upload !");
//            return redirect(base_url().'attendance/daily');
            echo "Select a file!";
        }

        $classId = $this->input->post('class_id');
        $unitId = $this->input->post('unit_id');

        $this->load->model('Unit_model');

        $this->load->model('Subject_model');
        $subjectList = $this->Subject_model->getClassSubjectByClassId($classId);
        $insertBy = $this->session->userdata('user_id');

//        $this->pr($subjectList);
        $data = $this->readExcel($response);
        $resultData = array();
        $resultTotalData = array();

        $timestamp = NOW();
        $ip = $this->input->ip_address();

        foreach ($data as $key => $row)
        {
            $rollNo = $row['roll_no'];
            $res = $this->Result_model->getStdInfoByRollNo($rollNo);
            $classStdId = $res->id;
            $obtTotalMarks = null;
            $totalMarks = null;
            $grade = null;
//            $this->pr($row);
            foreach ($row as $i => $v)
            {
                foreach ($subjectList as $j)
                {
                    if($i == strtolower($j->subject))
                    {
                        $unitTestInfo = $this->Unit_model->getUnitTestInfoBySujectId(array('unit_id' => $unitId, 'subject_id' => $j->id));
                        $totalMarks = $totalMarks + $unitTestInfo->total_mark;
                        $obtTotalMarks = $obtTotalMarks + $v;
                        array_push($resultData,array('class_student_id' => $classStdId, 'insert_by' => $insertBy, 'unit_test_id' => $unitTestInfo->id, 'subject_id' => $j->id, 'obtained_mark' => $v, 'timestamp' => $timestamp, 'ip' => $ip ));
                        break;
                    }

                }
            }


            $percentage = $obtTotalMarks * 100 / $totalMarks;

            if($percentage > 90)
            {
                $grade = 'A';
            }
            elseif($percentage < 90 && $percentage > 80)
            {
                $grade = 'B';
            }
            elseif($percentage < 80 && $percentage > 60)
            {
                $grade = 'C';
            }
            else
            {
                $grade = 'D';
            }
            array_push($resultTotalData, array('class_student_id' => $classStdId, 'unit_id' => $unitId, 'percentage' => $percentage, 'grade' => $grade, 'total_obtained_mark' => $obtTotalMarks, 'timestamp' => $timestamp, 'ip' => $ip ));

        }

        $this->Result_model->insertResult($resultData);
        $this->Result_model->insertResultTotal($resultTotalData);
        redirect($this->reURL);
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Result_model->updatePage($pData,$id);
		redirect($this->reURL);
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Result_model->deletePage($id);
		redirect($this->reURL);
	}
	
	public function downloadResultExcel()
	{
	    $pdata = $this->input->post();
        $res =  $this->Result_model->getClassStudentsList($pdata);
        $data = array();

        $this->load->model('Subject_model');
        $subArry = $this->Subject_model->getClassSubjectByClassId($pdata['class_id']);
        $subList = array();
        $nullArry = array();

        foreach ($subArry as $sub)
        {
            array_push($subList, $sub->subject);
            array_push($nullArry, '');
        }

		$head = array('Roll No','Name','Father Name','Mother Name');
        $head = array_merge($head,$subList);

		foreach ($res as $row)
        {
            array_push($data,array_merge(array(
                !empty($row->roll_no) ? $row->roll_no : '',
                !empty($row->name) ? $row->name : '',
                !empty($row->father_name) ? $row->father_name : '',
                !empty($row->mother_name) ? $row->mother_name : ''
                ),$nullArry)
            );
        }

        $this->load->model('Branch_model');
		$className = $this->Branch_model->getBranchById($pdata['class_id']);
		$className = $className->name;
		$fname = $className.'-Result';

		$this->writeExcel($head,$data,$fname);
	}

    public function downloadReport()
    {
        array_push($this->bCrumb, array('title' => "Download Class Student Report","url" => '', 'icon' => ''));

        $data['title'] = "Download Class Student Report | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_add_result", "enctype" => true, "type" => "insert", "action" => $this->mbase."downloadResultExcel","title" => "Download Class Student Report", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "save_result","lable" => "Download Report", "id" => "", "class" => "btn btn-success"),
            "controles" => $this->formControls2() );
        $this->load->view('./comman/data/form',$data);
    }

    public function formControls2()
    {
        $this->load->model('Session_model');
        $sessList = $this->Session_model->getSessionList();

        $this->load->model('School_model');
        $schList = $this->School_model->getSchoolList();

        $this->load->model('Branch_model');
        $clasList = $this->Branch_model->getBranchList();

        $sessionId = $this->session->userdata('session_id');

        $schoolId = $this->session->userdata('school_id');

        if(!empty($this->session->userdata('class_id')))
        {
            $classId = $this->session->userdata('class_id');
        }
        else{
            $classId = null;
        }

        return array(
            array("name" => "session_id", "lable" => "Session" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $sessList , "required" => true, "default" => $sessionId ),
            array("name" => "school_id", "lable" => "School", "type" => "select", "class" => "span11", "id" => "", "size" => "","options" => $schList, "required" => true, "default" => $schoolId ),
            array("name" => "class_id", "lable" => "Class" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $clasList , "required" => true, "default" => $classId )
        );
    }

    public function downloadReportCard()
    {
        $this->load->view('./comman/comingsoon');
    }

    public function viewReportCard()
    {
        $id =  $this->uri->segment('3');
        $id = $this->dec($id);

        $data = $this->getReportCardData($id);

        array_push($this->bCrumb, array('title' => "Report Card","url" => '', 'icon' => ''));
        $data['breadcrumb'] = $this->bCrumb;

        $data['add'] = array('lable'=> 'Back','url'=> 'result/');
        $data['link'] = array('lable'=> 'Download Report Card (PDF)','url'=> 'result/downloadPDF/');

//        $this->pr($data);
//        die;
        $this->load->view('./comman/result/reportcard',$data);
    }

    public function downloadPDF()
    {
//load mPDF library
        $this->load->library('m_pdf');
//load mPDF library
        $id =  $this->uri->segment('3');
        $id = $this->dec($id);

        $data = $this->getReportCardData($id);

//now pass the data//
        $data['title']="MY PDF TITLE 1.";
        $data['description']="";
        $data['description']= 'Testing'; //$this->official_copies;
//now pass the data //

        $html=$this->load->view('./comman/result/card',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
//this the the PDF filename that user will get to download
        $filename = $data['classInfo']->name.'-'.$data['classInfo']->roll_no;
        $pdfFilePath = $filename."-ReportCard.pdf";

//actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();

        $stylesheet = file_get_contents(base_url() . 'assets/css/mpdfstyleA4.css');
        $pdf->WriteHTML($stylesheet, 1);

//generate the PDF!
        $pdf->WriteHTML($html,2);
//offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "D");
    }

    public function getReportCardData($id)
    {
        $reportData = $this->Result_model->getReportCardData($id);
        $data['reportResult'] = $reportData;

        $classStdId = $reportData->class_student_id;
        $unitId = $reportData->unit_id;

        $classInfo = $this->Result_model->getStudentClassInfo($classStdId);
        $data['classInfo'] = $classInfo;

        $subjectMarks = $this->Result_model->getSubjectMark($classStdId,$unitId);
        $data['subjectMarks'] = $subjectMarks;
        return $data;
    }
}