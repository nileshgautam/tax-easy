<?php

require APPPATH . 'libraries/REST_Controller.php';

class Common extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    //load database
    $this->load->database();
    $this->load->model(array("api/main_model", "api/driver_model", "api/common_model"));
    $this->load->library(array("form_validation"));
    $this->load->helper("security", "common");
    $this->load->library("pagination");
    if (!$this->session->has_userdata('userInfo')) {
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

  public function upload_category_icon_post()
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

  public function category_post()
  {
    // print_r($_POST);
    // die;    
    $path = $this->security->xss_clean($this->input->post("file-path"));
    $title = $this->security->xss_clean($this->input->post("category-title"));
    $status = $this->security->xss_clean($this->input->post("category-status"));

    // $this->form_validation->set_rules("file-path", "Icon", "required");
    $this->form_validation->set_rules("category-title", "Category name", "required");
    $this->form_validation->set_rules("category-status", "Status", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('tbl_category', array('category_name' => $title))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'category_name' => $title,
          'category_icon' => $path,
          'status' => $status,
          'create_timestamp' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('tbl_category', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Category added",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 1,
            "message" => "Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function edit_category_post()
  {
    $id = $this->security->xss_clean($this->input->post("category-id"));
    $path = $this->security->xss_clean($this->input->post("file-path"));
    $title = $this->security->xss_clean($this->input->post("category-title"));

    $status = $this->security->xss_clean($this->input->post("category-status"));

    $this->form_validation->set_rules("category-title", "Category name", "required");
    $this->form_validation->set_rules("category-status", "Status", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'category_name' => $title,
        'category_icon' => $path,
        'status' => $status,
        'update_timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => $id);
      $res = $this->common_model->update_table('tbl_category', $formdata, $condition);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Category updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function delete_category_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "Category id", "required");


    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Category required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {

      $condition = array('sr' => $id);
      $res = $this->common_model->delete_from_table('tbl_category', $condition);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Category deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function search_in_category_post()
  {
    $value = $this->input->post('value');
    $data = $this->common_model->search_category($value);
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

  // Sub category

  public function sub_category_post()
  {
    // print_r($_POST);
    // die;    
    $id = $this->security->xss_clean($this->input->post("category-id"));
    $title = $this->security->xss_clean($this->input->post("sub-category-title"));

    // $this->form_validation->set_rules("file-path", "Icon", "required");
    $this->form_validation->set_rules("sub-category-title", "Sub category name", "required");
    $this->form_validation->set_rules("category-id", "Category", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('tbl_sub_category', array('sub_category_name' => $title))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'sub_category_name' => $title,
          'category_id' => $id,
          'create_timestamp' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('tbl_sub_category', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Sub category added",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 1,
            "message" => "Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function edit_sub_category_post()
  {

    $category_id = $this->security->xss_clean($this->input->post("category-id"));
    $id = $this->security->xss_clean($this->input->post("sub-category-id"));

    $title = $this->security->xss_clean($this->input->post("sub-category-title"));

    $this->form_validation->set_rules("sub-category-title", "Sub Category name", "required");
    $this->form_validation->set_rules("category-id", "category", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'sub_category_name' => $title,
        'category_id' => $category_id,
        'update_timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => $id);
      $res = $this->common_model->update_table('tbl_sub_category', $formdata, $condition);
      //  print_r($res);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Sub category updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function delete_sub_category_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "Sub Category id", "required");
    // Contact persion details
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Category required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {

      $condition = array('sr' => $id);
      $res = $this->common_model->delete_from_table('tbl_sub_category', $condition);
      //  print_r($res);
      //  die;
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Sub category deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }
  public function search_in_sub_category_post()
  {
    $value = $this->input->post('value');
    $data = $this->common_model->search_sub_category($value);
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

  // Sub category level

  public function sub_category_level_post()
  {


    $sub_category_id = $this->security->xss_clean($this->input->post("sub-category-id"));



    $title = $this->security->xss_clean($this->input->post("sub-category-lvel-title"));

    $path = $this->security->xss_clean($this->input->post("file-path"));

    $this->form_validation->set_rules("sub-category-id", "Sub Category name", "required");

    $this->form_validation->set_rules("sub-category-lvel-title", "Sub Category name", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('tbl_category_level', array('level_name' => $title))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'level_name' => $title,
          'sub_category_id' => $sub_category_id,
          'level_image' => $path,
          'create_timestamp' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('tbl_category_level', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Sub category level added",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 1,
            "message" => "Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function edit_sub_category_level_post()
  {

    $id = $this->security->xss_clean($this->input->post("sclv-id"));

    $sub_category_id = $this->security->xss_clean($this->input->post("sub-category-id"));

    $title = $this->security->xss_clean($this->input->post("sub-category-lvel-title"));

    $path = $this->security->xss_clean($this->input->post("file-path"));

    $this->form_validation->set_rules("sub-category-id", "Sub Category name", "required");

    $this->form_validation->set_rules("sub-category-lvel-title", "Sub Category name", "required");

    $this->form_validation->set_rules("sclv-id", "category", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'level_name' => $title,
        'sub_category_id' => $sub_category_id,
        'level_image' => $path,
        'update_timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => $id);
      $res = $this->common_model->update_table('tbl_category_level', $formdata, $condition);
      //  print_r($res);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Sub category updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function delete_sub_category_level_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "Sub Category level id", "required");
    // Contact persion details
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Id required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {

      $condition = array('sr' => $id);
      $res = $this->common_model->delete_from_table('tbl_category_level', $condition);
      //  print_r($res);
      //  die;
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Sub category level deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function search_in_sub_category_lv_post()
  {
    $value = $this->input->post('value');
    $data = $this->common_model->search_sub_category_lv($value);
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
  // Store

  public function store_post()
  {
    // print_r($_POST);
    // die;    
    $id = $this->security->xss_clean($this->input->post("category-id"));

    $title = $this->security->xss_clean($this->input->post("store-name"));
    $lat = $this->security->xss_clean($this->input->post("store-lattitude"));
    $long = $this->security->xss_clean($this->input->post("store-longitude"));
    $address = $this->security->xss_clean($this->input->post("store-address"));

    $this->form_validation->set_rules("store-name", "Store name", "required");
    $this->form_validation->set_rules("store-lattitude", "Store Latitude", "required");
    $this->form_validation->set_rules("store-longitude", "Store Longitude", "required");
    $this->form_validation->set_rules("store-address", "Store Address", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('tbl_store_locations', array('store_name' => $title))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'store_name' => $title,
          'store_lat' => $lat,
          'store_long' => $long,
          'store_address' => $address,
          'timestamp' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('tbl_store_locations', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Store added",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 1,
            "message" => "Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function edit_store_post()
  {
    // print_r($_POST);
    // die;

    $id = $this->security->xss_clean($this->input->post("store-id"));

    $title = $this->security->xss_clean($this->input->post("store-name"));
    $lat = $this->security->xss_clean($this->input->post("store-lattitude"));
    $long = $this->security->xss_clean($this->input->post("store-longitude"));
    $address = $this->security->xss_clean($this->input->post("store-address"));

    $this->form_validation->set_rules("store-name", "Store name", "required");
    $this->form_validation->set_rules("store-lattitude", "Store Latitude", "required");
    $this->form_validation->set_rules("store-longitude", "Store Longitude", "required");
    $this->form_validation->set_rules("store-address", "Store Address", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'store_name' => $title,
        'store_lat' => $lat,
        'store_long' => $long,
        'store_address' => $address,
        'update_timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => $id);
      $res = $this->common_model->update_table('tbl_store_locations', $formdata, $condition);
      //  print_r($res);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Store updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function delete_store_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "Store id", "required");
    // Contact persion details
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Store Id required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {

      $condition = array('sr' => $id);
      $res = $this->common_model->delete_from_table('tbl_store_locations', $condition);
      print_r($res);
      die;
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Store deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }


  // Addons

  public function upload_addon_icon_post()
  {
    // print_r($_FILES);die;
    $this->load->helper("common");
    $folder = $_POST['flag'] == 1 ? 'addon' : 'addon';
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

  public function addon_removefile_post()
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

  public function addons_post()
  {
    // print_r($_POST);
    // die;    
    // $id = $this->security->xss_clean($this->input->post("addons-id"));

    $path = $this->security->xss_clean($this->input->post("file-path"));
    $title = $this->security->xss_clean($this->input->post("name"));
    $price = $this->security->xss_clean($this->input->post("price"));

    $this->form_validation->set_rules("name", "Addons name", "required");
    $this->form_validation->set_rules("price", "Addons price", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      if ($this->common_model->get_data_where('tbl_addon', array('name' => $title))) {
        $this->response(array(
          "status" => 0,
          "message" => "Duplicate name not allowed.",
        ), REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $formdata = array(
          'name' => $title,
          'price' => $price,
          'image' => $path,
          'timestamp' => date('Y-m-d h:i:s')
        );
        if ($this->common_model->insert_data('tbl_addon', $formdata)) {
          $this->response(array(
            "status" => 1,
            "message" => "Addons added",
          ), REST_Controller::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 1,
            "message" => "Please contact your service provider.",
          ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
  }

  public function edit_addons_post()
  {
    // print_r($_POST);
    // die;

    $id = $this->security->xss_clean($this->input->post("addons-id"));

    $title = $this->security->xss_clean($this->input->post("name"));
    $price = $this->security->xss_clean($this->input->post("price"));

    $this->form_validation->set_rules("name", "Addons name", "required");
    $this->form_validation->set_rules("price", "Addons price", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'name' => $title,
        'price' => $price,
        'timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => $id);
      $res = $this->common_model->update_table('tbl_addon', $formdata, $condition);
      //  print_r($res);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Addons updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  public function delete_addons_post()
  {
    $id = $this->security->xss_clean($this->input->post("id"));
    $this->form_validation->set_rules("id", "Addons id", "required");
    // Contact persion details
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "Addons Id required"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {

      $condition = array('sr' => $id);
      $res = $this->common_model->delete_from_table('tbl_addon', $condition);
      print_r($res);
      die;
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Addons deleted",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => $res['message'],
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }

  // Admin Profit

  public function admin_profit_post()
  {
    // print_r($_POST);
    // die;

    $id = $this->security->xss_clean($this->input->post("profit-id"));

    $title = $this->security->xss_clean($this->input->post("profit"));

    $this->form_validation->set_rules("profit", "Enter Admin Profit", "required");

    // Contact persion details
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      // we have some errors
      $this->response(array(
        "status" => 0,
        "message" => "All fields are needed"
      ), REST_Controller::HTTP_NOT_FOUND);
    } else {
      // Check unique 
      $formdata = array(
        'admin_profit' => $title,
        'timestamp' => date('Y-m-d h:i:s')
      );
      $condition = array('sr' => 1);
      $res = $this->common_model->update_table('tbl_admin_profit', $formdata, $condition);
      //  print_r($res);
      if ($res) {
        $this->response(array(
          "status" => 1,
          "message" => "Profit updated",
        ), REST_Controller::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 1,
          "message" => "Please contact your service provider.",
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
  }
}
