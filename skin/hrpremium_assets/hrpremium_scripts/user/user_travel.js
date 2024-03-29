$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : site_url+"user/travel_list/",
		type : 'GET'
	},
	dom: 'lBfrtip',
	"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
	"fnDrawCallback": function(settings){
	$('[data-toggle="tooltip"]').tooltip();          
	}
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
$('#description').trumbowyg();

// edit
$('.edit-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var travel_id = button.data('travel_id');
	var modal = $(this);
$.ajax({
	url : site_url+"user/read_travel/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=travel&travel_id='+travel_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal").html(response);
		}
	}
	});
});
$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var travel_id = button.data('travel_id');
	var modal = $(this);
$.ajax({
	url : site_url+"user/read_travel/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=view_modal&data=view_travel&travel_id='+travel_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal_view").html(response);
		}
	}
	});
});

/* Add data */ /*Form Submit*/
$("#xin-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=travel&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
				$('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			} else {
				xin_table.api().ajax.reload(function(){ 
					toastr.success(JSON.result);
				}, true);
				$('input[name="csrf_hrpremium"]').val(JSON.csrf_hash);
				$('.add-form').removeClass('show');
				$('.icon-spinner3').hide();
				$('#xin-form')[0].reset(); // To reset form fields
				$('.save').prop('disabled', false);
			}
		}
	});
});
});
