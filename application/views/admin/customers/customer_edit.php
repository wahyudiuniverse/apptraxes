<?php
/* Company view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php //$count_emp_request_cancel = $this->Xin_model->count_emp_request_cancel($session['employee_id']);?>
<?php //$count_emp_request_nae = $this->Xin_model->count_emp_request_nae($session['employee_id']);?>
<?php //$count_emp_request_nom = $this->Xin_model->count_emp_request_nom($session['employee_id']);?>
<?php //$count_emp_request_hrd = $this->Xin_model->count_emp_request_hrd($session['employee_id']);?>



<?php //$list_bank = $this->Xin_model->get_bank_code();?>
<!-- $data['list_bank'] = $this->Xin_model->get_bank_code(); -->

<hr class="border-light m-0 mb-3">
<?php //$employee_id = $this->Xin_model->generate_random_employeeid();?>
<?php //$employee_pincode = $this->Xin_model->generate_random_pincode();?>

<?php if(in_array('69',$role_resources_ids)) {?>

<div class="card mb-4">
  <!-- <div id="accordion"> -->
    <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong>UBAH DATA </strong> CUSTOMER/TOKO/LOKASI</span>
      <div class="card-header-elements ml-md-auto"> </div>
    </div>
    <div id="add_form" class="add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="card-body">
        <?php $attributes = array('name' => 'add_employee', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('_user' => $session['user_id']);?>
        <?php echo form_open_multipart('admin/customers/customer_save', $attributes, $hidden);?>
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <input name="customer_id" type="hidden" value="<?php echo $customer_id;?>">

                <!--NAMA LENGKAP-->
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="nama_toko">Nama Toko/Lokasi<i class="hrpremium-asterisk">*</i></label>
                    <input class="form-control" placeholder="Isi Nama Toko/Lokasi" name="customer_name" type="text" value="<?php echo $customer_name; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="owner_name">Owner/Pemilik Toko<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="Nama Pemilik Toko/Owner" name="owner_name" type="text" value="<?php echo $owner_name; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="kontak_owner">No Kontak Owner<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="No Kontak Owner" name="kontak_owner" type="text" value="<?php echo $no_contact; ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <!--ALAMAT-->
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="alamat_toko">Alamat Toko/Lokasi<i class="hrpremium-asterisk">*</i></label>
                    <input class="form-control" placeholder="Isi Alamat Toko/Lokasi" name="alamat_toko" type="text" value="<?php echo $address; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="kota">Kota<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="Nama Kota/Kab" name="kota" type="text" value="<?php echo $kota; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="kecamatan">Kecamatan<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="Nama Kecamatan" name="kecamatan" type="text" value="<?php echo $kecamatan; ?>">
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="latitude">Latitude<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="Latitude" name="latitude" type="text" value="<?php echo $latitude; ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="longitude">Longitude<i class="hrpremium-asterisk"></i></label>
                    <input class="form-control" placeholder="Longitude" name="longitude" type="text" value="<?php echo $longitude; ?>">
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-md-8">
                  <img style="max-width:100%;height:auto;border-radius:6px" src="<?php echo $foto_toko;?>">
                </div>
              </div>

              <div class="row">

                      <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrpremium_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> '.$this->lang->line('xin_save'))); ?> 
                      </div>
                      
              </div>
            </div>

            <div class="col-md-6">


              <div class="row">

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.609455292879!2d106.80147997453237!3d-6.314923961795896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fa9b12e9062f%3A0xc6ff4363fee4ed21!2sPT%20Siprama%20Cakrawala!5e0!3m2!1sid!2sid!4v1716870594850!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>


            <!-- end row -->
            </div>
          </div>




          </div>

        </div>

        <?php echo form_close(); ?> 
      </div>
    </div>

</div>

<?php } ?>
<div class="card">
  <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_companies');?></span> </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th>No.</th>
            <th>ID</th>
            <th>Nama Toko</th>
            <th>Owner</th>
            <th>Kontak Owner</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Kecamatan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<style type="text/css">
  
  input[type=file]::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #26ae61;
  padding: 10px 20px;
  border-radius: 2px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
  background: #20c997;
}
</style>
