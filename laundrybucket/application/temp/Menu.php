<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Core_controller {
	
	protected $bCrumb = array();
	protected $mbase = 'menu/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	protected $isBool = null;

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
		array_push($this->bCrumb, array('title' => 'Menus','url' => base_url().'index.php/menu'));	
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Name','Sidebar Menu','Setting Menu','Parent Menu Id','Docket'); //'Title','URL','Page','IP'
		$this->delete =  'delete/';
		$this->edit = 'edit/';

		$this->isBool = array('sidebar_menu','setting_menu');
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Menus | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Menus";
		$data['thead'] = $this->tHead;
        $data['isbool'] = $this->isBool;
		// $data['tbldata'] = $this->Menu_model->getMenus();
		$data['add'] = array('lable'=> 'Add New Menu','url'=> 'menu/add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		$totalRec = count($this->Menu_model->getMenus());
		
		$config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Menu_model->getMenus(array('limit'=>$this->perPage));
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = 1;
		
		$this->load->view('./comman/data/view',$data);
	}
	
	public function ajaxPaginationData()
	{
        $conditions = array();
		
        $data['encode'] = $this;
		$data['thead'] = $this->tHead;
        $data['isbool'] = $this->isBool;
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;   
		
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        //total rows count
        $totalRec = count($this->Menu_model->getMenus($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Menu_model->getMenus($conditions);
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
        
		$data['cstart'] = $page+1;
		
        //load the view
        $this->load->view('./comman/data/data-table', $data, false);
    }

	protected function getConfig($totalRec)
	{
		$config['target']      = '#view-data';
        $config['base_url']    = base_url().$this->mbase.'ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
		return $config; 
	}
 	
	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Menu","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Menu | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_menu", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Menu", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_menu","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Menu","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array();
		$data['values'] = $this->Menu_model->getMenuById($id);
		$data['title'] = "Update Menu | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_menu", "type" => "update", "action" => $this->mbase."update","title" => "Update Menu", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_menu","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		$this->load->model('Page_model');
		$pagList = $this->Page_model->getPageList();

        $menuList = $this->Menu_model->getMenuList();
		
		return array( 
		array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
		array("name" => "title", "lable" => "Title", "placeholder" => "Title", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
		array("name" => "url", "lable" => "URL", "placeholder" => "URL ","type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true ),
		array("name" => "page_id", "lable" => "Page" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $pagList, "required" => true ),
        array("name" => "sidebar_menu", "lable" => "Sidebar Menu", "type" => "toggle", "class" => "", "id" => "", "size" => ""),
        array("name" => "setting_menu", "lable" => "Setting Menu", "type" => "toggle", "class" => "", "id" => "", "size" => ""),
        array("name" => "parent_menu_id", "lable" => "Parent Menu" , "type" => "select", "class" => "span11", "id" => "", "size" => "", "options" => $menuList),
        array("name" => "docket", "lable" => "Index", "placeholder" => "Index", "type" => "text", "class" => "span11", "id" => "")
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();

        $pData = $this->checkToggle($pData,'sidebar_menu');
        $pData = $this->checkToggle($pData,'setting_menu');

		$this->Menu_model->insertMenu($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();

        $pData = $this->checkToggle($pData,'sidebar_menu');
        $pData = $this->checkToggle($pData,'setting_menu');

		$id = $pData['id'];
		unset($pData['id']);

		$this->Menu_model->updateMenu($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Menu_model->deleteMenu($id);
		redirect($this->reURL);
	}
	
}