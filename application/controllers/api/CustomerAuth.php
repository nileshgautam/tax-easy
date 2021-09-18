<?php

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';


class CustomerAuth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        //load database
        date_default_timezone_set("Asia/Kolkata");
        $this->objOfJwt = new CreatorJwt();
        header('Content-Type: application/json');
        $this->load->database();
        $this->load->model(array("api/auth_model", "api/main_model", "api/common_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper(array("security", "email", "common"));
    }
    private function sendOTP($mobile = null, $otp = null)
    {
        //This function sends an OTP to desired mobile number using smseasy api
        $url = 'http://alerts.smseasy.in/api/v3/';
        $message = $otp . " is your OTP for signing taxeagy. Valid for 10 minutes. Requested on " . date('d/m/Y') . " , " . date('H:i') . " IST";
        $sender = ''; //
        $apiKey = '';

        $fields = array(
            'method' => "sms",
            'api_key' => $apiKey,
            'to'    => $mobile,
            'sender'   => $sender,
            'message' => $message
        );
        return true;
        //open connection
        // $ch = curl_init();
        //set the url, number of POST vars, POST data
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, count($fields));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        // $responce = curl_exec($ch);
        // echo  $responce;
        // die;
        //close connection
        // curl_close($ch);
    }



    public function signin_post()
    {
        // collecting form data inputs
        $mobile = $this->security->xss_clean($this->input->post("mobile"));
        $password = $this->security->xss_clean($this->input->post("password"));
        // form validation for inputs
        $this->form_validation->set_rules("mobile", "mobile", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        // checking form submittion have any error or not
        if ($this->form_validation->run() === FALSE) {
            // we have some errors
            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ), REST_Controller::HTTP_NOT_FOUND);
        } else {
            if (!empty($mobile) && !empty($password)) {
                // all values are available
                $condition = array(
                    "mobile" => $mobile,
                    "password" => hash('sha512', $password),
                    "is_deleted" => 0
                );
                $table = 'users';
                $isValid = $this->main_model->get_where($table, $condition);
                if ($isValid) {
                    $tokenData['mobile'] = $mobile;
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
                        "message" => "mobile and password did not match."
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

    public function signup_post()
    {
        $first_name = $this->security->xss_clean($this->input->post("name"));
        $mobile = $this->security->xss_clean($this->input->post("mobile"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $password = $this->security->xss_clean($this->input->post("password"));

        $this->form_validation->set_rules("name", "Name", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_rules("mobile", "mobile", "required");

        // checking form submittion have any error or not
        if ($this->form_validation->run() === FALSE) {
            // we have some errors
            $this->response(array(
                "status" => 400,
                "message" => "Name, password and email are required"
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Check unique 
            if ($this->common_model->get_data_where('users', array('email' => $email))) {
                $this->response(array(
                    "status" => 409,
                    "message" =>  "'" . $email . "' is already exists",
                ), REST_Controller::HTTP_CONFLICT);
            } else if ($this->common_model->get_data_where('users', array('mobile' => $mobile))) {
                $this->response(array(
                    "status" => 409,
                    "message" =>  "'" . $mobile . "' mobile number is already exists.",
                ), REST_Controller::HTTP_CONFLICT);
            } else {
                $userid = $this->main_model->getNewIDorNo('users', "CST");
                $formdata = array(
                    'userid' => $userid,
                    'username' => makeuserid($email),
                    'password' => hash('sha512', $password),
                    'first_name' => $first_name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'role' => 3,
                    'status' => false,
                    'created_datetime' => date('Y-m-d h:i:s')
                );
                $is_inserted = $this->common_model->insert_data('users', $formdata);
                if ($is_inserted) {
                    $this->response(array(
                        "status" => 200,
                        "message" => "Created Customer."
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

    public function sendotp_post()
    {
        $mobile = $this->security->xss_clean($this->input->post("mobile"));
        $this->form_validation->set_rules("mobile", "mobile", "required");
        if ($this->form_validation->run() === FALSE) {
            // we have some errors
            $this->response(array(
                "status" => 0,
                "message" => "mobile is required"
            ), REST_Controller::HTTP_NOT_FOUND);
        } else {
            // all values are available
            $six_digit_random_number = mt_rand(100000, 999999);
            $condition = array('mobile' => $mobile);
            $isUpdate = $this->auth_model->update_admin_information($condition, array('otp' => $six_digit_random_number));
            $isTrue = $this->sendOTP($mobile, $six_digit_random_number);
            if ($isTrue) {
                $this->response(array(
                    "status" => 1,
                    "message" => "Please check your inbox,OTP sent.-" . $six_digit_random_number,
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Unable to send otp,try again"
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function verifyotp_post()
    {
        $otp = $this->security->xss_clean($this->input->post("otp"));
        $userid = $this->security->xss_clean($this->input->post("userid"));
        $condition = array('userid' => $userid, 'otp' => $otp);
        $isValid = $this->common_model->get_data_where('users', $condition);
        if ($isValid) {
            $information = array(
                "status" => true,
                "userid" => $userid,
            );
            $isUpdate = $this->auth_model->update_admin_information(array('userid' => $userid), $information);
            $this->response(array(
                "status" => 1,
                "message" => "success"
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "Invalid otp"
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function reset_password_post()
    {
        $id = $this->security->xss_clean($this->input->post("userid"));
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
                if (!empty($newpassword) && !empty($cnfpassword)) {
                    if ($newpassword === $cnfpassword) {
                        $condition = array(
                            "userid" => $id,
                        );
                        $password = array(
                            "password" => hash('sha512', $newpassword),
                            "updated_datetime" => date("Y-m-d H:i:s"),
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
                                "message" => "Failure! password updation., try again",
                            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        $this->response(array(
                            "status" => 1,
                            "message" => "Password did not match.,try another",
                        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                    // all values are available

                } else {
                    // We have some empty field
                    $this->response(array(
                        "status" => 0,
                        "message" => "All fields are needed."
                    ), REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
    }

    public function updateprofile_post()
    {
        $userid = $this->security->xss_clean($this->input->post("userid"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $name = $this->security->xss_clean($this->input->post("name"));
        $firm_name = $this->security->xss_clean($this->input->post("firm-name"));
        $this->form_validation->set_rules("name", "Name", "required");
        $this->form_validation->set_rules("email", "Email", "required");
        // checking form submittion have any error or not
        if ($this->form_validation->run() === FALSE) {
            // we have some errors
            $this->response(array(
                "status" => 400,
                "message" => "Name and email are required"
            ), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Check unique 
            if ($this->common_model->get_data_where('users', array('email' => $email))) {
                $this->response(array(
                    "status" => 409,
                    "message" =>  "'" . $email . "' is already exists",
                ), REST_Controller::HTTP_CONFLICT);
            } else {
                $condition = array('userid' => $userid);
                $formdata = array(
                    'first_name' => $name,
                    'email' => $email,
                    'firm_name' => $firm_name,
                    'updated_datetime' => date('Y-m-d h:i:s')
                );
                $isUpdate = $this->common_model->update_table('users', $formdata, $condition);
                if ($isUpdate) {
                    $this->response(array(
                        "status" => 200,
                        "message" => "Updated."
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        "status" => 500,
                        "message" => "updatation failure,Internal server error, Please contact your service provider.",
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
    }

    public function change_password_post()
    {
        $cnfpass = $this->security->xss_clean($this->input->post("cnf-password"));
        $condition = array('userid' => $this->session->userdata("userid"));
        $information = array(
            "password" => hash('sha512', $cnfpass),
            "updated_datetime" => date("Y-m-d H:i:s"),
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
