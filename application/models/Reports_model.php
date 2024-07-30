<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

	public function getAttendToday($company_id,$project_id,$employee_id,$start_date,$end_date) {
		$query = $this->db->query("

			SELECT attdin.employee_id, attdin.customer_id, attdin.date_phone, attdin.time_in, cout.time_out, TIMEDIFF(cout.time_out, attdin.time_in) AS timestay
			FROM (
				SELECT employee_id, customer_id, DATE_FORMAT(datetime_phone, '%Y-%m-%d') AS date_phone, c_io, DATE_FORMAT(datetime_phone, '%H:%i:%s') AS time_in
				FROM xin_trx_cio
				WHERE DATE_FORMAT(datetime_phone, '%Y-%m-%d') = CURDATE()
				AND c_io = 1
				ORDER BY createdon DESC) attdin
			LEFT JOIN (
				SELECT employee_id, c_io, DATE_FORMAT(datetime_phone, '%H:%i:%s') AS time_out
				FROM xin_trx_cio
				WHERE DATE_FORMAT(datetime_phone, '%Y-%m-%d') = CURDATE()
				AND c_io = 2
			) cout ON cout.employee_id = attdin.employee_id"

		);
		return $query->result();
	}

	// get payslip list> reports
	public function get_payslip_list($cid,$eid,$re_date) {
	  if($eid=='' || $eid==0){
		
		$sql = 'SELECT * from xin_salary_payslips where salary_month = ? and company_id = ?';
		$binds = array($re_date,$cid);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	  } else {
	 	 
		$sql = 'SELECT * from xin_salary_payslips where employee_id = ? and salary_month = ? and company_id = ?';
		$binds = array($eid,$re_date,$cid);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	  }
	}
	// get training list> reports
	public function get_training_list($cid,$sdate,$edate) {
		
		$sql = 'SELECT * from `xin_training` where company_id = ? and start_date >= ? and finish_date <= ?';
		$binds = array($cid,$sdate,$edate);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	// get leave list> reports
	public function get_leave_application_list() {
		
		$this->db->query("SET SESSION sql_mode = ''");
		$sql = 'SELECT * from `xin_leave_applications` group by employee_id';
		$query = $this->db->query($sql);
		return $query;
	}
	// get filter leave list> reports
	public function get_leave_application_filter_list($sd,$ed,$user_id,$company_id) {
		
		$this->db->query("SET SESSION sql_mode = ''");
		$sql = 'SELECT * from `xin_leave_applications` where company_id = ? and employee_id = ? and from_date >= ? and to_date <= ? group by employee_id';
		$binds = array($company_id,$user_id,$sd,$ed);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get pending leave list> reports
	public function get_pending_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,1);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get approved leave list> reports
	public function get_approved_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,2);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get upcoming leave list> reports
	public function get_upcoming_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,4);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get rejected leave list> reports
	public function get_rejected_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,3);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get only pending leave list> reports
	public function get_pending_leave_list($employee_id,$status) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,$status);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get project list> reports
	public function get_project_list($projId,$projStatus) {
		
		if($projId==0 && $projStatus=='all') {
			return $query = $this->db->query("SELECT * FROM `xin_projects`");
		} else if($projId==0 && $projStatus!='all') {
			$sql = 'SELECT * from `xin_projects` where status = ?';
			$binds = array($projStatus);
			$query = $this->db->query($sql, $binds);
			return $query;
		} else if($projId!=0 && $projStatus=='all') {
			$sql = 'SELECT * from `xin_projects` where project_id = ?';
			$binds = array($projId);
			$query = $this->db->query($sql, $binds);
			return $query;
		} else if($projId!=0 && $projStatus!='all') {
			$sql = 'SELECT * from `xin_projects` where project_id = ? and status = ?';
			$binds = array($projId,$projStatus);
			$query = $this->db->query($sql, $binds);
			return $query;
		}
	}
	// get employee projects
	public function get_employee_projectsx($id) {
	
		$sql = "SELECT * FROM `xin_projects` where assigned_to like '%$id,%' or assigned_to like '%,$id%' or assigned_to = '$id'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get task list> reports
	public function get_task_list($taskId,$taskStatus) {
		
		  if($taskId==0 && $taskStatus==4) {
			  return $query = $this->db->query("SELECT * FROM xin_tasks");
		  } else if($taskId==0 && $taskStatus!=4) {
			  $sql = 'SELECT * from xin_tasks where task_status = ?';
			  $binds = array($taskStatus);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($taskId!=0 && $taskStatus==4) {
			  $sql = 'SELECT * from xin_tasks where task_id = ?';
			  $binds = array($taskId);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($taskId!=0 && $taskStatus!=4) {
		  	  $sql = 'SELECT * from xin_tasks where task_id = ? and task_status = ?';
			  $binds = array($taskId,$taskStatus);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  }
	}
	
	// get roles list> reports
	public function get_roles_employees($role_id) {
		  if($role_id==0) {
			  return $query = $this->db->query("SELECT * FROM xin_employees");
		  } else {
			  $sql = 'SELECT * from xin_employees where user_role_id = ?';
			  $binds = array($role_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  }
	}
	


	// get employees list> reports
	public function filter_employees_reports($project_id,$sub_project_id,$status_resign) {

		if($project_id==0 && $sub_project_id==0 && $status_resign==0){
			return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id IN (99)");
		} else if ($project_id!=0 && $sub_project_id==0 && $status_resign==0)  {
			$sql = "SELECT * from xin_employees where project_id = ? AND employee_id NOT IN (1)";
			$binds = array($project_id);
			$query = $this->db->query($sql, $binds);
			return $query;

		} else if ($project_id!=0 && $sub_project_id!=0 && $status_resign==0) {
			$sql = "SELECT * from xin_employees where project_id = ? AND sub_project_id = ? AND employee_id NOT IN (1)";
			$binds = array($project_id,$sub_project_id);
			$query = $this->db->query($sql, $binds);
			return $query;

		} else if ($project_id!=0 && $sub_project_id==0 && $status_resign!=0) {
			$sql = "SELECT * from xin_employees where project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
			$binds = array($project_id,$status_resign);
			$query = $this->db->query($sql, $binds);
			return $query;

		} else if ($project_id!=0 && $sub_project_id!=0 && $status_resign!=0){
			$sql = "SELECT * from xin_employees where project_id = ? AND sub_project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
			$binds = array($project_id,$sub_project_id,$status_resign);
			$query = $this->db->query($sql, $binds);
			return $query;

		} else {

		  return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id IN (99)");

		}

		// 0-0-0-0-0
		//   if($company_id==0 && $department_id==0 && $project_id==0 && $sub_project_id==0 && $status_resign==0) {
		//  	 return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id NOT IN (1)");
		// // 1-0-0-0-0
		//   } else if($company_id!=0 && $department_id==0 && $project_id==0 && $sub_project_id==0 && $status_resign==0) {
		//  	  // $sql = "SELECT * from xin_employees where company_id = ? AND employee_id NOT IN (1)";
		// 	  // $binds = array($company_id);
		// 	  // $query = $this->db->query($sql, $binds);
		// 	  // return $query;
		// 	  return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id NOT IN (1) LIMIT 0");
		// // 1-1-0-0-0
		//   } else if($company_id!=0 && $department_id!=0 && $project_id==0 && $sub_project_id==0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where company_id = ? and department_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($company_id,$department_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-1-1-0-0
		//   } else if($company_id!=0 && $department_id!=0 && $project_id!=0 && $sub_project_id==0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where department_id = ? AND project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($department_id,$project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-1-1-1-0
		//   } else if($company_id!=0 && $department_id!=0 && $project_id!=0 && $sub_project_id!=0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where department_id = ? AND project_id = ? AND sub_project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($department_id,$project_id,$sub_project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 0-0-1-0-0
		//   } else if ($company_id==0 && $department_id==0 && $project_id!=0 && $sub_project_id==0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 0-0-1-1-0
		//   } else if ($company_id==0 && $department_id==0 && $project_id!=0 && $sub_project_id!=0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND sub_project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id,$sub_project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-0-1-0-0
		//   } else if ($company_id!=0 && $department_id==0 && $project_id!=0 && $sub_project_id==0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-0-0-0-1
		//   } else if ($company_id!=0 && $department_id==0 && $project_id==0 && $sub_project_id==0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where status_resign = ? AND employee_id NOT IN (1) LIMIT 0";
		// 	  $binds = array($status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-1-0-0-1
		//   } else if ($company_id!=0 && $department_id!=0 && $project_id==0 && $sub_project_id==0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where department_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($department_id, $status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-1-1-0-1
		//   } else if ($company_id!=0 && $department_id!=0 && $project_id!=0 && $sub_project_id!=0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where department_id = ? AND $project_id = ? AND $sub_project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($department_id, $project_id, $sub_project_id, $status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-0-1-0-1
		//   } else if ($company_id!=0 && $department_id==0 && $project_id!=0 && $sub_project_id==0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id, $status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-0-1-1-0
		//   } else if ($company_id!=0 && $department_id==0 && $project_id!=0 && $sub_project_id!=0 && $status_resign==0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND sub_project_id = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id, $sub_project_id);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-0-1-1-1
		//   } else if ($company_id!=0 && $department_id==0 && $project_id!=0 && $sub_project_id!=0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where project_id = ? AND sub_project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($project_id, $sub_project_id, $status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		// // 1-1-1-1-1
		//   } else if ($company_id!=0 && $department_id!=0 && $project_id!=0 && $sub_project_id!=0 && $status_resign!=0) {
		//  	  $sql = "SELECT * from xin_employees where department_id = ? AND $project_id = ? AND $sub_project_id = ? AND status_resign = ? AND employee_id NOT IN (1)";
		// 	  $binds = array($department_id, $project_id, $sub_project_id, $status_resign);
		// 	  $query = $this->db->query($sql, $binds);
		// 	  return $query;
		//   } else {
		// 	  return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id NOT IN (1)");
		//   }

	}


	// get employees list> reports
	public function filter_employees_reports_null($project_id,$sub_project_id,$status_resign) {
		return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id IN (99)");
// 		return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id IN (
// 		22505515,
// 22505525,
// 22505526,
// 22505527,
// 22505530,
// 22505531,
// 22505532,
// 22505533,
// 22505534,
// 22505536,
// 22505537,
// 22505539,
// 22505540,
// 22505541,
// 22505542,
// 22505543,
// 22505545,
// 22505546,
// 22505547,
// 22505548,
// 22505551,
// 22505552,
// 22505554,
// 22505555,
// 22505559,
// 22505560,
// 22505561,
// 22505562,
// 22505563,
// 22505564,
// 22505565,
// 22505566,
// 22505567,
// 22505568,
// 22505569,
// 22505570,
// 22505571,
// 22505573,
// 22505574,
// 22505575,
// 22505576,
// 22505577,
// 22505578,
// 22505579,
// 22505580,
// 22505581,
// 22505582,
// 22505583,
// 22505584,
// 22505585,
// 22505586,
// 22505587,
// 22505588,
// 22505589,
// 22505590,
// 22505591,
// 22505592,
// 22505593,
// 22505594,
// 22505595,
// 22505596,
// 22505597,
// 22505599,
// 22505600,
// 22505603,
// 22505604,
// 22505605,
// 22505606,
// 22505607,
// 22505610,
// 22505611,
// 22505612,
// 22505613,
// 22505614
//  	)");
	}


	// get employees list> reports
	public function filter_employees_hotspot($company_id,$department_id,$project_id,$sub_project_id,$status_resign) {
		return $query = $this->db->query("SELECT * FROM xin_employees WHERE employee_id IN (99)");
	}


	// get employees list> reports
	public function filter_esign_reports_null($company_id,$department_id,$project_id,$sub_project_id,$status_resign) {
		
		// 0-0-0-0-0
		  if($company_id==0 && $department_id==0 && $project_id==0 && $sub_project_id==0 && $status_resign==0) {
		 	 return $query = $this->db->query("SELECT * FROM xin_qrcode_skk WHERE nip in('99') ORDER BY secid DESC");
		// 1-0-0-0-0
		  } else if($company_id!=0 && $department_id==0 && $project_id!=0 && $sub_project_id==0 && $status_resign==0) {
		 	  $sql = "SELECT skk.*, emp.company_id, emp.project_id, emp.designation_id 
						FROM xin_qrcode_skk skk
						LEFT JOIN xin_employees emp ON emp.employee_id = skk.nip 
						WHERE skk.nip not in('0') 
						AND emp.company_id = ?
						AND emp.project_id = ?
						ORDER BY skk.secid DESC";
			  $binds = array($company_id, $project_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		// 1-1-0-0-0
		  }	else {





		return $query = $this->db->query("SELECT * FROM xin_qrcode_skk WHERE nip not in('0') ORDER BY secid DESC LIMIT 500");
		}




	}

		// get employees att reports
	public function filter_report_emp_att_null() {


		return $query = $this->db->query("SELECT employee_id, customer_id, datetime_phone as date_phone, datetime_phone as time_in, datetime_phone as time_out, datetime_phone as timestay
FROM xin_trx_cio
WHERE employee_id = '99'");





	}

		// get employees att reports
	public function filter_report_emp_att($project_id,$sub_id,$area,$start_date,$end_date) {

		if($sub_id=='0'){

		return $query = $this->db->query("


SELECT cio.status_emp, cio.employee_id, userm.fullname, userm.company_id, userm.company_name, userm.project_id, userm.project_name, userm.project_sub, userm.jabatan, userm.penempatan, cio.customer_id, cust.customer_name, cust.address, cust.owner_name, cust.no_contact, DATE_FORMAT(cio.date_cio, '%Y-%m-%d') AS date_phone, DATE_FORMAT(cio.datetimephone_in, '%H:%i:%s') AS time_in, DATE_FORMAT(cio.datetimephone_out, '%H:%i:%s') AS time_out, TIMEDIFF(cio.datetimephone_out, cio.datetimephone_in) AS timestay, cio.latitude_in, cio.longitude_in, distance_in, foto_in, foto_out
FROM `tx_cio` cio
LEFT JOIN xin_user_mobile userm ON userm.employee_id = cio.employee_id
LEFT JOIN xin_customer cust ON cust.customer_id = cio.customer_id
WHERE userm.project_id = '$project_id'
AND DATE_FORMAT(cio.date_cio, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'

		
			");


		} else {

		return $query = $this->db->query("


SELECT cio.status_emp, cio.employee_id, userm.fullname, userm.company_id, userm.company_name, userm.project_id, userm.project_name, userm.project_sub, userm.jabatan, userm.penempatan, cio.customer_id, cust.customer_name, cust.address, cust.owner_name, cust.no_contact, DATE_FORMAT(cio.date_cio, '%Y-%m-%d') AS date_phone, DATE_FORMAT(cio.datetimephone_in, '%H:%i:%s') AS time_in, DATE_FORMAT(cio.datetimephone_out, '%H:%i:%s') AS time_out, TIMEDIFF(cio.datetimephone_out, cio.datetimephone_in) AS timestay, cio.latitude_in, cio.longitude_in, distance_in, foto_in, foto_out
FROM `tx_cio` cio
LEFT JOIN xin_user_mobile userm ON userm.employee_id = cio.employee_id
LEFT JOIN xin_customer cust ON cust.customer_id = cio.customer_id
WHERE cio.project_id = '$project_id'
AND REPLACE(userm.project_sub, ' ', '')  = '$sub_id'
AND DATE_FORMAT(cio.date_cio, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'


			");

		}
	
	}


		// get employees att reports
	public function filter_report_emp_sellout_null() {


		return $query = $this->db->query("SELECT ordr.secid, ordr.employee_id, userm.fullname, userm.company_id, userm.company_name, userm.project_id, userm.project_name, userm.project_sub, userm.jabatan, userm.penempatan, ordr.customer_id, cust.customer_name, ordr.material_id, skum.kode_sku, skum.nama_material, skum.brand, skum.variant, skum.uom, skum.price, ordr.order_date, ordr.qty, ordr.price, ordr.total
FROM `xin_mobile_order` ordr
LEFT JOIN xin_sku_material skum ON skum.kode_sku = ordr.material_id
LEFT JOIN xin_user_mobile userm ON userm.employee_id = ordr.employee_id
LEFT JOIN xin_customer cust ON cust.customer_id = ordr.customer_id LIMIT 0");





	}


		// get employees att reports
	public function filter_report_emp_sellout($project_id, $sub_id, $area, $start_date, $end_date) {

		if($sub_id=='0'){

		return $query = $this->db->query("

SELECT ordr.secid, ordr.employee_id, userm.fullname, userm.company_id, userm.company_name, userm.project_id, userm.project_name, userm.project_sub, userm.jabatan, userm.penempatan, ordr.customer_id, cust.customer_name, ordr.material_id, skum.kode_sku, skum.nama_material, skum.brand, skum.variant, skum.uom, skum.price, ordr.order_date, skum.poin, ordr.qty, ordr.price as sell_price, ordr.total
FROM `xin_mobile_order` ordr
LEFT JOIN xin_sku_material skum ON skum.kode_sku = ordr.material_id
LEFT JOIN xin_user_mobile userm ON userm.employee_id = ordr.employee_id
LEFT JOIN xin_customer cust ON cust.customer_id = ordr.customer_id
WHERE userm.project_id = '$project_id'
AND DATE_FORMAT(ordr.order_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'


		
			");


		} else {

		return $query = $this->db->query("

SELECT ordr.secid, ordr.employee_id, userm.fullname, userm.company_id, userm.company_name, userm.project_id, userm.project_name, userm.project_sub, userm.jabatan, userm.penempatan, ordr.customer_id, cust.customer_name, ordr.material_id, skum.kode_sku, skum.nama_material, skum.brand, skum.variant, skum.uom, skum.price, ordr.order_date, skum.poin, ordr.qty, ordr.price as sell_price, ordr.total
FROM `xin_mobile_order` ordr
LEFT JOIN xin_sku_material skum ON skum.kode_sku = ordr.material_id
LEFT JOIN xin_user_mobile userm ON userm.employee_id = ordr.employee_id
LEFT JOIN xin_customer cust ON cust.customer_id = ordr.customer_id
WHERE userm.project_id = '$project_id'
AND REPLACE(userm.project_sub, ' ', '') = '$sub_id'
AND DATE_FORMAT(ordr.order_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'

			");

		}
	
	}


		// get employees att reports
	public function report_order() {

		return $query = $this->db->query("
			SELECT morder.secid, 
			morder.customer_id,cust.customer_name, cust.address, city.name as city, kec.name as kec, desa.name as desa,
            cust.owner_name, cust.no_contact,
			morder.employee_id,emp.first_name, morder.material_id, sku.nama_material, 
		morder.order_date, morder.qty, morder.price, morder.total
		FROM xin_mobile_order morder
		LEFT JOIN xin_sku_material sku ON sku.kode_sku = morder.material_id
		LEFT JOIN xin_employees emp ON emp.employee_id = morder.employee_id
		LEFT JOIN xin_customer cust ON cust.customer_id = morder.customer_id
		LEFT JOIN mt_regencies city ON city.id = cust.city_id
		LEFT JOIN mt_districts kec ON kec.id = cust.district_id
		LEFT JOIN mt_villages desa ON desa.id = cust.village_id

		WHERE DATE_FORMAT(morder.order_date, '%Y-%m-%d') = CURDATE() 
		-- AND morder.customer_id = '123456789'
		-- AND morder.employee_id = '21300043'
		");
	}

		// get employees order
	public function report_order_filter($company_id,$project_id,$sub_id,$start_date,$end_date) {

		return $query = $this->db->query("
			SELECT morder.secid, 
			morder.customer_id,cust.customer_name, cust.address, city.name as city, kec.name as kec, desa.name as desa,
			cust.owner_name, cust.no_contact,
			morder.employee_id,emp.first_name, morder.material_id, sku.nama_material, 
		morder.order_date, morder.qty, morder.price, morder.total
		FROM xin_mobile_order morder
		LEFT JOIN xin_sku_material sku ON sku.kode_sku = morder.material_id
		LEFT JOIN xin_employees emp ON emp.employee_id = morder.employee_id
		LEFT JOIN xin_customer cust ON cust.customer_id = morder.customer_id
		LEFT JOIN mt_regencies city ON city.id = cust.city_id
		LEFT JOIN mt_districts kec ON kec.id = cust.district_id
		LEFT JOIN mt_villages desa ON desa.id = cust.village_id

		WHERE DATE_FORMAT(morder.order_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'
		AND emp.project_id = '$project_id'
		-- AND morder.customer_id = '123456789'
		-- AND morder.employee_id = '21300043'
		");

	}

		// get employees att reports
	public function report_order_resume() {

		return $query = $this->db->query("

SELECT distinct(cio.employee_id) AS emp_id, emp.penempatan, CURDATE() AS sdate, CURDATE() AS ndate, COUNT(cio.customer_id) count_call, morder.count_ec, morder.qty_renceng, morder.total
FROM xin_trx_cio cio
LEFT JOIN xin_employees emp ON emp.employee_id = cio.employee_id
LEFT JOIN (SELECT DISTINCT(employee_id) emp_order, COUNT(DISTINCT customer_id) count_ec, SUM(qty) AS qty_renceng, SUM(total) AS total
	FROM xin_mobile_order 
	WHERE DATE_FORMAT(order_date, '%Y-%m-%d') BETWEEN CURDATE() AND CURDATE()
    GROUP BY employee_id) morder ON morder.emp_order = cio.employee_id
WHERE cio.project_id = 25
AND emp.sub_project_id = 151
AND cio.c_io = 1
AND DATE_FORMAT(cio_date, '%Y-%m-%d') BETWEEN CURDATE() AND CURDATE()
GROUP BY cio.employee_id


		-- 	SELECT morder.secid, 
		-- 	morder.customer_id,cust.customer_name, cust.address, city.name as city, kec.name as kec, desa.name as desa,
		-- 	morder.employee_id,emp.first_name, morder.material_id, sku.nama_material, 
		-- morder.order_date, morder.qty, morder.price, morder.total
		-- FROM xin_mobile_order morder
		-- LEFT JOIN xin_sku_material sku ON sku.kode_sku = morder.material_id
		-- LEFT JOIN xin_employees emp ON emp.employee_id = morder.employee_id
		-- LEFT JOIN xin_customer cust ON cust.customer_id = morder.customer_id
		-- LEFT JOIN mt_regencies city ON city.id = cust.city_id
		-- LEFT JOIN mt_districts kec ON kec.id = cust.district_id
		-- LEFT JOIN mt_villages desa ON desa.id = cust.village_id
		-- WHERE DATE_FORMAT(morder.order_date, '%Y-%m-%d') = CURDATE() 
		-- AND morder.customer_id = '123456789'
		-- AND morder.employee_id = '21300043'
		");
	}


		// get employees att reports
	public function report_order_resume_filter($company_id,$project_id,$sub_id,$start_date,$end_date) {

		return $query = $this->db->query("

SELECT distinct(cio.employee_id) AS emp_id, emp.penempatan, '$start_date' AS sdate, '$end_date' AS ndate, COUNT(cio.customer_id) count_call, morder.count_ec, morder.qty_renceng, morder.total
FROM xin_trx_cio cio
LEFT JOIN xin_employees emp ON emp.employee_id = cio.employee_id
LEFT JOIN (SELECT DISTINCT(employee_id) emp_order, COUNT(DISTINCT customer_id) count_ec, SUM(qty) AS qty_renceng, SUM(total) AS total
	FROM xin_mobile_order 
	WHERE DATE_FORMAT(order_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'
    GROUP BY employee_id) morder ON morder.emp_order = cio.employee_id
WHERE cio.project_id = '$project_id'
AND emp.sub_project_id = '$sub_id'
AND cio.c_io = 1
AND DATE_FORMAT(cio_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'
GROUP BY cio.employee_id

		-- 	SELECT morder.secid, 
		-- 	morder.customer_id,cust.customer_name, cust.address, city.name as city, kec.name as kec, desa.name as desa,
		-- 	morder.employee_id,emp.first_name, morder.material_id, sku.nama_material, 
		-- morder.order_date, morder.qty, morder.price, morder.total
		-- FROM xin_mobile_order morder
		-- LEFT JOIN xin_sku_material sku ON sku.kode_sku = morder.material_id
		-- LEFT JOIN xin_employees emp ON emp.employee_id = morder.employee_id
		-- LEFT JOIN xin_customer cust ON cust.customer_id = morder.customer_id
		-- LEFT JOIN mt_regencies city ON city.id = cust.city_id
		-- LEFT JOIN mt_districts kec ON kec.id = cust.district_id
		-- LEFT JOIN mt_villages desa ON desa.id = cust.village_id
		-- WHERE DATE_FORMAT(morder.order_date, '%Y-%m-%d') = CURDATE() 
		-- AND morder.customer_id = '123456789'
		-- AND morder.employee_id = '21300043'
		");
	}

		// get employees att reports
	public function filter_pkwt_history() {

		return $query = $this->db->query("SELECT employee_id, customer_id, datetime_phone as date_phone, datetime_phone as time_in, datetime_phone as time_out, datetime_phone as timestay
		FROM xin_trx_cio
		WHERE employee_id = '99'");

	}

}
?>