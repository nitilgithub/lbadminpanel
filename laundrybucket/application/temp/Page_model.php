<?php
class Page_model extends Core_Model {

	var $tblName = 'pages';

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
	
	public function getPages($params = array())
	{
		try
		{
			$this->db->select('*');
			$this->db->from('pages');
			
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
	
	public function getPageList()
	{
		try
		{
			$this->db->select('id,name');
			$this->db->from('pages');
			$res = $this->db->get()->result();
			return $res; 
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	public function getPageById($id)
	{
		try
		{
			$this->db->select('*');
			$this->db->from('pages');
			$this->db->where('id',$id);
			$res = $this->db->get()->result();
			return $res[0];
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	public function insertPage($data)
	{
		$this->add($data,$this->tblName);
	}
	
	public function updatePage($data,$id)
	{
		$this->update($data,$this->tblName,$id);
	}
	
	public function deletePage($id)
	{
		$this->delete($this->tblName,$id);
	}
	
	public function getTotalPages()
	{
		return $this->getRows($this->tblName);
	}
		
}