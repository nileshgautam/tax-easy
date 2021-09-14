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
    $this->load->helper("security", "common");
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

  public function file_post()
  {
    // print_r($_FILES);die;
    $this->load->helper("common");
    $folder = $_POST['flag'] == 1 ? 'category' : 'category_level';
    $result = uploadData($_FILES,  $folder);
    // $result
    $res = json_decode($result, true);
    // print_r($result);die;
    if ($res['status'] == 200) {
      $this->response(array(
        "status" => 200,
        "message" => "File uploaded successfully",
        "data" => $res['data'],
      ), REST_Controller::HTTP_OK);
    } else if ($res['status'] == 404) {
      $this->response(array(
        "status" => 500,
        "message" => "Invalid Type.",
      ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    } else {
      $this->response(array(
        "status" => 500,
        "message" => "Server error try again.",
      ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function removefile_post()
  {
    // print_r($_POST);
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
        $condition['assign_datetime']=date('Y-m-d h:i:s');
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
}
