<?php
class Place_model extends Core_Model {

	var $tblName = 'tbl_places';
	
    public function __construct()
    {
            parent::__construct();
            // Your own constructor code
    }
		
	public function getPlaces()
	{
		try
		{
			$this->db->select('p.*,c.country_name as country,d.destination_name as destination,p.place_name as place');
            $this->db->from('tbl_places as p');
            $this->db->join('tbl_country as c','c.id=p.country_id');
            $this->db->join('tbl_destinations as d','d.id=p.destination_id');
            $res = $this->db->get()->result();
			return $res;	
		}
		catch( exception $e)
		{
			return $e;
		}
	}
	
	public function getPlaceById($id)
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

    public function getPlaceList()
    {
        try
        {
            $this->db->select('id,place_name as name');
            $this->db->from('tbl_places');
            $res = $this->db->get()->result();
            return $res;
        }
        catch(exception $e)
        {
            return $e;
        }
    }

	public function getCountryList()
	{
		try
		{
			$this->db->select('id,country_name as name');
			$this->db->from('tbl_country');
			$res = $this->db->get()->result();
			return $res; 
		}
		catch(exception $e)
		{
			return $e;
		}
	}

    public function getDesList()
    {
        try
        {
            $this->db->select('id,destination_name as name');
            $this->db->from('tbl_destinations');
            $res = $this->db->get()->result();
            return $res;
        }
        catch(exception $e)
        {
            return $e;
        }
    }
	
	public function insertPlace($data)
	{
		$this->add($data,$this->tblName);
	}
	
	public function updatePlace($data,$id)
	{
		$this->update($data,$this->tblName,$id);
	}
	
	public function deleteSchool($id)
	{
		$this->delete($this->tblName,$id);
	}


}