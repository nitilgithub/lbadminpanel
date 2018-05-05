<?php
class Package_model extends Core_Model {

	var $tblName1 = 'tbl_package';
    var $tblName2 = 'class_students';

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
	
	public function getPackages($params = array())
	{
		try
		{
			$this->db->select('p.*,pc.category_name as category');
			$this->db->from('tbl_package as p');
            $this->db->join('tbl_package_category as pc','pc.id=p.category_id');
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
	
	public function getStudentList()
	{
		try
		{
			$this->db->select('id,name');
			$this->db->from($this->tblName1);
			$res = $this->db->get()->result();
			return $res; 
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	public function getPackageById($id)
	{
		try
		{
			$this->db->select('*');
			$this->db->from($this->tblName1);
			$this->db->where('id',$id);
			$res = $this->db->get()->result();
			return $res[0];
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	public function insertPackage($data)
	{
		return $this->add($data,$this->tblName1);
	}
	
	public function updatePackage($data,$id)
	{
		$this->update($data,$this->tblName1,$id);
	}
	
	public function deletePackage($id)
	{
		$this->delete($this->tblName1,$id);
	}
	
	public function getTotalPackages()
	{
		return $this->getRows($this->tblName1);
	}

    /************ Class Students *************/
    public function getClassStudents($params = array())
    {
        $this->db->select('cs.*,c.name class,s.name as student,se.name as session,sc.name as school');
        $this->db->from('class_students as cs');
        $this->db->join('classes as c','c.id = cs.class_id');
        $this->db->join('students as s','s.id=cs.student_id');
        $this->db->join('sessions as se','se.id=cs.session_id');
        $this->db->join('schools as sc','sc.id=cs.school_id');
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

    public function getClassStudentById($id)
    {
        try
        {
            $this->db->select('*');
            $this->db->from($this->tblName2);
            $this->db->where('id',$id);
            $res = $this->db->get()->result();
            return $res[0];
        }
        catch(exception $e)
        {
            return $e;
        }
    }

    public function insertClassStudent($data)
    {
       return $this->add($data,$this->tblName2);
    }

    public function updateClassStudent($data,$id)
    {
        $this->update($data,$this->tblName2,$id);
    }

    public function deleteClassStudent($id)
    {
        $this->delete($this->tblName2,$id);
    }

    public function getStudentClass($studentId)
    {
        try{
            $this->db->select('*');
            $this->db->from($this->tblName2);
            $this->db->limit(1);
            $this->db->where('student_id',$studentId);
            $res = $this->db->get()->result();
            if(!empty($res))
            {
                return $res[0];
            }
        }
        catch(Exception $e)
        {
            return $e;
        }
    }

    public function getIncludes()
    {
        try
        {
            $res = $this->db->get('tbl_includes')->result();
            return $res;
        }
        catch( exception $e)
        {
            return $e;
        }
    }

    public function getExcludes()
    {
        try
        {
            $res = $this->db->get('tbl_excludes')->result();
            return $res;
        }
        catch( exception $e)
        {
            return $e;
        }
    }

    public function getPaymentOptions()
    {
        try
        {
            $res = $this->db->get('tbl_payment_option')->result();
            return $res;
        }
        catch( exception $e)
        {
            return $e;
        }
    }
}