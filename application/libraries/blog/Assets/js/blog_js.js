var ajax_url;
jQuery(document).ready(function($) {
	ajax_url = $("#url_ajax").val();
	
	$(".well").tooltip();
	$(":file").filestyle({buttonText: "Find file"});

	$("#add_event").click(function(event) {
		event.preventDefault();
		var btn = $(this);
		btn.button('loading');
		var categoria = $("#category").val();
		$("#category").val("Salvando! Por favor, aguarde...");
		$.post(ajax_url+"/add_category", {category: categoria, post:$("#post_id").val()}, function(data, textStatus, xhr) {
			btn.button('reset');
			$("#category").val("");
			list_categorias(data);
		});
	});

	$("#add_tag_event").click(function(event) {
		event.preventDefault();
		var btn = $(this);
		btn.button('loading');
		var tag = $("#tag").val();
		$("#tag").val("Salvando! Por favor, aguarde...");
		$.post(ajax_url+"/add_tag", {tag: tag, post:$("#post_id").val()}, function(data, textStatus, xhr) {
			btn.button('reset');
			$("#category").val("");
			list_tags(data);
		});
	});

	

	$(".facebook_ajax").submit(function(event) {
		event.preventDefault();
		var ajax_url = $(this).attr("action");
		var data_send = $(this).serialize();
		var form = $(this);
		$.post(ajax_url, data_send,function(data, textStatus, xhr) {
			alert("salvo!");
		});
	});

	$(".ajax_request").click(function(event) {
		event.preventDefault();
		
		var remove_id = $(this).attr("data_id_remove");
		var ajax_url = $(this).attr("href");

		$("#"+remove_id).hide();
		$.post(ajax_url, function(data, textStatus, xhr) {
			$("#"+remove_id).html(data.response);
			$("#"+remove_id).show();
		});
		
	});
	$(".form_submiter").click(function(event) {
		$("#action_hidden").val($(this).val());
 		$("#blog_save").submit();
	});
	// $("#blog_save").submit(function(event) {
	// 	// $("#set_tags").val($("#tags").val());
	// 	console.log("as");
	// 	$("#blog_save").submit();
	// 	// event.preventDefault();
	// });

	$(document).on('click', '#gallery-photos li', function(event) {
		event.preventDefault();
		var img = $(this).attr("data-image");
		$(this).remove();
		$(":input[value='"+img+"']").remove();
	});
	var myDropzone = new Dropzone("#demo_upload");
	myDropzone.on("complete", function(file) {
		var img = file.xhr.response.replace('"',"").replace('"',"").trim();
		add_galery_image(img);
	});

	load_gallery_images();

	load_data();
});
	
	function load_gallery_images(){
		var post = $("#id_blog_post").val();
		if(post == undefined){
			post = $("#post_id").val();
		}
		$.post(ajax_url+"/load_gallery_images", {post_id:post}, function(data, textStatus, xhr) {
			$.each(data.gallery, function(index, val) {
				add_galery_image(val.image);
			});
		});
	}

function add_galery_image(image_name){
	$("#blog_save").append(
			$("<input>").attr({
				name: 'blog_gallery_image[]',
				type: 'hidden',
				value: image_name
			})
		);

		image = $("#image_path").val()+image_name;

	  $("#gallery-photos").append(
	  	$("<li>").addClass('col-md-2 col-sm-3 col-xs-6').attr("data-image",image_name).append(
	  		$("<div>").attr("class","photo-box").attr("style","background-image: url('"+image+"');")
	  	).append(
	  		$("<a>").attr("href","#").addClass("remove-photo-link")
	  			.append($("<span>").addClass('fa-stack fa-lg')
	  				.append($("<i>").addClass('fa fa-circle fa-stack-2x'))
	  				.append($("<i>").addClass('fa fa-trash-o fa-stack-1x fa-inverse'))

	  				))
	  );
}

function load_data(){
	$.post(ajax_url+"/load_post_dependencies",{post:$("#post_id").val()}, function(data, textStatus, xhr) {
		list_categorias(data);
	});
}

$(document).on('click', '.remove_category', function(event) {
	event.preventDefault();
	/* Act on the event */
	var nome_categoria = $(this).attr("category_name");
	var category_id = $(this).attr("data-remove");

	if(confirm("Tem certeza que deseja deletar a categoria "+nome_categoria+"?")){
		$.post(ajax_url+"/remove_category", {category_id:category_id} , function(data, textStatus, xhr) {
			list_categorias(data);
		});
	}
});

function list_categorias(data){
	// alert(JSON.stringify(data));
	
	categorias = $("#categorias_elementos");
	categorias.empty();
	$.each(data.categories, function(i,elem) {
		categorias.append(
		$("<div>").attr("class","check-box").
			append($("<label>").
			append($("<input>").
				attr("type","checkbox").
				attr("checked",(elem.blog_check!=null)).
				attr("name","categories[]").
				val(elem.id)
			).append(" "+elem.title+" ").append($("<a>").attr("class","fa fa-times remove_category").attr("category_name",elem.title).attr("data-remove",elem.id).attr("href","#"))
		));
	});
}