<?php
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Common extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    //load database
    $this->load->database();
    $this->load->model(array("api/main_model", "api/common_model"));
    $this->load->library(array("form_validation"));
    $this->load->helper(array("security", "common"));
    $this->load->library("pagination");
    if (!$this->session->has_userdata('userid')) {
      redirect(base_url());
    }
  }

  function remove_file($path = null)
  {
    $return_text = 0;
    // Check file exist or not
    if (file_exists($path)) {
      // Remove file
      unlink($path);
      // Set status
      $return_text = 1;
    } else {
      // Set status
      $return_text = 0;
    }
    // Return status
    return $return_text;
    // exit;
  }


  public function uploadpo_post()
  {
    $trasactionid = $this->main_model->getNewIDorNo('transaction', "TRID");
    $path = $_POST['fy'] . '/gst' . $_POST['csid'] . '/' . $_POST['month'] . '/' . $this->input->post('doc-title');
    $file = uploadFile($_FILES, $path);
    $file_data = array(
      'transactionid' => $trasactionid,
      'fy' => $this->input->post('fy'),
      'customer_id' => $this->input->post('csid'),
      'financial_year_month' => $this->input->post('month'),
      'purchage_document' => $file,
      'uploaded_date_time' => date('Y-m-d H:i:s'),
      'uploaded_by' => $this->session->userdata('userid'),
      'service_type' => 'gst',
    );
    if ($this->common_model->insert_data('transaction', $file_data)) {
      $this->response(array(
        "status" => 1,
        "message" => "Purchage document uploaded.",
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 0,
        "message" => "Upload failure!, Please contact your service provider.",
        "data" => $trasactionid,
      ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function uploadsd_post()
  {
    $trasactionid = $this->main_model->getNewIDorNo('transaction', "TRID");
    $path = $_POST['fy'] . '/' . $_POST['csid'] . '/' . $_POST['month'];
    $file = uploadFile($_FILES, $path);
    $file_data = array(
      'transactionid' => $trasactionid,
      'fy' => $this->input->post('fy'),
      'customer_id' => $this->input->post('csid'),
      'financial_year_month' => $this->input->post('month'),
      'purchage_document' => json_encode(array('title' => $this->input->post('doc-title'), 'file' => $file)),
      'uploaded_date_time' => date('Y-m-d H:i:s'),
      'uploaded_by' => $this->session->userdata('userid'),
      'service_type' => 'GST',
    );
    if ($this->common_model->insert_data('transaction', $file_data)) {
      $this->response(array(
        "status" => 1,
        "message" => "Purchage document uploaded.",
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 0,
        "message" => "Upload failure!, Please contact your service provider.",
        "data" => $trasactionid,
      ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }


  public function removefile_post()
  {
    if ($this->input->post('path')) {
      $res = $this->remove_file($this->input->post('path'));
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "removed",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 404,
          "message" => "Not found",
        ), REST_Controller::HTTP_NOT_FOUND);
      }
    }
  }

  public function toggle_user_post() // for activate / deactivate
  {

    $userid = $this->input->post('id');
    $value = $this->input->post('status');
    $value = ($value == "1") ? false : true;
    $message = ($value == '1') ? 'User successfully activated' : 'User successfully deactivated';
    $condition = array('id' => $userid);
    $formdata = array('status' => $value);
    $data = $this->common_model->update_table('users', $formdata, $condition);
    if ($data) {
      $this->response(array(
        "status" => 200,
        "message" => $message,
        'data' => $data,
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => "Not found",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }


  public function assign_customer_post()
  {
    $subamin_id = $this->input->post('subamin_id');
    $customer_id = $this->input->post('customer_id');
    $table = 'subadmin_users_relation';

    if (!empty($subamin_id) && !empty($customer_id)) {
      $condition = array(
        'customer_id' => $customer_id,
        'sub_admin_id' => $subamin_id
      );
      $is_availble = $this->common_model->get_data_where($table, $condition);
      if ($is_availble) {
        $this->response(array(
          "status" => 409,
          "message" => "This coustomer is already assigned",
        ), REST_Controller::HTTP_CONFLICT);
      } else {
        $condition['assign_datetime'] = date('Y-m-d h:i:s');
        $data = $this->common_model->insert_data($table, $condition);
        if ($data) {
          $this->response(array(
            "status" => 200,
            "message" => 'Assigned successfully',
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 500,
            "message" => "Assignment failed please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function subadmin_permission_post()
  {
    $userid = $this->input->post('userid');
    $permission = $this->input->post('permission');
    $condition = array('id' => $userid);
    $formdata = array('access_control' => json_encode($permission));
    $data = $this->common_model->update_table('users', $formdata, $condition);
    if ($data) {
      $this->response(array(
        "status" => 200,
        "message" => 'Permission granted!',
        'data' => $data,
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 500,
        "message" => "Permission failure!",
      ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  // ITR section
  public function upload_itrdoc_post()
  {
    $trasactionid = $this->main_model->getNewIDorNo('transaction', "TRID");
    $fy = $this->input->post('fy');
    $title = $this->input->post('title');
    $userid = $this->input->post('userid');
    $path = $fy . '/itr/' . $userid . '/' . $title;
    $file = uploadFile($_FILES, $path);
    $condition = array(
      'customer_id' => $userid,
      'service_type' => 'itr',
      'fy' => $fy,
    );
    $doc = $this->common_model->get_data_where(
      'transaction',
      $condition
    );
    if ($doc != false) {
      $condition['transactionid'] = $doc[0]['transactionid'];
      $file_data = array(
        'douments' => $file,
        'uploaded_date_time' => date('Y-m-d H:i:s'),
        'uploaded_by' => $this->session->userdata('userid'),
      );
      $res = $this->common_model->update_table('transaction', $file_data, $condition);
      if ($res) {
        $this->response(array(
          "status" => 200,
          "message" => "Document uploaded.",
          "data" => $doc[0]['transactionid'],
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 500,
          "message" => "Upload failure!, Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    } else {
      $file_data = array(
        'transactionid' => $trasactionid,
        'fy' =>  $fy,
        'customer_id' =>  $userid,
        'financial_year_month' => $fy,
        'douments' => $file,
        'uploaded_date_time' => date('Y-m-d H:i:s'),
        'uploaded_by' => $this->session->userdata('userid'),
        'service_type' => 'itr',
      );
      if ($this->common_model->insert_data('transaction', $file_data)) {
        $this->response(array(
          "status" => 200,
          "message" => "Document uploaded.",
          "data" => $trasactionid
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 500,
          "message" => "Upload failure!, Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  // ITR section
  public function upload_itrack_post()
  {

    $fy = $this->input->post('fy');
    $title = $this->input->post('title');
    $userid = $this->input->post('userid');
    $path = $fy . '/itr/' . $userid . '/' . $title;
    $file = uploadFile($_FILES, $path);

    $condition = array(
      'fy' =>  $fy,
      'customer_id' =>  $userid,
      'financial_year_month' => $fy,
    );
    $doc = $this->common_model->get_data_where('transaction', $condition);
    if ($doc[0]['transactionid'] != '') {

      $file_data = array(
        'acknowledge_document' => $file,
        'uploaded_date_time' => date('Y-m-d H:i:s'),
        'uploaded_by' => $this->session->userdata('userid'),
        'service_type' => 'itr',
      );

      if ($this->common_model->update_table('transaction', $file_data, $condition)) {
        $this->response(array(
          "status" => 200,
          "message" => "Acknowledge file uploaded uploaded.",
          "data" => $doc[0]['transactionid'],
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 500,
          "message" => "Upload failure!, Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function getitrdoc_post()
  {

    $trid = $this->input->post('trid');
    $condition = array(
      'transactionid' => $trid,
      'service_type' => 'itr',
    );
    $doc = $this->common_model->get_data_where('transaction', $condition);

    if ($doc) {
      $this->response(array(
        "status" => 200,
        "message" => "success.",
        "data" => $doc
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => "Not found.",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }

  // Function ITR history by user
  public function getitrhistory_post()
  {
    $userid = $this->input->post('userid');
    $condition = array(
      'customer_id' => $userid,
      'service_type' => 'itr',
    );
    $log = $this->common_model->get_data_where(
      'transaction',
      $condition
    );
    if ($log) {
      $this->response(array(
        "status" => 200,
        "message" => "success.",
        "data" => $log
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => "Not found.",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function update_payment_post()
  {
    $tird = $this->input->post('trid');
    $paystatus = $this->input->post('status');
    $condition = array(
      'transactionid' => $tird,
      'service_type' => 'itr',
    );
    $file_data['payment'] = $paystatus;
    $res = $this->common_model->update_table('transaction', $file_data, $condition);
    if ($res) {
      $this->response(array(
        "status" => 200,
        "message" => "success.",
        "data" => $res
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => " Transaction ID Not found.",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }

  // Function ITR history by user

}
