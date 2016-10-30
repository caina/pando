var url;
var page=0;
var search;
var type;
jQuery(document).ready(function($) {
	url = $("#host").val();

	$(".client_type_togle").click(function(event) {
		$(".client_type_togle").removeClass('active');
		$(this).addClass('active');
		list_clientes(0,search,$(this).val());
	});

	$(".form_search").submit(function(event) {
		/* Act on the event */
		event.preventDefault();
		list_clientes(0,$("#client_name_").val(),type);	
	});

	$(document).on('click', '.pagination li a', function(event) {
		event.preventDefault();
		var p = $(this).attr("data_page");

		if(p=="next"){
			page = parseInt(page)+1;
		}else if(page=="previous"){
			page = parseInt(page)-1;
		}else{
			page = p;
		}

		// $(".pagination li").removeClass("active");
		$(".page_handler[data_page='"+page+"']").parent().addClass("active");
		// console.log($(".page_handler").parent());
		console.log(".page_handler[data_page='"+page+"']");
		list_clientes(page,undefined,undefined);
	});
	
	list_clientes(0,undefined,undefined);
});

function list_clientes(page_,search_,type_){
	if(page_ !== undefined){
		page = page_;
	}
	if(search_ !== undefined){
		search = search_;
	}
	if(type_ !== undefined){
		type = type_;
	}

	$.post(url+"/list_clients", {page:page,search:search,type:type}, function(data, textStatus, xhr) {
		$("#listagem_display").empty().html(data);
		
	});
}