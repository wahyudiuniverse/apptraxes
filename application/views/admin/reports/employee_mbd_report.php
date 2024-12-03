<?php
/* Date Wise Attendance Report > EMployees view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="row">
    <div class="col-md-12 <?php echo $get_animate;?>">
        <div class="ui-bordered px-4 pt-4 mb-4">
        <input type="hidden" id="user_id" value="0" />
        <?php $attributes = array('name' => 'attendance_datewise_report', 'id' => 'attendance_datewise_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
		<?php $hidden = array('euser_id' => $session['user_id']);?>
        <?php echo form_open('admin/reports/employee_mbd_report', $attributes, $hidden);?>
        <?php
            $data = array(
              'name'        => 'user_id',
              'id'          => 'user_id',
              'value'       => $session['user_id'],
              'type'   		  => 'hidden',
              'class'       => 'form-control',
            );
            echo form_input($data);
            ?>

<!-- form row  -->
          <div class="form-row">
            <div class="col-md mb-3">
            <label class="form-label">Projects</label>
              <select class="form-control" name="project" id="project" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_projects');?>">
                  <option value="0">--</option>
                  <?php if($session['user_id']=='9197'){ echo '<option value="22">PT. SIPRAMA CAKRAWALA</option>'; }?>
                <?php foreach($all_projects as $proj) {?>
                  <option value="<?php echo $proj->project_id;?>"> <?php echo $proj->title;?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md mb-3" id="dropdown_employee">
              <label class="form-label">Users</label>
              <select class="form-control" id="employee_select" name="employee_select" data-plugin="select_hrm" data-placeholder="Sub Project">
              <option value="0">--</option>
              </select> 
            </div>
                      
            <div class="col-md mb-3" id="dropdown_toko">
              <label class="form-label">Toko</label>
              <select class="form-control" id="toko_select" name="toko_select" data-plugin="select_hrm" data-placeholder="Sub Project">
                <option value="0">--</option>
              </select>
            </div>

            <div class="col-md mb-3">
                <label class="form-label">Start Date</label>
                <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" id="select_start_date" type="text" value="<?php echo date('Y-m-d');?>">
            </div>
            
            <div class="col-md mb-3">
              <label class="form-label">End Date</label>
              <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" id="select_end_date" type="text" value="<?php echo date('Y-m-d');?>">
            </div>

            <div class="col-md col-xl-2 mb-4">
              <label class="form-label d-none d-md-block">&nbsp;</label>
              <button type="button" onclick="kelistmbdreport()" class="btn btn-primary btn-block"><?php echo $this->lang->line('xin_get');?></button>
            </div>

          </div>
          <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- <div class="row m-b-1 <?php echo $get_animate;?>">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong><?php echo $this->lang->line('xin_hr_reports_attendance_employee');?></strong></span> </div>
      <div class="card-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th colspan="2"><?php echo $this->lang->line('xin_hr_info');?></th>
                <th colspan="9"><?php echo $this->lang->line('xin_attendance_report');?></th>
              </tr>
              <tr>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Project</th>
                <th>Sub Project</th>
                <th>Posisi/Jabatan</th>
                <th>Area/Penempatan</th>
                <th>Status Visit</th>
                <th>ID Toko/Office</th>
                <th>Toko/Office</th>
                <th>Alamat/Lokasi</th>
                <th>Nama Pemilik</th>
                <th>Nomor Kontak</th>
                <th>Tanggal</th>
                <th><?php echo $this->lang->line('dashboard_clock_in');?></th>
                <th>Clock IN System</th>
                <th><?php echo $this->lang->line('dashboard_clock_out');?></th>
                <th>Clock Out System</th>
                <th>Koordinat</th>
                <th>Total Jam Kerja</th>
                <th>Foto IN</th>
                <th>Foto OUT</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div> -->
</div>

<!-- list data table MBD Report -->
<div class="card-header with-elements"> <span class="card-header-title mr-2"><strong> List </strong> MBD Report </span> </div>
      <div class="card-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="mbd_report_table">
            <thead>
              <!-- Column Text -->
              <tr>
                <th width="25px">ID Foto</th>
                <th>Tgl Foto</th>
                <th>Projek</th>
                <th>Nip Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Jabatan Karyawan</th>
                <th>ID Toko</th>
                <th>Nama Toko</th>
                <th>Area/Penempatan</th>
                <th>Foto</th>
                <th>Status Verifikasi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- logic ajax -->
 <!-- logic -->
<script type="text/javascript">
  $(document).ready(function() {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    //project-user
    $('#project').change(function() {
      // var nip = "<?php //echo $employee_id; ?>";
      var project_id = $("#project option:selected").val();
      // alert(project_id);
      // alert(csrfName);
      // alert(csrfHash);
      // AJAX request
      $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_project_0_mbd_report/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          project_id: project_id,
        },
        beforeSend: function() {
          // $('#dropdown_employee').html(" <label class='form-label'>User</label>  <div class='form-control'>LOADING...</div>");
        },
        success: function(response) {
          var res = jQuery.parseJSON(response);
          var data_result = res;
          $('#employee_select').find('option').not(':first').remove();
          // Add options
    //       $(response).each(function(index, data) {
    //         $('#employee_select').append('<option value="' + data['employee_id'] + '">' + data['fullname'] + '</option>');
    //       }).show();
            // Add options
            $.each(data_result, function(index, data) {
              $('#employee_select').append('<option value="' + data['employee_id'] + '">' + data['fullname'] + '</option>');
              // html_jabatan = html_jabatan + '<option value="' + data['customer_id'] + '" >' + data['customer_name'] + '</option>';
            });
        }
      });
    });

    // Sub Change - 
  $('#employee_select').change(function() {
      // var nip = "<?php //echo $employee_id; ?>";
      var employee_id = $("#employee_select option:selected").val();
      // alert(employee_id);
      $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_toko_by_user/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          employee_id: employee_id,
        },
        beforeSend: function() {
          // alert("masuk ajax");
          // $('#dropdown_employee').html(" <label class='form-label'>User</label>  <div class='form-control'>LOADING...</div>");
        },
        success: function(response) {
          // alert(response);
          var res = jQuery.parseJSON(response);
          var data_result = res;
          $('#toko_select').find('option').not(':first').remove().val();
          // Add options
    //       $(response).each(function(index, data) {
    //         $('#employee_select').append('<option value="' + data['employee_id'] + '">' + data['fullname'] + '</option>');
    //       }).show();
            // Add options
            $.each(data_result, function(index, data) {
              // $('#toko_select').append('<option value="' + data['customer_id'] + '">' + data['customer_name'] + '</option>');
              $('#toko_select').append('<option value="' + data['customer_id'] + '">' + data['customer_id'] + '</option>');
              // html_jabatan = html_jabatan + '<option value="' + data['customer_id'] + '" >' + data['customer_name'] + '</option>';
            });
        }
      });
    });
  });
</script>

<!-- Ajax data table saltab_table -->
<script>
  var saltab_table;

  function kelistmbdreport(){
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
  csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
  var project_id = $("#project option:selected").val();
  var employee_id = $("#employee_select option:selected").val();
  var toko_id = $("#toko_select option:selected").val();
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();

  // alert(project_id);
  // alert(employee_id);
  // alert(toko_id);
  // alert(start_date);
  // alert(end_date);

  // saltab_table
  saltab_table = $('#mbd_report_table').DataTable({
      "bDestroy": true,
      'processing': true,
      'serverSide': true,
      //'stateSave': true,
      'bFilter': true,
      'serverMethod': 'post',
      //'dom': 'plBfrtip',
      'dom': 'lBfrtip',
      "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
      //'columnDefs': [{
      //  targets: 11,
      //  type: 'date-eu'
      //}],
      'order': [
        [1, 'desc']
      ],
      'ajax': {
        'url': '<?= base_url() ?>admin/Employees/get_list_mbd_report',
        data: {
          [csrfName]: csrfHash,
          project_id: project_id,
          employee_id: employee_id,
          toko_id: toko_id,
          start_date: start_date,
          end_date: end_date,
        }, 
        error: function(xhr, ajaxOptions, thrownError) {
          alert("Status :" + xhr.status);
          alert("responseText :" + xhr.responseText);
        },
      },
      'columns': [{
          data: 'secid',
          // "orderable": false
        },
        {
          data: 'display_date',
          // "orderable": false,
          //searchable: true
        },
        {
          data: 'title',
          // "orderable": false,
          //searchable: true
        },
        {
          data: 'employee_id',
          //"orderable": false
        },
        {
          data: 'nama_karyawan',
          // "orderable": false,
        },
        {
          data: 'jabatan',
          // "orderable": false,
        },
        {
          data: 'id_toko',
          //"orderable": false,
        },
        {
          data: 'nama_toko',
          //"orderable": false,
        },
        {
          data: 'area_penempatan',
          // "orderable": false,
        },
        {
          data: 'foto',
          // "orderable": false,
        },
        {
          data: 'status_verfikasi',
          // "orderable": false,
        },
        
      ]
    });
  }
</script>

















































