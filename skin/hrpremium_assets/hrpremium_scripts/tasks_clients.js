$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : site_url+"tasks/task_list/",
		type : 'GET'
	},
	"fnDrawCallback": function(settings){
	$('[data-toggle="tooltip"]').tooltip();          
	}
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
$('#description').trumbowyg();
// Date
$('.date').datepicker({
  changeMonth: true,
  changeYear: true,
  dateFormat:'yy-mm-dd',
  yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10)
});
/* Delete data */
$("#delete_record").submit(function(e){;
});

/* Add data */ /*Form Submit*/
$("#xin-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=task&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
				$('.save').prop('disabled', false);
			} else {
				xin_table.api().ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
				$('.add-form').removeClass('in');
				$('.select2-selection__rendered').html('--Select--');
				$('#xin-form')[0].reset(); // To reset form fields
				$('.save').prop('disabled', false);
			}
		}
	});
});
});
$( document ).on( "click", ".delete", function() {
$('input[name=_token]').val($(this).data('record-id'));
$('#delete_record').attr('action',site_url+'timesheet/delete_task/'+$(this).data('record-id'));
});
