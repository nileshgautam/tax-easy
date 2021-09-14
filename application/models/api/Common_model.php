<?php

class Common_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    date_default_timezone_set("Asia/Kolkata");
  }

  public function get_data($table = null, $order_by = null)
  {
    $this->db->order_by($order_by, 'ASC');
    $query = $this->db->get($table);
    return $query->result();
  }

  public function get_count($table = null)
  {
    return $this->db->count_all($table);
  }

  public function get_records($limit = null, $start = null, $table = null)
  {
    $this->db->select('*');
    $this->db->from($table);
    $this->db->limit($limit, $start);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_data_where($tableName = null, $condition = null)
  {
    $result = $this->db->get_where($tableName, $condition)->result_array();
    return $this->db->affected_rows() ? $result : FALSE;
  }

  public function insert_data($tableName = null, $data = null)
  {
    $this->db->insert($tableName, $data);
    return $this->db->affected_rows();
  }

  public function update_table($tableName = null, $data = null, $condition = null)
  {
    $this->db->where($condition);

    $res=$this->db->update($tableName, $data);
    print_r($res);die;

    return $this->db->affected_rows() ? TRUE : FALSE;
  }
  
  public function delete_from_table($tableName = null, $condition = null)
  {
    try {
      $this->db->delete($tableName, $condition);
      $db_error = $this->db->error();
      if (!empty($db_error)) {
        throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
        return $db_error; // unreachable retrun statement !!!
      } else {
        return TRUE;
      }
    } catch (Exception $e) {
      log_message('error: ', $e->getMessage());
      return $db_error;
    }
  }

  public function delete($tableName = null, $condition = null)
  {
    try {
      $this->db->delete($tableName, $condition);
      $db_error = $this->db->error();
      if (!empty($db_error)) {
        throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
        print_r($db_error);die;
        return $db_error; // unreachable retrun statement !!!
      } else {
        return TRUE;
      }
    } catch (Exception $e) {
      log_message('error: ', $e->getMessage());
      return $db_error;
    }
  }
}
