<?php
  /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the dndsoft License
 * that is bundled with this package in the file license.txt.
 * @author   dndsoft
 * @author-email  komputer.dnd@gmail.com
 * @copyright  Copyright © dndsoft.my.id All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Job_post_model");
		$this->load->model("Xin_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Recruitment_model");
		$this->load->model('Employees_model');
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
		$system = $this->Xin_model->read_setting_info(1);
		if($system[0]->module_recruitment=='true'){
			$data['title'] = 'HOME';
			$data['path_url'] = 'job_home';
			$data['all_jobs'] = $this->Recruitment_model->get_all_jobs_last_desc();
			$data['all_featured_jobs'] = $this->Recruitment_model->get_featured_jobs_last_desc();
			$data['all_job_categories'] = $this->Recruitment_model->all_job_categories();
			$data['subview'] = $this->load->view("frontend/hrpremium/home-2", $data, TRUE);
			$this->load->view('frontend/hrpremium/job_layout/job_layout', $data); //page load
		} else {
			$data['title'] = $this->Xin_model->site_title().' | Log in';
			$theme = $this->Xin_model->read_theme_info(1);
			if($theme[0]->login_page_options == 'login_page_1'):
				// redirect('http://google.com');
				$this->load->view('admin/auth/login-1', $data);
			elseif($theme[0]->login_page_options == 'login_page_2'):
				$this->load->view('admin/auth/login-2', $data);
			elseif($theme[0]->login_page_options == 'login_page_3'):
				$this->load->view('admin/auth/login-3', $data);
			elseif($theme[0]->login_page_options == 'login_page_4'):
				$this->load->view('admin/auth/login-4', $data);
			elseif($theme[0]->login_page_options == 'login_page_5'):
				$this->load->view('admin/auth/login-5', $data);				
			else:
				$this->load->view('admin/auth/login-1', $data);	
			endif;
			
		}
     }
}
