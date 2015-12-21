jQuery(document).ready(function($) {

	show_hide_theme_price($("input[name='use_template']"));
	$("input[name='use_template']").change(function(event) {
		show_hide_theme_price($(this));
		if($(this).is(":checked")){
			$("input[name='has_home_page']").prop( "checked", false );
		}
	});

	$("input").change(function(event) {
		$.post($("#form_create").attr("action"), $("#form_create").serialize(), function(embrabnco, textStatus, xhr) {
			$.post($("#url").val(), {id:$("input[name='id']").val()}, function(data, textStatus, xhr) {
				
				$("#subtotal").html(data.sub_total);
				$("#imposto").html(data.taxes);
				$("#lucro").html(data.profit);
				$("#total").html(data.total);
				$("#body_detail").empty();

				$.each(data.itens, function(index, el) {
					$("#body_detail").append(
						$("<tr>")
							.append($("<td>").html(el.name))
							.append($("<td>").addClass('text-center').html(el.hours))
							.append($("<td>").addClass('text-center').html(el.unity_value))
							.append($("<td>").addClass('text-center').html(el.total_value))
					);
				});
				// <tr>
				// 	<td>
				// 		iPad Mini 32GB Wifi
				// 	</td>
				// 	<td class="text-center">
				// 		5
				// 	</td>
				// 	<td class="text-center">
				// 		&dollar; 225.20
				// 	</td>
				// 	<td class="text-center">
				// 		&dollar; 1126.00
				// 	</td>
				// </tr>
			});
		});
	});
});


function show_hide_theme_price(el){
	if(el.is(":checked")){
		$("#theme_value").show(); 
	}else{
		$("#theme_value").hide(); 
		$("#theme_value").find("input").val(0).trigger('changed');
	}
}