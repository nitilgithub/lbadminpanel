<?php
class Api_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function addapikey()
    {
        $table = 'api_logs';
        try{

            $key = $this->randomPassword();

            $data['api_url'] = current_url();
            $data['api_key'] = $key;
            $data['status'] = 0;
            $data['addon'] = date('Y-m-d H:i:s');

            $res = $this->db->insert($table, $data);
//            $res = $this->db->get($table)->last_row();
//            $res = intval($res->id);  // $this->db->insert_id();
            if($res)
            {
                return $key;
            }else{
                return false;
            }
        }catch (Exception $e)
        {
            return $e;
        }
    }

}