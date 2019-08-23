<?php
 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
/**
 * Description of BaseModel
 *
 * @author homsriang
 */
class Base_model extends CI_Model {
 
    private $permission_id;
    private $performance = 'v_item_chart';
 
    public function __construct() {
        parent::__construct();
//$this->update_user_status();
    }
 
    public function save($tblname, $data) {
        $this->db->insert($tblname, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
 
    public function update($tblname, $update, $condition) {
        //$condition = array('a' => 1, 'b' => 2)
        while ($d = current($condition)) {
            $this->db->where($condition, $d);
            next($condition);
        }
        $this->db->update($tblname, $update);
    }
 
    public function delete($tblname, $condition) {
        //$condition = array('a' => 1, 'b' => 2)
        while ($d = current($condition)) {
            $this->db->where($condition, $d);
            next($condition);
        }
        $this->db->delete($tblname);
    }
    public function select($query, $condition) {
        //$condition = array(1,2,3,array(4,5))
        //$query = select a,b,c where d=? and e=? and f=? and g in ?
        //=> select a,b,c where d=1 and e=1 and f=3 and g in (4,5)
        $qu = $this->db->query($query, $condition);
        return $qu->result();
    }
    public function select_all($query) {
        $select = 'SELECT * FROM ' . $query;
        $qu = $this->db->query($select);
        return $qu->result();
    }
    public function select_value($query, $condition, $column) {
        //$condition = array(1,2,3,array(4,5))
        //$query = select a,b,c where d=? and e=? and f=? and g in ?
        //=> select a,b,c where d=1 and e=1 and f=3 and g in (4,5)
        $qu = $this->db->query($query, $condition);
        $return_val = '';
        foreach ($qu->result() as $q) {
            $return_val = $q->$column;
        }
        return $return_val;
    }
 
    public function execute_query($query, $condition) {
        //$condition = array(1,2,3)
        //$query = select a,b,c where d=? and e=? and f=?
        //=> select a,b,c where d=1 and e=1 and f=3
        $this->db->query($query, $condition);
    }
 
    public function current_date($date_format) {
        $date = new DateTime("now", new DateTimeZone('Asia/Phnom_Penh'));
        return $date->format($date_format);
    }
 
    public function checkValidation($field, $tblname, $txt) {
        $this->db->select($field);
        $this->db->from($tblname);
        $this->db->where($field, $txt);
 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            print("Data is existen!");
            return;
        }
    }
 
    public function get_data_by($sql) {
        $result = array();
        //$this->db->select('qty');
//          $this->db->from($tblname);
//          $this->db->where($field,$value);
        $query = $this->db->query($sql);
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    public function get_data($tblname) {
         
        $result = array();
        $this->db->select('*');
        $this->db->from($tblname);
 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        //todo clear from memory
        $query->free_result();
        return $result;
    }
    function backup() {
        $this->load->dbutil();
        $this->load->helper('download');
        $tanggal = date('Ymd-His');
        $namaFile = $tanggal . '.sql.zip';
        $this->load->dbutil();
        $backup = & $this->dbutil->backup();
        force_download($namaFile, $backup);
    }
 
    public function record_count($tbl_name) {
        return $this->db->count_all($tbl_name);
    }
 
    public function fetch_countries($limit, $start, $tbl_name) {
        $this->db->limit($limit, $start);
        $query = $this->db->get($tbl_name);
 
//                if ($query->num_rows() > 0) {
//                    foreach ($query->result() as $row) {
//                        $data[] = $row;
//                    }
//                    return $data;
//                }
//                return false; 
        return $query->result();
    }
    public function run_query($query) {
        $q = $this->db->query($query);
        return $q;
    }
    public function count_page($query_str) {
        return $this->db->query($query_str);
    }
 
    public function pagingationhighpoint($limit, $start, $str_query) {
        $this->db->limit($limit, $start);
        $query = $this->db->query($str_query);
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
 
        return false;
    }
 
//            function restoredb(){
//                
//                $isi_file = file_get_contents('downloads/db_backup.zip.sql');
//                $string_query = rtrim($isi_file, "\n;" );
//                $array_query = explode(";", $string_query);
//                
//                foreach($array_query as $query)
//                {
//                    $this->db->query($query);
//                }
//                
//            }   
 
    public function get_value($tblname, $field, $wherefield, $wherecondition) {
        $qu = "select " . $field . " from " . $tblname . " where " . $wherefield . "='" . $wherecondition . "' limit 1";
        $query = $this->db->query($qu);
        $return_val = '';
        foreach ($query->result() as $qu) {
            $return_val = $qu->$field;
        }
        return $return_val;
    }
     
    public function get_value_two_cond($tblname, $field, $wherefield1, $wherecondition1, $wherefield2, $wherecondition2) {
        $qu = "select " . $field . " from " . $tblname . " where " . $wherefield1 . "='" . $wherecondition1 . "' AND  " . $wherefield2 . "='" . $wherecondition2 . "' limit 1";
        $query = $this->db->query($qu);
        $return_val = '';
        foreach ($query->result() as $qu) {
            $return_val = $qu->$field;
        }
        return $return_val;
    }
    public function get_count_value($tblname, $field, $wherefield, $wherecondition) {
        $qu = "select count(" . $field . ") as " . $field . " from " . $tblname . " where " . $wherefield . "='" . $wherecondition . "'";
        $query = $this->db->query($qu);
        $return_val = '';
        foreach ($query->result() as $qu) {
            $return_val = $qu->$field;
        }
        return $return_val;
    }
    public function get_count_alert($tblname, $field, $wherefield, $wherecondition) {
        $qu = "select count(" . $field . ") as " . $field . " from " . $tblname . " where " . $wherefield . "='" . $wherecondition . "'";
        $query = $this->db->query($qu);
        $return_val = '';
        foreach ($query->result() as $qu) {
            $return_val = $qu->$field;
        }
        return $return_val;
    }
 
    public function get_value_byQuery($qu, $field) {
        $query = $this->db->query($qu);
        $return_val = '';
        foreach ($query->result() as $qu) {
            $return_val = $qu->$field;
        }
        return $return_val;
    }
 
    function get_chart_data() {
        $query = $this->db->get($this->performance);
        $results['chart_data'] = $query->result();
 
        return $results;
    }
 
    function get_all_session_data() {
        $query = $this->db->select('user_data')->get('ci_sessions');
        return $query;
    }
 
    function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
 
//
//    public function insertCSV($data) {
//        $this->db->insert('measure', $data);
//        return TRUE;
//    }
     
    function check_set_user_cookie(){
         
    }
     
    public function loadToListJoin($query) {
        $qu = $this->db->query($query);
        return $qu->result();
    }
}