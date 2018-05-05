<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Core_Controller {
	
	var $data = array('title' => "Laundry Bucket" );
    protected $bCrumb = array();
    protected $mbase = 'auth/';
	
	public function __construct()
    {
            parent::__construct();
            $this->load->model('Auth_model');
    }
	
	public function index()
	{

		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
        );

		if(user_is_login())
		{
//            $this->load->view('./dashboard',$this->data);
            return redirect('dashboard');
        }else{
//            $this->load->view('./auth/login',$this->data);
            return redirect('auth/login');
        }
		
	}

	public function login()
    {
        if(user_is_login())
        {
            return redirect('dashboard');
        }else{
            $this->load->view('./auth/login');
        }

    }

	public function loginUser()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
		if(isset($_POST['btnSubmit']))
		{
			if($this->form_validation->run() == false)
			{
				$this->load->view('./auth/login',$this->data);
			}else{
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$params = array('user_name' => $username, 'password' => md5($password),'status' => true);
				$res = $this->Auth_model->loginUser($params);

				if(!empty($res))
				{
				    $rolePer = array();
//					$menuArr = $this->getUserMenus($res->role_id);
//					$setMenuArr = $this->Auth_model->getSettingMenus($res->role_id);

//                    if($res->user_role == 'teacher')
//                    {
//                        $teacherId = $res->teacher_id;
//                        $this->load->model('Incharge_model');
//                        $resTeacher = $this->Incharge_model->getTeacherClass($teacherId);
//                        $classId = $resTeacher->class_id;
//                    }
//                    elseif ($res->user_role == 'student')
//                    {
//                        $studentId = $res->student_id;
//                        $this->load->model('Student_model');
//                        $resStudent = $this->Student_model->getStudentClass($studentId);
//                        $classId = $resStudent->class_id;
//                    }

//					foreach ($menuArr as $item)
//                    {
//                        $rolePer[strtolower($item->name)] = array('read' => $item->pr_read, 'write' => $item->pr_write, 'update' => $item->pr_update, 'delete' => $item->pr_delete ) ;
//                    }

					$sessionData = array(
						'user_name' => $res->user_name,
						'role_id' => $res->role_id,
						'user_id'  => $res->id,
						'user_role' => $res->user_role,
						'menuIcons' => $this->menuIcons(),
                        'role_permission' => $rolePer,
                        'user_login' => true
					);
					$this->session->set_userdata($sessionData);

					redirect('dashboard',$this->data);
				}else{
					$errors = array('message'=>'Please Enter Correct User Name & Password!');
					$this->load->view('./auth/login',$errors);
				}
			}
		}else{
			$this->load->view('./auth/login',$this->data);
		}	
	}

	public function getLoginRole($params)
    {

    }

	public function logout()
	{
		$this->session->sess_destroy();
		$url = base_url().midurl().'auth';
		redirect($url);
	}
	
	public function getUserMenus($roleId)
	{
		$res = $this->Auth_model->getUserMenus($roleId);
		return $res;
	}

	public function profile()
    {

    }

    public function changepassword()
    {
        array_push($this->bCrumb, array('title' => "Change Password","url" => '', 'icon' => ''));

        $data['title'] = "Change Password | ".$this->pageTitle;
        $data['breadcrumb'] = $this->bCrumb;
        $data['form'] = array("name" => "frm_change_pass", "enctype" => true, "type" => "update", "action" => $this->mbase."update","title" => "Change Password", "id" => "", "class" => "form-horizontal",
            "submit" => array("name" => "update_pass","lable" => "Submit", "id" => "", "class" => "btn btn-success"),
            "controles" => $this->formControls() );
        $this->load->view('./comman/data/form',$data);
    }

    public function formControls()
    {
        return array(
            array("name" => "old_password", "lable" => "Old Password", "placeholder" => "Old Password", "type" => "password", "class" => "span11", "id" => "", "size" => ""),
            array("name" => "new_password", "lable" => "New Password", "placeholder" => "New Password", "type" => "password", "class" => "span11", "id" => "", "size" => ""),
            array("name" => "comfirm_password", "lable" => "Comfirm Password","placeholder" => "Comfirm Password", "type" => "password", "class" => "span11", "id" => "", "size" => "")
        );
    }
}
