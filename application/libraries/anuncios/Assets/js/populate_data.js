var ajax_url;
var brands;
var brand_html;
var current_brand=0;

jQuery(document).ready(function($) {
	ajax_url = $("#host").val();
	brand_html = $("#brand_html");

	// populate_list();

	$.post(ajax_url+"/api_get_brand", {}, function(data, textStatus, xhr) {
		brands = data;
	});

	$("#execute").click(function(event) {
		event.preventDefault();
		init();
	});


});

function init(){

	console.log(brands[0]);
	populate_list(brands[0]);
}
function next_brand(){
	if(brands.length > current_brand){
		current_brand++;
		populate_list(brands[current_brand]);

	}
}

function populate_list(brand){
	



	var brand_element = $("#brand_html_"+brand.id).empty();
			var line_row = $("<tr>");

			$.post(ajax_url+"/api_get_model", {brand_id:brand.id}, function(models, textStatus, xhr) {
				// console.log(models);

				$.each(models,function(index, model) {
					line_row.append($("<td>").html(model.title));

					$.post(ajax_url+"/api_get_ano", {model_id:model.id}, function(years, textStatus, xhr) {
						$.each(years,function(index, year) {
							$.post(ajax_url+'/api_get_versao', {model_id:model.id,year:year.title,year_id:year.id}, function(versoes, textStatus, xhr) {
								
								// $.each(versoes,function(index, versao) {
								// 	/*optional stuff to do after success */
								// 	// console.log(versoes);
								// 	var obj = {
								// 		brand:brand.id,
								// 		model:model.id,
								// 		year:year.title,
								// 		version:versao.id
								// 	};


								// 	$.post(ajax_url+'/api_get_price', obj, function(data, textStatus, xhr) {
								// 		/*optional stuff to do after success */
								// 		// console.log(data);
								// 		// next_brand();
								// 	});
								// 	// return false;
								// });
							});

							// return false;
						});

						// console.log(data);
					});
					// brand_element.append(line_row);
					// return false;
				});

				setTimeout(next_brand(), 100000);
			
			});
}