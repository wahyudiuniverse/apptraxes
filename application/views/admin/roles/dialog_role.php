<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['role_id']) && $_GET['data']=='role'){
$role_resources_ids = explode(',',$role_resources);
?>
<div class="modal-header">
  <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_role_editrole');?></h4>
</div>
<?php $attributes = array('name' => 'edit_role', 'id' => 'edit_role', 'autocomplete' => 'off','class' => '"m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', 'ext_name' => $role_name, '_token' => $role_id);?>
<?php echo form_open('admin/roles/update/'.$role_id, $attributes, $hidden);?>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_name"><?php echo $this->lang->line('xin_role_name');?><i class="hrpremium-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_role_name');?>" name="role_name" type="text" value="<?php echo $role_name;?>">
            </div>
          </div>
        </div>
        <div class="row">
        	<input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
          <div class="col-md-12">
            <div class="form-group">
              <label for="role_access"><?php echo $this->lang->line('xin_role_access');?><i class="hrpremium-asterisk">*</i></label>
              <select class="form-control custom-select" id="role_access_modal" name="role_access" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_role_access');?>">
                <option value="">&nbsp;</option>
                <option value="1" <?php if($role_access==1):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_all_menu');?></option>
                <option value="2" <?php if($role_access==2):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_role_cmenu');?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <p><strong><?php echo $this->lang->line('xin_role_note_title');?></strong></p>
            <p><?php echo $this->lang->line('xin_role_note1');?></p>
            <p><?php echo $this->lang->line('xin_role_note2');?></p>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="resources"><?php echo $this->lang->line('xin_role_resource');?></label>
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="all_resources">
                <div class="demo-section k-content">
                  <div>
                    <div id="treeview_m2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fas fa-check-square"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hrpremium_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> '.$this->lang->line('xin_update'))); ?> 
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
 $(document).ready(function(){
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		 Ladda.bind('button[type=submit]');

		/* Edit data */
		$("#edit_role").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=role&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
						Ladda.stopAll();
					} else {
						// On page load: datatable
						var xin_table = $('#xin_table').dataTable({
							"bDestroy": true,
							"ajax": {
								url : "<?php echo site_url("admin/roles/role_list") ?>",
								type : 'GET'
							},
							dom: 'lBfrtip',
							"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
						});
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
						Ladda.stopAll();
					}
				}
			});
		});
	});	
  </script>
  <script>

jQuery("#treeview_m1").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"
template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
},*/
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"
},
check: onCheck,
dataSource: [

{ 
	id: "", 
	class: "role-checkbox-modal custom-control-input", 
	text: "DASHBOARD",  
	add_info: "", 
	check: "<?php if(isset($_GET['role_id'])) { if(in_array('1',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
	value: "1",  
	items: [


// MY PROFILE
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('header_my_profile');?>",  
		add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", 
		value: "2",  
		items: [
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "2",
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('2',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_edit');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_edit');?>", 
			value: "3",
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},


// CEK NIP
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "CEK NIP",  
		add_info: "CEK NIP", 
		value: "134",  
		items: [
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "134",
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('134',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},



// BPJS KARTU
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_manage_employees_bpjs');?>",  
		add_info: "<?php echo $this->lang->line('xin_add_edit_view_delete_role_info');?>", 
		value: "502",  
		items: [
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "502",
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('140',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},


	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('hr_staff_dashboard_title');?>",  
		add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
		value: "422", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('422',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"},
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_set_employees_salary');?>",  
		add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
		value: "351", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('351',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
	},
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_download_profile_title');?>",  
		add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
		value: "421", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('421',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
	},

	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('left_employees_directory');?>",  
		add_info: "<?php echo $this->lang->line('xin_view');?>", value: "88", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('88',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
	},

	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_lock_user');?>",  
		add_info: "<?php echo $this->lang->line('xin_lock_user');?>", 
		value: "465",
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('465',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
	},
	]},



//MENU CALL CENTER CS
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_menu_cs');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('479',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_menu_cs');?>", 
		value: "479",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_whatsapp_blast');?>",  
			add_info: "Wa.me", 
			value: "480", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('480',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},


//USER MOBILE
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_user_mobile');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('59',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
		value: "59",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "59", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('59',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_add');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "64", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('64',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_edit');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "65", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('65',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_delete');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "66", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('66',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},

// CUSTOMER
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_customer');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('69',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
		value: "69",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "69", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('69',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_view_menu');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "70", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('70',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_add');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "100", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_edit');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "101", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('101',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_delete');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "102", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('102',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},

//USER CALLPLAN
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_callplan');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('105',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
		value: "105",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_enable');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
			value: "105", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('105',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_add');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "123", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('123',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_edit');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "124", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('124',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_role_delete');?>",  
			add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
			value: "125", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('125',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},


//MANAGE KARYAWAN
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_manage_employees');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('467',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
		value: "467",
		items: 
		[

			// DATABASE
			{
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('left_db_employee');?>",  
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('470',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
				add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
				value: "470",
				items: 
				[
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "470", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('470',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_e_details_edit_profile');?>",  
					add_info: "<?php echo $this->lang->line('xin_e_details_edit_profile');?>", 
					value: "471", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('471',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_e_details_edit_grade');?>",  
					add_info: "<?php echo $this->lang->line('xin_e_details_edit_grade');?>", 
					value: "473", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('473',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_manage_employees_bpjs');?>",  
					add_info: "<?php echo $this->lang->line('xin_manage_employees_bpjs');?>", 
					value: "472", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('472',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_manage_employees_rekening');?>",  
					add_info: "<?php echo $this->lang->line('xin_manage_employees_rekening');?>", 
					value: "474", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('474',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_manage_employees_blacklist');?>",  
					add_info: "<?php echo $this->lang->line('xin_manage_employees_blacklist');?>", 
					value: "475", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('475',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				}
				]
			},

		]
	},


//KARYAWAN AKSES
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('xin_hr_report_employees');?>",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('117',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "<?php echo $this->lang->line('xin_create_edit_view_delete');?>", 
		value: "117",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_inhouse');?>",  
			add_info: "<?php echo $this->lang->line('xin_inhouse');?>", 
			value: "139", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('139',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},

		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_project');?>",  
			add_info: "<?php echo $this->lang->line('xin_project');?>", 
			value: "117", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('117',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},

	
// ORGANIZATION
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "<?php echo $this->lang->line('left_organization');?>", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('2',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "", 
		value:"2", 
		items: [
			// designation
			{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('left_designation');?>",  
			add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
			value: "4", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
			items: [
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_role_enable');?>",  
				add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
				value: "4", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			},
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_role_add');?>",  
				add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
				value: "243", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('243',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			},
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_role_edit');?>",  
				add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
				value: "244", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('244',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			},
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_role_delete');?>",  
				add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
				value: "245", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('245',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			},
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo '<small>'.$this->lang->line('xin_role_view').' '.$this->lang->line('left_designation').'</small>';?>",  
				add_info: "<?php echo $this->lang->line('xin_role_view');?>", 
				value: "249",
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('249',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			}]
		},
			// project
			{
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('left_projects');?>",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "44", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('44',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
				items: [
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_enable');?>",  
						add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
						value: "44", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('44',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_add');?>",  
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "45", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('45',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_edit');?>", 
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "47", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('47',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_delete');?>", 
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "90", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('90',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
				]
			},
			// Sub-project
			{
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "Sub Project",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "130", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
				items: [
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_enable');?>",  
						add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
						value: "130", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_add');?>",  
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "130", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('130',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_edit');?>", 
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "132", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
					{ 
						id: "", 
						class: "role-checkbox-modal custom-control-input", 
						text: "<?php echo $this->lang->line('xin_role_delete');?>", 
						add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
						value: "133", 
						check: "<?php if(isset($_GET['role_id'])) { if(in_array('131',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
					},
				]
			},
			// akses project
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_akses_project');?>",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "207", check: "<?php if(isset($_GET['role_id'])) { if(in_array('207',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
				items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "207", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('207',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_add');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "208", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('208',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_delete');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "209", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('209',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				}]
			},
			// company
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('left_company');?>",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "5", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
				items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "5", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_add');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "246", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('246',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_edit');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "247", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('247',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_delete');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "248", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('248',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				]
			},
			// location
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('left_location');?>",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "6", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
				items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "6", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_add');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "250", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('250',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_edit');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "251", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('251',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_delete');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_add');?>", 
					value: "252", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('252',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				]
			},
			// daftar esign
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_esign_register');?>",  
				add_info: "<?php echo $this->lang->line('xin_add_edit_delete_role_info');?>", 
				value: "478", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('478',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>",
				items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "478", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('478',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				}]
			},
	]}, // sub 1 end
	
	// DISPLAY

	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "Display Menu",  
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('200',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "info displya", 
		value: "200",
		items: 
		[
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Display RAK",  
			add_info: "", 
			value: "201", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('201',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},

		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Display MBD",  
			add_info: "", 
			value: "202", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('202',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}, 
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Display PLANO",  
			add_info: "", 
			value: "203", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('203',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
		]
	},
	// REPORT TRAXES MOBILE
	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input", 
		text: "Report Traxes Mobile",  
		add_info: "", 
		value: "11", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		items: [
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Absensi [C/I/S/OFF]",  
			add_info: "<?php echo $this->lang->line('xin_view');?>", 
			value: "111", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "<?php echo $this->lang->line('xin_role_enable');?>",  
				add_info: "", 
				value: "111", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			},
			{ 
				id: "", 
				class: "role-checkbox-modal custom-control-input", 
				text: "Edit/Delete",  
				add_info: "Manage Module", 
				value: "111", 
				check: "<?php if(isset($_GET['role_id'])) { if(in_array('111',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
			}
			]
		},


		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Sell IN",  
			add_info: "Stock", 
			value: "12", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('12',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "121", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('121',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Edit/Delete",  
					add_info: "Manage", 
					value: "122",
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('122',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
			]
		},
		
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Sell OUT",  
			add_info: "ORDER", 
			value: "13", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('13',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "13", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('13',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Edit/Delete",  
					add_info: "Manage", 
					value: "135",
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('135',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
			]
		},
		
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Over Time",  
			add_info: "LEMBUR", 
			value: "15", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('15',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "<?php echo $this->lang->line('xin_role_enable');?>",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "151", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('151',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Edit/Delete",  
					add_info: "Manage", 
					value: "152",
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('152',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
			]
		},

		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Display",  
			add_info: "Foto Display", 
			value: "14", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Display Produk",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "141", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('141',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Display Produk",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "141", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('141',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
			]
		},


		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "Display",  
			add_info: "Display MBD", 
			value: "17", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
			items: [
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Display Produk",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "171", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('171',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
				{ 
					id: "", 
					class: "role-checkbox-modal custom-control-input", 
					text: "Display Produk",  
					add_info: "<?php echo $this->lang->line('xin_role_enable');?>", 
					value: "171", 
					check: "<?php if(isset($_GET['role_id'])) { if(in_array('171',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
				},
			]
		},

	]},
	
]
});

jQuery("#treeview_m2").kendoTreeView({
checkboxes: {
checkChildren: true,
//template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
/*template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"
template: "<label><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'> #= item.text #</label>"
},*/
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text # <small>#= item.add_info #</small></span></label>"
},
check: onCheck,
dataSource: [





	{ 
		id: "", 
		class: "role-checkbox-modal custom-control-input",
		text: "<?php echo $this->lang->line('xin_hr_report_title');?>", 
		check: "<?php if(isset($_GET['role_id'])) { if(in_array('110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", 
		add_info: "",
		value: "110", 
		items: [
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_hr_reports_attendance_employee');?>",  
			add_info: "<?php echo $this->lang->line('xin_view');?>", 
			value: "112", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('112',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_hr_reports_projects');?>",  
			add_info: "<?php echo $this->lang->line('xin_view');?>", 
			value: "114", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('114',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		},
		{ 
			id: "", 
			class: "role-checkbox-modal custom-control-input", 
			text: "<?php echo $this->lang->line('xin_hr_report_user_roles');?>",  
			add_info: "<?php echo $this->lang->line('xin_view');?>", 
			value: "116", 
			check: "<?php if(isset($_GET['role_id'])) { if(in_array('116',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>"
		}
	]},
]
});
		
// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
treeView = jQuery("#treeview").data("kendoTreeView"),
message;
//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
jQuery("#result").html(message);
}
$(document).ready(function(){
	$("#role_access_modal").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox-modal').prop('checked', true);
		} else {
			$('.role-checkbox-modal').prop("checked", false);
		}
	});
});
</script>
<?php }
?>
