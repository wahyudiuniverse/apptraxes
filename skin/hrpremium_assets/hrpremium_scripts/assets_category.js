$(document).ready(function() {
   var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : base_url+"/category_list/",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
	
	$(".add-new-form").click(function(){
		$(".add-form").slideToggle('slow');
	});
	
	// delete
	/* Delete data */
	$("#delete_record").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&type=delete_record&form="+action,
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
	$('.payroll_template_modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var assets_category_id = button.data('field_id');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read_assets_category/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=assets_category&assets_category_id='+assets_category_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_payroll").html(response);
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
			data: obj.serialize()+"&is_ajax=1&add_type=add_category&form="+action,
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
					$('.icon-spinner3').hide();
					$('#xin-form')[0].reset(); // To reset form fields
					$('.select2-selection__rendered').html('--Select--');
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete_assets_category/'+$(this).data('record-id'));
});