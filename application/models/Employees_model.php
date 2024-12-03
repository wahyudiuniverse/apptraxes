<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
 	// get all employes
	public function get_employees() {

		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id not IN (1)';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


	public function get_project_0_mbd_report($postData)
	{
		$this->db->select('*');
		$this->db->from('xin_user_mobile');
		$this->db->where('project_id', $postData['project_id']);

		$query = $this->db->get()->result_array();

		// $query = $this->db->query("SELECT DISTINCT a.`project_id` , b.employee_id FROM `xin_user_mobile` a 
		// LEFT JOIN xin_projects b ON  b.employee_id = a.employee_id
		// WHERE `a.project_id` = '$id' ");

  	  return $query;	
	}


	public function get_user_1_mbd_report($postData)
	{

		$query = $this->db->query("SELECT a.secid,a.customer_id,a.display_date, a.display_foto, a.status_display, a.verify_status, a.verify_by, a.verify_on, a.employee_id  FROM `xin_display_mbd` a 
		LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		WHERE a.customer_id = '$id' 
		ORDER BY a.secid DESC");

  	  	return $query->result_array();


		// $this->db->select('*');
		// $this->db->from('xin_display_mbd');
		// $this->db->where('user_id', $postData['user_id']);

		// $query = $this->db->get()->result_array();

		// // $query = $this->db->query("SELECT DISTINCT a.`project_id` , b.employee_id FROM `xin_user_mobile` a 
		// // LEFT JOIN xin_projects b ON  b.employee_id = a.employee_id
		// // WHERE `a.project_id` = '$id' ");

  	  	// return $query;	
	}

	// //ambil data jabatan berdasarkan sub project untuk Json
	// public function get_user_1_mbd_report($postData)
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('xin_display_mbd');
	// 	$this->db->where('', $postData['project_id']);

	// 	$query = $this->db->get()->result_array();

	// 	return $query;
	// }


	//ambil data jabatan berdasarkan sub project untuk Json
	public function get_employee_by_project($postData)
	{
		$this->db->select('*');
		$this->db->from('xin_user_mobile');
		$this->db->where('project_id', $postData['project_id']);

		$query = $this->db->get()->result_array();

		return $query;
	}

	public function get_employee_by_toko($id)
	{
		$query = $this->db->query("SELECT DISTINCT a.`customer_id` , b.customer_name FROM `xin_display_mbd` a 
		LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		WHERE `employee_id` = '$id' ");

  	  return $query->result_array();	
	}

	public function get_employee_by_toko_detail($id)
	{
		$query = $this->db->query("SELECT a.secid,a.customer_id,a.display_date, a.display_foto, a.status_display, a.verify_status, a.verify_by, a.verify_on, a.employee_id  FROM `xin_display_mbd` a 
		LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		WHERE a.customer_id = '$id' 
		ORDER BY a.secid DESC");

  	  return $query->result_array();
	}

	public function get_employee_by_toko_mbd_report($id)
	{
		$query = $this->db->query("SELECT a.secid,a.customer_id,a.display_date, a.display_foto, a.status_display, a.verify_status, a.verify_by, a.verify_on, a.employee_id  FROM `xin_display_mbd` a 
		LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		WHERE a.employee_id = '$id' 
		ORDER BY a.secid DESC");

  	  return $query->result_array();
	}

	public function get_tanggal_mbd_report() {
		$query = $this->db->query("SELECT a.secid,a.customer_id,a.display_date, a.display_foto, a.status_display, a.verify_status, a.verify_by, a.verify_on, a.employee_id  FROM `xin_display_mbd` a 
		LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		WHERE a.customer_id = '$id' 
        AND DATE_FORMAT(display_date, '%Y-%m-%d')
        BETWEEN '2024-10-19' AND '2024-11-19' ");
	}

	public function get_employee_by_toko_detail_mbd($id)
	{
		$query = $this->db->query("SELECT secid,customer_id,display_date, display_foto, status_display, verify_status, verify_by, verify_on  FROM `xin_display_mbd` WHERE secid = '$id' ");

  	  return $query->row_array();
	}

	public function get_employee_by_toko_detail_mbd_validasi($id)
	{
		$query = $this->db->query("SELECT secid,customer_id,display_date, display_foto, status_display, verify_status, verify_by, verify_on  FROM `xin_display_mbd` WHERE secid = '$id' ");
 
  	  return $query->row_array();
	}

	public function update_verifikasi($secid) {
        // Set nilai kolom status ke 'valid' dan tambahkan timestamp updated_at
		$session = $this->session->userdata('username');

		$this->db->set('verify_by', $session['employee_id']);
        $this->db->set('verify_status', '1');
        $this->db->set('verify_on', date('Y-m-d H:i:s')); // Timestamp otomatis
        $this->db->where('secid', $secid); // Kondisi berdasarkan id_user
        return $this->db->update('xin_display_mbd'); // Nama tabel validasi
    }

	public function update_tolakverifikasi($secid) {
        // Set nilai kolom status ke 'valid' dan tambahkan timestamp updated_at
		$session = $this->session->userdata('username');

		$this->db->set('verify_by', $session['employee_id']);
        $this->db->set('verify_status', '0');
        $this->db->set('verify_on', date('Y-m-d H:i:s')); // Timestamp otomatis
        $this->db->where('secid', $secid); // Kondisi berdasarkan id_user
        return $this->db->update('xin_display_mbd'); // Nama tabel validasi
    }

	public function get_toko() {

		$sql = 'SELECT emp.user_id, emp.employee_id, emp.ktp_no, emp.first_name, emp.project_id, pro.title, emp.last_login_date,
				emp.designation_id, pos.designation_name, emp.penempatan, emp.contact_no, emp.date_of_birth, emp.user_role_id
				FROM xin_user_mobile emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
				LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id
				WHERE emp.employee_id not IN (1)';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


	public function get_toko_by_user($id)
	{
		$query = $this->db->query("SELECT distinct a.customer_id,a.employee_id  FROM `xin_display_mbd` a 
		WHERE a.employee_id = '$id' 
		ORDER BY a.secid DESC");

		// $query = $this->db->query("SELECT distinct a.customer_id,b.customer_name,a.employee_id  FROM `xin_display_mbd` a 
		// LEFT JOIN xin_customer b ON  b.customer_id = a.customer_id
		// WHERE a.employee_id = '$id' 
		// ORDER BY a.secid DESC");

  	  return $query->result_array();
	}

	// get data table List MBD Report
	public function get_datatable_mbd_report() {
		$query = $this->db->query("SELECT secid,customer_id,display_date, display_foto, status_display, verify_status, verify_by, verify_on, project_id  FROM `xin_display_mbd` WHERE secid = '$id' ");

  	  	return $query->row_array();
	}


	//ambil nama karyawan
	function get_nama_karyawan_by_nip($nip)
	{
		if ($nip == null) {
			return "";
		} else if ($nip == 0) {
			return "";
		} else {
			$this->db->select('fullname');
			$this->db->from('xin_user_mobile');
			$this->db->where('employee_id', $nip);

			$query = $this->db->get()->row_array();

			if (empty($query)) {
				return "";
			} else {
				return $query['fullname'];
			}
			//return $query['title'];
		}
	}

	//ambil jabatan karyawan
	function get_jabatan_by_nip($nip)
	{
		if ($nip == null) {
			return "";
		} else if ($nip == 0) {
			return "";
		} else {
			$this->db->select('jabatan');
			$this->db->from('xin_user_mobile');
			$this->db->where('employee_id', $nip);

			$query = $this->db->get()->row_array();

			if (empty($query)) {
				return "";
			} else {
				return $query['jabatan'];
			}
			//return $query['title'];
		}
	}

	//ambil jabatan karyawan
	function get_customername_by_customerid($nip)
	{
		if ($nip == null) {
			return "";
		} else if ($nip == 0) {
			return "";
		} else {
			$this->db->select('customer_name');
			$this->db->from('xin_customer');
			$this->db->where('customer_id', $nip);

			$query = $this->db->get()->row_array();

			if (empty($query)) {
				return "";
			} else {
				return $query['customer_name'];
			}
			//return $query['title'];
		}
	}

	//ambil jabatan karyawan
	function get_area_by_employeeid($nip)
	{
		if ($nip == null) {
			return "";
		} else if ($nip == 0) {
			return "";
		} else {
			$this->db->select('penempatan');
			$this->db->from('xin_user_mobile');
			$this->db->where('employee_id', $nip);

			$query = $this->db->get()->row_array();

			if (empty($query)) {
				return "";
			} else {
				return $query['penempatan'];
			}
			//return $query['title'];
		}
	}

	//ambil jabatan karyawan
	function get_title_by_project($nip)
	{
		if ($nip == null) {
			return "";
		} else if ($nip == 0) {
			return "";
		} else {
			$this->db->select('title');
			$this->db->from('xin_projects');
			$this->db->where('project_id', $nip);

			$query = $this->db->get()->row_array();

			if (empty($query)) {
				return "";
			} else {
				return $query['title'];
			}
			//return $query['title'];
		}
	}


	// saltab_datatable_List_MBD_Report
	function get_list_mbd_report($postData = null)
	{
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$response = array();

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value

		//variabel filter (diambil dari post ajax di view)
		$project_id = $postData['project_id'];
		$employee_id = $postData['employee_id'];
		$toko_id = $postData['toko_id'];
		$start_date = $postData['start_date'];
		$end_date = $postData['end_date'];

		## Search 
		$searchQuery = "";
		if ($searchValue != '') {
			$searchQuery = " (project_id like '%" . $searchValue .  "%' or customer_id like '%" . $searchValue . "%') ";
		}

		## Filter
		$filterProject = "";
		if (($project_id != null) && ($project_id != "") && ($project_id != '0')) {
			$filterProject = "(
				project_id = " . $project_id . "
			)";
		} else {
			$filterProject = "";
		}
		$filterEmployee = "";
		if (($employee_id != null) && ($employee_id != "") && ($employee_id != '0')) {
			$filterEmployee = "(
				employee_id = '" . $employee_id . "'
			)";
		} else {
			$filterEmployee = "";
		}
		$filterToko = "";
		if (($toko_id != null) && ($toko_id != "") && ($toko_id != '0')) {
			$filterToko = "(
				customer_id = '" . $toko_id . "'
			)";
		} else {
			$filterToko = "";
		}
		$filterStartDate = "";
		if (($start_date != null) && ($start_date != "") && ($start_date != '0')) {
			$filterStartDate = "(
				display_date >= '" . $start_date . "'
			)";
		} else {
			$filterStartDate = "";
		}
		$filterEndDate = "";
		if (($end_date != null) && ($end_date != "") && ($end_date != '0')) {
			$filterEndDate = "(
				display_date <= '" . $end_date . "'
			)";
		} else {
			$filterEndDate = "";
		}
		## Kondisi Default 
		// $kondisiDefaultQuery = "project_id in (SELECT project_id FROM xin_projects_akses WHERE nip = " . $session_id . ")";
		
		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		if ($filterProject != '') {
			$this->db->where($filterProject);
		}
		if ($filterEmployee != '') {
			$this->db->where($filterEmployee);
		}
		if ($filterToko != '') {
			$this->db->where($filterToko);
		}
		if ($filterStartDate != '') {
			$this->db->where($filterStartDate);
		}
		if ($filterEndDate != '') {
			$this->db->where($filterEndDate);
		}
		// $this->db->where($kondisiDefaultQuery);
		$records = $this->db->get('xin_display_mbd')->result();
		$totalRecords = $records[0]->allcount;
		#Debugging variable
		// $tes_query = $this->db->last_query();
		//print_r($tes_query);

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		// $this->db->where($kondisiDefaultQuery);
		if ($searchQuery != '') {
			$this->db->where($searchQuery);
		}
		if ($filterProject != '') {
			$this->db->where($filterProject);
		}
		if ($filterEmployee != '') {
			$this->db->where($filterEmployee);
		}
		if ($filterToko != '') {
			$this->db->where($filterToko);
		}
		if ($filterStartDate != '') {
			$this->db->where($filterStartDate);
		}
		if ($filterEndDate != '') {
			$this->db->where($filterEndDate);
		}
		$records = $this->db->get('xin_display_mbd')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('*');
		// $this->db->where($kondisiDefaultQuery);
		if ($searchQuery != '') {
			$this->db->where($searchQuery);
		}
		if ($filterProject != '') {
			$this->db->where($filterProject);
		}
		if ($filterEmployee != '') {
			$this->db->where($filterEmployee);
		}
		if ($filterToko != '') {
			$this->db->where($filterToko);
		}
		if ($filterStartDate != '') {
			$this->db->where($filterStartDate);
		}
		if ($filterEndDate != '') {
			$this->db->where($filterEndDate);
		}
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('xin_display_mbd')->result();

		$data = array();
		foreach ($records as $record) {
			// $nama_download_bpjs = "";
			// if (empty($record->down_bpjs_by) || ($record->down_bpjs_by == "")) {
			// 	$nama_download_bpjs = "";
			// } else {
			// 	$nama_download_bpjs = "<BR> <span style='color:#3F72D5;'>" . $this->Employees_model->get_nama_karyawan_by_nip($record->down_bpjs_by) . ' [' . $this->Xin_model->tgl_indo($record->down_bpjs_on) . ']' . "</span>";
			// }

			// $periode_salary = "";
			// if (empty($record->periode_salary) || ($record->periode_salary == "")) {
			// 	$periode_salary = "--";
			// } else {
			// 	$periode_salary = $this->Xin_model->tgl_indo($record->periode_salary) . $nama_download_bpjs;
			// }
			// if (empty($record->eslip_release) || ($record->eslip_release == "")) {
			// 	$eslip_release = "";
			// } else {
			// 	$eslip_release = "<p>&#x2705;</p>";
			// }
			// $text_periode_from = "";
			// $text_periode_to = "";
			// $text_periode = "";
			// if (empty($record->periode_cutoff_from) || ($record->periode_cutoff_from == "")) {
			// 	$text_periode_from = "";
			// } else {
			// 	$text_periode_from = $this->Xin_model->tgl_indo($record->periode_cutoff_from);
			// }
			// if (empty($record->periode_cutoff_to) || ($record->periode_cutoff_to == "")) {
			// 	$text_periode_to = "";
			// } else {
			// 	$text_periode_to = $this->Xin_model->tgl_indo($record->periode_cutoff_to);
			// }
			// if (($text_periode_from == "") && ($text_periode_to == "")) {
			// 	$text_periode = "";
			// } else {
			// 	$text_periode = $text_periode_from . " s/d " . $text_periode_to;
			// }
			// cek apakah sudah release eslip
			$release_eslip = "";
			if (empty($record->status_display) || ($record->status_display == "") || ($record->status_display == "0")) {
				$release_eslip = "<span style='color:#FF0000;'>Belum Verifikasi</span>";
			} else {
				$release_eslip = "<span style='color:#0000FF;'>Sudah Verifikasi</span>";
			}
			// $addendum_id = $this->secure->encrypt_url($record->id);
			// $addendum_id_encrypt = strtr($addendum_id, array('+' => '.', '=' => '-', '/' => '~'));
			// $view = '<button id="tesbutton" type="button" onclick="lihatBatchSaltabRelease(' . $record->id . ')" class="btn btn-block btn-sm btn-outline-twitter" >VIEW</button>';
			// $download_nip_kosong = '<button type="button" onclick="downloadBatchSaltabReleaseNIPKosong(' . $record->id . ')" class=" btn-block btn-sm btn-outline-success" ><i class="fa fa-download"></i> NIP Kosong</button>';
			// $download_raw = '<button type="button" onclick="downloadBatchSaltabRelease(' . $record->id . ')" class=" btn-block btn-sm btn-outline-success" ><i class="fa fa-download"></i> Raw Data</button>';
			// $download_BPJS = '<button type="button" onclick="downloadBatchSaltabReleaseBPJS(' . $record->id . ')" class="btn btn-block btn-sm btn-outline-success" ><i class="fa fa-download"></i> Data BPJS</button>';
			// $download_Payroll = '<button type="button" onclick="downloadBatchSaltabReleasePayroll(' . $record->id . ')" class="btn btn-block btn-sm btn-outline-success" ><i class="fa fa-download"></i> Data Payroll</button>';
			// $delete = '<button type="button" onclick="deleteBatchSaltabRelease(' . $record->id . ')" class="btn btn-block btn-sm btn-outline-danger" >DELETE</button>';
			// $button_download = '<div class="btn-group mt-2">
      		// 	<button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-toggle="dropdown">
      		// 		DOWNLOAD <span class="caret"></span></button>
      		// 		<ul class="dropdown-menu" role="menu" style="width: 100px;background-color:#faf7f0;">
			// 		<span style="color:#3F72D5;">DOWNLOAD OPTION:</span>
        	// 			<li class="mb-1">' . $download_nip_kosong . '</li>
        	// 			<li class="mb-1">' . $download_raw . '</li>
			// 			<li class="mb-1">' . $download_BPJS . '</li>
			// 			<li class="mb-1">' . $download_Payroll . '</li>
      		// 		</ul>
    		// </div>';
			// $teslinkview = 'type="button" onclick="lihatAddendum(' . $addendum_id_encrypt . ')" class="btn btn-xs btn-outline-twitter" >VIEW</button>';
			$data[] = array(
				"secid" => $record->secid, 
				"display_date" => $record->display_date,
				"title" => $this->get_title_by_project($record->project_id),
				"employee_id" => $record->employee_id,
				"nama_karyawan" => $this->get_nama_karyawan_by_nip($record->employee_id),
				"jabatan" => $this->get_jabatan_by_nip($record->employee_id),
				"id_toko" => $record->customer_id,
				"nama_toko" => $this->get_customername_by_customerid($record->customer_id),
				"area_penempatan" => $this->get_area_by_employeeid($record->employee_id),
				// "foto" => "<img src='" . base_url() . $record->display_foto . "'>",
				"foto" => "<img width='50px' heigth='50px' src='" . "https://api.traxes.id/" . $record->display_foto . "'>",
				// "foto <img src="base_url() . $display_foto">"
				"status_verfikasi" => $release_eslip,	
				// "status_verfikasi" => "<span style='color:#3F72D5" . $record->status_display . "'>",
			);	
		}
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		//print_r($this->db->last_query());
		//die;
		return $response;
	}

	public function get_employees_all() {

		$sql = 'SELECT emp.user_id, emp.employee_id, emp.ktp_no, emp.first_name, emp.project_id, pro.title, emp.last_login_date,
				emp.designation_id, pos.designation_name, emp.penempatan, emp.contact_no, emp.date_of_birth, emp.user_role_id
				FROM xin_user_mobile emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
				LEFT JOIN xin_designations pos ON pos.designation_id = emp.
				designation_id
				WHERE emp.employee_id not IN (1)';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

	public function get_employees_notho() {

		$sql = "SELECT emp.user_id, emp.employee_id, emp.ktp_no, emp.first_name, emp.project_id, pro.title,
				emp.designation_id, pos.designation_name, emp.penempatan, emp.contact_no, emp.date_of_birth, emp.user_role_id, emp.last_login_date
				FROM xin_user_mobile emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
				LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id
				WHERE emp.employee_id not IN (1)
				AND emp.project_id not in (22)";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


	public function get_employees_who() {

		$sql = "SELECT emp.user_id, emp.employee_id, emp.ktp_no, emp.first_name, emp.project_id, pro.title,
				emp.designation_id, pos.designation_name, emp.penempatan, emp.contact_no, emp.date_of_birth, emp.user_role_id, emp.last_login_date
				FROM xin_user_mobile emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
				LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id
				WHERE emp.employee_id not IN (1)";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

	public function get_all_employees_all()
	{
	  $query = $this->db->query("SELECT user_id, employee_id, CONCAT( employee_id, '-', first_name) AS fullname, date_of_leaving,month(date_of_leaving) bln_skrng
		FROM xin_user_mobile 
		WHERE is_active = 1 
		AND  status_resign = 1
		AND employee_id not IN (SELECT 1 AS nip FROM DUAL)
		AND project_id = 50
		ORDER BY date_of_leaving DESC;");
  	  return $query->result();
	}


	public function get_all_employees_byproject($id)
	{
	  $query = $this->db->query("SELECT user_id, employee_id, CONCAT( employee_id, '-', first_name) AS fullname, project_id, date_of_leaving,month(date_of_leaving) bln_skrng
		FROM xin_user_mobile 
		WHERE is_active = 1 
		AND status_resign = 1
		AND employee_id not IN (SELECT 1 AS nip FROM DUAL
			UNION
			SELECT employee_id AS nip FROM xin_employee_contract WHERE status_pkwt = 1)
		AND project_id = '$id'
		ORDER BY date_of_leaving DESC;");
  	  return $query->result();
	}


	public function get_empdeactive_byproject($id)
	{
	  $query = $this->db->query("SELECT user_id, employee_id, CONCAT( employee_id, '-', first_name) AS fullname, project_id, date_of_leaving,month(date_of_leaving) bln_skrng
		FROM xin_user_mobile 
		WHERE is_active = 1 
		AND status_resign in (2,3,4,5)
		AND project_id = '$id'
		ORDER BY date_of_leaving DESC;");
  	  return $query->result();
	}

		// AND employee_id not IN (SELECT 1 AS nip FROM DUAL
		// 	UNION
		// 	SELECT employee_id AS nip FROM xin_employee_contract WHERE status_pkwt in (0,1))


	public function get_all_employees_byposisi($id)
	{
	  $query = $this->db->query("SELECT posisi_jabatan FROM xin_employee_ratecard WHERE project_id = '19' AND status_ratecard = '1';");
  	  return $query->result();
	}

	public function get_all_employees_byarea($id)
	{
	  $query = $this->db->query("SELECT distinct(kota) as area FROM xin_employee_ratecard WHERE project_id = '19' AND status_ratecard = '1';");
  	  return $query->result();
	}

	public function get_all_employees_project()
	{
	  $query = $this->db->query("SELECT user_id, employee_id, CONCAT( employee_id, '-', first_name) AS fullname, date_of_leaving,month(date_of_leaving) bln_skrng
		FROM xin_user_mobile 
		WHERE is_active = 1 
		AND  status_resign = 1
		AND project_id NOT IN (22)
		AND employee_id not IN (SELECT 1 AS nip FROM DUAL)
		ORDER BY date_of_leaving DESC;");
  	  return $query->result();
	}

 	// monitoring request
	public function get_monitoring_daftar_ho() {

		$sql = "SELECT *
				FROM xin_employee_request
				WHERE datediff(current_date(),DATE_FORMAT(createdon, '%Y-%m-%d')) <=20
				AND request_empby = '1'
				AND project = '22'
				AND e_status = '0'
				ORDER BY secid DESC";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_daftar() {

		$sql = "SELECT emp.fullname,emp.contact_no,emp.project,emp.createdon,pro.title
				FROM xin_employee_request emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project
				WHERE datediff(current_date(),DATE_FORMAT(emp.createdon, '%Y-%m-%d')) <=10
				AND emp.request_empby = '1'
				AND e_status = '0'
				ORDER BY secid DESC";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_daftar_tkhl() {

		$sql = "SELECT emp.fullname,emp.contact_no,emp.project,emp.createdon,pro.title
				FROM xin_employee_request emp
				LEFT JOIN xin_projects pro ON pro.project_id = emp.project
				WHERE datediff(current_date(),DATE_FORMAT(emp.createdon, '%Y-%m-%d')) <=10
				AND emp.request_empby = '1'
				AND e_status = '1'
				ORDER BY secid DESC";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_daftarnik($nik_ktp) {

		$sql = "SELECT *
				FROM xin_employee_request
				WHERE datediff(current_date(),DATE_FORMAT(createdon, '%Y-%m-%d')) <=20
				AND e_status = '0'
				ORDER BY secid DESC";	
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}
 	// monitoring request
	public function get_monitoring_request($empID) {

		$sql = 'SELECT *
				FROM xin_employee_request
				WHERE datediff(current_date(),DATE_FORMAT(createdon, "%Y-%m-%d")) <=20
				AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = "$empID")
				ORDER BY secid DESC';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_request_cancel($empID) {

		$sql = "SELECT * FROM xin_employee_request 
		WHERE cancel_stat = 1
		AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID');";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_request_nae($empID) {

		$sql = "SELECT * 
			FROM xin_employee_request 
			WHERE request_empby is not null 
			AND approved_naeby is null 
			AND approved_nomby is null 
	        AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')
	        AND cancel_stat = 0
        	AND e_status = 0
			ORDER BY secid DESC";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_request_nom($empID) {

		$sql = "SELECT * FROM xin_employee_request 
		WHERE request_empby is not null 
		AND approved_naeby is not null
		AND approved_nomby is null
		AND approved_hrdby is null
		AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')
		AND cancel_stat = 0
        AND e_status = 0";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_request_hrd($empID) {

		$sql = "SELECT * FROM xin_employee_request 
		WHERE request_empby is not null 
		AND approved_naeby is not null
		AND approved_nomby is not null
		AND approved_hrdby is null
		AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')
		AND cancel_stat = 0
        AND e_status = 0";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


 	// monitoring request
	public function get_request_tkhl($empID) {

		$sql = "SELECT * FROM xin_employee_request 
		WHERE request_empby is not null 
		AND approved_naeby is not null
		AND approved_nomby is not null
		AND approved_hrdby is null
		AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')
		AND cancel_stat = 0
        AND e_status = 1";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_request_approve($empID) {


		$sql = "SELECT secid,nik_ktp,fullname,location_id,project,sub_project,department,posisi,penempatan,doj,contact_no,approved_naeby,approved_nomby,approved_hrdby,cancel_stat
				FROM xin_employee_request
				WHERE datediff(current_date(),DATE_FORMAT(createdon, '%Y-%m-%d')) <=30
				AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')
				ORDER BY createdon DESC";

		// $sql = "SELECT * FROM xin_employee_request 
		// WHERE request_empby is not null 
		// AND approved_naeby is not null
		// AND approved_nomby is not null
		// AND approved_hrdby is not null
		// AND project in (SELECT project_id FROM xin_projects_akses WHERE nip = '$empID')";

		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by is not null
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign_cancel() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")	
		-- AND approve_resignnae NOT IN ("NULL","0")
		-- AND approve_resignnom NOT IN ("NULL","0")
		AND approve_resignhrd IS NULL
		AND cancel_resign_stat = 1
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign_nae() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")		
		AND approve_resignnae IS NULL
		AND approve_resignnom IS NULL
		AND cancel_resign_stat = 0
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign_nom() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")
		AND approve_resignnae NOT IN ("NULL","0")
		AND approve_resignnom IS NULL
		-- AND project_id NOT IN (22)
		AND cancel_resign_stat = 0
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


 	// monitoring request
	public function get_monitoring_rsign_ho() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")
		AND approve_resignnae NOT IN ("NULL","0")
		AND approve_resignnom IS NULL
		-- AND project_id = 22
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign_hrd() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")
		AND approve_resignnae NOT IN ("NULL","0")
		AND approve_resignnom NOT IN ("NULL","0")
		AND approve_resignhrd IS NULL
		AND cancel_resign_stat = 0
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_monitoring_rsign_history() {

		$sql = 'SELECT *
		FROM xin_user_mobile
		WHERE request_resign_by NOT IN ("NULL","0")
		AND approve_resignnae NOT IN ("NULL","0")
		AND approve_resignnom NOT IN ("NULL","0")
		AND approve_resignhrd IS NOT NULL
		ORDER BY request_resign_date DESC;';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// get all employes
	public function get_employees_request() {

		$sql = 'SELECT * FROM xin_employee_request WHERE verified_by is null';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


 	// get all employes
	public function default_list() {

		$sql = "SELECT '2' FROM DUAL;";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// get all employes
	public function get_employees_request_verify() {

		$sql = 'SELECT * FROM xin_employee_request WHERE verified_by is not null AND approved_by is null';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// get all employes
	public function get_employees_request_approve() {

		$sql = 'SELECT *
				FROM xin_employee_request
				WHERE datediff(current_date(),DATE_FORMAT(createdon, "%Y-%m-%d")) <=30
				AND migrasi = 1
				ORDER BY modifiedon DESC';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// get all employes
	public function get_employees_master() {

		$sql = 'SELECT * FROM xin_user_mobile WHERE e_status = 1';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}


	// get single employee
	public function read_employee_info($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee
	public function checknik($nik) {
	
		$sql = 'SELECT max(date_of_joining), emp.employee_id, emp.first_name, emp.designation_id, pos.designation_name, emp.project_id,pro.priority, pro.title, emp.company_id,emp.ktp_no, emp.status_resign
FROM xin_user_mobile emp
LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id
LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
WHERE ktp_no = ?';
		$binds = array($nik);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIP
	public function read_employee_info_by_nik($id) {
	
		$sql = 'SELECT * FROM xin_employees WHERE employee_id = ? AND user_id not in (1)';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIK KTP
	public function read_employee_info_by_nik_ktp($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE ktp_no = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee request
	public function read_employee_request($id) {
	
		$sql = "SELECT * FROM xin_employee_request WHERE secid = ?";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// get single employee request
	public function read_employee_expired($id) {
	
		$sql = "SELECT * FROM xin_user_mobile WHERE employee_id = ?";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIP
	public function read_eslip_info_by_nip_periode($id, $periode) {
	
		$sql = 'SELECT * FROM xin_user_mobile_eslip WHERE nip = ? and periode = ?';
		$binds = array($id, $periode);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIP
	public function read_eslip_info_by_id($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile_eslip WHERE secid = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIP
	public function read_eslip_by_nip($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ? ORDER BY user_id DESC LIMIT 6';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return null;
		}
	}

	// get all my team employes > not super admin
	public function get_employees_my_team($cid) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id != ? and reports_to = ?' ;
		$binds = array(1,$cid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all employes > not super admin
	public function get_employees_for_other($cid) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id != ? and company_id = ?';
		$binds = array(1,$cid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all employes > not super admin
	public function get_employees_for_location($cid) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id != ? and location_id = ?';
		$binds = array(1,$cid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}


	// get all employes|company>
	public function get_company_employees_flt($cid) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ?';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all MY TEAM employes
	public function get_my_team_employees($reports_to) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE reports_to = ?';
		$binds = array($reports_to);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}

		// get all employes temporary
	public function get_employees_temp($importid) {
		
		$sql = 'SELECT * FROM xin_user_mobile_temp WHERE uploadid = ?';
		$binds = array($importid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}

	// get all employes>company|location >
	public function get_company_location_employees_flt($cid,$lid) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id = ?';
		$binds = array($cid,$lid);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all employes>company|location|department >
	public function get_company_location_department_employees_flt($cid,$lid,$dep_id) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id = ? and department_id = ?';
		$binds = array($cid,$lid,$dep_id);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all employes>company|location|department|designation >
	public function get_company_location_department_designation_employees_flt($cid,$lid,$dep_id,$des_id) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ?';
		$binds = array($cid,$lid,$dep_id,$des_id);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	// get all employes >
	public function get_employees_payslip() {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_role_id != ?';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);
	    return $query;
	}
	
	// get employes
	public function get_attendance_employees() {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE is_active = ?';
		$binds = array(1);
		$query = $this->db->query($sql, $binds);
		
	    return $query;
	}
	// get employes with location
	public function get_attendance_location_employees($location_id) {
		
		$sql = 'SELECT * FROM xin_user_mobile WHERE location_id = ? and is_active = ?';
		$binds = array($location_id,1);
		$query = $this->db->query($sql, $binds);
		
	    return $query;
	}
	


	// get single record > company | locations
	 public function ajax_project_sub($id) {
	
		$sql = 'SELECT * FROM xin_projects_sub WHERE id_project = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// get single record > company | locations
	 public function ajax_project_posisi($id) {
	
		$sql = "SELECT pos.posisi, jab.designation_name FROM xin_projects_posisi pos
LEFT JOIN xin_designations jab ON jab.designation_id = pos.posisi
WHERE pos.project_id = ?
ORDER BY jab.designation_id ASC";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get total number of employees
	public function get_total_employees() {
	  $query = $this->db->get("xin_user_mobile");
	  return $query->num_rows();
	}
		 
	 public function read_employee_information($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	 public function read_employee_information_nip($nip) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($nip);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	 public function read_employee_nae($id) {
	
		$sql = "SELECT * FROM xin_user_mobile WHERE sub_project_id = '1' AND user_id = ?";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single employee by NIP
	public function read_employee_jabatan($id) {
	
		$sql = 'SELECT emp.employee_id, emp.first_name, emp.designation_id, pos.designation_name FROM xin_user_mobile emp LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id WHERE emp.employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	 public function CheckExistNIK($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	 public function CheckExistNIP_Periode($id, $periode) {
	
		$sql = 'SELECT * FROM xin_user_mobile_saltab WHERE nip = ? and periode = ?';
		$binds = array($id,$periode);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function CheckExistNIP_esaltab($id, $periode, $project) {
	
		$sql = 'SELECT * FROM xin_user_mobile_saltab WHERE fullname = ? and periode = ? and project = ?';
		$binds = array($id,$periode,$project);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function CheckExistNIP($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// check employeeID
	public function check_employee_id($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}	

	// check employeeID
	public function check_usermobile($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}	

	// check old password
	public function check_old_password($old_password,$user_id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id = ?';
		$binds = array($user_id);
		$query = $this->db->query($sql, $binds);
		//$rw_password = $query->result();
		$options = array('cost' => 12);
		$password_hash = password_hash($old_password, PASSWORD_BCRYPT, $options);
		if ($query->num_rows() > 0) {
			$rw_password = $query->result();
			if(password_verify($old_password,$rw_password[0]->password)){
				return 1;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// check username
	public function check_employee_username($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE username = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// check email
	public function check_employee_email($id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE email = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// check email
	public function check_employee_pincode($pincode) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE pincode = ?';
		$binds = array($pincode);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_user_mobile', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function addeslip($data){
		$this->db->insert('xin_user_mobile_eslip', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function addtemp($data){
		$this->db->insert('xin_user_mobile_temp', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function addkandidat($data){
		$this->db->insert('xin_employee_request', $data);
		// $this->db->insert('xin_employee_kandidat', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}


	// Function to add record in table
	public function addrequest($data){
		$this->db->insert('xin_employee_request', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to add record in table
	public function request_resign($data, $id){

		$this->db->where('employee_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}	
	}

	// Import try
	public function add_marital($data){
		$this->db->insert('mt_marital', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('user_id', $id);
		$this->db->delete('xin_user_mobile');
		
	}
	
	// Function to Delete selected record from table
	public function delete_temp_by_employeeid(){
		$this->db->where('employee_id', 'nip');
		$this->db->delete('xin_user_mobile_temp');
		
	}
	/*  Update Employee Record */
	
	// Function to Delete selected record from table
	public function delete_new_emp($id){
		$this->db->where('secid', $id);
		$this->db->delete('xin_employee_request');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table
	public function update_record_bynip($data, $id){
		$this->db->where('employee_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	
	// Function to update record in table > basic_info
	public function basic_info($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > basic_info
	public function update_error_temp($data, $id){
		$this->db->where('secid', $id);
		if( $this->db->update('xin_user_mobile_temp',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > basic_info
	public function update_error_eslip_temp($data, $id){
		$this->db->where('secid', $id);
		if( $this->db->update('xin_user_mobile_eslip_temp',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to update record in table > change_password
	public function change_password($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > social_info
	public function social_info($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > profile picture
	public function profile_picture($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}
		// Function to update record in table
	public function update_request_employee($data, $id){
		$this->db->where('secid', $id);
		if( $this->db->update('xin_employee_request',$data)) {
			return true;
		} else {
			return false;
		}		
	}

		// Function to update record in table
	public function save_pkwt_expired($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}

		// Function to update record in table
	public function update_resign_apnae($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}

		// Function to update record in table
	public function update_resign_apnom($data, $id){
		$this->db->where('user_id', $id);
		if( $this->db->update('xin_user_mobile',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to add record in table > contact_info
	public function contact_info_add($data){
		$this->db->insert('xin_employee_contacts', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > contact_info
	public function contact_info_update($data, $id){
		$this->db->where('contact_id', $id);
		if( $this->db->update('xin_employee_contacts',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > document_info_update
	public function document_info_update($data, $id){
		$this->db->where('document_id', $id);
		if( $this->db->update('xin_employee_documents',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > document_info_update
	public function img_document_info_update($data, $id){
		$this->db->where('immigration_id', $id);
		if( $this->db->update('xin_employee_immigration',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > document info
	public function document_info_add($data){
		$this->db->insert('xin_employee_documents', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > document info
	public function document_pkwt_add($data){
		$this->db->insert('xin_employee_contract_pdf', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}

	// Function to add record in table > immigration info
	public function immigration_info_add($data){
		$this->db->insert('xin_employee_immigration', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	
	// Function to add record in table > qualification_info_add
	public function qualification_info_add($data){
		$this->db->insert('xin_employee_qualification', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > qualification_info_update
	public function qualification_info_update($data, $id){
		$this->db->where('qualification_id', $id);
		if( $this->db->update('xin_employee_qualification',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > work_experience_info_add
	public function work_experience_info_add($data){
		$this->db->insert('xin_employee_work_experience', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > work_experience_info_update
	public function work_experience_info_update($data, $id){
		$this->db->where('work_experience_id', $id);
		if( $this->db->update('xin_employee_work_experience',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > bank_account_info_add
	public function bank_account_info_add($data){
		$this->db->insert('xin_employee_bankaccount', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to add record in table > security level info_add
	public function security_level_info_add($data){
		$this->db->insert('xin_employee_security_level', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > bank_account_info_update
	public function bank_account_info_update($data, $id){
		$this->db->where('bankaccount_id', $id);
		if( $this->db->update('xin_employee_bankaccount',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table > security_level_info_update
	public function security_level_info_update($data, $id){
		$this->db->where('security_level_id', $id);
		if( $this->db->update('xin_employee_security_level',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > contract_info_add
	public function contract_info_add($data){
		$this->db->insert('xin_employee_contract', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	//for current contact > employee
	public function check_employee_contact_current($id) {
	   
	    $sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
		$binds = array($id,'current');
		$query = $this->db->query($sql, $binds);
	
		return $query;		
	}
	
	//for permanent contact > employee
	public function check_employee_contact_permanent($id) {
	
		$sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
		$binds = array($id,'permanent');
		$query = $this->db->query($sql, $binds);
		
		return $query;		
	}
	
	// get current contacts by id
	 public function read_contact_info_current($id) {
	
		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
		$binds = array($id,'current');
		$query = $this->db->query($sql, $binds);
	
		$row = $query->row();
		return $row;
		
	}
	
	// get permanent contacts by id
	 public function read_contact_info_permanent($id) {
	
		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
		$binds = array($id,'permanent');
		$query = $this->db->query($sql, $binds);
		
		$row = $query->row();
		return $row;
	}
	
	// Function to update record in table > contract_info_update
	public function contract_info_update($data, $id){
		$this->db->where('contract_id', $id);
		if( $this->db->update('xin_employee_contract',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > leave_info_add
	public function leave_info_add($data){
		$this->db->insert('xin_employee_leave', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table > leave_info_update
	public function leave_info_update($data, $id){
		$this->db->where('leave_id', $id);
		if( $this->db->update('xin_employee_leave',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > shift_info_add
	public function shift_info_add($data){
		$this->db->insert('xin_employee_shift', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > shift_info_update
	public function shift_info_update($data, $id){
		$this->db->where('emp_shift_id', $id);
		if( $this->db->update('xin_employee_shift',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to add record in table > location_info_add
	public function location_info_add($data){
		$this->db->insert('xin_employee_location', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table > location_info_update
	public function location_info_update($data, $id){
		$this->db->where('office_location_id', $id);
		if( $this->db->update('xin_employee_location',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all office shifts 
	public function all_office_shifts() {
	  $query = $this->db->query("SELECT * from xin_office_shift");
  	  return $query->result();
	}
		
	// get contacts
	public function set_employee_contacts($id) {
		
		$sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	    return $query;
	}
	
	// get documents
	public function set_employee_documents($id) {
	
		$sql = 'SELECT * FROM xin_employee_documents WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	    return $query;
	}
	
	// get documents
	public function get_documents_expired_all() {
			
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_documents where date_of_expiry < '".$curr_date."' ORDER BY `date_of_expiry` asc");
  	  	return $query;
	}
	// user/
	public function get_user_documents_expired_all($employee_id) {
			
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_documents where employee_id = '".$employee_id."' and date_of_expiry < '".$curr_date."' ORDER BY `date_of_expiry` asc");
  	  	return $query;
	}
	// get immigration documents
	public function get_img_documents_expired_all() {
			
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_immigration where expiry_date < '".$curr_date."' ORDER BY `expiry_date` asc");
  	  	return $query;
	}
	//user // get immigration documents
	public function get_user_img_documents_expired_all($employee_id) {
			
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_employee_immigration where employee_id = '".$employee_id."' and expiry_date < '".$curr_date."' ORDER BY `expiry_date` asc");
  	  	return $query;
	}
	public function company_license_expired_all() {
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_company_documents where expiry_date < '".$curr_date."' ORDER BY `expiry_date` asc");
  	  	return $query;
	}
	public function get_company_license_expired($company_id) {
	
		$curr_date = date('Y-m-d');
		$sql = "SELECT * FROM xin_company_documents WHERE expiry_date < '".$curr_date."' and company_id = ?";
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// assets warranty all
	public function warranty_assets_expired_all() {
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where warranty_end_date < '".$curr_date."' ORDER BY `warranty_end_date` asc");
  	  	return $query;
	}
	// user assets warranty all
	public function user_warranty_assets_expired_all($employee_id) {
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where employee_id = '".$employee_id."' and warranty_end_date < '".$curr_date."' ORDER BY `warranty_end_date` asc");
  	  	return $query;
	}
	// company assets warranty all
	public function company_warranty_assets_expired_all($company_id) {
		$curr_date = date('Y-m-d');
		$query = $this->db->query("SELECT * from xin_assets where company_id = '".$company_id."' and warranty_end_date < '".$curr_date."' ORDER BY `warranty_end_date` asc");
  	  	return $query;
	}
	// get immigration
	public function set_employee_immigration($id) {
	
		$sql = 'SELECT * FROM xin_employee_immigration WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	 	return $query;
	}
	
	// get employee qualification
	public function set_employee_qualification($id) {
	
		$sql = 'SELECT * FROM xin_employee_qualification WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	    return $query;
	}
	
	// get employee work experience
	public function set_employee_experience($id) {
	
		$sql = 'SELECT * FROM xin_employee_work_experience WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

	    return $query;
	}
	
	// get employee bank account
	public function set_employee_bank_account($id) {
	
		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee bank account
	public function set_employee_security_level($id) {
	
		$sql = 'SELECT * FROM xin_employee_security_level WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee bank account > Last
	 public function get_employee_bank_account_last($id) {
	
		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee contract
	public function set_employee_contract($id) {
	
		$sql = 'SELECT * FROM xin_employee_contract WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	 	return $query;
	}
	
	// get employee office shift
	public function set_employee_shift($id) {
	
		$sql = 'SELECT * FROM xin_employee_shift WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	
	// get employee leave
	public function set_employee_leave($id) {
	
		$sql = 'SELECT * FROM xin_employee_leave WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	
	// get employee location
	public function set_employee_location($id) {
	
		$sql = 'SELECT * FROM xin_employee_location WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

	  	return $query;
	}
	
	 // get document type by id
	 public function read_document_type_information($id) {
	
		$sql = 'SELECT * FROM xin_document_type WHERE document_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// contract type
	public function read_contract_type_information($id) {
	
		$sql = 'SELECT * FROM xin_contract_type WHERE contract_type_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// contract employee
	public function read_contract_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_contract WHERE contract_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// office shift
	public function read_shift_information($id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	

	
	// get all contract types
	public function all_contract_types() {
	  $query = $this->db->query("SELECT * from xin_contract_type");
  	  return $query->result();
	}
	
	// get all contracts
	public function all_contracts() {
	  $query = $this->db->query("SELECT * from xin_employee_contract");
  	  return $query->result();
	}
	
	// get all document types
	public function all_document_types() {
	  $query = $this->db->query("SELECT * from xin_document_type");
  	  return $query->result();
	}

	// get all document types
	public function all_document_types_ready($id) {


		$sql = 'SELECT * 
FROM xin_document_type 
WHERE document_type_id 
NOT IN (SELECT distinct(document_type_id) AS iddoc FROM xin_employee_documents WHERE employee_id = ?)';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}

	}

	
	// get all education level
	public function all_education_level() {
	  $query = $this->db->query("SELECT * from xin_qualification_education_level");
  	  return $query->result();
	}
	
	// get education level by id
	 public function read_education_information($id) {
	
		$sql = 'SELECT * FROM xin_qualification_education_level WHERE education_level_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get all qualification languages
	public function all_qualification_language() {
	  $query = $this->db->query("SELECT * from xin_qualification_language");
  	  return $query->result();
	}
	
	// get languages by id
	 public function read_qualification_language_information($id) {
	
		$sql = 'SELECT * FROM xin_qualification_language WHERE language_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get all qualification skills
	public function all_qualification_skill() {
	  $query = $this->db->query("SELECT * from xin_qualification_skill");
  	  return $query->result();
	} 
	
	// get qualification by id
	 public function read_qualification_skill_information($id) {
	
		$sql = 'SELECT * FROM xin_qualification_skill WHERE skill_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get contacts by id
	 public function read_contact_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
				
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get documents by id
	 public function read_document_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_documents WHERE document_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get documents by id
	 public function read_imgdocument_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_immigration WHERE immigration_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get qualifications by id
	 public function read_qualification_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_qualification WHERE qualification_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get qualifications by id
	 public function read_work_experience_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_work_experience WHERE work_experience_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get bank account by id
	 public function read_bank_account_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_bankaccount WHERE bankaccount_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get sc level by id
	 public function read_security_level_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_security_level WHERE security_level_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get leave by id
	 public function read_leave_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_leave WHERE leave_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
		
	// get shift by id
	 public function read_emp_shift_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_shift WHERE emp_shift_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_contact_record($id){
		$this->db->where('contact_id', $id);
		$this->db->delete('xin_employee_contacts');
		
	}
	
	// Function to Delete selected record from table
	public function delete_document_record($id){
		$this->db->where('document_id', $id);
		$this->db->delete('xin_employee_documents');
		
	}
	
	// Function to Delete selected record from table
	public function delete_imgdocument_record($id){
		$this->db->where('immigration_id', $id);
		$this->db->delete('xin_employee_immigration');
		
	}
	
	// Function to Delete selected record from table
	public function delete_qualification_record($id){
		$this->db->where('qualification_id', $id);
		$this->db->delete('xin_employee_qualification');
		
	}
	
	// Function to Delete selected record from table
	public function delete_work_experience_record($id){
		$this->db->where('work_experience_id', $id);
		$this->db->delete('xin_employee_work_experience');
		
	}
	
	// Function to Delete selected record from table
	public function delete_bank_account_record($id){
		$this->db->where('bankaccount_id', $id);
		$this->db->delete('xin_employee_bankaccount');
		
	}
	// Function to Delete selected record from table
	public function delete_security_level_record($id){
		$this->db->where('security_level_id', $id);
		$this->db->delete('xin_employee_security_level');
		
	}
	
	// Function to Delete selected record from table
	public function delete_contract_record($id){
		$this->db->where('contract_id', $id);
		$this->db->delete('xin_employee_contract');
		
	}
	
	// Function to Delete selected record from table
	public function delete_leave_record($id){
		$this->db->where('leave_id', $id);
		$this->db->delete('xin_employee_leave');
		
	}
	
	// Function to Delete selected record from table
	public function delete_shift_record($id){
		$this->db->where('emp_shift_id', $id);
		$this->db->delete('xin_employee_shift');
		
	}
	
	// Function to Delete selected record from table
	public function delete_location_record($id){
		$this->db->where('office_location_id', $id);
		$this->db->delete('xin_employee_location');
		
	}
	
	// get location by id
	 public function read_location_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_location WHERE office_location_id = ? limit 1';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function record_count() {
		$sql = 'SELECT * FROM xin_user_mobile where user_role_id!=1';
		$query = $this->db->query($sql);
		return $query->num_rows();
    }
	public function record_count_myteam($reports_to) {
		$sql = 'SELECT * FROM xin_user_mobile where user_role_id!=1 and reports_to = '.$reports_to.'';
		$query = $this->db->query($sql);
		return $query->num_rows();
    }
	// read filter record
	public function get_employee_by_department($cid) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE department_id = ?';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// read filter record
	public function record_count_company_employees($cid) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ?';
		$binds = array($cid);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_employees($cid,$lid) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id= ?';
		$binds = array($cid,$lid);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_department_employees($cid,$lid,$dep_id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id= ? and department_id= ?';
		$binds = array($cid,$lid,$dep_id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// read filter record
	public function record_count_company_location_department_designation_employees($cid,$lid,$dep_id,$des_id) {
	
		$sql = 'SELECT * FROM xin_user_mobile WHERE company_id = ? and location_id= ? and department_id= ? and designation_id= ?';
		$binds = array($cid,$lid,$dep_id,$des_id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	//reports_to -> my employees
    public function fetch_all_team_employees($limit, $start) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		//$this->db->where("user_role_id!=",1);
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		$this->db->where("reports_to",$session['user_id']);
		$this->db->where("user_role_id!=1");
        $query = $this->db->get("xin_user_mobile");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
    public function fetch_all_employees($limit, $start) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		//$this->db->where("user_role_id!=",1);
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id!=1){
			$this->db->where("company_id",$user_info[0]->company_id);
		}
		$this->db->where("user_role_id!=1");
        $query = $this->db->get("xin_user_mobile");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   // get company employees
   public function fetch_all_company_employees_flt($limit, $start,$cid) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id",$cid);
        $query = $this->db->get("xin_user_mobile");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   // get company|location employees
   public function fetch_all_company_location_employees_flt($limit, $start,$cid,$lid) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=",$cid);
		$this->db->where("location_id=",$lid);
        $query = $this->db->get("xin_user_mobile");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   // get company|location|department employees
   public function fetch_all_company_location_department_employees_flt($limit, $start,$cid,$lid,$dep_id) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=",$cid);
		$this->db->where("location_id=",$lid);
		$this->db->where("department_id=",$dep_id);
        $query = $this->db->get("xin_user_mobile");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   // get company|location|department|designation employees
   public function fetch_all_company_location_department_designation_employees_flt($limit, $start,$cid,$lid,$dep_id,$des_id) {
		$session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
		$this->db->order_by("designation_id asc");
		$this->db->where("company_id=",$cid);
		$this->db->where("location_id=",$lid);
		$this->db->where("department_id=",$dep_id);
		$this->db->where("designation_id=",$des_id);
        $query = $this->db->get("xin_user_mobile");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   
   public function des_fetch_all_employees($limit, $start) {
       // $this->db->limit($limit, $start);
		
		$sql = 'SELECT * FROM xin_user_mobile order by designation_id asc limit ?, ?';
		$binds = array($limit,$start);
		$query = $this->db->query($sql, $binds);
		
      //  $query = $this->db->get("xin_user_mobile");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   // get employee allowances
	public function set_employee_allowances($id) {
	
		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee commissions
	public function set_employee_commissions($id) {
	
		$sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee statutory deductions
	public function set_employee_statutory_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee other payments
	public function set_employee_other_payments($id) {
	
		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee overtime
	public function set_employee_overtime($id) {
	
		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	
	// get employee allowances
	public function set_employee_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	//-- payslip data
	// get employee allowances
	public function set_employee_allowances_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_allowances WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee commissions
	public function set_employee_commissions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_commissions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee other payments
	public function set_employee_other_payments_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_other_payments WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee statutory_deductions
	public function set_employee_statutory_deductions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_statutory_deductions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	// get employee overtime
	public function set_employee_overtime_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_overtime WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	
	// get employee allowances
	public function set_employee_deductions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_loan WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query;
	}
	//------
	// get employee allowances
	public function count_employee_allowances_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_allowances WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee commissions
	public function count_employee_commissions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_commissions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee statutory_deductions
	public function count_employee_statutory_deductions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_statutory_deductions WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee other payments
	public function count_employee_other_payments_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_other_payments WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee overtime
	public function count_employee_overtime_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_overtime WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	
	// get employee allowances
	public function count_employee_deductions_payslip($id) {
	
		$sql = 'SELECT * FROM xin_salary_payslip_loan WHERE payslip_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	//////////////////////
	// get employee allowances
	public function count_employee_allowances($id) {
	
		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee commissions
	public function count_employee_commissions($id) {
	
		$sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee other payments
	public function count_employee_other_payments($id) {
	
		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee statutory deduction
	public function count_employee_statutory_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	// get employee overtime
	public function count_employee_overtime($id) {
	
		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	
	// get employee allowances
	public function count_employee_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
	  	return $query->num_rows();
	}
	
	// get employee salary allowances
	public function read_salary_allowances($id) {
	
		$sql = 'SELECT * FROM xin_salary_allowances WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary commissions
	public function read_salary_commissions($id) {
	
		$sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary other payments
	public function read_salary_other_payments($id) {
	
		$sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee statutory deductions
	public function read_salary_statutory_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee overtime
	public function read_salary_overtime($id) {
	
		$sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary loan_deduction
	public function read_salary_loan_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee salary loan_deduction
	public function read_single_loan_deductions($id) {
	
		$sql = 'SELECT * FROM xin_salary_loan_deductions WHERE loan_deduction_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	//Calculates how many months is past between two timestamps.
	public function get_month_diff($start, $end = FALSE) {
		$end OR $end = time();
		$start = new DateTime($start);
		$end   = new DateTime($end);
		$diff  = $start->diff($end);
		return $diff->format('%y') * 12 + $diff->format('%m');
	}
	// get employee salary allowances
	public function read_single_salary_allowance($id) {
	
		$sql = 'SELECT * FROM xin_salary_allowances WHERE allowance_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee commissions
	public function read_single_salary_commissions($id) {
	
		$sql = 'SELECT * FROM xin_salary_commissions WHERE salary_commissions_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get
	public function read_single_salary_statutory_deduction($id) {
	
		$sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE statutory_deductions_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function read_single_salary_other_payment($id) {
	
		$sql = 'SELECT * FROM xin_salary_other_payments WHERE other_payments_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get employee overtime record
	public function read_salary_overtime_record($id) {
	
		$sql = 'SELECT * FROM xin_salary_overtime WHERE salary_overtime_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to add record in table > allowance
	public function add_salary_allowances($data){
		$this->db->insert('xin_salary_allowances', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > commissions
	public function add_salary_commissions($data){
		$this->db->insert('xin_salary_commissions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > statutory_deductions
	public function add_salary_statutory_deductions($data){
		$this->db->insert('xin_salary_statutory_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > other payments
	public function add_salary_other_payments($data){
		$this->db->insert('xin_salary_other_payments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > loan
	public function add_salary_loan($data){
		$this->db->insert('xin_salary_loan_deductions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table > overtime
	public function add_salary_overtime($data){
		$this->db->insert('xin_salary_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to Delete selected record from table
	public function delete_allowance_record($id){
		$this->db->where('allowance_id', $id);
		$this->db->delete('xin_salary_allowances');
		
	}
	// Function to Delete selected record from table
	public function delete_commission_record($id){
		$this->db->where('salary_commissions_id', $id);
		$this->db->delete('xin_salary_commissions');
		
	}
	// Function to Delete selected record from table
	public function delete_statutory_deductions_record($id){
		$this->db->where('statutory_deductions_id', $id);
		$this->db->delete('xin_salary_statutory_deductions');
		
	}
	// Function to Delete selected record from table
	public function delete_other_payments_record($id){
		$this->db->where('other_payments_id', $id);
		$this->db->delete('xin_salary_other_payments');
		
	}
	// Function to Delete selected record from table
	public function delete_loan_record($id){
		$this->db->where('loan_deduction_id', $id);
		$this->db->delete('xin_salary_loan_deductions');
		
	}
	// Function to Delete selected record from table
	public function delete_overtime_record($id){
		$this->db->where('salary_overtime_id', $id);
		$this->db->delete('xin_salary_overtime');
		
	}
	// Function to update record in table > update allowance record
	public function salary_allowance_update_record($data, $id){
		$this->db->where('allowance_id', $id);
		if( $this->db->update('xin_salary_allowances',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table >
	public function salary_commissions_update_record($data, $id){
		$this->db->where('salary_commissions_id', $id);
		if( $this->db->update('xin_salary_commissions',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table >
	public function salary_statutory_deduction_update_record($data, $id){
		$this->db->where('statutory_deductions_id', $id);
		if( $this->db->update('xin_salary_statutory_deductions',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table >
	public function salary_other_payment_update_record($data, $id){
		$this->db->where('other_payments_id', $id);
		if( $this->db->update('xin_salary_other_payments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table > update allowance record
	public function salary_loan_update_record($data, $id){
		$this->db->where('loan_deduction_id', $id);
		if( $this->db->update('xin_salary_loan_deductions',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table > update allowance record
	public function salary_overtime_update_record($data, $id){
		$this->db->where('salary_overtime_id', $id);
		if( $this->db->update('xin_salary_overtime',$data)) {
			return true;
		} else {
			return false;
		}
	}
	// get single record > company | office shift
	 public function ajax_company_officeshift_information($id) {
	
		$sql = 'SELECT * FROM xin_office_shift WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	public function ktp_exist_blacklist($ktp)
	{
	  $query = $this->db->query("SELECT ktp_no FROM xin_user_mobile WHERE status_resign = '3' AND ktp_no = '$ktp';");
  	  return $query->num_rows();
	}

	public function ktp_exist_active($ktp)
	{
	  $query = $this->db->query("SELECT ktp_no FROM xin_user_mobile WHERE status_resign = '1' AND ktp_no = '$ktp';");
  	  return $query->num_rows();
	}

	public function ktp_exist_regis($ktp)
	{
	  $query = $this->db->query("SELECT nik_ktp AS ktp_no FROM xin_employee_request WHERE migrasi = 0 AND nik_ktp = '$ktp';");
  	  return $query->num_rows();
	}

	// get single project by id
	public function read_ethnicity($id) {
	
		$sql = 'SELECT * FROM xin_ethnicity_type WHERE ethnicity_type_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// get all my team employes > not super admin
	public function get_all_employees_active() {
		
		$sql = 'SELECT user_id, employee_id, CONCAT( employee_id, " - ", first_name) AS fullname FROM xin_user_mobile WHERE is_active = 1 AND employee_id not IN (1)';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

	// get all my team employes > not super admin
	public function get_all_employees_resign() {
		
		$sql = 'SELECT user_id, employee_id, CONCAT( employee_id, " - ", first_name) AS fullname FROM xin_user_mobile WHERE is_active = 1 AND  status_resign = 2 AND employee_id not IN (1)';
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

	// public function get_maxid() {
	// 	$sql = 'SELECT max(employee_id) AS maxid FROM xin_user_mobile';
	// 	$query = $this->db->query($sql);
	//     return $query->result();
	// }

	public function get_maxid() {
	  // $query = $this->db->query("SELECT max(employee_id) AS maxid FROM xin_user_mobile");
  	//   return $query->result();

  	  $maxid = 0;
		$row = $this->db->query("SELECT max(employee_id) AS maxid FROM xin_user_mobile")->row();
		if ($row) {
		    $maxid = $row->maxid; 
		}
	return $maxid;
	}

	// public function get_countries()
	// {
	//   $query = $this->db->query("SELECT * from xin_countries");
 //  	  return $query->result();
	// }
}
?>