var url;
var page;
var code;
var client_id;
var user_id;
jQuery(document).ready(function($) {
	url = $("#url").val();
	$("#client_register").hide();

	$("#new_client").click(function(event) {
		/* Act on the event */
		event.preventDefault();
		$("#client_register").toggle();
	});
	display_data();

	$(document).on('click', '.page', function(event) {
		event.preventDefault();
		var _page = $(this).attr("data_page");
		console.log(_page);
		if(_page=="previous"){
			page = page-1;
		}else if(_page=="next"){
			page = page+1;
		}else{
			page = _page;
		}

		if(page < 0){
			page = 0;
		}
		display_data(page,code,client_id,user_id);
	});

	$("#filter_samples").submit(function(event) {
		event.preventDefault();
		display_data(0,$(this).find("input[name='code']").val(),$(this).find("select[name='client_id']").val(),undefined);
	});
});


function display_data(page_, code_, client_id_, user_id_){
	$.post(url+'/list_samples', {page:page_,code:code_,client_id:client_id_,user_id:user_id_}, function(data, textStatus, xhr) {
		$("#listagem_dados").html(data);
		page = page_ ===undefined?0:page_;
		$(".pagination li").removeClass('active');
		$(".pagination a[data_page='"+page+"']").parent().addClass("active");
		
	});
}