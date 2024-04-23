<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cis_model extends CI_Model {
 

    // public function getAllEmployees()
    // {
    //     //$otherdb = $this->load->database('default', TRUE);

    //     $query = $this->db->get('xin_employee_request')->result_array();

    //     return $query;
    // }


	// get all employees
	public function all_employees($nip)	
	{

      $cisdb = $this->load->database('cisdb', TRUE);
	  $query = $cisdb->query("SELECT user_id, employee_id, first_name
		FROM xin_employees
		where project_id in (select DISTINCT(project_id) from xin_projects_akses where nip = '$nip')
		AND status_resign = 1");
  	  return $query->result();
	}


	public function get_employee_cis($empID)
	{

	  $cisdb = $this->load->database('cisdb', TRUE);
	  $query = $cisdb->query("SELECT  emp.employee_id, emp.first_name, emp.company_id, com.name as comp_name, emp.project_id, pro.title, prosub.sub_project_name, pos.designation_name, emp.penempatan
			FROM xin_employees emp
			LEFT JOIN xin_companies com ON com.company_id = emp.company_id
			LEFT JOIN xin_projects pro ON pro.project_id = emp.project_id
			LEFT JOIN xin_projects_sub prosub ON prosub.secid = emp.sub_project_id 
			LEFT JOIN xin_designations pos ON pos.designation_id = emp.designation_id
			WHERE emp.employee_id = '$empID';");
  	  return $query->result();
	}
	

	// check employeeID
	public function check_employee_id($id) {
	
		$sql = 'SELECT * FROM xin_employees WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
}
?>