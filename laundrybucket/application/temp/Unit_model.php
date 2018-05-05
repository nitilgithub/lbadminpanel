<?php
class Unit_model extends Core_Model {
        	
		var $tblName1 = 'units';
        var $tblName2 = 'unit_tests';

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		public function getUnits()
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
		
		public function getUnitById($id)
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
		
		public function getUnitList()
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
		
		public function insertUnit($data)
		{
			$this->add($data,$this->tblName1);
		}
		
		public function updateUnit($data,$id)
		{
			$this->update($data,$this->tblName1,$id);
		}
		
		public function deleteUnit($id)
		{
			$this->delete($this->tblName1,$id);
		}

        /************ Module Menus *************/
        public function getUnitTests($params = array())
        {
            $this->db->select('ut.*,s.name subject,u.name as unit');
            $this->db->from('unit_tests as ut');
            $this->db->join('subjects as s','s.id = ut.subject_id');
            $this->db->join('units as u','u.id=ut.unit_id');
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

        public function getUnitTestById($id)
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

        public function insertUnitTest($data)
        {
            $this->add($data,$this->tblName2);
        }

        public function updateUnitTest($data,$id)
        {
            $this->update($data,$this->tblName2,$id);
        }

        public function deleteUnitTest($id)
        {
            $this->delete($this->tblName2,$id);
        }

        public function getUnitTestInfoBySujectId($params = array())
        {
            try{
                $this->db->select('*');
                $this->db->from($this->tblName2);
                $this->db->where($params);
                $res = $this->db->get()->result();
                return $res[0];
            }
            catch(exception $e)
            {
                return $e;
            }
        }
}