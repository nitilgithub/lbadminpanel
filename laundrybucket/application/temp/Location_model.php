<?php
class Location_model extends Core_Model {
        	
		var $tblName = 'tbl_locations';

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		public function getLocations()
		{
			try
			{
			    $this->db->select('l.*,c.country_name as country,l.location_name as location');
			    $this->db->from('tbl_locations as l');
                $this->db->join('tbl_country as c','c.id=l.country_id');
				$res = $this->db->get($this->tblName)->result();
				return $res;	
			}
			catch( exception $e)
			{
				return $e;
			}
		}
		
		public function getLocationById($id)
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
		
		public function getSessionList()
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

		public function getActiveSession()
        {
            try
            {
                $this->db->select('*');
                $this->db->from($this->tblName);
                $this->db->where('active=true');
                $this->db->limit(1);
                $res = $this->db->get()->result();
                return $res[0];
            }
            catch(exception $e)
            {
                return $e;
            }
        }

		public function insertSession($data)
		{
			$this->add($data,$this->tblName);
		}
		
		public function updateLocation($data,$id)
		{
			$this->update($data,$this->tblName,$id);
		}
		
		public function deleteSession($id)
		{
			$this->delete($this->tblName,$id);
		}
}