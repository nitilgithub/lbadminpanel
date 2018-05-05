<?php
class Incharge_model extends Core_Model {
        	
		var $tblName = 'incharges';

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		public function getIncharges($params = array())
		{
			try
			{
                $this->db->select('i.*,c.name class,t.name as teacher,sc.name as school');
                $this->db->from('incharges as i');
                $this->db->join('classes as c','c.id = i.class_id');
                $this->db->join('teachers as t','t.id=i.teacher_id');
                $this->db->join('schools as sc','sc.id=i.school_id');

                //set start and limit
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
                }

                $query = $this->db->get();
                //return fetched data
                return ($query->num_rows() > 0)?$query->result():FALSE;
			}
			catch( exception $e)
			{
				return $e;
			}
		}
		
		public function getInchargeById($id)
		{
			try
			{
				$this->db->select('*');
				$this->db->from($this->tblName);
				$this->db->where('id',$id);
				$res = $this->db->get()->result();
				return $res[0];
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function getInchargeList()
		{
			try
			{
				$this->db->select('id,name');
				$this->db->from($this->tblName);
				$res = $this->db->get()->result();
				return $res; 
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function insertIncharge($data)
		{
			$this->add($data,$this->tblName);
		}
		
		public function updateIncharge($data,$id)
		{
			$this->update($data,$this->tblName,$id);
		}
		
		public function deleteIncharge($id)
		{
			$this->delete($this->tblName,$id);
		}

        public function getTeacherClass($teacherId)
        {
            try{
                $this->db->select('*');
                $this->db->from($this->tblName);
                $this->db->limit(1);
                $this->db->where('teacher_id',$teacherId);
                $res = $this->db->get()->result();
                return $res[0];
            }
            catch(Exception $e)
            {
                return $e;
            }
        }
}