$(document).ready(function() {

	$('#color_picker').colorpicker();

	$("#state_select").change(function(event) {
		var path = $(this).attr("data_path");
 		var selected_state_id = $(this).val();
		$.post(path, {state_id: selected_state_id}, function(data, textStatus, xhr) {
			var cities = $("#city_select");
			cities.empty();
			$.each(data, function(index, val) {
				 cities.append(
				 	$("<option>").val(val.id).html(val.nome)
				 );
			});
		});
	});

	var table = $('#list_client').dataTable({
		'info': false,
		'sDom': 'lTfr<"clearfix">tip',
		'oTableTools': {
            'aButtons': [
                {
                    'sExtends':    'collection',
                    'sButtonText': '<i class="fa fa-cloud-download"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-down"></i>',
                    'aButtons':    [ 'csv', 'xls', 'pdf', 'copy', 'print' ]
                }
            ]
        }
	});
	
	// new $.fn.dataTable.FixedHeader( tableFixed );
});