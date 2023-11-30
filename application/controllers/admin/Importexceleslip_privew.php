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

class Importexceleslip_privew extends MY_Controller 
{

	public function __construct(){
    parent::__construct();
    $this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->database();
		$this->load->library('form_validation');
		//load the model
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Callplan_model");
		$this->load->model("Customers_model");
		$this->load->model("Import_model");
		$this->load->model("Designation_model");
			$this->load->library("pagination");
			$this->load->library('Pdf');
			$this->load->helper('string');
  }
	 		
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	// invoices page
	public function index() {
	
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}

		$data['title'] = $this->lang->line('xin_import_excl_eslip_view').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_import_excl_eslip_view');

		$product_id = 
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['uploadid'] = $this->input->get('upid', TRUE);
		// $data['all_posisi'] = $this->Xin_model->get_designations();
		// $data['get_all_companies'] = $this->Xin_model->get_companies();
		$data['path_url'] = 'import_excel_eslip_view';
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
				
		if(in_array('127',$role_resources_ids)) {
			if(!empty($session)){ 
			$data['subview'] = $this->load->view("admin/import_excel/view_import_eslip", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
			} else { 
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	
	public function view_import_excel_employees() {

		$datad['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/import_excel/view_import_eslip", $datad);
		} else {
			redirect('admin/');
		}
		// $product_id = $this->input->get('upid', TRUE);
		$product_id = $this->uri->segment(4);

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$employees_temp = $this->Import_model->get_eslip_temp($product_id);
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$data = array();

    foreach($employees_temp->result() as $r) {
			  
			  $importid = $r->uploadid;
			  $nip = $r->nip;
			  $fullname = $r->fullname;
			  $periode = $r->periode;
			  $project = $r->project;
			  $project_sub = $r->project_sub;
			  $area = $r->area;
			  $hari_kerja = $r->hari_kerja;
			  $gaji_pokok = $r->gaji_pokok;

			  $allow_jabatan = $r->allow_jabatan;
			  $allow_konsumsi = $r->allow_konsumsi;
			  $allow_transport = $r->allow_transport;
			  $allow_rent = $r->allow_rent;
			  $allow_comunication = $r->allow_comunication;
			  $allow_parking = $r->allow_parking;
			  $allow_residence_cost = $r->allow_residence_cost;
			  $allow_device = $r->allow_device;
			  $penyesuaian_umk = $r->penyesuaian_umk;
			  $insentive = $r->insentive;
			  $overtime = $r->overtime;
			  $overtime_national_day = $r->overtime_national_day;
			  $overtime_rapel = $r->overtime_rapel;
			  $kompensasi = $r->kompensasi;
			  $bonus = $r->bonus;
			  $thr = $r->thr;
			  $bpjs_tk_deduction = $r->bpjs_tk_deduction;
			  $bpjs_ks_deduction = $r->bpjs_ks_deduction;
			  $jaminan_pensiun_deduction = $r->jaminan_pensiun_deduction;
			  $pendapatan = $r->pendapatan;
			  $bpjs_tk = $r->bpjs_tk;
			  $bpjs_ks = $r->bpjs_ks;
			  $jaminan_pensiun = $r->jaminan_pensiun;
			  $pph = $r->pph;
			  $penalty_late = $r->penalty_late;
			  $penalty_attend = $r->penalty_attend;
			  $deduction = $r->deduction;
			  $simpanan_pokok = $r->simpanan_pokok;
			  $simpanan_wajib_koperasi = $r->simpanan_wajib_koperasi;
			  $pembayaran_pinjaman = $r->pembayaran_pinjaman;
			  $biaya_admin_bank = $r->biaya_admin_bank;
			  $total = $r->total;

				$now = new DateTime(date("Y-m-d"));

				$isExist = $this->Employees_model->CheckExistNIP_Periode($r->nip, $r->periode);


				if(!is_null($r->status_error)){
					if($r->status_error=='Duplicate'){

						$error = '<p style="color:#6b4141">Gagal Tersimpan (Duplikat NIK)</p>';
					} else {
						$error = '<p style="color:#75b37f">Success Import</p>';
					}
				} else {

						if(!is_null($isExist)){
							// $status_btn = 'btn-success'; 
							// $status_title = $this->lang->line('xin_employees_active');

							$error = '<p style="color:#f95275">NIK-PERIODE Sudah Terdaftar</p>';

						}else {
							$status_btn = 'btn-outline-danger'; 
							$status_title = $this->lang->line('xin_employees_inactive');

							$error = '
								<div class="btn-group btn-success" data-toggle="tooltip" data-state="primary" data-placement="top">
									<div>
										<a class="dropdown-item inserttemp" href="javascript:void(0)" data-status="1" data-user-id="'.$r->secid.'" style="color: azure;">Import</a>
									</div>
								</div>';
						}
				}


		   $data[] = array(
		   	$error,
				// $importid,
				$nip,
				$fullname,
				$periode,
				$project,
				$project_sub,
				$area,
				$hari_kerja,
				$gaji_pokok,
			  $allow_jabatan,
			  $allow_konsumsi,
			  $allow_transport,
			  $allow_rent,
			  $allow_comunication,
			  $allow_parking,
			  $allow_residence_cost,
			  $allow_device,
			  $penyesuaian_umk,
			  $insentive,
			  $overtime,
			  $overtime_national_day,
			  $overtime_rapel,
			  $kompensasi,
			  $bonus,
			  $thr,
			  $bpjs_tk_deduction,
			  $bpjs_ks_deduction,
			  $jaminan_pensiun_deduction,
			  $pendapatan,
			  $bpjs_tk,
			  $bpjs_ks,
			  $jaminan_pensiun,
			  $pph,
			  $penalty_late,
			  $penalty_attend,
			  $deduction,
			  $simpanan_pokok,
			  $simpanan_wajib_koperasi,
			  $pembayaran_pinjaman,
			  $biaya_admin_bank,
			  $total,
		   );
     }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $employees_temp->num_rows(),
                 "recordsFiltered" => $employees_temp->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
    }

	// Validate and update status info in database // status info
	public function temp_to_primary() {
		/* Define return | here result is used to return user data and error for error message */
		$status_id = $this->uri->segment(4);
		// if($status_id == 2){
		// 	$status_id = 0;
		// }
		// $user_id = $this->uri->segment(5);
		$user = $this->Xin_model->read_eslip_temp_info($status_id);
		$duplicate = $this->Employees_model->read_eslip_info_by_nip_periode($user[0]->nip, $user[0]->periode);


			if(!is_null($duplicate)) {
				// $error = 'Error';

				$datas = array(
					'status_error' => 'Duplicate',
				);

				$this->Employees_model->update_error_eslip_temp($datas, $user[0]->secid);

			} else {

				$uploadid = $user[0]->uploadid;
				$nip = $user[0]->nip;
				$fullname = $user[0]->fullname;
				$periode = $user[0]->periode;
				$project = $user[0]->project;
				$project_sub = $user[0]->project_sub;
				$area = $user[0]->area;
				$hari_kerja = $user[0]->hari_kerja;
				$gaji_pokok = $user[0]->gaji_pokok;
				$allow_jabatan = $user[0]->allow_jabatan;
				$allow_konsumsi = $user[0]->allow_konsumsi;
				$allow_transport = $user[0]->allow_transport;
				$allow_rent = $user[0]->allow_rent;
				$allow_comunication = $user[0]->allow_comunication;
				$allow_parking = $user[0]->allow_parking;
				$allow_residence_cost = $user[0]->allow_residence_cost;
				$allow_device = $user[0]->allow_device;
				$penyesuaian_umk = $user[0]->penyesuaian_umk;
				$insentive = $user[0]->insentive; 
				$overtime = $user[0]->overtime;
				$overtime_national_day = $user[0]->overtime_national_day;
				$overtime_rapel = $user[0]->overtime_rapel;
				$kompensasi = $user[0]->kompensasi;
				$bonus = $user[0]->bonus;
				$thr = $user[0]->thr;
				$bpjs_tk_deduction = $user[0]->bpjs_tk_deduction;
				$bpjs_ks_deduction = $user[0]->bpjs_ks_deduction;
				$jaminan_pensiun_deduction = $user[0]->jaminan_pensiun_deduction;
				$pendapatan = $user[0]->pendapatan;
				$bpjs_tk = $user[0]->bpjs_tk;
				$bpjs_ks = $user[0]->bpjs_ks; 
				$jaminan_pensiun = $user[0]->jaminan_pensiun;
				$pph = $user[0]->pph;
				$penalty_late = $user[0]->penalty_late;
				$penalty_attend = $user[0]->penalty_attend;
				$deduction = $user[0]->deduction;
				$simpanan_pokok = $user[0]->simpanan_pokok;
				$simpanan_wajib_koperasi = $user[0]->simpanan_wajib_koperasi;
				$pembayaran_pinjaman = $user[0]->pembayaran_pinjaman;
				$biaya_admin_bank = $user[0]->biaya_admin_bank;
				$adjustment = $user[0]->adjustment;
				$total = $user[0]->total;

				$data = array(
					'uploadid' => $uploadid,
					'nip' => $nip,
					'fullname' => $fullname,
					'periode' => $periode,
					'project' => $project,
					'project_sub' => $project_sub,
					'area' => $area,
					'hari_kerja' => $hari_kerja,
					'gaji_pokok' => $gaji_pokok,
					'allow_jabatan' => $allow_jabatan,
					'allow_konsumsi' => $allow_konsumsi,
					'allow_transport' => $allow_transport,
					'allow_rent' => $allow_rent,
					'allow_comunication' => $allow_comunication,
					'allow_parking' => $allow_parking,
					'allow_residence_cost' => $allow_residence_cost,
					'allow_device' => $allow_device,
					'penyesuaian_umk' => $penyesuaian_umk,
					'insentive' => $insentive,
					'overtime' => $overtime,
					'overtime_national_day' => $overtime_national_day,
					'overtime_rapel' => $overtime_rapel,
					'kompensasi' => $kompensasi,
					'bonus' => $bonus,
					'thr' => $thr,
					'bpjs_tk_deduction' => $bpjs_tk_deduction,
					'bpjs_ks_deduction' => $bpjs_ks_deduction,
					'jaminan_pensiun_deduction' => $jaminan_pensiun_deduction,
					'pendapatan' => $pendapatan,
					'bpjs_tk' => $bpjs_tk,
					'bpjs_ks' => $bpjs_ks,
					'jaminan_pensiun' => $jaminan_pensiun,
					'pph' => $pph,
					'penalty_late' => $penalty_late,
					'penalty_attend' => $penalty_attend,
					'deduction' => $deduction,
					'simpanan_pokok' => $simpanan_pokok,
					'simpanan_wajib_koperasi' => $simpanan_wajib_koperasi,
					'pembayaran_pinjaman' => $pembayaran_pinjaman,
					'biaya_admin_bank' => $biaya_admin_bank,
					'adjustment' => $adjustment,
					'total' => $total,
					// 'created_by' => $session['user_id'],
				);

				//$id = $this->input->post('user_id');
				$this->Employees_model->addeslip($data);

				$datas = array(
					'status_error' => 'Success Import',
				);

				$this->Employees_model->update_error_eslip_temp($datas, $user[0]->secid);

				//$Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
				echo $user[0]->nip.' '.$this->lang->line('xin_employee_status_updated');

			}

		//$this->output($Return);
		//exit;
	}
	
	// Validate and update status info in database // status info
	public function temp_to_primary_all() {
		/* Define return | here result is used to return user data and error for error message */
		// $status_id = $this->uri->segment(4);
		// $session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$upload_id = $this->uri->segment(4);


		$tempEmployees = $this->Import_model->get_temp_eslip($upload_id);

		for($i=0; $i< count($tempEmployees); $i++){



				$user = $this->Xin_model->read_eslip_temp_info($tempEmployees[$i]->secid);
				$duplicate = $this->Employees_model->read_eslip_info_by_nip_periode($user[0]->nip, $user[0]->periode);


					if(!is_null($duplicate)) {
						// $error = 'Error';

						$datas = array(
							'status_error' => 'Duplicate',
						);

						$this->Employees_model->update_error_eslip_temp($datas, $user[0]->secid);

					} else {

				$uploadid = $user[0]->uploadid;
				$nip = $user[0]->nip;
				$fullname = $user[0]->fullname;
				$periode = $user[0]->periode;
				$project = $user[0]->project;
				$project_sub = $user[0]->project_sub;
				$area = $user[0]->area;
				$hari_kerja = $user[0]->hari_kerja;
				$gaji_pokok = $user[0]->gaji_pokok;
				$allow_jabatan = $user[0]->allow_jabatan;
				$allow_konsumsi = $user[0]->allow_konsumsi;
				$allow_transport = $user[0]->allow_transport;
				$allow_rent = $user[0]->allow_rent;
				$allow_comunication = $user[0]->allow_comunication;
				$allow_parking = $user[0]->allow_parking;
				$allow_residence_cost = $user[0]->allow_residence_cost;
				$allow_device = $user[0]->allow_device;
				$penyesuaian_umk = $user[0]->penyesuaian_umk;
				$insentive = $user[0]->insentive; 
				$overtime = $user[0]->overtime;
				$overtime_national_day = $user[0]->overtime_national_day;
				$overtime_rapel = $user[0]->overtime_rapel;
				$kompensasi = $user[0]->kompensasi;
				$bonus = $user[0]->bonus;
				$thr = $user[0]->thr;
				$bpjs_tk_deduction = $user[0]->bpjs_tk_deduction;
				$bpjs_ks_deduction = $user[0]->bpjs_ks_deduction;
				$jaminan_pensiun_deduction = $user[0]->jaminan_pensiun_deduction;
				$pendapatan = $user[0]->pendapatan;
				$bpjs_tk = $user[0]->bpjs_tk;
				$bpjs_ks = $user[0]->bpjs_ks; 
				$jaminan_pensiun = $user[0]->jaminan_pensiun;
				$pph = $user[0]->pph;
				$penalty_late = $user[0]->penalty_late;
				$penalty_attend = $user[0]->penalty_attend;
				$deduction = $user[0]->deduction;
				$simpanan_pokok = $user[0]->simpanan_pokok;
				$simpanan_wajib_koperasi = $user[0]->simpanan_wajib_koperasi;
				$pembayaran_pinjaman = $user[0]->pembayaran_pinjaman;
				$biaya_admin_bank = $user[0]->biaya_admin_bank;
				$adjustment = $user[0]->adjustment;
				$total = $user[0]->total;
				// $userid = $session['user_id'];

				$data = array(
					'uploadid' => $uploadid,
					'nip' => $nip,
					'fullname' => $fullname,
					'periode' => $periode,
					'project' => $project,
					'project_sub' => $project_sub,
					'area' => $area,
					'hari_kerja' => $hari_kerja,
					'gaji_pokok' => $gaji_pokok,
					'allow_jabatan' => $allow_jabatan,
					'allow_konsumsi' => $allow_konsumsi,
					'allow_transport' => $allow_transport,
					'allow_rent' => $allow_rent,
					'allow_comunication' => $allow_comunication,
					'allow_parking' => $allow_parking,
					'allow_residence_cost' => $allow_residence_cost,
					'allow_device' => $allow_device,
					'penyesuaian_umk' => $penyesuaian_umk,
					'insentive' => $insentive,
					'overtime' => $overtime,
					'overtime_national_day' => $overtime_national_day,
					'overtime_rapel' => $overtime_rapel,
					'kompensasi' => $kompensasi,
					'bonus' => $bonus,
					'thr' => $thr,
					'bpjs_tk_deduction' => $bpjs_tk_deduction,
					'bpjs_ks_deduction' => $bpjs_ks_deduction,
					'jaminan_pensiun_deduction' => $jaminan_pensiun_deduction,
					'pendapatan' => $pendapatan,
					'bpjs_tk' => $bpjs_tk,
					'bpjs_ks' => $bpjs_ks,
					'jaminan_pensiun' => $jaminan_pensiun,
					'pph' => $pph,
					'penalty_late' => $penalty_late,
					'penalty_attend' => $penalty_attend,
					'deduction' => $deduction,
					'simpanan_pokok' => $simpanan_pokok,
					'simpanan_wajib_koperasi' => $simpanan_wajib_koperasi,
					'pembayaran_pinjaman' => $pembayaran_pinjaman,
					'biaya_admin_bank' => $biaya_admin_bank,
					'adjustment' => $adjustment,
					'total' => $total,	
				);

						//$id = $this->input->post('user_id');
						$this->Employees_model->addeslip($data);

						$datas = array(
							'status_error' => 'Success Import',
						);

						$this->Employees_model->update_error_eslip_temp($datas, $user[0]->secid);
		}

				//$Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
				echo $user[0]->employee_id.' '.$this->lang->line('xin_employee_status_updated');

			}

		//$this->output($Return);
		//exit;
	}


	public function view() {

		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}

		$system = $this->Xin_model->read_setting_info(1);		
		 // create new PDF document
   	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$vpin = $this->uri->segment(4);
		$ideslip = $this->uri->segment(5);
		// $employee_id = $this->uri->segment(5);


		if($session['employee_id'] != $vpin) {
			redirect('admin/');
		}
				// $cekEmp = $this->Employees_model->read_employee_info_by_nik($vpin);


				// $cekEslip = $this->Import_model->get_eslip_by_id($ideslip);
				// if(!is_null($cekEslip)){
				// 	redirect('admin/');
				// }


		// $eslip = $this->Employees_model->read_eslip_info_by_nip_periode($vpin, $vperiode);
		$eslip = $this->Employees_model->read_eslip_info_by_id($ideslip);
		$employee = $this->Employees_model->read_employee_info_by_nik($vpin);

		$designation = $this->Designation_model->read_designation_information($employee[0]->designation_id);
				if(!is_null($designation)){
					$jabatan = $designation[0]->designation_name;
				} else {
					$jabatan = '--';	
				}

		// if($eslip[0]->status_kirim==1){
			// $bMargin = $this->getBreakMargin();
			// $bMargi = $this->getBreakMargin();


			// set document information
			$pdf->SetCreator('PT Siprama Cakrawala');
			$pdf->SetAuthor('PT Siprama Cakrawala');
			// $baseurl=base_url();

			$header_namae = 'PT. Siprama Cakrawala';
			$header_string = 'HR Power Services | Facility Services'."\n".'Gedung Graha Krista Aulia, Jalan Andara Raya No. 20, Pangakalan Jati Baru, Kecamatan Cinere, Kota Depok 16513, Telp: (021) 27813599';

			$pdf->SetHeaderData(PDF_HEADER_LOGO, 35, $header_namae, $header_string);
			
			$pdf->setFooterData(array(0,64,0), array(0,64,128));
		
			// set header and footer fonts
			// $pdf->setHeaderFont(Array('helvetica', '', 20));
			// $pdf->setFooterFont(Array('helvetica', '', 9));
		
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont('courier');
			
			// set margins
			$pdf->SetMargins(15, 27, 15);
			$pdf->SetHeaderMargin(5);
			$pdf->SetFooterMargin(10);
			
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 25);
			
			// set image scale factor
			$pdf->setImageScale(10);


			$pdf->SetAuthor('HRCakrawala');
			// $pdf->SetProtection(array('print','copy'),$employee[0]->private_code,null, 0, null);

			$pdf->SetTitle('PT. Siprama Cakrawala '.' - '.$this->lang->line('xin_eslip'));
			$pdf->SetSubject($this->lang->line('xin_eslip'));
			$pdf->SetKeywords($this->lang->line('xin_eslip'));
			// set font
			$pdf->SetFont('helvetica', 'B', 10);
					
			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 9));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 12);
			
			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			// ---------------------------------------------------------

			// set default font subsetting mode
			$pdf->setFontSubsetting(true);
			
			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			$pdf->SetFont('helvetica', '', 9, '', true);
			
			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();
		
			// set cell padding
			$pdf->setCellPaddings(1, 1, 1, 1);
			
			// set cell margins
			$pdf->setCellMargins(0, 0, 0, 0);
			
			// set color for background
			$pdf->SetFillColor(255, 255, 127);
			/////////////////////////////////////////////////////////////////////////////////

			if(!is_null($eslip)){

				// $tanggal = $this->Xin_model->tgl_indo($eslip[0]->tanggal);
				$nip = $eslip[0]->nip;
				$namalengkap = $employee[0]->first_name;
				$periode = $eslip[0]->periode;
				$project = $eslip[0]->project;
				$area = $eslip[0]->area;
				$hari_kerja = $eslip[0]->hari_kerja;
				$gaji_pokok = $eslip[0]->gaji_pokok;
				$allow_jabatan = $eslip[0]->allow_jabatan;
				$allow_konsumsi = $eslip[0]->allow_konsumsi;
				$allow_transport = $eslip[0]->allow_transport;
				$allow_rent = $eslip[0]->allow_rent;
				$allow_comunication = $eslip[0]->allow_comunication;
				$allow_parking = $eslip[0]->allow_parking;
				$allow_residence_cost = $eslip[0]->allow_residence_cost;
				$allow_device = $eslip[0]->allow_device;
				$penyesuaian_umk = $eslip[0]->penyesuaian_umk;
				$insentive = $eslip[0]->insentive;
				$overtime = $eslip[0]->overtime;
				$overtime_national_day = $eslip[0]->overtime_national_day;
				$overtime_rapel = $eslip[0]->overtime_rapel;
				$kompensasi = $eslip[0]->kompensasi;
				$bonus = $eslip[0]->bonus;
				$thr = $eslip[0]->thr;
				$bpjs_tk_deduction = $eslip[0]->bpjs_tk_deduction;
				$bpjs_ks_deduction = $eslip[0]->bpjs_ks_deduction;
				$jaminan_pensiun_deduction = $eslip[0]->jaminan_pensiun_deduction;
				$pendapatan = $eslip[0]->pendapatan;
				$bpjs_tk = $eslip[0]->bpjs_tk;
				$bpjs_ks = $eslip[0]->bpjs_ks;
				$jaminan_pensiun = $eslip[0]->jaminan_pensiun;
				$pph = $eslip[0]->pph;
				$penalty_late = $eslip[0]->penalty_late;
				$penalty_attend = $eslip[0]->penalty_attend;
				$deduction = $eslip[0]->deduction;
				$simpanan_pokok = $eslip[0]->simpanan_pokok;
				$simpanan_wajib_koperasi = $eslip[0]->simpanan_wajib_koperasi;
				$pembayaran_pinjaman = $eslip[0]->pembayaran_pinjaman;
				$biaya_admin_bank = $eslip[0]->biaya_admin_bank;
				$adjustment = $eslip[0]->adjustment;
				$total = $eslip[0]->total;
				$monyear =  date('M Y');
				$tanggalcetak = date("Y-m-d");
	

				// $pengirim = $eslip[0]->nip;
				  // if(!is_null($pengirim)){
				  // 	$supplier_name = $pengirim[0]->name;
				  // } else {
					 //  $supplier_name = '--';	
				  // }

				// $transporter = $eslip[0]->nip;
				  // if(!is_null($transporter)){
				  // 	$trans_name = $transporter[0]->name;
				  // } else {
					 //  $trans_name = '--';	
				  // }


				// $tujuan = $this->Kbm_model->read_suppdis($SJ[0]->tujuan);
				//   if(!is_null($tujuan)){
				//   	$nama_tujuan = $tujuan[0]->name;
				//   } else {
				// 	  $nama_tujuan = '--';	
				//   }


				// $distributor_alamat = $this->Kbm_model->read_distributor_alamat($SJ[0]->alamat_tujuan);
				//   if(!is_null($distributor_alamat)){
				//   	$alamat_tujuan = $distributor_alamat[0]->lokasi;
				//   } else {
				// 	  $alamat_tujuan = '--';	
				//   }

				// $armada = $eslip[0]->nip;
				  // if(!is_null($armada)){
				  // 	$platnomor = $armada[0]->no_polisi;
				  // } else {
					 //  $platnomor = '--';	
				  // }


			} else {
				redirect('admin/');

			}


			$tbl_2 = '
			<div style="text-align: center; text-justify: inter-word;">
				<b>SLIP GAJI</b>
			</div>
			<br>

			<table cellpadding="0" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>NIP</td>
								<td colspan="2">: '.$nip.'</td>
							</tr>
						</table>
					</td>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Project</td>
								<td colspan="2">: '.strtoupper($project).'</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Nama</td>
								<td colspan="2">: '.strtoupper($namalengkap).'</td>
							</tr>
						</table>
					</td>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Area</td>
								<td colspan="2">: '.strtoupper($area).'</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Periode</td>
								<td colspan="2">: '.strtoupper($periode).'</td>
							</tr>
						</table>
					</td>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Hari Kerja</td>
								<td colspan="2">: '.$hari_kerja.'</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td>Posisi/Jabatan</td>
								<td colspan="2">: '.$jabatan.'</td>
							</tr>
						</table>
					</td>
					<td>
					</td>
				</tr>

			</table>
			<br><br>
			<table cellpadding="3" cellspacing="0" border="1" style="text-align: justify; text-justify: inter-word;">
			</table>
			<br><br>

			<table cellpadding="0" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Gaji Pokok</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($gaji_pokok).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';

				if($allow_jabatan!=0){
				$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Jabatan</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_jabatan).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
				}


if($allow_konsumsi!=0){
				$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Makan/Meal</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_konsumsi).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($allow_transport!=0){
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Transportasi</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_transport).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($allow_rent!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Sewa/Rent</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_rent).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($allow_comunication!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Komunikasi/Pulsa</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_comunication).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($allow_parking!=0){
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Parkir</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_parking).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($allow_residence_cost!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Tempat Tinggal</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_residence_cost).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}


if($allow_device!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Laptop</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_device).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}


			if($allow_kasir!=0){	
			$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Kasir</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_kasir).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
			}



			if($allow_trans_meal!=0){	
			$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Transport + Meal</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_trans_meal).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
			}

if($allow_vitamin!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Tunjangan Medicine</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($allow_vitamin).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($penyesuaian_umk!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Penyesuaian UMK/Meal/Transport/Sewa/Pulsa</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($penyesuaian_umk).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($insentive!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Insentif</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($insentive).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($overtime!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Lembur</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($overtime).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($overtime_national_day!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Lembur Libur Nasional</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($overtime_national_day).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($overtime_rapel!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Rapel Lembur</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($overtime_rapel).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($kompensasi!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Kompensasi</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($kompensasi).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}

if($bonus!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Bonus</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($bonus).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}


if($thr!=0){	
$tbl_2 .= '
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">THR</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($thr).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>';
}
	
$tbl_2 .= '
			</table>

			<table cellpadding="0" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="2">Deduction</td>
								<td colspan="0"></td>
								<td colspan="0" align="right"></td>
								<td colspan="6" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="2">*BPJS Ketenagakerjaan</td>
								<td colspan="0">: Rp.</td>
								<td colspan="0" align="right">'.$this->Xin_model->rupiah_titik($bpjs_tk_deduction).' &nbsp;&nbsp;&nbsp;</td>
								<td colspan="6" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="2">*BPJS Kesehatan</td>
								<td colspan="0">: Rp.</td>
								<td colspan="0" align="right">'.$this->Xin_model->rupiah_titik($bpjs_ks_deduction).' &nbsp;&nbsp;&nbsp;</td>
								<td colspan="6" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="2">*Jaminan Pensiun</td>
								<td colspan="0">: Rp.</td>
								<td colspan="0" align="right">'.$this->Xin_model->rupiah_titik($jaminan_pensiun_deduction).' &nbsp;&nbsp;&nbsp;</td>
								<td colspan="6" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>

			</table>


			<br>
			<table cellpadding="3" cellspacing="0" border="0.5" style="text-align: justify; text-justify: inter-word;">
			</table>
			<br>

			<table cellpadding="0" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4"><b>Pendapatan</b></td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right"><b>'.$this->Xin_model->rupiah_titik($pendapatan).'&nbsp;&nbsp;&nbsp;</b> &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4"><u><i>Potongan</i></u></td>
								<td colspan="2"></td>
								<td colspan="2" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">*BPJS Ketenagakerjaan</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($bpjs_tk).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">*BPJS Kesehatan</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($bpjs_ks).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">*Jaminan Pensiun</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($jaminan_pensiun).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">*Deposit</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($deposit).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">*PPH Karyawan (5%)</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($pph).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Penalty Keterlambatan</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($penalty_late).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Penalty Kehadiran</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($penalty_attend).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Deduction</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($deduction).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Simpanan Pokok</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($simpanan_pokok).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Simpanan Wajib Koperasi</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($simpanan_wajib_koperasi).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Pembayaran Pinjaman</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($pembayaran_pinjaman).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4">Biaya Admin Bank</td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($biaya_admin_bank).' &nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>';

if($adjustment!=0){
$tbl_2 .= '

			<br>
			<table cellpadding="3" cellspacing="0" border="0.5" style="text-align: justify; text-justify: inter-word;">
			</table>
			<br>

			<table cellpadding="0" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4"><i>Adjustment</i></td>
								<td colspan="2">: Rp.</td>
								<td colspan="2" align="right">'.$this->Xin_model->rupiah_titik($adjustment).'&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
}
$tbl_2 .= '
			<br><br>


			<table cellpadding="4" cellspacing="0" border="0" style="text-align: justify; text-justify: inter-word; background-color: #ffffff; filter: alpha(opacity=40); opacity: 1;border:1;">
				<tr>
					<td>
						<table cellpadding="1" cellspacing="0">
							<tr>
								<td colspan="4"><b>TOTAL PENDAPATAN DITERIMA</b></td>
								<td colspan="2"><b>: Rp.</b></td>
								<td colspan="2" align="right"><b>'.$this->Xin_model->rupiah_titik($total).' &nbsp;&nbsp;</b> </td>
							</tr>
						</table>
					</td>
				</tr>

			</table>
			
			<p style="font-size: 10px;"><i>*Segala bentuk pengembalian terhadap pendapatan diatas hanya dilakukan ke Rekening Perusahaan PT. SIPRAMA CAKRAWALA.</i></p>

			';
			$pdf->writeHTML($tbl_2, true, false, false, false, '');


			$tbl_ttd = '';
			$pdf->writeHTML($tbl_ttd, true, false, false, false, '');


			$lampiran = '';
			$pdf->writeHTML($lampiran, true, false, false, false, '');
		
			$fname = strtolower($fname);
			$pay_month = strtolower(date("F Y"));
			//Close and output PDF document
			ob_start();
			$pdf->Output('eslip'.$fname.'_'.$pay_month.'.pdf', 'I');
			ob_end_flush();

		// } else {
		//  	echo '<script>alert("ORDER BELUM DI PROSES...!  \nPlease Contact Admin For Approval..!"); window.close();</script>';
		// 	// redirect('admin/pkwt');
		// }
	}



} 
?>