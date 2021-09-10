<?php

require APPPATH . 'libraries/REST_Controller.php';

class Subadmin extends REST_Controller
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

  public function index_get()
  {
    $condition['role'] = 2;
    $condition['is_deleted'] = 0;
    $subadmin = $this->common_model->get_data_where(
      'users',
      $condition
    );
    if ($subadmin) {
      $this->response(array(
        "status" => 200,
        "message" => "success",
        'data' => $subadmin,
      ), REST_Controller::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 404,
        "message" => "Users not found",
      ), REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function insert_post()
  {   
    $password = $this->security->xss_clean($this->input->post("password"));
    $email = $this->security->xss_clean($this->input->post("email"));
    $first_name = $this->security->xss_clean($this->input->post("fname"));
    $last_name = $this->security->xss_clean($this->input->post("lname"));
    $mobile = $this->security->xss_clean($this->input->post("mobile"));

    $this->form_validation->set_rules("fname", "first name", "required");
    $this->form_validation->set_rules("password", "Password", "required");
    $this->form_validation->set_rules("email", "Email", "required");

    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 400,
        "message" => "First name, password and email are required"
      ), REST_Controller::HTTP_BAD_REQUEST);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('users', array('email' => $email))) {
        $this->response(array(
          "status" => 409,
          "message" =>  "'".$email. "' is already exists",
        ), REST_Controller::HTTP_CONFLICT);
      } else {
        $userid = $this->main_model->getNewIDorNo('users', "SBA");
        $formdata = array(
          'userid' => $userid,
          'username' => makeuserid($email),
          'password' => hash('sha512', $password),
          'first_name' => $first_name,
          'last_name' => $last_name,
          'email' => $email,
          'mobile' => $mobile,
          'role' => 2,
          'status' => 1,
          'created_datetime' => date('Y-m-d h:i:s')
        );
        $is_inserted=$this->common_model->insert_data('users', $formdata);
        if ($is_inserted) {
          $this->response(array(
            "status" => 200,
            "message" => "Created sub-admin."
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 500,
            "message" => "Insertion failure,Internal server error, Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function delete_post()
  {

    $id = $this->security->xss_clean($this->input->post("id"));
 
    // checking form submittion have any error or not
    if (!$id) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => " Id required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      $condition = array('id' => $id);
      $res = $this->common_model->update_table('users',array('is_deleted'=>true), $condition);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Sub-admin deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 0,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }
  
}
