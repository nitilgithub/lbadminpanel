<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";
require_once APPPATH."/third_party/PHPExcel/IOFactory.php";

class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */