<?php
/* Invoices view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>


<hr class="border-light m-0 mb-3">

<div class="card <?php echo $get_animate;?>">
  <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_callplan');?></span>
    <?php if(in_array('35',$role_resources_ids)) {?>
    <div class="card-header-elements ml-md-auto"> <a class="text-dark"href="<?php echo site_url('admin/customers/create/')?>">
      <button type="button" class="btn btn-xs btn-primary" onclick="window.location='<?php echo site_url('admin/customers/create/')?>'"> <span class="ion ion-md-add"></span> <?php echo $this->lang->line('xin_callplan_create');?></button>
      </a> </div>
    <?php } ?>
  </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th><?php echo $this->lang->line('xin_action');?></th>
            <th><?php echo $this->lang->line('xin_id');?></th>
            <th><?php echo $this->lang->line('xin_nik');?></th>
            <th><?php echo $this->lang->line('dashboard_fullname');?></th>
            <th><?php echo $this->lang->line('xin_customer_id');?></th>
            <th><?php echo $this->lang->line('xin_cust_name');?></th>
            <th><?php echo $this->lang->line('xin_callplan_date');?></th>
            <th><?php echo $this->lang->line('xin_callplan_sun');?></th>
            <th><?php echo $this->lang->line('xin_callplan_mon');?></th>
            <th><?php echo $this->lang->line('xin_callplan_tue');?></th>
            <th><?php echo $this->lang->line('xin_callplan_wed');?></th>
            <th><?php echo $this->lang->line('xin_callplan_thu');?></th>
            <th><?php echo $this->lang->line('xin_callplan_fri');?></th>
            <th><?php echo $this->lang->line('xin_callplan_sat');?></th>
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
