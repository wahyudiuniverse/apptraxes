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

class Customers extends MY_Controller 
{

   /*Function to set JSON output*/
	public function output($Return=array()) {
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function __construct(){
          parent::__construct();
          //load the login model
		  $this->load->model('Xin_model');
			$this->load->model("Roles_model");
      $this->load->model('Company_model');
			$this->load->model("Employees_model");
			$this->load->model("Customers_model");

		  // $this->load->model("Project_model");
		  // $this->load->model("Tax_model");
		  // $this->load->model("Invoices_model");
		  // $this->load->model("Clients_model");
		  // $this->load->model("Finance_model");
			// $this->load->model("Department_model");
			// $this->load->model("Designation_model");
			// $this->load->model("Location_model");
			// $this->load->model("City_model");
			// $this->load->model("Contracts_model");
			$this->load->library("pagination");
			//$this->load->library('Pdf');
			$this->load->helper('string');
     }
	 
	// invoices page
	public function index() {
	
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('left_customer').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('left_customer');
		// $data['all_customers'] = $this->Customers_model->get_customers();
		// $data['all_taxes'] = $this->Tax_model->get_all_taxes();
		$data['path_url'] = 'customers';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('69',$role_resources_ids)) {
			$data['subview'] = $this->load->view("admin/customers/customers_list", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
	}

	public function customers_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/customers/customers_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$id = $this->uri->segment(4);
		// $id = 0;



			if($id=="0" || is_null($id)){
				// $aksesproject = $this->Project_model->list_akses_project();
				$client = $this->Customers_model->get_customer_default();
				// $usermobile = $this->Usersmobile_model->user_mobile_limit();
			}else{
				$client = $this->Customers_model->get_all_customer($id);
				// $aksesproject = $this->Project_model->list_akses_project_nip($nip);
				// $usermobile = $this->Usersmobile_model->user_mobile_limit_fillter($company_id, $project_id, $subproject_id);
			}

		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$data = array();

    foreach($client->result() as $r) {
			  
			  $id_customer = $r->customer_id;
			  $name_cust = $r->customer_name;
			  $address = $r->address;
			  $latitude = $r->latitude;
			  $longitude = $r->longitude;
			  $kota = $r->city_id;
			  $kecamatan = $r->district_id;
			  $createdon = $r->createdon;

				$now = new DateTime(date("Y-m-d"));

			  if(in_array('69',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="'.$this->lang->line('xin_edit').'"><a href="'.site_url().'admin/customers/customer_edit/'.$r->customer_id.'" target="_blank"><button type="button" class="btn icon-btn btn-sm btn-outline-info waves-effect waves-light"><span class="fas fa-pencil-alt"></span></button></a></span>';
			} else {
				$edit = '';
			}

			$combhr = $edit;

		   $data[] = array(
				$combhr,
				$id_customer,
				$name_cust,
				$address,
				$kota,
				$kecamatan,
				$latitude.' '.$longitude,
				$createdon,
				// $this->Xin_model->tgl_indo($end),
		   );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $client->num_rows(),
                 "recordsFiltered" => $client->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
  }


	public function customer_edit() {

		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$id = $this->uri->segment(4);
		// $id = '5700';
		$result = $this->Customers_model->read_single_customer($id);
		// $result = $this->Pkwt_model->read_pkwt_info($id);
		if(is_null($result)){
			redirect('admin/');
		}

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$check_role = $this->Employees_model->read_employee_information($session['user_id']);


		// $emp_info = $this->Employees_model->read_employee_information_nip($result[0]->employee_id);
		// if(!is_null($emp_info)){
		//   $fullname = $emp_info[0]->first_name;
		//   $ibu_kandung = $emp_info[0]->ibu_kandung;
		//   $ktp_no = $emp_info[0]->ktp_no;
		//   $tempat_lahir = $emp_info[0]->tempat_lahir;
		//   $date_of_birth = $emp_info[0]->date_of_birth;
		//   $sub_project = $emp_info[0]->sub_project_id;
		//   $department = $emp_info[0]->department_id;

		//   $kk_no = $emp_info[0]->kk_no;
		//   $alamat_ktp = $emp_info[0]->alamat_ktp;
		//   $alamat_domisili = $emp_info[0]->alamat_domisili;
		//   $npwp_no = $emp_info[0]->npwp_no;
		//   $contact_no = $emp_info[0]->contact_no;
		//   $email = $emp_info[0]->email;
		//   $date_of_joining = $emp_info[0]->date_of_joining;
		//   $gender = $emp_info[0]->gender;
		//   $ethnicity_type = $emp_info[0]->ethnicity_type;
		//   $marital_status = $emp_info[0]->marital_status;

		//   $fullname = $emp_info[0]->first_name;
		//   $fullname = $emp_info[0]->first_name;
		// } else {
		//   $fullname = '--';
		//   $ibu_kandung = '--';
		//   $ktp_no = '--';
		//   $tempat_lahir = '--';
		//   $date_of_birth = '--';

		//   $sub_project = '-';
		//   $department = '-';

		// }


		$data = array(

			'title' => 'Edit Customer/Toko',
			'breadcrumbs' => 'EDIT >> '. $result[0]->customer_id .' - '.strtoupper($result[0]->customer_name),
			// 'path_url' => 'emp_pkwt_edit',
			'path_url' => 'customer_edit',

			'customer_id' => $result[0]->customer_id,
			'customer_name' => strtoupper($result[0]->customer_name),
			'owner_name' => $result[0]->owner_name,
			'address' => $result[0]->address,
			'no_contact' => $result[0]->no_contact,
			'latitude' => $result[0]->latitude,
			'longitude' => $result[0]->longitude,
			'kota' => $result[0]->city_id,
			'kecamatan' => $result[0]->district_id,
			// 'foto_toko' => 'data:image/png;base64,'.$result[0]->photo,
			'foto_toko' => $result[0]->photo,

			// 'project_list' => $this->Project_model->get_project_maping($session['employee_id']),


			);
		
		// if($check_role[0]->user_role_id==1 || $check_role[0]->user_role_id==3 || $check_role[0]->user_role_id==4) {

		// $data['subview'] = $this->load->view("admin/employees/employee_detail", $data, TRUE);
		// } else {

		$data['subview'] = $this->load->view("admin/customers/customer_edit", $data, TRUE);
		// }

		// if($result[0]->user_id == $id) {

		// $data['subview'] = $this->load->view("admin/employees/employee_detail", $data, TRUE);
		// } else {
		// $data['subview'] = $this->load->view("admin/employees/employee_detail", $data, TRUE);
		// }

		$this->load->view('admin/layout/layout_main', $data); //page load
		
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// Validate and add info in database
	public function customer_save() {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		} 

			$idrequest 						= $this->input->post('customer_id');

			// if($this->input->post('add_type')=='company') {
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				// $system = $this->Xin_model->read_setting_info(1);

					if($this->input->post('customer_name')=='') {
						$Return['error'] = 'Nama Toko/Lokasi Kosong';
					} else if ($this->input->post('latitude')==''){
						$Return['error'] = 'Koordinat Latitude tidak ditemukan...';
					} else if ($this->input->post('longitude')==''){
						$Return['error'] = 'Koordinat Longitude tidak ditemukan...';
					} else {

							$idrequest 						= $this->input->post('customer_id');
							$customer_name 						= $this->input->post('customer_name');
							$owner_name 							= $this->input->post('owner_name');
							$kontak_owner 							= $this->input->post('kontak_owner');
							$alamat_toko 							= $this->input->post('alamat_toko');
							$latitude 								= $this->input->post('latitude');
							$longitude 								= $this->input->post('longitude');
							$kota 										= $this->input->post('kota');
							$kecamatan 								= $this->input->post('kecamatan');

						}

					if($Return['error']!=''){
					$this->output($Return);

			    }

							$data = array(

								'customer_name' 					=> $customer_name,
								'owner_name' 							=> $owner_name,
								'no_contact' 							=> $kontak_owner,
								'address' 								=> $alamat_toko,
								'district_id' 						=> $kecamatan,
								'city_id' 								=> $kota,
								'latitude' 								=> $latitude,
								'longitude' 							=> $longitude,
							);

						$iresult = $this->Customers_model->update_toko($data,$idrequest);

					// }

				if ($iresult == TRUE) {
					$Return['result'] = $idrequest.' Perubahan Customer/Toko/Lokasi berhasil..';
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
				// $Return['result'] = $idrequest.' Permintaan Karyawan Baru berhasil di Ubah..';

				$this->output($Return);
				exit;
			// }
	}

} 
?>