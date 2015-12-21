var url;
jQuery(document).ready(function($) {
	url = $("#url").val();


	$("#category_add").submit(function(event) {
		event.preventDefault();
		var form = $(this).serialize();
		$.ajax({
			url: $(this).attr("action"),
			type: 'POST',
			data: form,
			success: function(data){
				list_categories();
			}
		});
	});
});

function list_categories(){
	$.post(url+'/list_categories', {}, function(data, textStatus, xhr) {
		$("#category_list").empty().html(data);
	});
}