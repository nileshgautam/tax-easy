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
    $this->db->update($tableName, $data);
    return $this->db->affected_rows() ? TRUE : FALSE;
  }

  public function delete_from_table($tableName = null, $condition = null)
  {
    try {
      $this->db->delete($tableName, $condition);
      $db_error = $this->db->error();
      if (!empty($db_error)) {
        throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
        // print_r($db_error);
        return $db_error; // unreachable retrun statement !!!
      } else {
        return TRUE;
      }
    } catch (Exception $e) {
      //throw $th;
      log_message('error: ', $e->getMessage());
      return $db_error;
    }
  }

  // Get sub category

  public function get_sub_category($limit, $start)
  {
    $this->db->select('tbl_sub_category.sr,tbl_sub_category.sub_category_name,tbl_category.category_name');

    $this->db->from('tbl_sub_category');
    $this->db->join('tbl_category', 'tbl_category.sr=tbl_sub_category.category_id', 'left');

    $this->db->limit($limit, $start);
    $query = $this->db->get();

    if ($query->num_rows() != 0) {
      return $query->result_array();
    } else {
      return false;
    }
  }

  public function get_sub_category_level($limit, $start)
  {
    $this->db->select('tbl_category_level.sr,tbl_category.category_name,tbl_category_level.sub_category_id,tbl_sub_category.sub_category_name,tbl_category_level.level_name,tbl_category_level.level_image');
    $this->db->from('tbl_category_level');
    $this->db->join('tbl_sub_category', 'tbl_sub_category.sr=tbl_category_level.sub_category_id', 'left');

    $this->db->join('tbl_category', 'tbl_category.sr=tbl_sub_category.category_id', 'left');

    $this->db->order_by('tbl_category_level.level_name', 'asc');

    $this->db->limit($limit, $start);
    $query = $this->db->get();

    if ($query->num_rows() != 0) {
      return $query->result_array();
    } else {
      return false;
    }
  }
  // orders
  public function get_orders($limit, $start)
  {
    $this->db->select('*');
    $this->db->from('tbl_order');
    $this->db->limit($limit, $start);
    $this->db->order_by('order_status', 'ASC');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function update_order_table($table = null, $condition = null, $data = null)
  {
    $this->db->trans_start();
    $this->db->where($condition);
    $this->db->update($table, $data);

    $formData = [];

    if (isset($data['pickup_driver_id'])) {
      $formData['pickup_driver_timestamp'] = date('Y-m-d h:i:s');
    } else if (isset($data['tailor_id'])) {
      $formData['tailor_timestamp'] = date('Y-m-d h:i:s');
    } else if (isset($data['delivery_driver_id'])) {
      $formData['delivery_timestamp'] = date('Y-m-d h:i:s');
    }
    $this->db->where($condition);
    $this->db->update('tbl_order_status', $formData);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function order_details($orderid = null)
  {
    $query = "SELECT tbl_order.order_id,tbl_order.user_id,tbl_user.user_name,tbl_user.user_phone,tbl_user.user_email,tbl_user.user_profile,tbl_user.user_address,tbl_user.user_city,tbl_user.user_pin, if(tbl_user.subscription=0,'No','Yes') as subscription,tbl_user.subscription_validity,
    tbl_order_details.category,tbl_order_details.sub_category,tbl_order_details.child_category,tbl_order_details.dimesnion, tbl_category.category_name,tbl_category.category_icon,tbl_sub_category.sub_category_name,
       tbl_category_level.level_name,tbl_category_level.level_image,tbl_order.order_status,
       tbl_order_status.create_timestamp,
    tbl_order.tailor_id,tbl_tailor.tailor_name,tbl_tailor.tailor_profile, tbl_tailor.tailor_phone,tbl_tailor.tailor_address,tbl_tailor.tailor_city, tbl_order_status.tailor_timestamp,
    
    tbl_order.pickup_driver_id,a.delivery_name as pick_up_name,tbl_order_status.pickup_driver_timestamp,a.delivery_profile as 'pickup_pro',a.delivery_phone as 'pickup_dr_phone',a.delivery_city as 'pickup_dr_city',a.delivery_address as 'pickup_dr_address',
    
    tbl_order.delivery_driver_id as drop_driver_id,b.delivery_name as drop_driver_name,b.delivery_profile as drop_driver_pro,
    b.delivery_phone as 'drop_dr_phone',b.delivery_city as 'drop_dr_city',b.delivery_address as 'drop_dr_address',
    tbl_order_status.delivery_timestamp,tbl_order.order_amount, if(tbl_order.clothe_design=0,'Category','Custom') as clothe_design,
       tbl_order.custom_design_image,
       if(tbl_order.payment_mode=0,'Online','Cash') as 'paymet_mode', if(tbl_order.pick_sample=0,'No','Yes') as 'sample_pick'
       FROM `tbl_order`
       LEFT JOIN tbl_order_details ON tbl_order_details.order_id=tbl_order.order_id
       LEFT JOIN tbl_order_status on tbl_order_status.order_id=tbl_order.order_id
       LEFT JOIN tbl_user on tbl_user.user_id=tbl_order.user_id
       LEFT JOIN tbl_tailor ON tbl_tailor.tailor_id=tbl_order.tailor_id
       LEFT JOIN tbl_delivery a ON a.delivery_id=tbl_order.pickup_driver_id
       LEFT JOIN tbl_delivery b ON b.delivery_id=tbl_order.delivery_driver_id
       LEFT JOIN tbl_category ON tbl_category.sr=tbl_order_details.category
       LEFT JOIN tbl_sub_category ON tbl_sub_category.sr=tbl_order_details.sub_category
       LEFT JOIN tbl_category_level ON tbl_category_level.sr=tbl_order_details.child_category where tbl_order.order_id='$orderid'";
    return $this->db->query($query)->result_array();
  }

  public function get_store($limit, $start)
  {
    $this->db->select('*');

    $this->db->from('tbl_store_locations');

    $this->db->limit($limit, $start);
    $query = $this->db->get();

    if ($query->num_rows() != 0) {
      return $query->result_array();
    } else {
      return false;
    }
  }
  
  public function get_addon($limit, $start)
  {
    $this->db->select('*');

    $this->db->from('tbl_addon');

    $this->db->limit($limit, $start);
    $query = $this->db->get();

    if ($query->num_rows() != 0) {
      return $query->result_array();
    } else {
      return false;
    }
  }

}

// SELECT tbl_order_details.sr, tbl_order_details.user_id,tbl_order_details.order_id,tbl_order_details.category,tbl_order_details.sub_category,tbl_order_details.child_category,tbl_order_details.dimesnion, tbl_category.category_name,tbl_category.category_icon,tbl_sub_category.sub_category_name,tbl_category_level.level_name,tbl_category_level.level_image,tbl_order.order_status,tbl_order_status.create_timestamp,tbl_order_status.pickup_driver_timestamp,tbl_order_status.tailor_timestamp,tbl_order_status.delivery_timestamp, tbl_order.tailor_id,tbl_order.pickup_driver_id,tbl_order.delivery_driver_id,tbl_order.order_amount,tbl_order.clothe_design,tbl_order.custom_design_image,tbl_order.payment_mode,tbl_order.pick_sample

// FROM `tbl_order_details`
// LEFT JOIN tbl_category on tbl_category.sr=tbl_order_details.category
// LEFT JOIN tbl_sub_category ON tbl_sub_category.sr=tbl_order_details.sub_category
// LEFT JOIN tbl_category_level on tbl_category_level.sr=tbl_order_details.child_category
// LEFT JOIN tbl_order on tbl_order.order_id=tbl_order_details.order_id
// LEFT JOIN tbl_order_status on tbl_order_status.order_id=tbl_order_details.order_id
// LEFT JOIN tbl_user on tbl_user.user_id=tbl_order_details.user_id



// SELECT tbl_order_details.sr, tbl_order_details.user_id, tbl_user.user_name,tbl_user.user_phone,tbl_user.user_email,tbl_user.user_profile,tbl_order_details.order_id,tbl_order_details.category,tbl_order_details.sub_category,tbl_order_details.child_category,tbl_order_details.dimesnion, tbl_category.category_name,tbl_category.category_icon,tbl_sub_category.sub_category_name,tbl_category_level.level_name,tbl_category_level.level_image,tbl_order.order_status,tbl_order_status.create_timestamp,tbl_order_status.pickup_driver_timestamp,tbl_order_status.tailor_timestamp,tbl_order_status.delivery_timestamp, tbl_order.tailor_id,tbl_tailor.tailor_name tbl_order.pickup_driver_id,tbl_delivery.delivery_name, tbl_order.delivery_driver_id, tbl_order.order_amount,tbl_order.clothe_design,tbl_order.custom_design_image,tbl_order.payment_mode,tbl_order.pick_sample

// FROM `tbl_order_details`
// LEFT JOIN tbl_category on tbl_category.sr=tbl_order_details.category
// LEFT JOIN tbl_sub_category ON tbl_sub_category.sr=tbl_order_details.sub_category
// LEFT JOIN tbl_category_level on tbl_category_level.sr=tbl_order_details.child_category
// LEFT JOIN tbl_order on tbl_order.order_id=tbl_order_details.order_id
// LEFT JOIN tbl_order_status on tbl_order_status.order_id=tbl_order_details.order_id
// LEFT JOIN tbl_user on tbl_user.user_id=tbl_order_details.user_id
// LEFT JOIN tbl_tailor on tbl_tailor.tailor_id=tbl_order.tailor_id
// LEFT JOIN tbl_delivery on tbl_delivery.delivery_id=tbl_order.pickup_driver_id
// LEFT JOIN tbl_delivery a on a.delivery_id=tbl_order.delivery_driver_id