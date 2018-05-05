<?php
class Role_model extends Core_Model {

		var $tblName1 = 'roles';
		var $tblName2 = 'role_modules';
		
        public function __construct()
        {
                parent::__construct();
                // Your own constructor code
        }

		/*********** Role Methods ************/		
		public function getRoles()
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
		
		public function getRoleList()
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
		
		public function getRoleById($id)
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

        public function getRoleIdByName($name)
        {
            try
            {
                $this->db->select('*');
                $this->db->from($this->tblName1);
                $this->db->where('name',$name);
                $res = $this->db->get()->result();
                return $res[0];
            }
            catch(exception $e)
            {
                return $e;
            }
        }
		
		public function insertRole($data)
		{
			$this->add($data,$this->tblName1);
		}
		
		public function updateRole($data,$id)
		{
			$this->update($data,$this->tblName1,$id);
		}
		
		public function deleteRole($id)
		{
			$this->delete($this->tblName1,$id);
		}
		
		/*********** Role Modules Methods *************/
		public function getRoleModules()
		{
			try
			{
				$this->db->select('rm.*,r.title as role,m.name as module');
				$this->db->from('role_modules as rm');
				$this->db->join('roles as r','r.id=rm.role_id');
				$this->db->join('modules as m','m.id=rm.module_id');
				$res = $this->db->get()->result();
				return $res;	
			}
			catch( exception $e)
			{
				return $e;
			}
		}
		
		public function getRoleModuleById($id)
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
		
		public function insertRoleModule($data)
		{
			$this->add($data,$this->tblName2);
		}
		
		public function updateRoleModule($data,$id)
		{
			$this->update($data,$this->tblName2,$id);
		}
		
		public function deleteRoleModule($id)
		{
			$this->delete($this->tblName2,$id);
		}

}