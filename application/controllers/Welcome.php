<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{

		parent::__construct();
		//load database
	}

	public function index()
	{
		$this->load->view('admin_ui/auth/login');
		$this->load->view('admin_ui/auth/scripts/login');
	}

	public function signup()
	{
		$this->load->view('admin_ui/auth/sign-up');
	}

	public function forgot_password()
	{
		$data['title']="Forgot password";
		$this->load->view('admin_ui/auth/forgot-password',$data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}
	
}
