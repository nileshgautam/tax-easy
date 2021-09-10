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

  public function save_notification_post()
  {  
   $notification = $this->security->xss_clean($this->input->post("notification-description"));
    $this->form_validation->set_rules("notification-description", "Notification", "required");
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Notification description required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('notification', array('notification' => $notification))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'notification' => $notification,
          'status' => 1,
          'createdby' => $this->session->userdata('userid'),
          'created_datetime' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('notification', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Notification added.",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 0,
            "message" => "Internal server error, Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function delete_notification_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "id", "required");
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => " id required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      $condition = array('id' => $id);
      $res = $this->common_model->delete_from_table('notification', $condition);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Notification deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 0,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function notification_get()
  {
    $data = $this->common_model->get_data('notification','created_datetime');
    if ($data) {
      $this->response(array(
        "status" => 200,
        "message" => "Data found",
        'data' => $data,
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => "Not found",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
