<?php

class Users_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }


  public function get_users($limit, $start)
  {
    $this->db->select('*');
    $this->db->from('tbl_user');
    $this->db->join('tbl_login', 'tbl_user.user_id=tbl_login.login_id', 'left');
    $this->db->limit($limit, $start);
    $query = $this->db->get();
    return $query->result_array();
  }
}
