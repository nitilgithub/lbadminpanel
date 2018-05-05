<?php
class Subject_model extends Core_Model {
        	
		var $tblName1 = 'subjects';
        var $tblName2 = 'class_subjects';

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		public function getSubjects()
		{
			try
			{
				$res = $this->db->get($this->tblName1)->result();
				return $res;	
			}
			catch( exception $e)
			{
				return $e;
			}
		}
		
		public function getSubjectById($id)
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
		
		public function getSubjectList()
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
		
		public function insertSubject($data)
		{
			$this->add($data,$this->tblName1);
		}
		
		public function updateSubject($data,$id)
		{
			$this->update($data,$this->tblName1,$id);
		}
		
		public function deleteSubject($id)
		{
			$this->delete($this->tblName1,$id);
		}

    /************ Module Menus *************/
    public function getClassSubjects($params = array())
    {
        $this->db->select('cs.*,c.name class,s.name as subject');
        $this->db->from('class_subjects as cs');
        $this->db->join('classes as c','c.id = cs.class_id');
        $this->db->join('subjects as s','s.id=cs.subject_id');
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

    public function getClassSubjectById($id)
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

    public function insertClassSubject($data)
    {
        $this->add($data,$this->tblName2);
    }

    public function updateClassSubject($data,$id)
    {
        $this->update($data,$this->tblName2,$id);
    }

    public function deleteClassSubject($id)
    {
        $this->delete($this->tblName2,$id);
    }

    public function getClassSubjectByClassId($classId = null)
    {
        try
        {
            $this->db->select('s.name as subject,s.id');
            $this->db->from('class_subjects as cs');
            $this->db->where('class_id',$classId);
            $this->db->join('subjects as s','s.id=cs.subject_id');
            $res = $this->db->get()->result();
            return $res;
        }
        catch(exception $e)
        {
            return $e;
        }
    }
}