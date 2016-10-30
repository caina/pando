var ajax_url;
jQuery(document).ready(function($) {
	ajax_url = $("#ajax_url").val();

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

});
function load_gallery_images(){
		var post = $("input[name='id']").val();
		$.post(ajax_url, {id:post}, function(data, textStatus, xhr) {
			$.each(data, function(index, val) {
				add_galery_image(val.image_name);
			});
		});
	}

function add_galery_image(image_name){
	$("form").append(
			$("<input>").attr({
				name: 'component_gallery[]',
				type: 'hidden',
				value: image_name
			})
		);

		image = $("#image_path").val()+image_name;
		console.log(image);
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