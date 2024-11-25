
<?php
/*
* Designation View
*/
$session = $this->session->userdata('username');
?>

<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>


<hr class="border-light m-0 mb-3">
<?php if(in_array('208',$role_resources_ids)) {?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<!-- Modal -->
<div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="validationModalLabel">Confirm Validation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

              <!-- Body Modal -->
              <div class="modal-body text-center">
                 <div id="dialog_mbd"> </div><br>

                 <input hidden type="text" id="hidden_validasi_mbd">
                 <input hidden type="text" id="hidden_tolak_mbd">
                    <!-- Tombol -->
                    <div class="d-flex justify-content-between">
                        <button id="rejectButton" type="button" class="btn btn-danger" onclick="tolakData()">Tolak</button>
                        <button id="validateButton" type="button" class="btn btn-success" onclick="validasiData()">Validasi</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


<div class="row m-b-1 <?php echo $get_animate;?>">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header with-elements"> 
      
    </div>
      <div class="card-body">
      <?php $attributes = array('name' => 'add_designation', 'id' => 'xin-form', 'autocomplete' => 'off');?>
      <?php $hidden = array('user_id' => $session['user_id']);?>


        <div class="form-group">
          <select class=" form-control" id="project" name="project" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_project');?>">
            <option value=""></option>
            <?php foreach($all_projects as $pro) {?>
            <option value="<?php echo $pro->project_id?>"><?php echo $pro->title ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="form-group" id="dropdown_employee">
          <select class=" form-control" id="employee" name="employee" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee');?>">
            <option value=""> Pilih Employee</option>
            
          </select>
        </div>

      <div class="form-actions">
      <label class="form-label d-none d-md-block">&nbsp;</label>
            <button onclick="filter_toko()" id="btn_filter" type="button" class="btn btn-primary btn-block"> FILTER </button>
      </div>

      <div id="list_toko"></div>

      <?php echo form_close(); ?> </div></div>
  </div>


  <?php $colmdval = 'col-md-8';?>
  <?php } else {?>
  <?php $colmdval = 'col-md-12';?>
  <?php } ?>
  <div class="<?php echo $colmdval;?>">
    <div class="card">

      <div class="row">
    <div class="col-md-12 <?php echo $get_animate;?>">
        <div class="ui-bordered px-4 pt-4 mb-4">
        <input type="hidden" id="user_id" value="0" />
        <?php $attributes = array('name' => 'employee_akses', 'id' => 'employee_akses', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
    <?php $hidden = array('euser_id' => $session['user_id']);?>
        <?php echo form_open('admin/akses_project/', $attributes, $hidden);?>
      <?php
        $data = array(
          'name'  => 'user_id',
          'id'    => 'user_id',
          'type'  => 'hidden',
          'value' => $session['user_id'],
          'class' => 'form-control');
            echo form_input($data);
      ?> 

      <!-- <div id="list_toko_detail"> </div> -->
          <!-- <div id="list_toko_detail_mbd"></div> -->

          <input hidden type="text" id="id_toko_selected">
      <table id="" class="table">
          <thead>
              <tr>
                  <th>ID Toko</th>
                  <th>Nama Toko</th>
                  <th>Tanggal Display</th>
                  <th>Foto</th>
                  <th>Verify Status</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody id="list_toko_detail"></tbody>  
      </table>

          <?php echo form_close(); ?>
        </div>

      </div>
    </div>

      <div class="card-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="">
          
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- <div id="list_toko_detail_mbd"></div> -->

<script type="text/javascript">
  $(document).ready(function() {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    // Sub Change - Jabatan
    $('#project').change(function() {
      // var nip = "<?php //echo $employee_id; ?>";
      var project_id = $("#project option:selected").val();

      // alert(project_id);

      // AJAX request
      $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_employee_by_project/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          project_id: project_id,
        },
        beforeSend: function() {
          $('#dropdown_employee').html("<div class='form-control'>LOADING...</div>");
        },
        success: function(response) {
          var res = jQuery.parseJSON(response);
          var data_result = res;
          var flag_select = "";
          var html_jabatan = '<select class="form-control" id="employee" name="employee" data-plugin="select_employee" data-placeholder="Employee">';
          html_jabatan = html_jabatan + '<option value="">Pilih Employee</option>';

          // Add options
          $.each(data_result, function(index, data) {
            html_jabatan = html_jabatan + '<option value="' + data['employee_id'] + '" >' + data['first_name'] + '</option>';
          });

          //write element
          $('#dropdown_employee').html(html_jabatan);

          //load select2 ke element dropdown jabatan
          $('[data-plugin="select_employee"]').select2({
            width: "100%",
            // dropdownParent: $("#container_modal_project")
          });
        }
      });
    });

    
    // // table
    // var saltab_table = $('#saltab_table2').DataTable({
    //   //"bDestroy": true,
    //   'processing': true,
    //   'serverSide': true,
    //   //'stateSave': true,
    //   'bFilter': true,
    //   'serverMethod': 'post',
    //   //'dom': 'plBfrtip',
    //   'dom': 'lBfrtip',
    //   "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
    //   //'columnDefs': [{
    //   //  targets: 11,
    //   //  type: 'date-eu'
    //   //}],
    //   'order': [
    //     [7, 'desc']
    //   ],
    //   'ajax': {
    //     'url': '<?= base_url() ?>admin/importexcel/list_batch_saltab_release_download',
    //     data: {
    //       [csrfName]: csrfHash,
    //       session_id: session_id,
    //       project: project,
    //       search_periode_from: search_periode_from,
    //       search_periode_to: search_periode_to,
    //       //base_url_catat: base_url_catat
    //     }, 
    //     error: function(xhr, ajaxOptions, thrownError) {
    //       alert("Status :" + xhr.status);
    //       alert("responseText :" + xhr.responseText);
    //     },
    //   },
    //   'columns': [{
    //       data: 'aksi',
    //       "orderable": false
    //     },
    //     {
    //       data: 'id_toko',
    //       // "orderable": false,
    //       //searchable: true
    //     },
    //     {
    //       data: 'nama_toko',
    //       "orderable": false,
    //       //searchable: true
    //     },
    //     {
    //       data: 'tanggal_display',
    //       //"orderable": false
    //     },
    //     {
    //       data: 'foto',
    //       //"orderable": false,
    //     },
    //     {
    //       data: 'status_verifikasi',
    //       "orderable": false,
    //     },
    //     // {
    //     //   data: 'release_by',
    //     //   //"orderable": false,
    //     // },
    //     // {
    //     //   data: 'release_on',
    //     //   //"orderable": false,
    //     // },
    //     // {
    //     //   data: 'release_eslip',
    //     //   "orderable": false,
    //     // },
    //   ]
    // });

  });
</script>

<!-- fungsi filter toko -->
<script>
 function filter_toko() {
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
  var project_id = $("#project option:selected").val();
  var employee_id = $("#employee option:selected").val();
  // alert(project_id);
  // alert(employee_id);w

  $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_toko_by_employee/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          employee_id: employee_id,
        },
        beforeSend: function() {
          // $('#dropdown_employee').html("<div class='form-control'>LOADING...</div>");
        },
        success: function(response) {
          var res = jQuery.parseJSON(response);
          var list_toko = "<table style='width:100%' class='mt-5'><tbody>";

          // Add options
          $.each(res, function(index, data) {
            list_toko = list_toko + '<tr><td><button onclick="show_toko('+data['customer_id']+')" type="button" class="btn  form-control mb-2">'+data['customer_name']+'</button> </td></tr>';
          });

          list_toko = list_toko + '</tbody></table>';

          //write element
          $('#list_toko').html(list_toko);

          //load select2 ke element dropdown jabatan
          $('[data-plugin="select_employee"]').select2({
            width: "100%",
            // dropdownParent: $("#container_modal_project")
          });
        }
      });

  }
</script>


<!-- fungsi show toko -->
<script>
 function show_toko(id_toko) {
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
  // alert(customer_id);
  // alert(employee_id);

  $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_toko_by_employee_detail/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          id_toko: id_toko,
        },
        beforeSend: function() {
          // $('#dropdown_employee').html("<div class='form-control'>LOADING...</div>");
        },
        success: function(response) {

          var res = jQuery.parseJSON(response);
          $('#id_toko_selected').val(id_toko);
          // var list_toko_detail = '';
          var list_toko_detail = "<table style='width:100 %' class='mt-5'> <tbody>";
          // // Add options
          $.each(res, function(index, data) {
          //   list_toko_detail = list_toko_detail + '<div class="row">';
          // list_toko_detail = list_toko_detail + '<div class="col-sm-12">';
          // list_toko_detail = list_toko_detail + '<div class="card">';
          // list_toko_detail = list_toko_detail + '<div class="card-body">';
          // list_toko_detail = list_toko_detail + '<div class="row">';
          // list_toko_detail = list_toko_detail + '<div class="col-sm-3 text-center align-self-center">';
          // list_toko_detail = list_toko_detail + '<img src="https://api.traxes.id/'+data["display_foto"]+'" height="150px">';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '<div class="col-sm-9 text-left align-self-center">';
          // list_toko_detail = list_toko_detail + '<h5 class="card-title">tanggal foto</h5>';
          // list_toko_detail = list_toko_detail + '<p class="card-text">';
          // list_toko_detail = list_toko_detail + 'Employee ID';
          // list_toko_detail = list_toko_detail + 'Status Verifikasi';
          // list_toko_detail = list_toko_detail + 'Verifikasi Oleh';
          // list_toko_detail = list_toko_detail + 'Waktu Verifikasi';
          // list_toko_detail = list_toko_detail + '</p>';
          // list_toko_detail = list_toko_detail + '<button type="button" onclick="show_mbd('+data['secid']+')" class="btn btn-primary"> Verifikasi </button>';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '</div>';
          // list_toko_detail = list_toko_detail + '</div>';

            // list_toko_detail = list_toko_detail + '<tr> <td><button onclick="show_mbd('+data['secid']+')" type="button" class="btn btn-outline-success mb-2">'+data['employee_id']+'</button> <button type="button" class="btn btn-danger"> Verifikasi </button></td>';
            // list_toko_detail = list_toko_detail + '<td><button onclick="show_mbd('+data['secid']+')" type="button" class="btn btn-outline-success mb-2"><img src="<?= base_url() ?>'+data['display_foto']+'"></button></td>';
            // list_toko_detail = list_toko_detail + '<td><p onclick="show_mbd('+data['secid']+')" type="" class=""><img src="https://api.traxes.id/'+data['display_foto']+'" height="50px" width="50px"></p></td>';
            list_toko_detail = list_toko_detail + ' <tr><td><p onclick="" type="" class="">'+data['customer_id']+'</p></td>';
            list_toko_detail = list_toko_detail + '<td><p onclick="" type="" class="">'+data['employee_id']+'</p></td>';
            list_toko_detail = list_toko_detail + ' <td><p onclick="" type="" class="">'+data['display_date']+'</p></td>';
            // list_toko_detail = list_toko_detail + ' <td><p onclick="show_mbd('+data['secid']+')" type="" class="">'+data['verify_by']+'</p></td>';
            // list_toko_detail = list_toko_detail + ' <td><p onclick="show_mbd('+data['secid']+')" type="" class="">'+data['verify_on']+'</p></td>';
            list_toko_detail = list_toko_detail + '<td><p onclick="show_mbd('+data['secid']+')" type="" class=""><img src="https://api.traxes.id/'+data['display_foto']+'" height="50px" width="70px"></p></td>';
            list_toko_detail = list_toko_detail + ' <td><p onclick="" type="" class="">'+data['verify_status']+'</p></td> </td>';
            // list_toko_detail = list_toko_detail + '<td><button onclick="show_mbd('+data['secid']+')" type="button" class="btn btn-outline-success mb-2"> Verifikasi </button> </td> </tr>';
            // list_toko_detail = list_toko_detail + '<tr><td><button onclick="show_mbd('+data['secid']+')" type="button" class="btn btn-outline-success form-control mb-1"><img src="https://api.traxes.id/'+data['display_foto']+'"></button> </td></tr>';

                // list_toko_detail += '<td>' + data['employee_id'] + '</td>'; // Nama toko
                // Cek status verifikasi
                if (data['verify_status'] == "0") {
                    list_toko_detail += `
                        <td>
                            <button onclick="show_mbd(${data['secid']})" 
                                type="button" class="btn btn-danger mb-2">
                                Verifikasi
                            </button>
                        </td>`;
                } else {
                    list_toko_detail += `
                        <td>
                            <span class="text-success">
                                VERIFIED [${data['verify_on']}]
                            </span>
                        </td>`;
                }
          }); 

          list_toko_detail = list_toko_detail + '</tbody></table>';

          //write element
          $('#list_toko_detail').html(list_toko_detail);s

          //load select2 ke element dropdown jabatan
          $('[data-plugin="select_employee"]').select2({
            width: "100%",
            // dropdownParent: $("#container_modal_project")
          });
        }
      });

  }
</script>

<!-- fungsi show toko -->
<script>
 function show_mbd(id_mbd) {
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

            $('#validationModal').appendTo("body").modal('show');
          // alert(id_mbd);
          // alert(employee_id);

  $.ajax({
        url: '<?= base_url() ?>admin/Employees/get_toko_by_employee_detail_mbd/',
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          id_mbd: id_mbd,
        },
        beforeSend: function() {
          // $('#dropdown_employee').html("<div class='form-control'>LOADING...</div>");
        },
        success: function(response) {
          var res = jQuery.parseJSON(response);
          // var dialog_mbd = "<table style='width:100%' class='mt-5'><tbody>";

          // Add options
          // $.each(res, function(index, data) {
           var dialog_mbd = '<img src="https://api.traxes.id/'+res['display_foto']+'" width="250px" height="250px">';
          // });

          $('#hidden_validasi_mbd').val(id_mbd);
          $('#hidden_tolak_mbd').val(id_mbd);

          // alert("https://api.traxes.id/"+res['display_foto']);
          //write element
          $('#dialog_mbd').html(dialog_mbd);

          //load select2 ke element dropdown jabatan
          $('[data-plugin="select_employee"]').select2({
            width: "100%",
            // dropdownParent: $("#container_modal_project")
          });
        }

      });
  }
</script>


<script>
  function validasiData() {
    var id_toko = $("#id_toko_selected").val();
    var id_mbd = $("#hidden_validasi_mbd").val();
    //
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    // Kirim permintaan AJAX ke controller
    $.ajax({
        url: '<?= site_url("admin/Employees/update_verifikasi/"); ?>',
        type: 'POST',
        data: { [csrfName]: csrfHash, id_mbd: id_mbd },
        success: function(response) {
          show_toko(id_toko);
          alert(response); // Tampilkan respon dari server
            // locati5on.reload(); // Muat ulang halaman
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
            alert('Gagal memperbarui data.');
        }
    });
}
</script>

<script>
  function tolakData() {
    var id_toko = $("#id_toko_selected").val();
    var id_mbd = $("#hidden_tolak_mbd").val();
    //
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    // Kirim permintaan AJAX ke controller
    $.ajax({
        url: '<?= site_url("admin/Employees/update_tolakverifikasi/"); ?>',
        type: 'POST',
        data: { [csrfName]: csrfHash, id_mbd: id_mbd },
        success: function(response) {
          show_toko(id_toko); // tampilkan verfik tanpa reload atau refresh
            alert(response); // Tampilkan respon dari server
            // locati5on.reload(); // Muat ulang halaman
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
            alert('Gagal memperbarui data.');
        }
    });
}
</script>

































