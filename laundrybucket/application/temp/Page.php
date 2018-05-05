<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Core_controller {
	
	protected $bCrumb = array(); 
	protected $mbase = 'page/';
	protected $reURL = null;
	protected $tHead = array();
	protected $delete = null;
	protected $edit = null;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Page_model');
		array_push($this->bCrumb, array('title' => 'Pages','url' => base_url().'index.php/page'));	
		$this->reURL = base_url().'index.php/'.$this->mbase;
		
		$this->perPage = 10;
		$this->tHead = array('Name','Title','IP');
		$this->delete =  'delete/';
		$this->edit = 'edit/';
    }
	
	public function index()
	{
		$this->bCrumb = $this->resetUrlbCrumb($this->bCrumb);
		
		$data['encode'] = $this;
		$data['title'] = "Pages | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['pageheading'] = "Pages";
		$data['thead'] = $this->tHead;
		
		$data['add'] = array('lable'=> 'Add New Page','url'=> 'page/add');
		$data['edit'] = $this->mbase.$this->edit;
		$data['del'] = $this->mbase.$this->delete;
		
		//total rows count
        $totalRec = count($this->Page_model->getTotalPages());
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        $data['tbldata'] = $this->Page_model->getPages(array('limit'=>$this->perPage));
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
        $totalRec = count($this->Page_model->getPages($conditions));
        
        //pagination configuration
        $config = $this->getConfig($totalRec);
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['tbldata'] = $this->Page_model->getPages($conditions);
		$data['pagination'] = true;
		$data['mbase'] = $this->mbase;
		
		$data['cstart'] = $page+1;
        
        //load the view
        $this->load->view('./comman/data/data-table', $data, false);
    }

//	protected function getConfig($totalRec)
//	{
//		$config['target']      = '#view-data';
//        $config['base_url']    = base_url().$this->mbase.'ajaxPaginationData';
//        $config['total_rows']  = $totalRec;
//        $config['per_page']    = $this->perPage;
//        $config['link_func']   = 'searchFilter';
//		return $config;
//	}
 	
	public function add()
	{
		array_push($this->bCrumb, array('title' => "Add Page","url" => '', 'icon' => ''));
		
		$data['title'] = "Add Page | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_add_page", "type" => "insert", "action" => $this->mbase."insert","title" => "Add Page", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "save_page","lable" => "Save", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function edit()
	{
		array_push($this->bCrumb, array('title' => "Update Page","url" => '', 'icon' => ''));
		
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$data['readonly'] = array('password');
		$data['values'] = $this->Page_model->getPageById($id);
		$data['title'] = "Update Page | ".$this->pageTitle;
		$data['breadcrumb'] = $this->bCrumb;
		$data['form'] = array("name" => "frm_update_page", "type" => "update", "action" => $this->mbase."update","title" => "Update Page", "id" => "", "class" => "form-horizontal",
		"submit" => array("name" => "update_page","lable" => "Update", "id" => "", "class" => "btn btn-success"), 
		"controles" => $this->formControls() );
		$this->load->view('./comman/data/form',$data);
	}
	
	public function formControls()
	{
		return array( 
		array("name" => "name", "lable" => "Name", "placeholder" => "Name", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
		array("name" => "title", "lable" => "Title", "placeholder" => "Title", "type" => "text", "class" => "span11", "id" => "", "size" => "", "required" => true),
		array("name" => "meta_keyword", "lable" => "Meta Keyword", "placeholder" => "Meta Keyword","type" => "textarea", "class" => "span11", "id" => "", "size" => ""),
		array("name" => "meta_description", "lable" => "Meta Description", "placeholder" => "Meta Description", "type" => "textarea", "class" => "span11", "id" => "", "size" => "")
		);
	}
	
	public function insert()
	{
		$pData = $this->input->post();
		
		$this->Page_model->insertPage($pData);
		redirect($this->reURL);		
	}
	
	public function update()
	{
		$pData = $this->input->post();
		$id = $pData['id'];
		unset($pData['id']);
		 
		$this->Page_model->updatePage($pData,$id);
		redirect($this->reURL);	
	}
	
	public function delete()
	{
		$id =  $this->uri->segment('3');
		$id = $this->dec($id);
		
		$this->Page_model->deletePage($id);
		redirect($this->reURL);
	}
	
}