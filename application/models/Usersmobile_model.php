<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Usersmobile_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_users_mobile()
	{
		// return $this->db->get("xin_user_mobile");
		$this->db->order_by("createdon");
		$this->db->limit(5);
		$query = $this->db->get('xin_user_mobile');

		return $query->result();
	}


	// get employees list> reports
	public function user_mobile_limit()
	{
		$query = $this->db->query("SELECT * FROM xin_user_mobile ORDER BY createdon DESC LIMIT 10");
		return $query;
	}

	public function user_mobile_limit_newtraxes()
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);
		$query = $api_traxes_db->query("SELECT * FROM xin_user_mobile ORDER BY createdon DESC LIMIT 10");
		return $query;
	}

	// get employees list> reports
	public function user_mobile_limit_fillter($company_id, $project_id, $subproject)
	{

		if ($subproject == "0") {
			return $query = $this->db->query("SELECT * FROM xin_user_mobile WHERE project_id = '$project_id'");
		} else {
			return $query = $this->db->query("SELECT * FROM xin_user_mobile WHERE project_id = '$project_id'
			AND project_sub = '$subproject'");
		}
	}

	// get employees list> reports
	public function user_mobile_limit_fillter_newtraxes($company_id, $project_id, $subproject)
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);

		if ($subproject == "0") {
			$query = $api_traxes_db->query("SELECT * FROM xin_user_mobile WHERE project_id = '$project_id'");
			return $query;
		} else {
			$query = $api_traxes_db->query("SELECT * FROM xin_user_mobile WHERE project_id = '$project_id'
			AND project_sub = '$subproject'");
			return $query;
		}
	}

	// get employees list> reports
	public function user_mobile_byemployee($company_id)
	{
		return $query = $this->db->query("SELECT * FROM xin_user_mobile WHERE employee_id= '$company_id';");
	}

	// get employees list> reports
	public function user_mobile_byemployee_newtraxes($company_id)
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);
		$query = $api_traxes_db->query("SELECT * FROM xin_user_mobile WHERE employee_id= '$company_id';");
		return $query;
	}

	public function get_usertype()
	{
		return $this->db->get("xin_user_mobile_type");
	}

	public function get_district()
	{
		return $this->db->get("mt_districts");
	}


	public function get_regencies()
	{
		return $this->db->get("mt_regencies");
	}

	public function get_all_users()
	{
		$query = $this->db->get("xin_user_mobile");
		return $query->result();
	}


	public function read_usersmobile_type($id)
	{

		$sql = 'SELECT * FROM xin_user_mobile_type WHERE secid = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function read_usersmobile_area($id)
	{

		$sql = 'SELECT * FROM mt_regencies WHERE id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function read_users_info($id)
	{

		$sql = 'SELECT * FROM xin_user_mobile WHERE user_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}


	public function read_users_mobile_by_nik($id)
	{
		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function read_users_mobile_by_nik_newtraxes($id)
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);

		$sql = 'SELECT * FROM xin_user_mobile WHERE employee_id = ?';
		$binds = array($id);
		$query = $api_traxes_db->query($sql, $binds);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}


	// Function to update record in table
	public function update_record($data, $id)
	{	
		$this->db->where('employee_id', $id);
		$data = $this->security->xss_clean($data);
		if ($this->db->update('xin_user_mobile', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// Function to update record in table
	public function update_record_newtraxes($data, $id)
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);
		
		$api_traxes_db->where('employee_id', $id);
		$data = $this->security->xss_clean($data);
		if ($api_traxes_db->update('xin_user_mobile', $data)) {
			return true;
		} else {
			return false;
		}
	}

	// check email address
	public function check_user_email($email)
	{

		$sql = 'SELECT * FROM xin_users WHERE email = ?';
		$binds = array($email);
		$query = $this->db->query($sql, $binds);

		return $query;
	}

	// Function to add record in table
	public function add($data)
	{
		$this->db->insert('xin_user_mobile', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// Function to Delete selected record from table
	public function delete_record($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete('xin_user_mobile');
	}

	// Function to Delete selected record from table
	public function delete_record_newtraxes($id)
	{
		$api_traxes_db = $this->load->database('api_traxes_db', TRUE);

		$api_traxes_db->where('user_id', $id);
		$api_traxes_db->delete('xin_user_mobile');
	}

	// Function to update record without photo > in table
	public function update_record_no_photo($data, $id)
	{
		$this->db->where('user_id', $id);
		if ($this->db->update('xin_users', $data)) {
			return true;
		} else {
			return false;
		}
	}
}
