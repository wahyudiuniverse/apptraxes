<?php
/* Company view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $system = $this->Xin_model->read_setting_info(1);?>


<?php $count_emp_request_cancel = $this->Xin_model->count_emp_request_cancel($session['employee_id']);?>
<?php $count_emp_request_nae = $this->Xin_model->count_emp_request_nae($session['employee_id']);?>
<?php $count_emp_request_nom = $this->Xin_model->count_emp_request_nom($session['employee_id']);?>
<?php $count_emp_request_hrd = $this->Xin_model->count_emp_request_hrd($session['employee_id']);?>


<?php //$list_bank = $this->Xin_model->get_bank_code();?>
<!-- $data['list_bank'] = $this->Xin_model->get_bank_code(); -->

<div id="smartwizard-2" class="smartwizard-example sw-main sw-theme-default">
  <ul class="nav nav-tabs step-anchor">

    <?php if(in_array('337',$role_resources_ids)) { ?>
    <li class="nav-item clickable"> <a href="<?php echo site_url('admin/employee_request/');?>" data-link-data="<?php echo site_url('admin/employee_request/');?>" class="mb-3 nav-link hrpremium-link"> <span class="sw-icon fa fa-database"></span> AREA
      </a> </li>
    <?php } ?>  

    <?php if(in_array('374',$role_resources_ids)) { ?>
    <li class="nav-item clickable"> <a href="<?php echo site_url('admin/employee_request_nae/');?>" data-link-data="<?php echo site_url('admin/employee_request_nae/');?>" class="mb-3 nav-link hrpremium-link"> <span class="sw-icon ion ion-ios-paper"></span>KARYAWAN BARU <?php echo '('.$count_emp_request_nae.')';?>
      </a> </li>
    <?php } ?>


    <?php if(in_array('378',$role_resources_ids)) { ?>
    <li class="nav-item clickable"> <a href="<?php echo site_url('admin/employee_request_hrd/');?>" data-link-data="<?php echo site_url('admin/employee_request_hrd/');?>" class="mb-3 nav-link hrpremium-link"> <span class="sw-icon ion ion-ios-paper"></span> HO - HRD <?php echo '('.$count_emp_request_hrd.')';?>
      </a> </li>
    <?php } ?>

    <?php if(in_array('338',$role_resources_ids)) { ?>
    <li class="nav-item clickable"> <a href="<?php echo site_url('admin/Employee_request_cancelled/');?>" data-link-data="<?php echo site_url('admin/Employee_request_cancelled/');?>" class="mb-3 nav-link hrpremium-link"> <span class="sw-icon ion ion-ios-paper"></span> Ditolak <?php echo '('.$count_emp_request_cancel.')';?>
      </a> </li>
    <?php } ?>
    
    <?php if(in_array('337',$role_resources_ids)) { ?>
    <li class="nav-item active"> <a href="<?php echo site_url('admin/employee_request_approve/');?>" data-link-data="<?php echo site_url('admin/employee_request_approve/');?>" class="mb-3 nav-link hrpremium-link"> <span class="sw-icon ion ion-ios-paper"></span> Status Karyawan
      </a> </li>
    <?php } ?>


  </ul>
</div>

<hr class="border-light m-0 mb-3">
<?php $employee_id = $this->Xin_model->generate_random_employeeid();?>
<?php $employee_pincode = $this->Xin_model->generate_random_pincode();?>


<div class="card">
  <div class="card-header with-elements"> <span class="card-header-title mr-2">Monitoring <strong>Karyawan Baru</strong></span> </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th>No.</th>
            <th>NIK-KTP</th>
            <th><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_employees_full_name');?></th>
            <th><?php echo $this->lang->line('left_projects');?></th>
            <th><?php echo $this->lang->line('left_sub_projects');?></th>
            <th><?php echo $this->lang->line('left_department');?></th>
            <th><?php echo $this->lang->line('left_designation');?></th>
            <th><?php echo $this->lang->line('xin_placement');?></th>
            <th><?php echo $this->lang->line('xin_employee_doj');?></th>
            <th><?php echo $this->lang->line('xin_e_details_contact');?></th>
            <th><?php echo $this->lang->line('xin_request_employee_status');?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
