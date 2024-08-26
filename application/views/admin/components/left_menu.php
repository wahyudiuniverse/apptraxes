<?php
$session = $this->session->userdata('username');
$theme = $this->Xin_model->read_theme_info(1);
// set layout / fixed or static
if ($theme[0]->right_side_icons == 'true') {
  $icons_right = 'expanded menu-icon-right';
} else {
  $icons_right = '';
}

if ($theme[0]->bordered_menu == 'true') {
  $menu_bordered = 'menu-bordered';
} else {
  $menu_bordered = '';
}

$user_info = $this->Xin_model->read_user_info($session['user_id']);
if ($user_info[0]->is_active != 1) {
  redirect('admin/');
}

$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
if (!is_null($role_user)) {
  $role_resources_ids = explode(',', $role_user[0]->role_resources);
} else {
  $role_resources_ids = explode(',', 0);
}
?>


<?php $system = $this->Xin_model->read_setting_info(1); ?>
<?php $arr_mod = $this->Xin_model->select_module_class($this->router->fetch_class(), $this->router->fetch_method()); ?>
<?php $count_emp_resign = $this->Xin_model->count_emp_resign(); ?>
<?php

if ($theme[0]->sub_menu_icons != '') {
  $submenuicon = $theme[0]->sub_menu_icons;
} else {
  $submenuicon = 'fa-circle-o';
}

// reports to
$reports_to = get_reports_team_data($session['user_id']);
?>

<?php
if ($user_info[0]->profile_picture != '' && $user_info[0]->profile_picture != 'no file') {
?>
  <?php $cpimg = base_url() . 'uploads/profile/' . $user_info[0]->profile_picture; ?>
<?php
} else {
?>

  <?php
  if ($user_info[0]->gender == 'Male') {
  ?>
    <?php $de_file = base_url() . 'uploads/profile/default_male.jpg'; ?>
  <?php
  } else {
  ?>
    <?php $de_file = base_url() . 'uploads/profile/default_female.jpg'; ?>
  <?php
  }
  ?>
  <?php $cpimg = $de_file; ?>
<?php
}
?>

<ul class="sidenav-inner py-1">
  <!-- Dashboards -->
  <li class="sidenav-item <?php if (!empty($arr_mod['active'])) echo $arr_mod['active']; ?>">
    <a href="<?php echo site_url('admin/dashboard'); ?>" class="sidenav-link">
      <i class="sidenav-icon ion ion-md-speedometer"></i>
      <div><?php echo $this->lang->line('dashboard_title'); ?></div>
    </a>
  </li>

  <!-- profile -->
  <?php
  if (in_array('132', $role_resources_ids)) {
  ?>

    <li class="sidenav-item <?php if (!empty($arr_mod['profile_active'])) echo $arr_mod['profile_active']; ?>">
      <a href="<?php echo site_url('admin/profile/'); ?>" class="sidenav-link">
        <!-- <i class="sidenav-icon fa fa-calculator"></i> -->
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div><?php echo $this->lang->line('header_my_profile'); ?></div>
      </a>
    </li>
  <?php
  }
  ?>


  <!-- CEK NIP -->
  <?php
  if (in_array('134', $role_resources_ids)) {
  ?>
    <li class="sidenav-item <?php if (!empty($arr_mod['ceknip_active'])) echo $arr_mod['ceknip_active']; ?>">
      <a href="<?php echo site_url('admin/ceknip/'); ?>" class="sidenav-link">
        <!-- <i class="sidenav-icon fa fa-calculator"></i> -->
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div>Check NIP</div>
      </a>
    </li>
  <?php
  }
  ?>





  <?php
  if (
    in_array('13', $role_resources_ids)
    || in_array('7', $role_resources_ids)
    || in_array('422', $role_resources_ids)
    || $reports_to > 0
    || $user_info[0]->user_role_id == 1
  ) {
  ?>

    <li class="<?php if (!empty($arr_mod['stff_open'])) echo $arr_mod['stff_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-user-friends"></i>
        <div><?php echo $this->lang->line('dashboard_employees'); ?></div>
      </a>

      <ul class="sidenav-menu">
        <?php
        if ($user_info[0]->user_role_id == 1) {
        ?>
          <?php
          if (in_array('422', $role_resources_ids)) {
          ?>

            <li class="sidenav-item <?php if (!empty($arr_mod['staff_active'])) echo $arr_mod['staff_active']; ?>">
              <a class="sidenav-link" href="<?php echo site_url('admin/employees/staff_dashboard/'); ?>"> <?php echo $this->lang->line('hr_staff_dashboard_title'); ?>
              </a>
            </li>
          <?php
          }
          ?>
        <?php
        }
        ?>

        <?php
        if (in_array('13', $role_resources_ids) || $reports_to > 0) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['hremp_active'])) echo $arr_mod['hremp_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/employees/'); ?>"> <?php echo $this->lang->line('dashboard_employees'); ?>
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if ($user_info[0]->user_role_id == 1) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['roles_active'])) echo $arr_mod['roles_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/roles/'); ?>"> <?php echo $this->lang->line('left_set_roles'); ?>
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('7', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['shift_active'])) echo $arr_mod['shift_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/timesheet/office_shift/'); ?>"> <?php echo $this->lang->line('left_office_shifts'); ?>
            </a>
          </li>
        <?php
        }
        ?>


      </ul>
    </li>
  <?php
  }
  ?>


  <!-- MENU CALL CENTER -->
  <?php
  if (in_array('479', $role_resources_ids)) {
  ?>
    <li class="<?php if (!empty($arr_mod['callcenter_open'])) echo $arr_mod['callcenter_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div><?php echo $this->lang->line('xin_menu_cs'); ?></div>
      </a>

      <ul class="sidenav-menu">
        <?php
        if (in_array('480', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['whatsapp_active'])) echo $arr_mod['whatsapp_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/Customerservices/'); ?>"> <?php echo $this->lang->line('xin_whatsapp_blast'); ?>
            </a>
          </li>
        <?php
        }
        ?>

      </ul>
    </li>
  <?php
  }
  ?>



  <!-- user mobile -->
  <?php
  if (in_array('59', $role_resources_ids)) {
  ?>
    <li class="<?php if (!empty($arr_mod['usermobile_open'])) echo $arr_mod['usermobile_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div><?php echo $this->lang->line('xin_user_mobile'); ?></div>
      </a>

      <ul class="sidenav-menu">
        <?php
        if (in_array('59', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['project_active'])) echo $arr_mod['project_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/usermobile/'); ?>"> <?php echo $this->lang->line('xin_user_mobile'); ?>
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('105', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['callplan_active'])) echo $arr_mod['callplan_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/callplan'); ?>"> <?php echo $this->lang->line('xin_callplan'); ?>
            </a>
          </li>
        <?php
        }
        ?>
      </ul>
    </li>
  <?php
  }
  ?>


  <!-- customer -->
  <?php
  if (in_array('69', $role_resources_ids)) {
  ?>
    <li class="<?php if (!empty($arr_mod['customer_open'])) echo $arr_mod['customer_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div>Database</div>
      </a>

      <ul class="sidenav-menu">
        <?php
        if (in_array('69', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['customer_active'])) echo $arr_mod['customer_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/customers/'); ?>"> Customers/Toko/Lokasi
            </a>
          </li>
        <?php
        }
        ?>
        <?php
        if (in_array('11923', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['clients_active'])) echo $arr_mod['clients_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/pkwtexpired'); ?>"> <?php echo $this->lang->line('xin_pkwt_expired'); ?>
            </a>
          </li>
        <?php
        }
        ?>

      </ul>
    </li>
  <?php
  }
  ?>



  <!-- MANAGE EMP -->
  <?php
  if (in_array('327', $role_resources_ids) || in_array('470', $role_resources_ids) || in_array('490', $role_resources_ids)) {
  ?>
    <li class="<?php if (!empty($arr_mod['emp_manage_open'])) echo $arr_mod['emp_manage_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div><?php echo $this->lang->line('xin_manage_employees'); ?></div>
      </a>

      <ul class="sidenav-menu">


        <?php
        if (in_array('470', $role_resources_ids)) { ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['man_employees_active'])) echo $arr_mod['man_employees_active']; ?>">
            <a href="<?php echo site_url('admin/reports/manage_employees'); ?>" class="sidenav-link">
              <!-- <i class="sidenav-icon fa fa-calculator"></i> -->
              <i class="sidenav-icon ion ion-logo-buffer"></i>
              <div>Employees</div>
            </a>
          </li>
        <?php
        }
        ?>

      </ul>
    </li>
  <?php
  }
  ?>

  <!-- organization -->
  <?php
  if (
    in_array('2', $role_resources_ids)
    || in_array('3', $role_resources_ids)
    || in_array('4', $role_resources_ids)
    || in_array('5', $role_resources_ids)
    || in_array('6', $role_resources_ids)
    || in_array('11', $role_resources_ids)
    || in_array('9', $role_resources_ids)
    || in_array('119', $role_resources_ids)
  ) {
  ?>
    <li class="<?php if (!empty($arr_mod['adm_open'])) echo $arr_mod['adm_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-md-business"></i>
        <div><?php echo $this->lang->line('left_organization'); ?></div>
      </a>
      <ul class="sidenav-menu">
        <?php
        if (in_array('5', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['com_active'])) echo $arr_mod['com_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/company/'); ?>"> <?php echo $this->lang->line('xin_companies'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('6', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['loc_active'])) echo $arr_mod['loc_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/location/'); ?>"> <?php echo $this->lang->line('xin_locations'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('3', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['dep_active'])) echo $arr_mod['dep_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/department/'); ?>"> <?php echo $this->lang->line('left_department'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('4', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['des_active'])) echo $arr_mod['des_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/designation/'); ?>"> <?php echo $this->lang->line('left_designation'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('44', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['project_active'])) echo $arr_mod['project_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/project/'); ?>"> <?php echo $this->lang->line('left_projects'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('130', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['sub_project_active'])) echo $arr_mod['sub_project_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/subproject/'); ?>"> <?php echo $this->lang->line('xin_pkwt_sub_project'); ?> </a>
          </li>
        <?php
        }
        ?>


        <?php
        if (in_array('207', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['akses_project_active'])) echo $arr_mod['akses_project_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/akses_project/'); ?>"> <?php echo $this->lang->line('xin_akses_project'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('478', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['esign_active'])) echo $arr_mod['esign_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/esign/'); ?>"> <?php echo $this->lang->line('xin_esign_register'); ?> </a>
          </li>
        <?php
        }
        ?>


        <?php
        if (in_array('9', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['pol_active'])) echo $arr_mod['pol_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/policy/'); ?>"> <?php echo $this->lang->line('header_policies'); ?> </a>
          </li>
        <?php
        }
        ?>
      </ul>
    </li>
  <?php
  }
  ?>





  <!-- mobile report -->
  <?php
  if (in_array('110', $role_resources_ids) || in_array('112', $role_resources_ids)) {
  ?>
    <li class="<?php if (!empty($arr_mod['reports_open'])) echo $arr_mod['reports_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon ion ion-logo-buffer"></i>
        <div><?php echo $this->lang->line('xin_report_mobileapp'); ?></div>
      </a>

      <ul class="sidenav-menu">
        <?php
        if (in_array('111', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['remployees_active'])) echo $arr_mod['remployees_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/reports/employee_attendance/'); ?>"> Report Check In-Out
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('131', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['sellout_active'])) echo $arr_mod['sellout_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/reports/employee_sellout/'); ?>"> Report Sell-Out
            </a>
          </li>
        <?php
        }
        ?>

         <?php
        if (in_array('121', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['sellin_active'])) echo $arr_mod['sellin_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/reports/employee_sellin/'); ?>"> Report Stock/Sell-in
            </a>
          </li>
        <?php
        }
        ?>

        
        <!-- KINO DISABLED -->
        <?php
        if (in_array('0009', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['rorder_active'])) echo $arr_mod['rorder_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/reports/report_order/'); ?>"> <?php echo $this->lang->line('xin_order_report'); ?>
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('151', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['rovertime_active'])) echo $arr_mod['rovertime_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/reports/employee_overtime/'); ?>"> Report lembur
            </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('105', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['callplan_active'])) echo $arr_mod['callplan_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/callplan'); ?>"> <?php echo $this->lang->line('xin_callplan'); ?>
            </a>
          </li>
        <?php
        }
        ?>
      </ul>
    </li>
  <?php
  }
  ?>

  <!-- invoice -->
  <?php
  if (
    in_array('121', $role_resources_ids)
    || in_array('330', $role_resources_ids)
    || in_array('122', $role_resources_ids)
    || in_array('426', $role_resources_ids)
  ) {
  ?>
    <li class="<?php if (!empty($arr_mod['invoices_open'])) echo $arr_mod['invoices_open']; ?> sidenav-item">
      <a href="#" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-file-invoice-dollar"></i>
        <div><?php echo $this->lang->line('xin_invoices_title'); ?></div>
      </a>
      <ul class="sidenav-menu">
        <?php
        if (in_array('121', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['invoices_inv_active'])) echo $arr_mod['invoices_inv_active']; ?>"> <a class="sidenav-link" href="<?php echo site_url('admin/invoices/'); ?>"> <?php echo $this->lang->line('xin_invoices_title'); ?> </a> </li>
        <?php
        }
        ?>

        <?php
        if (in_array('426', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['invoice_calendar_active'])) echo $arr_mod['invoice_calendar_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/invoices/invoice_calendar/'); ?>"> <?php echo $this->lang->line('xin_invoice_calendar'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('330', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['payments_history_inv_active'])) echo $arr_mod['payments_history_inv_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/invoices/payments_history/'); ?>"> <?php echo $this->lang->line('xin_acc_invoice_payments'); ?> </a>
          </li>
        <?php
        }
        ?>

        <?php
        if (in_array('122', $role_resources_ids)) {
        ?>
          <li class="sidenav-item <?php if (!empty($arr_mod['taxes_inv_active'])) echo $arr_mod['taxes_inv_active']; ?>">
            <a class="sidenav-link" href="<?php echo site_url('admin/invoices/taxes/'); ?>"> <?php echo $this->lang->line('xin_invoice_tax_type'); ?> </a>
          </li>
        <?php
        }
        ?>
      </ul>
    </li>
  <?php
  }
  ?>


</ul>