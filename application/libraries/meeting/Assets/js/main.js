var table;
var schedule;

jQuery(document).ready(function($) {
	
	$(".table_click").click(function(event) {
		event.preventDefault();
		$(".meet_params").val("");
		table = $(this).attr("data_table");
		$(".table_click").parent().removeClass("selected");
		$(this).parent().addClass("selected");
		$(".agenda_table").html($(this).html()+" - ");
		populate()
	});	

	$(".schedules_click").click(function(event) {
		event.preventDefault();
		$(".meet_params").val("");
		schedule = $(this).attr("data_table");
		$(".schedules_click").parent().removeClass("selected");
		$(this).parent().addClass("selected");
		$("#agenda_schedule").html($(this).html()+" - ");

		populate()

	});



	$(".meet_params").focusout(function(event) {
		event.preventDefault();
		var insert = {
			table : table,
			schedule : schedule,
			time : $(this).attr("data-time"),
			field : $(this).attr("name"),
			value : $(this).val()
		};

		$.post($("#ajax_url").val()+'/update', insert, function(data, textStatus, xhr) {
			// colocar aqui um aviso
		});

	});

});

function populate(){
	$.post($("#ajax_url").val()+'/listing', {table: table, schedule:schedule}, function(data, textStatus, xhr) {
		$.each(data, function(index, el) {
			$(".meet_"+el.time_id+"[name='guest_company']").val(el.guest_company);
			$(".meet_"+el.time_id+"[name='guest_name']").val(el.guest_name);
			$(".meet_"+el.time_id+"[name='host_company']").val(el.host_company);
			$(".meet_"+el.time_id+"[name='host_name']").val(el.host_name);
		});
	});
}