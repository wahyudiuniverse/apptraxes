<?php
/* Invoices view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>


<hr class="border-light m-0 mb-3">

<div class="card <?php echo $get_animate;?>">


    <div class="col-md-12 <?php echo $get_animate;?>">
        <div class="ui-bordered px-4 pt-4 mb-4">
        <input type="hidden" id="user_id" value="0" />
        <?php $attributes = array('name' => 'customer_akses', 'id' => 'customer_akses', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
    <?php $hidden = array('euser_id' => $session['user_id']);?>
        <?php echo form_open('admin/customers/', $attributes, $hidden);?>
      <?php
        $data = array(
          'name'  => 'user_id',
          'id'    => 'user_id',
          'type'  => 'hidden',
          'value' => $session['user_id'],
          'class' => 'form-control');
            echo form_input($data);
      ?> 
      
      <div class="form-row">

    
        <div class="col-md mb-3">
          <label class="form-label">ID Customer/ ID Toko/ ID Lokasi</label>
          <input type="text" class="form-control" placeholder="MASUKAN ID LOKASI / ID TOKO" value="" id="aj_id" name="id_toko" />
        </div>


        <div class="col-md col-xl-2 mb-4">
          <label class="form-label d-none d-md-block">&nbsp;</label>
            <button type="submit" class="btn btn-secondary btn-block">SHOW</button>
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>

  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th>Act</th>
            <th><?php echo $this->lang->line('xin_id');?></th>
            <th>Nama Toko/Lokasi</th>
            <th>Alamat</th>
            <th>Kota/Kab</th>
            <th>Kecamatan</th>
            <th>Koordinat</th>
            <th>CreatedOn</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<style type="text/css">
.info-box-number {
  font-size:15px !important;
  font-weight:300 !important;
}
</style>
