<?php
class Result_model extends Core_Model {

	var $tblName = 'results';
	var $tblName2 = 'result_total';

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
	
	public function getResults($params = array())
	{
		try
		{
			$this->db->select('rt.*,cs.class_id,cs.id as class_student_id,cs.roll_no,s.name,s.father_name');
			$this->db->from('result_total as rt');
			$this->db->join('class_students as cs','cs.id=rt.class_student_id');
			$this->db->join('students as s','s.id=cs.student_id');

			//set start and limit
	        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
	            $this->db->limit($params['limit'],$params['start']);
	        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
	            $this->db->limit($params['limit']);
	        }
			
			//get records
	        $query = $this->db->get();
	        //return fetched data
	        return ($query->num_rows() > 0)?$query->result():FALSE;
				
		}
		catch( exception $e)
		{
			return $e;
		}
	}	

	public function getSubjectMarks($classId,$classStdId)
    {
        try
        {
            $this->db->select('cs.*,r.obtained_mark,s.name as subject');
            $this->db->from('class_subjects as cs');
            $this->db->where('cs.class_id',$classId);
            $this->db->join('results as r','r.subject_id=cs.subject_id');
            $this->db->where('r.class_student_id',$classStdId);
            $this->db->join('subjects as s','s.id=cs.subject_id');
            $res = $this->db->get()->result();
            return $res;
        }
        catch (exception $e)
        {
            return $e;
        }
    }

    public function getStudentResult($studentId)
    {
        $this->db->select('rt.*,u.name as unit');
        $this->db->from('result_total as rt');
        $this->db->join('class_students as cs','cs.id=rt.class_student_id');
        $this->db->join('units as u','u.id=rt.unit_id');
        $this->db->where('cs.student_id',$studentId);
        $res = $this->db->get()->result();
        return $res;
    }

//	public function getPageList()
//	{
//		try
//		{
//			$this->db->select('id,name');
//			$this->db->from('pages');
//			$res = $this->db->get()->result();
//			return $res;
//		}
//		catch(exception $e)
//		{
//			return $e;
//		}
//	}
//
//	public function getPageById($id)
//	{
//		try
//		{
//			$this->db->select('*');
//			$this->db->from('pages');
//			$this->db->where('id',$id);
//			$res = $this->db->get()->result();
//			return $res[0];
//		}
//		catch(exception $e)
//		{
//			return $e;
//		}
//	}
//
//	public function insertPage($data)
//	{
//		$this->add($data,$this->tblName);
//	}
//
//	public function updatePage($data,$id)
//	{
//		$this->update($data,$this->tblName,$id);
//	}
//
//	public function deletePage($id)
//	{
//		$this->delete($this->tblName,$id);
//	}
	
	public function getTotalResults()
	{
		return $this->getRows($this->tblName2);
	}

	public function getClassStudentsList($params = array())
    {
        try
        {
            $this->db->select('cs.*,s.admission_no,s.name,s.father_name,s.mother_name');
            $this->db->from('class_students as cs');
            $this->db->where('cs.session_id='.$params['session_id'].' AND '.'cs.school_id='.$params['school_id'].' AND cs.class_id='.$params['class_id']);
            $this->db->join('students as s','s.id=cs.student_id');
            $res = $this->db->get()->result();
            return $res;
        }
        catch(exception $e)
        {
            return $e;
        }
    }

    public function getStdInfoByRollNo($rollNo)
    {
        try
        {
            $this->db->select('*');
            $this->db->where('roll_no',$rollNo);
            $this->db->from('class_students');
            $res = $this->db->get()->result();
            return $res[0];
        }
        catch(exception $e)
        {
            return $e;
        }
    }

    public function insertResult($data)
    {
        $this->addBatch($data,$this->tblName);
    }

    public function insertResultTotal($data)
    {
        $this->addBatch($data,$this->tblName2);
    }

    public function getReportCardData($id)
    {
        try
        {
            $this->db->select('rt.*,u.name as unit');
            $this->db->where('rt.id',$id);
            $this->db->from('result_total as rt');
            $this->db->join('units as u','u.id=rt.unit_id');
            $res = $this->db->get()->result();
            return $res[0];
        }
        catch(exception $e)
        {
            return $e;
        }
    }

    public function getStudentClassInfo($id)
    {
        try
        {
            $this->db->select('s.*,se.name as session,cs.roll_no,sc.name as school,c.id,c.name as class');
            $this->db->where('cs.id',$id);
            $this->db->from('class_students as cs');
            $this->db->join('students as s','s.id=cs.student_id');
            $this->db->join('sessions as se','se.id=cs.session_id');
            $this->db->join('schools as sc','sc.id=cs.school_id');
            $this->db->join('classes as c','c.id=cs.class_id');
            $res = $this->db->get()->result();
            return $res[0];
        }
        catch(exception $e)
        {
            return $e;
        }
    }

    public function getSubjectMark($classStdId,$unitId)
    {
        try
        {
            $this->db->select('r.*,ut.*,u.name as unit,s.name as subject');
            $this->db->where('r.class_student_id',$classStdId);
            $this->db->where('ut.unit_id',$unitId);
            $this->db->from('results as r');
            $this->db->join('unit_tests as ut','ut.id=r.unit_test_id');
            $this->db->join('units as u','u.id=ut.unit_id');
            $this->db->join('subjects as s','s.id=r.subject_id');
            $res = $this->db->get()->result();
            return $res;
        }
        catch(exception $e)
        {
            return $e;
        }
    }
}