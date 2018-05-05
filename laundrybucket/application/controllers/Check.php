<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends Core_controller {

	protected $isCheckDB;
	protected $addDevAccount;
	
	public function __construct()
    {
            parent::__construct();
			$this->load->model('Check_model');
			$this->isCheckDB = true;
			$this->addAdminAccount = true;
			$this->addDevAccount = true;
            $data = array();
			
    }

	public function index()
	{
        $data['apiurl'] = $this->apiurl;
        $url = "";
        if(user_is_login)
        {
            $url = base_url().midUrl().'auth';
//            $this->load->view('./auth/login',$data);
        }else{
            $url = base_url().midUrl().'dashboard';
//            $this->load->view('./auth/login',$data);
        }
        redirect($url);
	}
	
	public function dbIsExist()
	{
		if($this->isCheckDB)
		{
			$this->load->dbutil();
			$dbName = $this->db->database;
			if($this->dbutil->database_exists($dbName))
			{
				$tblArry = $this->getTableMeta();
				$this->createTables($tblArry);
			}	
			else
			{
				echo "Database Not Exists";
			}
		}
	}
	
	/************ Drop Table Methods ***********/
	public function dropdb()
	{
		$res = $this->Check_model->dropdb();
		echo $res;
	}
	
	public function dropTable()
	{
		$name = 'result_total';
		$this->Check_model->dropTable($name);
	}
	
	/************** Add Data Methods *************/
	public function addDevAccountData()
	{
		$ip = $this->input->ip_address();
		if($this->addDevAccount)
		{
			$devRoleData = array(
			'name' => 'developer',
			'title' => 'Developer',
			'pr_read' => true,
			'pr_write' => true,
			'pr_update' => true,
			'pr_delete' => true,
			'timestamp' => NOW(),
			'ip' => $ip
			);
			$roleId = $this->addRole($devRoleData);
			 
			$devAccountData = array(
			'user_name' => 'dev',
			'password' => md5('dev'),
			'status' => true,
			'role_id' => $roleId,
			'timestamp' => NOW(),
			'ip' => $ip
			);	
			$userId = $this->addUser($devAccountData);
			
			$devMduleData = array(
			'name' => 'developer',
			'timestamp' => NOW(),
			'ip' => $ip	
			);
			$moduleId = $this->addModule($devMduleData);
			
			$devRoleModuleData = array(
			'role_id' => $roleId,
			'module_id' => $moduleId,
			'timestamp' => NOW(),
			'ip' => $ip
			);
			$this->addRoleModule($devRoleModuleData);
			
			$pagesData = array(
				'User' => array('name' => 'User', 'title' => 'User', 'template' => 'panal'),
				'Role' => array('name' => 'Role', 'title' => 'Role', 'template' => 'panal'),
				'Module' => array('name' => 'Module', 'title' => 'Module', 'template' => 'panal'),
				'Role Module' => array('name' => 'Role Module', 'title' => 'Role Module', 'template' => 'panal'),
				'Menu' => array('name' => 'Menu', 'title' => 'Menu', 'template' => 'panal'),
				'Module Menu' => array('name' => 'Module Menu', 'title' => 'Module Menu', 'template' => 'panal'),
				'Pages' => array('name' => 'Page', 'title' => 'Page', 'template' => 'panal')
			);
			
			foreach($pagesData as $key => $value)
			{
				$value['timestamp'] = NOW();
				$value['ip'] = $ip;
				$pageId = $this->addPage($value);
				
				$url = strtolower($value['name']);
				$url = str_replace(' ', '', $url);
				$url = "/".$url;
				$menuData = array('name' => $value['name'], 'title' => $value['title'], 'url' => $url, 'page_id' => $pageId, 'timestamp' => NOW(), 'ip' => $ip );
				$menuId = $this->addMenu($menuData);
				
				$moduleMenuData = array('module_id' => $moduleId, 'menu_id' => $menuId, 'timestamp' => NOW(), 'ip' => $ip);
				$this->addModuleMenu($moduleMenuData);
							
			}
			
		}
	}
	
	public function addSchool(){
		
		$data = array(
			'name' => 'Dev',
			'timestamp' => NOW(),
			'status' => true,
			'ip' => $this->input->ip_address()
		); 
		return $this->Check_model->addSchool($data);
	}

	public function addRole($data)
	{
		return $this->Check_model->addRole($data);	
		
	}
	
	public function addUser($data){
		
		return $this->Check_model->addUser($data);
		
	}
	
	public function addModule($data)
	{
		return $this->Check_model->addModule($data);
	}
	
	public function addRoleModule($data)
	{
		return $this->Check_model->addRoleModule($data);
	}
	
	public function addPage($data)
	{
		return $this->Check_model->addPage($data);
	}
	
	public function addMenu($data)
	{
		return $this->Check_model->addMenu($data);
	}
	
	public function addModuleMenu($data)
	{
		return $this->Check_model->addModuleMenu($data);
	}
	
	/***************** Create Methods *****************/
	public function createTables($tblArry = array())
	{
		if(!empty($tblArry))
		{
			foreach($tblArry as $tblName => $tblCols)
			{

			    if($this->db->table_exists($tblName))
                {
                    continue;
                }

                $this->Check_model->createTable($tblName, $tblCols);
			}
		}
	}
	
	public function getTableMeta()
	{
		$schColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'name' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'description' => array('type' => 'VARCHAR', 'constraint' => '255' ),
			'address' => array( 'type' => 'VARCHAR','constraint' => '255' ),
			'city' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'state' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'pincode' => array('type' => 'INT', 'constraint' => 10),
			'principle' => array('type' => 'VARCHAR', 'constraint' => '50'),
			'logo' => array('type' => 'VARCHAR', 'constraint' => '255'),
			'url' => array('type' => 'VARCHAR', 'constraint' => '255'),
			'status' => array('type' => 'BOOLEAN'),
			'start_date' => array('type' => 'DATE'),
			'end_date' => array('type' => 'DATE'),
            'email' => array( 'type' => 'VARCHAR', 'constraint' => '55' ),
            'mobile_no' => array( 'type' => 'VARCHAR', 'constraint' => '20' ),
            'website' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		);
		
		$userColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'school_id' => array( 'type' => 'INT', 'constraint' => 10 ),
			'user_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'password' => array('type' => 'VARCHAR', 'constraint' => '50'),
			'status' => array('type' => 'BOOLEAN'),
			'role_id' => array('type' => 'INT', 'constraint' => 10),
			'student_id' => array('type' => 'INT', 'constraint' => 10),
			'teacher_id' => array('type' => 'INT', 'constraint' => 10),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		);
		 
		$roleColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'title' => array('type' => 'VARCHAR', 'constraint' => '50'),
			'pr_read' => array('type' => 'BOOLEAN'),
			'pr_write' => array('type' => 'BOOLEAN'),
			'pr_update' => array('type' => 'BOOLEAN'),
			'pr_delete' => array('type' => 'BOOLEAN'),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		); 
		
		$moduleColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		);
		 
		$roleModuleColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'role_id' => array( 'type' => 'INT', 'constraint' => 10 ),
			'module_id' => array( 'type' => 'INT', 'constraint' => 10 ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		);
		 
		$pageColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'title' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'meta_keyword' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
			'meta_description' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
			'template' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'query' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		); 
		 
		$menuColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'title' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
			'url' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
			'page_id' => array( 'type' => 'INT', 'constraint' => 10 ),
            'sidebar_menu' => array('type' => 'BOOLEAN'),
            'setting_menu' => array('type' => 'BOOLEAN'),
            'parent_menu_id' => array( 'type' => 'INT', 'constraint' => 10 ),
            'docket' => array( 'type' => 'INT', 'constraint' => 10 ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		); 
		 
		$moduleMenuColumns = array(
			'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
			'module_id' => array( 'type' => 'INT', 'constraint' => 10 ),
			'menu_id' => array( 'type' => 'INT', 'constraint' => 10 ),
			'timestamp' => array('type' => 'INT', 'constraint' => 10),
			'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
		);

		$studentColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'school_id' => array('type' => 'INT', 'constraint' => 10),
            'photo' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
            'admission_no' => array( 'type' => 'INT', 'constraint' => 10 ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'father_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'mother_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'dob' => array( 'type' => 'DATE' ),
            'gender' => array( 'type' => 'ENUM("m","f")'),
            'address' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
            'email' => array( 'type' => 'VARCHAR', 'constraint' => '55' ),
            'mobile_no' => array( 'type' => 'VARCHAR', 'constraint' => '20' ),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

		$classColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $unitColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $sessionColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'active' => array('type' => 'BOOLEAN'),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $teacherColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'school_id' => array('type' => 'INT', 'constraint' => 10),
            'photo' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'father_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'mother_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'husband_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'wife_name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'dob' => array( 'type' => 'DATE' ),
            'gender' => array( 'type' => 'ENUM("m","f")'),
            'address' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
            'email' => array( 'type' => 'VARCHAR', 'constraint' => '55' ),
            'mobile_no' => array( 'type' => 'VARCHAR', 'constraint' => '20' ),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $subjectColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'name' => array( 'type' => 'VARCHAR', 'constraint' => '50' ),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $classSubjectColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'class_id' => array('type' => 'INT', 'constraint' => 10),
            'subject_id' => array('type' => 'INT', 'constraint' => 10),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $classStudentColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'session_id' => array('type' => 'INT', 'constraint' => 10),
            'school_id' => array('type' => 'INT', 'constraint' => 10),
            'class_id' => array('type' => 'INT', 'constraint' => 10),
            'roll_no' => array('type' => 'INT', 'constraint' => 10),
            'student_id' => array('type' => 'INT', 'constraint' => 10),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $inchargeColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'class_id' => array('type' => 'INT', 'constraint' => 10),
            'teacher_id' => array('type' => 'INT', 'constraint' => 10),
            'school_id' => array('type' => 'INT', 'constraint' => 10),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $resultColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'class_student_id' => array('type' => 'INT', 'constraint' => 10),
            'insert_by' => array('type' => 'INT', 'constraint' => 10),
            'subject_id' => array('type' => 'INT', 'constraint' => 10),
            'unit_test_id' => array('type' => 'INT', 'constraint' => 10),
            'obtained_mark' => array('type' => 'INT', 'constraint' => 10),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $unitTestColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'subject_id' => array('type' => 'INT', 'constraint' => 10),
            'unit_id' => array('type' => 'INT', 'constraint' => 10),
            'total_mark' => array('type' => 'INT', 'constraint' => 10),
            'pass_mark' => array('type' => 'INT', 'constraint' => 10),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

        $resultTotalColumns = array(
            'id' => array( 'type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE, 'null' => false ),
            'class_student_id' => array('type' => 'INT', 'constraint' => 10),
            'unit_id' => array('type' => 'INT', 'constraint' => 10),
            'percentage' => array('type' => 'DECIMAL', 'constraint' => '15,2'),
            'grade' => array('type' => 'VARCHAR', 'constraint' => '20'),
            'total_obtained_mark' => array('type' => 'DECIMAL', 'constraint' => '15,2'),
            'timestamp' => array('type' => 'INT', 'constraint' => 10),
            'ip' => array('type' => 'VARCHAR', 'constraint' => '20')
        );

		$tablesArry = array(
					'schools' => $schColumns,
					'users' => $userColumns,
					'roles' => $roleColumns,
					'modules' => $moduleColumns,
					'role_modules' => $roleModuleColumns,
					'pages' => $pageColumns,
					'menus' => $menuColumns,
					'module_menus' => $moduleMenuColumns,
                    'students' => $studentColumns,
                    'classes' => $classColumns,
                    'units' => $unitColumns,
                    'sessions' => $sessionColumns,
                    'teachers' => $teacherColumns,
                    'subjects' => $subjectColumns,
                    'class_subjects' => $classSubjectColumns,
                    'class_students' => $classStudentColumns,
                    'incharges' => $inchargeColumns,
                    'results' => $resultColumns,
                    'unit_tests' => $unitTestColumns,
                    'result_total' => $resultTotalColumns
					);
		return $tablesArry;			
				
	}

	/**************** Truncates Tables ***************/
	public function truncateTable()
	{
	    //array( 'schools', 'users', 'roles', 'modules', 'role_modules', 'pages', 'menus', 'module_menus','students', 'class_students');
		$tablesArry = array( 'results', 'result_total','students','class_students','incharges', 'teachers');
		
		foreach($tablesArry as $tbl)
		{
			$this->Check_model->truncateTable($tbl);
		}
	}

}
