var ajax_url;

jQuery(document).ready(function($) {
	ajax_url = $("#api_url").val();

  $(document).on('click', '.image_cover', function(event) {
    event.preventDefault();
    var image_name = $(this).find('input').val();
    console.log(image_name);
    $(".image_cover").removeClass('active');
    $(this).addClass("active");

    $.post(ajax_url+"/set_image_cover", {image_name:image_name}, function(data, textStatus, xhr) {});

  });

  $(document).on('click', '.image_remove', function(event) {
    event.preventDefault();
    var image_name = $(this).parentsUntil(".bottom20").parent().find('input').val();
    $(this).parentsUntil(".bottom20").parent().remove();

    $.post(ajax_url+"/remove_image", {image_name:image_name}, function(data, textStatus, xhr) {});

  });

  $("#price_tag").keyup(function(event) {
    /* Act on the event */
    var version_id = $("#ad_version").val();
    if(version_id !== undefined){
      var price = $(this).val();
      $.post(ajax_url+'/check_price', {version_id:version_id, price:price}, function(data, textStatus, xhr) {
        console.log(data);
        if(data.valid_fipe){
          if(data.above){
            $("#fip_description").removeClass('label-success');
            $("#fip_description").addClass('label-danger');
            $("#fip_description").html("O preço está acima da tabela FIPE! ("+data.price+")");
          }else{
            $("#fip_description").addClass('label-success');
            $("#fip_description").removeClass('label-danger');
            $("#fip_description").html("Anúncio qualificado como Repasse!");
          }
        }else{
          $("#fip_description").html("Não temos os dados da tabela FIPE, anuncio será analizado");
        }
      });
      console.log($("#ad_version").val());
    }

  });

  $('#adImageUpload').fileupload({
        dataType: 'json',
        type:'POST',
        success:function(data){
          if(data.status == 'ok'){
            create_images(data.images);
          }else{
            alert(data.message);
          }
        },
        add: function (e, data) {
            // data.context = $('<p/>').text('Uploading...').appendTo(document.body);
            data.submit();
        }
    });

  if($("#ad_id").val() != undefined){
    $.post(ajax_url+"/load_images_by_ad", {ad_id: $("#ad_id").val()}, function(data, textStatus, xhr) {
      create_images(data);
    });

    $.post(ajax_url+"/ajax_ad", {ad_id: $("#ad_id").val()}, function(data, textStatus, xhr) {
      $(".radio_category_handler[value='"+data.category_id+"']").trigger('click');
     // $('.form_enhaced').first().trigger('change');
    });
  }

   $("#price_tag").maskMoney({symbol:'', 
    showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
     

	$(".radio_category_handler").change(function(event) {
		var category_id = $(this).val();
		$("#ajax_data").empty();
		$.post(ajax_url+"/get_ad_optionals", {ad_id: $("#ad_id").val(),category: category_id}, function(data, textStatus, xhr) {
			$("#ajax_data").append(data);
			$('.form_enhaced').select2().on("change", function (e) { categories_change_handler(e); });
		}).done(function() {
      // nao consegui fazer achar o primeiro objeto 
      $("#ad_brand").trigger("change");
    });
	});

   $("#price_tag").trigger('keyup');

});

function categories_change_handler(e){
	var table = e.target.attributes.getNamedItem("data_watch").value;
  if(table !=undefined){

    var post_data = {};
    post_data.table = table;
    post_data.field = e.target.attributes.getNamedItem("data_element").value;
    post_data.value =  $("#"+post_data.field.replace("_id","")).val();//e.val;
    post_data.ad_id = $("#ad_id").val();

    $("#"+table).empty();
		$.post(ajax_url+"/category_load_values", post_data, function(data, textStatus, xhr) {
			$("#"+table).append($("<option>").val(0).html("Selecione"));
      $.each(data.values,function(index, el) {
				$("#"+table).append($("<option>").attr("selected",el.selected).val(el.id).html(el.title));
			});
       $("#"+table).trigger('change');

		});
	}
}

function create_images(data){
	$.each(data,function(index, el) {

     $("#image_listing").append(
          $("<div>").attr("class",'col-sm-3 col-xs-6 col-md-2 col-lg-2 bottom20').append(
              $("<a>").attr("class",'thumbnail image_cover '+(el.is_cover==1?'active':'')).append(
                  $("<img>").attr("class",'img-responsive').attr("src",el.image_path).attr("width",100).attr("height",100)
              ).append(
								$("<input>").attr("type","hidden").attr("name","image[]").val(el.image)
							)
          ).append(
                  $("<div>").attr("class","text-right").append(
                      $("<a>").attr("class","image_remove").attr("data-remove",el.image).attr("href",'#').append(
                          $("<i>").attr("class","fa fa-times")
                      )
                  )
              )
      );
  });
}


