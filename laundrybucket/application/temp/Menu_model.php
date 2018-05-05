<?php
class Menu_model extends Core_Model {

		var $tblName1 = 'menus';
		var $tblName2 = 'module_menus';

        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }
		
		/************ Menus *************/
		public function getMenus($params = array())
		{
			$this->db->select('m.*,p.name page');
			$this->db->from('menus as m');
			$this->db->order_by('m.docket');
			$this->db->join('pages as p','p.id=m.page_id');
			
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
		
		public function getMenuById($id)
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
		
		public function getMenuList()
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

		public function insertMenu($data)
		{
			$this->add($data,$this->tblName1);
		}
		
		public function updateMenu($data,$id)
		{
			$this->update($data,$this->tblName1,$id);
		}
		
		public function deleteMenu($id)
		{
			$this->delete($this->tblName2,$id);
		}	
		
		/************ Module Menus *************/
		public function getModuleMenus($params = array())
		{
			$this->db->select('mm.*,m.name menu,mo.name as module');
			$this->db->from('module_menus as mm');
			$this->db->join('menus as m','m.id = mm.menu_id');
			$this->db->join('modules as mo','mo.id=mm.module_id');
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

		public function getModuleMenuById($id)
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
		
		public function insertModuleMenu($data)
		{
			$this->add($data,$this->tblName2);
		}
		
		public function updateModuleMenu($data,$id)
		{
			$this->update($data,$this->tblName2,$id);
		}
		
		public function deleteModuleMenu($id)
		{
			$this->delete($this->tblName2,$id);
		}
}