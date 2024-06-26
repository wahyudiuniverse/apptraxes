<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_customers()
	{
	  return $this->db->get("xin_customer");
	}


 	// monitoring request
	public function get_customer_default() {

		$sql = "SELECT *
			FROM xin_customer LIMIT 0;";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

 	// monitoring request
	public function get_all_customer($idtoko) {

		$sql = "SELECT customer_id, customer_name, owner_name, no_contact, address, village_id, district_id, city_id, latitude, longitude, createdon
			FROM xin_customer WHERE customer_id = '$idtoko';";
		// $binds = array(1,$cid);
		$query = $this->db->query($sql);
	    return $query;
	}

	// get single customer
	public function read_single_customer($id) {
	
		$sql = 'SELECT * FROM xin_customer WHERE customer_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// public function read_single_customer($id) {
		
	// 	$sql = 'SELECT * FROM xin_customer WHERE customer_id = ?';
	// 	$binds = array(1);
	// 	$query = $this->db->query($sql, $binds);
	//  	return $query->result();
	// }


	// $CI->db->select('*');
	// $CI->db->from('xin_employee_contract');
	// $CI->db->where('contract_id', $userid);
	// $CI->db->join('user_email', 'user_email.user_id = emails.id', 'left');
	// $query = $CI->db->get(); 

	public function get_pkwt_employee() {
	
		// $condition = "invoice_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('xin_employee_contract');
		// $this->db->where($id);
		// $this->db->limit(1);
		$this->db->join('xin_employees','xin_employees.user_id = xin_employee_contract.employee_id','left');
		// $query = $this->db->get();
		return $this->db->get();

	}

	public function get_pkwt_approval() {
	
		$this->db->select('*');
		$this->db->from('xin_employee_contract');
		$this->db->join('xin_employees','xin_employees.user_id = xin_employee_contract.employee_id','left');
		$this->db->where ( 'status_approve', 0);
		return $this->db->get();
		// $query = $this->db->get ();
  //   	return $query->result ();

	}


	// Function to add record in table
	public function add_pkwt_record($data){
		$this->db->insert('xin_employee_contract', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}


	// get single desa/kelurahan
	public function read_village($id) {
	
		$sql = 'SELECT * FROM mt_villages WHERE id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	// get single kecamatan
	public function read_kecamatan($id) {
	
		$sql = 'SELECT * FROM mt_districts WHERE id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


	// get single city
	public function read_city($id) {
	
		$sql = 'SELECT * FROM mt_regencies WHERE id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

		// Function to update record in table
	public function update_toko($data, $id){
		$this->db->where('customer_id', $id);
		if( $this->db->update('xin_customer',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	
	// Function to update record in table
	public function update_pkwt_record($data, $id){
		$this->db->where('contract_id', $id);
		if( $this->db->update('xin_employee_contract',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	
}
?>