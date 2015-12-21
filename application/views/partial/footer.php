	</div>
	<?php 
	foreach ($assets_js as $js) {
		$data_js = base_url("{$js}");
		echo "<script src='{$data_js}'></script>";
	}
	?>
	<script type="text/javascript">
	 <?php if(!empty($asset_inline_js)): ?> 
	 	<?php foreach ($asset_inline_js as $js) {
	 		echo $js;
	 	} ?>
	 <?php endif; ?>
	</script>
	  <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
	<script type="text/javascript">
		$(document).ready(function() { 
		    list_data();


		    $(".active_box_plugin").click(function(){
				data_send = {
					"field_name" : $(this).attr("active_box_field"),
					"pk"		: $(this).attr("active_box_pk"),
					"pk_val"	  : $(this).attr("active_box_id"),
					"checked"	  : $(this).is(":checked"),
					"data_table"	  : $(this).attr("data_table")

				};
				path = $(this).attr("data_path");
				$.getJSON(path, data_send, function(data) {
					// alert(JSON.stringify(data));
				});

			});
		}); 

		
		
		$(document).on('click', '.check-all', function(event) {
		     $(this).closest("table").find('input[name="checked[]"]').prop('checked', this.checked);
	 	});

		 $(document).on('submit', '.form_ajax', function(event) {
	    	event.preventDefault();
	    	var options = { 
		        target:        '',   // target element(s) to be updated with server response 
		        beforeSubmit:  showRequest,  // pre-submit callback 
		        success:       showResponse  // post-submit callback 
		    };  

	    	if($(this).attr("action").indexOf("create") > -1){
	    		$(this).append($("<input>").attr("type","hidden").attr("name","save").val(1));
	    	}else{
	    		$(this).append($("<input>").attr("type","hidden").attr("name","delete").val(1));
	    	}

	    	$(this).ajaxSubmit(options); 
	    	$(this).hide();
	    	$(".loader").show();
	    	return false;
	 	});
		// pre-submit callback 
		function showRequest(formData, jqForm, options) { 
			if(!jqForm.hasClass('not_clear')){
				jqForm.clearForm();
			}
			// $(".form-horizontal .form_ajax").clearForm();
		    return true; 
		} 
		 
		// post-submit callback 
		function showResponse(responseText, statusText, xhr, $form)  { 
		    //pegar os dados da div e puxar os dados pelo ajax
		    console.log(responseText);
		    
		    $('.form_ajax').show();
	    	$(".loader").hide();

		    list_data();
		} 

		function list_data(){
			$(".onetomanydisplay").each(function(index, el) {
		    	var token_ = $(this).attr("token");
		    	var table_ = $(this).attr("table");
		    	var _this = $(this);
		    	$.post("<?php echo site_url('screen/listing_ajax') ?>",{token:token_,table:table_}, function( data ) {
  					_this.html( data );
				});
		    });
		}
	</script>
	</body>	
</html>