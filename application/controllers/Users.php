<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//load database
		if (!$this->session->has_userdata('name')) {
			redirect(base_url());
		}
		$this->load->model(array("api/main_model", "api/common_model"));
		$this->load->library("pagination");
		$this->load->helper(array('common'));
	}

	public function dashboard()
	{
		$data['data'] = $this->main_model->get_dashboard_count();
		$data['title'] = "Dashboard";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/index');
		$this->load->view('admin_ui/layout/footer');
	}
	// Change password form 
	public function change_password($id = null)
	{
		$data['title'] = "Change password";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/form/change-password');
		$this->load->view('admin_ui/layout/footer');
	}

	public function notification()
	{
		$data['title'] = "Notification Administration";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/notification');
		$this->load->view('admin_ui/layout/footer');
		$this->load->view('admin_ui/forms/scripts/notification');
	}

	public function new_notice($id = null)
	{
		$data['title'] = "New Notice";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/new-notification');
		$this->load->view('admin_ui/layout/footer');
		$this->load->view('admin_ui/forms/scripts/notification');
	}

	public function customers()
	{

		$data['title'] = "Customers";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/customers');
		$this->load->view('admin_ui/forms/scripts/customers');
		$this->load->view('admin_ui/layout/footer');
	}

	// public function new_customers($id = null)
	// {
	// 	$data['title'] = "New customers";

	// 	if ($id !== null) {
	// 		$id = base64_decode($id);
	// 		$data['users'] = $this->common_model->get_data_where('users', array('id' => $id));
	// 	}
	// 	$this->load->view('admin_ui/layout/header', $data);
	// 	$this->load->view('admin_ui/layout/nav');
	// 	$this->load->view('admin_ui/forms/customer');
	// 	$this->load->view('admin_ui/forms/scripts/customers');
	// 	$this->load->view('admin_ui/layout/footer');
	// }

	public function subadmin()
	{
		$data['title'] = "Sub-admin";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/subadmin');
		$this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function subadmin_form($id = null)
	{
		$data['title'] = "Sub-admin";
		if ($id != null) {
			$id = base64_decode($id);
			$data['users'] = $this->common_model->get_data_where('users', array('id' => $id));
		}
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/subadmin');
		$this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function enquiry_form()
	{
		$data['title'] = "Enquiry Form";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/enquiry-form');
		// $this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function enquiry()
	{
		$data['title'] = "Enquiry";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/enquiry');
		$this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function service_form()
	{
		$data['title'] = "New Service";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/services');
		// $this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function services()
	{
		$data['title'] = "services";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/services');
		// $this->load->view('admin_ui/forms/scripts/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}

	public function gst()
	{
		$data['title'] = "GST Filing";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/gst');
		$this->load->view('admin_ui/pages/scripts/gst');
		$this->load->view('admin_ui/layout/footer');
	}

	public function gst_page($userid = null)
	{
		$data['title'] = "GST form";
		
		$table = 'users';
		$conditions['userid'] = base64_decode($userid);
		$conditions['status'] = true;
		$conditions['is_deleted'] = false;
		
		$data['user'] = $this->common_model->get_data_where($table, $conditions);

		// echo '<pre>';
		// print_r($data);
		// die;

		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/gst-page');
		// $this->load->view('admin_ui/form/scripts/gst-page');
		$this->load->view('admin_ui/layout/footer');
	}

	public function itr()
	{
		$data['title'] = "services";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/itr');
		$this->load->view('admin_ui/pages/scripts/itr');
		$this->load->view('admin_ui/layout/footer');
	}
}
