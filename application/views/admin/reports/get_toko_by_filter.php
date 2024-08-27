<?php $result = $this->Project_model->get_toko_by_filter($user_id);?>
<div class="form-group">
  <label for="xin_department_head">Toko</label>
   <select name="outlet_id" id="outlet_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
    <option value="0">All Toko</option>
    <?php foreach($result as $employee) {?>
    <option value="<?php echo $employee->customer_id;?>"> <?php echo $employee->customer_name;?></option>
    <?php } ?>
  </select>             
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>