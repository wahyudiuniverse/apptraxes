$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : base_url+"/training_list/",
		type : 'GET'
	},
	"fnDrawCallback": function(settings){
	$('[data-toggle="tooltip"]').tooltip();          
	}
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
//$('#description').trumbowyg();
jQuery("#aj_company").change(function(){
	jQuery('#trainer_option').prop('disabled', false);
	jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
		jQuery('#employee_ajax').html(data);
	});
});
jQuery("#trainer_option").change(function(){
	if(jQuery(this).val() == 2) {
		jQuery.get(base_url+"/get_all_trainers/"+jQuery(this).val(), function(data, status){
			jQuery('#trainers_data').html(data);
		});
	} else {
		jQuery.get(base_url+"/get_internal_employee/"+jQuery('#aj_company').val(), function(data, status){
			jQuery('#trainers_data').html(data);
		});
	}
});
/* Delete data */
$("#delete_record").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=2&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
			} else {
				$('.delete-modal').modal('toggle');
				xin_table.api().ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);		
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);					
			}
		}
	});
});

// edit
$('.edit-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var training_id = button.data('training_id');
	var modal = $(this);
$.ajax({
	url : base_url+"/read/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=training&training_id='+training_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal").html(response);
		}
	}
	});
});

/* Add data */ /*Form Submit*/
$("#xin-form").submit(function(e){
	var fd = new FormData(this);
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 1);
	fd.append("add_type", 'training');
	fd.append("form", action);
	e.preventDefault();
	$('.icon-spinner3').show();
	$('.save').prop('disabled', true);
	$.ajax({
		url: e.target.action,
		type: "POST",
		data:  fd,
		contentType: false,
		cache: false,
		processData:false,
		success: function(JSON)
		{
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
			} else {
				xin_table.api().ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('.icon-spinner3').hide();
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
				$('#xin-form')[0].reset(); // To reset form fields
				$('.add-form').removeClass('in');
				$('.select2-selection__rendered').html('--Select--');
				$('.save').prop('disabled', false);
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
			$('.icon-spinner3').hide();
			$('.save').prop('disabled', false);
		} 	        
   });
});
});
$( document ).on( "click", ".delete", function() {
$('input[name=_token]').val($(this).data('record-id'));
$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'))+'/';
});
