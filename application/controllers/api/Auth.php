<?php

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';


class Auth extends REST_Controller
{

    public function __construct()
    {

        parent::__construct();
        //load database
        date_default_timezone_set("Asia/Kolkata");
        $this->objOfJwt = new CreatorJwt();
        header('Content-Type: application/json');

        $this->load->database();
        $this->load->model(array("api/auth_model", "api/main_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper(array("security", "email"));
    }
    /*
    INSERT: POST REQUEST TYPE
    UPDATE: PUT REQUEST TYPE
    DELETE: DELETE REQUEST TYPE
    LIST: Get REQUEST TYPE
  */

    function email_reset($url = null)
    {
        $html = '';
        $data['url'] = $url;
        $html .= $this->load->view('admin_ui/emailtemplate/reset-link', $data, true);
        return $html;
    }


    public function verify_post()
    {
        // collecting form data inputs
        $email = $this->security->xss_clean($this->input->post("email"));
        $password = $this->security->xss_clean($this->input->post("password"));
        // form validation for inputs
        $this->form_validation->set_rules("email", "Email", "required|valid_email");
        $this->form_validation->set_rules("password", "Password", "required");
        // checking form submittion have any error or not
        if ($this->form_validation->run() === FALSE) {
            // we have some errors
            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ), REST_Controller::HTTP_NOT_FOUND);
        } else {
            if (!empty($email) && !empty($password)) {
                // all values are available
                $condition = array(
                    "email" => $email,
                    "password" => hash('sha512', $password),
                );
                // echo '<pre>';
                // print_r($condition);die;

                $table = 'users';
                $isValid = $this->main_model->get_where($table, $condition);
                if ($isValid) {
                    $tokenData['email'] = $email;
                    $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
                    $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
                    $information = array(
                        "auth_key" => $jwtToken,
                        "last_login" => date("Y-m-d H:i:s"),
                    );

                    $isUpdate = $this->auth_model->update_admin_information($condition, $information);
                    if ($isUpdate) {
                        $userdata = array(
                            'userid' => $isValid[0]['userid'],
                            'avatar' => $isValid[0]['avatar'],
                            'name' => $isValid[0]['username'],
                            'email' => $isValid[0]['email'],
                            'auth_key' => $jwtToken
                        );

                        $this->session->set_userdata($userdata);
                        $this->response(array(
                            "status" => 1,
                            "message" => "login success",
                            "tocken" => $jwtToken
                        ), REST_Controller::HTTP_OK);
                    } else {
                        $this->response(array(
                            "status" => 0,
                            "message" => "Login failed. Please contact your service provider.",
                            "tocken" => $jwtToken
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Email and password did not match."
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // we have some empty field
                $this->response(array(
                    "status" => 0,
                    "message" => "All fields are needed"
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function send_password_reset_post()
    {
        $email = $this->security->xss_clean($this->input->post("email"));
        $table = 'users';
        $condition = array('login_id' => $email);

        $data = $this->main_model->get_where($table, $condition);

        if (!$data) {
            $this->response(array(
                "status" => 0,
                "message" => "Invalid Emailid"
            ), REST_Controller::HTTP_NOT_FOUND);
        } else {
            $id = base64_encode($data[0]['login_id']);

            $condition = array(
                'login_id' => $data[0]['login_id']
            );

            $info = array('reset_pass_status' => false, 'updating' => date("Y-m-d H:i:s", strtotime("+30 minutes")));

            $this->main_model->update_where_information($table, $condition, $info);

            $msg = $this->email_reset(base_url('Auth/recoverpassword/') . $id);

            $subject = BRAND . " Rest password";

            $res = sentmail($email, $subject, $msg);

            if ($res) {
                $this->response(array(
                    "status" => 0,
                    "message" => "Check your email for create new password."
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Email sending failed try again"
                ), REST_Controller::HTTP_PRECONDITION_FAILED);
            }
        }
    }

    public function reset_password_post()
    {

        $id = $this->security->xss_clean($this->input->post("id"));
        if ($id == '') {
            $this->response(array(
                "status" => 0,
                "message" => "Invalid request"
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $newpassword = $this->security->xss_clean($this->input->post("password"));
            $cnfpassword = $this->security->xss_clean($this->input->post("confirm-password"));

            // form validation for inputs

            $this->form_validation->set_rules("password", "Password", "required");
            $this->form_validation->set_rules("confirm-password", "Confirm password", "required");


            // checking form submittion have any error or not
            if ($this->form_validation->run() === FALSE) {
                // we have some errors
                $this->response(array(
                    "status" => 0,
                    "message" => "All fields are needed"
                ), REST_Controller::HTTP_NOT_FOUND);
            } else {
                if (!empty($newpassword) && !empty($newpassword) && !empty($cnfpassword)) {
                    // all values are available
                    $condition = array(
                        "login_id" => $id,
                    );

                    $password = array(
                        "password" => hash('sha512', $newpassword),
                        "updating" => date("Y-m-d H:i:s"),
                    );

                    $isUpdate = $this->auth_model->update_admin_information($condition, $password);

                    if ($isUpdate) {
                        $this->response(array(
                            "status" => 1,
                            "message" => "Password has been changed",
                        ), REST_Controller::HTTP_OK);
                    } else {
                        $this->response(array(
                            "status" => 0,
                            "message" => "Password update failed",
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    // we have some empty field
                    $this->response(array(
                        "status" => 0,
                        "message" => "All fields are needed"
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
    }

    public function verify_old_password_post()
    {
        $password = $this->security->xss_clean($this->input->post("oldpassword"));
        $condition = array(
            "password" => hash('sha512', $password),
        );

        $table = 'users';
        $isValid = $this->main_model->get_where($table, $condition);

        if ($isValid) {
            $this->response(array(
                "status" => 200,
                "message" => "ok",
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 404,
                "message" => "Password did not match",
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function change_password_post()
    {
        
        $cnfpass = $this->security->xss_clean($this->input->post("cnf-password"));

        $condition=array('login_id'=>$_SESSION['userInfo']['email']);
        
        $information = array(
            "password" => hash('sha512',$cnfpass),
            "updating" => date("Y-m-d H:i:s"),
        );
        $isUpdate = $this->auth_model->update_admin_information($condition, $information);
        if ($isUpdate) {
            $this->response(array(
                "status" => 200,
                "message" => "Password has been changed.",
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 500,
                "message" => "Internal server error, please contact your service provider.",
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
