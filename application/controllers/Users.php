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
		$this->load->model(array("api/main_model"));
		$this->load->library("pagination");
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
	}

	public function new_notice($id = null)
	{
		$data['title'] = "New Notice";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/forms/new-notification');
		$this->load->view('admin_ui/layout/footer');
	}

	public function customers()
	{
		$data['title'] = "Customers";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/customers');
		$this->load->view('admin_ui/layout/footer');
	}

	public function subadmin()
	{
		$data['title'] = "Sub-admin";
		$this->load->view('admin_ui/layout/header', $data);
		$this->load->view('admin_ui/layout/nav');
		$this->load->view('admin_ui/pages/subadmin');
		$this->load->view('admin_ui/layout/footer');
	}
}
