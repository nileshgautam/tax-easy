<?php

class Auth_model extends CI_Model
{

  public $tbl_users = "users";

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }


  public function verify_user($tableName = null, $condition)
  {
    $result = $this->db->get_where($tableName, $condition)->result_array();
    return $this->db->affected_rows() ? $result : FALSE;
  }

  public function get_user()
  {
    $this->db->select("*");
    $this->db->from(Auth_model::$tbl_users);
    $query = $this->db->get();
    return $query->result();
  }

  public function insert_tocken($data = array())
  {
    return $this->db->insert(Auth_model::$tbl_users, $data);
  }

  public function delete_admin($admin_id)
  {
    $this->db->where("id", $admin_id);
    return $this->db->delete(Auth_model::$tbl_users);
  }

  public function update_admin_information($condition = null, $informations = null)
  {
    $this->db->where($condition);
    return $this->db->update('users', $informations);
  }
}
