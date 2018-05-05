<?php 
class Core_Model extends CI_Model {
	
	protected $timestamp ;
	protected $ip;
	protected $table = "tbl_api_logs";

	public function __construct()
    {
            parent::__construct();
            $this->load->dbforge();
			
			$this->timestamp = NOW();
			$this->ip = $this->input->ip_address(); 
    }

    /********************** Ganrate Random Key Start Here*********************/
    public function randomPassword($length = 12,$count = 1, $characters = "lower_case,upper_case,numbers") {

        $symbols = array();
        $passwords = array();
        $used_symbols = '';
        $pass = '';

        $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
        $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbols["numbers"] = '1234567890';
        $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

        $characters = explode(",",$characters); // get characters types to be used for the passsword
        foreach ($characters as $key=>$value) {
            $used_symbols .= $symbols[$value]; // build a string with all characters
        }
        $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

        for ($p = 0; $p < $count; $p++) {
            $pass = '';
            for ($i = 0; $i < $length; $i++) {
                $n = rand(0, $symbols_length); // get a random character from the string with all characters
                $pass .= $used_symbols[$n]; // add the character to the password string
            }
            $passwords[] = $pass;
        }

        return $passwords[0]; // return the generated password
    }
    /********************** Ganrate Random Key End Here*********************/

    public function getapikey()
    {
        try{

        }catch (Exception $e)
        {
            return $e;
        }
    }

    public function addapikey()
    {
        try{
            $data['timestamp'] = $this->timestamp;
            $data['ip'] = $this->ip;
            $data['api_url'] = "";
            $data['api_key'] = md5(now());
            $data['status'] = 0;

            $this->db->insert($this->table, $data);
            $res = $this->db->get($this->table)->last_row();
//            $res = intval($res->id);  // $this->db->insert_id();
            return $res;
        }catch (Exception $e)
        {
            return $e;
        }
    }

	public function add($data, $tblName)
	{
		try
		{
			$data['timestamp'] = $this->timestamp;
			$data['ip'] = $this->ip;
			
			$this->db->insert($tblName, $data);
			$res = $this->db->get($tblName)->last_row();
			$res = intval($res->id);  // $this->db->insert_id();
			return $res;
		}
		catch(exception $e)
		{
			return $e;
		}
	}

    public function addBatch($data, $tblName)
    {
        try
        {
            $this->db->insert_batch($tblName, $data);
            return true;
        }
        catch(exception $e)
        {
            return $e;
        }
    }
	
	public function update($data, $tblName,$id)
	{
		try
		{	$this->db->where('id', $id);
			$res = $this->db->update($tblName, $data);
			return $res;
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	public function delete($tblName,$id)
	{
		try
		{
			$this->db->where('id', $id);
			$res = $this->db->delete($tblName);
			return $res;
		}
		catch(exception $e)
		{
			return $e;
		}
	}
	
	/***************** Get Recods Count *****************/
	function getRows($tblName,$params = array()){
        $this->db->select('*');
        $this->db->from($tblName);
        //filter data by searched keywords
        // if(!empty($params['search']['keywords'])){
            // $this->db->like('first_name',$params['search']['keywords']);
        // }
        //sort data by ascending or desceding order
        // if(!empty($params['search']['sortBy'])){
            // $this->db->order_by('first_name',$params['search']['sortBy']);
        // }else{
            // $this->db->order_by('id','desc');
        // }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }

    public function getLastAdmissionNo($schoolId = null)
    {
        $schoolId = intval($schoolId);
        $this->db->select('admission_no');
        $this->db->from('students');
        $this->db->limit(1);
        $this->db->order_by('id','DESC');
        $this->db->where('school_id',$schoolId);
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)? $query->result():0;
    }

    public function getLastRollNo($params = array())
    {
        $this->db->select('roll_no');
        $this->db->from('class_students');
        $this->db->limit(1);
        $this->db->order_by('id','DESC');
        $this->db->where($params);
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)? $query->result():0;
    }
}