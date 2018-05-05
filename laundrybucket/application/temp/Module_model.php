<?php
class Module_model extends Core_Model {
        	
		var $tblName = 'modules';	

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		public function getModules()
		{
			try
			{
				$res = $this->db->get('modules')->result();
				return $res;	
			}
			catch( exception $e)
			{
				return $e;
			}
		}
		
		public function getModuleById($id)
		{
			try
			{
				$this->db->select('*');
				$this->db->from('modules');
				$this->db->where('id',$id);
				$res = $this->db->get()->result();
				return $res[0];
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function getModuleList()
		{
			try
			{
				$this->db->select('id,name');
				$this->db->from('modules');
				$res = $this->db->get()->result();
				return $res; 
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function insertModule($data)
		{
			$this->add($data,$this->tblName);
		}
		
		public function updateModule($data,$id)
		{
			$this->update($data,$this->tblName,$id);
		}
		
		public function deleteModule($id)
		{
			$this->delete($this->tblName,$id);
		}
}