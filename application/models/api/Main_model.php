<?php

class Main_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }


  public function get_count_where($tableName = null, $condition = null)
  {
    $this->db->select('*');
    $this->db->from($tableName);
    $this->db->like($condition);
    return $this->db->count_all_results();
  }

  public function get_dashboard_count()
  {
    // $users = $this->db->count_all_results('tbl_user');
    // $driver = $this->db->count_all_results('tbl_delivery');
    // $tailor = $this->db->count_all_results('tbl_tailor');
    // $store = $this->db->count_all_results('tbl_store_locations');

    // $active_users = $this->get_count_where('tbl_login', array('status' => 1,'login_type'=>0));
    // $active_driver = $this->get_count_where('tbl_login', array('status' => 1,'login_type'=>2));
    // $active_tailor = $this->get_count_where('tbl_login', array('status' => 1,'login_type'=>1));

    // $new_order = $this->get_count_where('tbl_order', array('order_status' => 0));
    // $assigned_to_tailor = $this->get_count_where('tbl_order', array('order_status' => 1));
    // $assigned_to_pickup = $this->get_count_where('tbl_order', array('order_status' => 2));
    // $complete = $this->get_count_where('tbl_order', array('order_status' => 3));
    // $deliverd = $this->get_count_where('tbl_order', array('order_status' => 4));

    // return array(
    //   'u' => $users,
    //   'au' => $active_users,
    //   'd' => $driver,
    //   'ad' => $active_driver,
    //   't' => $tailor,
    //   'at' => $active_tailor,
    //   's' => $store,
    //   'new_order' => $new_order,
    //   'a_pickup' => $assigned_to_pickup,
    //   'a_tailor' => $assigned_to_tailor,
    //   'a_complete' => $complete,
    //   'deliverd' => $deliverd
    // );
  }

  public function get_where($tableName = null, $condition = null)
  {
    $result = $this->db->get_where($tableName, $condition)->result_array();
    return $this->db->affected_rows() ? $result : FALSE;
  }

  public function update_where_information($table = null, $condition = null, $informations = null)
  {

    $this->db->where($condition);
    return $this->db->update($table, $informations);
  }

  public function get_data($table = null)
  {
    $this->db->order_by('joining', 'DESC');
    $query = $this->db->get($table);
    return $query->result();
  }
}
