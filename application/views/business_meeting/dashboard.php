
<?php function pt_color_to_en($color){
	switch ($color) {
		case 'amarelo':
			return "yellow";
			break;
		case 'azul':
			return "lblue";
			break;
		case 'verde':
			return "green";
			break;
		case 'vermelho':
			return "red";
			break;
		default:
			return $color;
			break;
	}
} 

?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".timer_change").click(function(e){
			e.preventDefault();
			$(".timer_change").removeClass("hidden");
			$(".changer").addClass("hidden");

			$(this).addClass("hidden");
			$("#"+$(this).attr("data_modify")).removeClass("hidden");
		});

		$(".add_company").click(function(){
			$(".company").removeClass("hidden");
			$(".company_create_hidden").addClass("hidden");

			$("#"+$(this).attr("data_hide")).addClass("hidden");
			$("#"+$(this).attr("data_element")).removeClass("hidden");
		});

		$(".form_submit").submit(function( event ) {
		  event.preventDefault();
		  data = new Object();
		  data.name = $(this).find("input").val();
		  var form_obj = $(this);

		  $(this).find("button").html("Enviando");
		  $(this).find("button").attr("type","button");

		  	$.ajax({
			  type: "POST",
			  url: form_obj.attr("action"),
			  data: data,
			  success: function(company_id){

			  	$("#company_"+form_obj.attr("data_parent_id")).addClass("hidden");
			  	$("#edit_"+form_obj.attr("data_parent_id")).removeClass("hidden");
			  	$("#parent_"+form_obj.attr("data_parent_id")).remove();
			  	$("#edit_"+form_obj.attr("data_parent_id")).find(".change_name").html(data.name);
			  	$("#edit_"+form_obj.attr("data_parent_id")).find(".edit").remove();



			  	$("#remove_"+form_obj.attr("data_parent_id")).find("a").attr("href","<?php echo site_url("business_meeting/remove_company/$category_obj->id/") ?>/"+company_id.id);
				$("#remove_"+form_obj.attr("data_parent_id")).removeClass("hidden");	

			  }
			});

		});
	});


</script>

<div class="report-block">
	<div class="container">
		<div class="row">
		<?php foreach ($arr_color_categories as $color):?>
			<div class="col-md-<?php echo  count($arr_color_categories)+1 ?>">
				<div class="hardware-report freport">
					<div class="report-head br-<?php echo pt_color_to_en($color); ?>">
						<h5><i class="fa fa-hdd-o"></i> <?php echo ucfirst($color) ?></h5>
					</div>
					<div class="report-body">									
						<table class="table">
							<tbody data-link="row">
							<?php foreach ($categories as $cat): 
								if($cat->color== $color):
							?>
							<tr class="<?php echo @$category_obj->id == $cat->id?"br-orange":"" ?>" >
								<td class="text-center ">
									<a href="<?php echo site_url("business_meeting/listing/{$cat->id}");?>" style="<?php echo @$category_obj->id == $cat->id?"color:#fff !important":"" ?>" >
										<?php echo $cat->categorie_name?>
									</a>
								</td>
							</tr>
							<?php 
							endif;
							endforeach; ?>
						</tbody></table>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		</div>					
	</div>
</div>

<?php if(!empty($times_obj)): ?>

<div class="br-<?php echo pt_color_to_en($category_obj->color); ?>" style="">
	<div class="page-title">
		
		<h5 class="text-center " style="color:#fff !important"><?php echo $category_obj->categorie_name ?></h5>
		<div class="clearfix"></div>
	</div>
</div>

<div class="container">
	<div class="row">
		
	<?php foreach ($times_obj as $time): ?>
		<div class="col-md-<?php echo count($times_obj)+1 ?>">
			<div class="widget projects-widget">
				<div class="widget-head">
					<h5 class="text-center">
							
							<div class="timer_change" data_modify="time_<?php echo $time->id ?>" > 
								<a href="#">
									<?php echo $time->time_string ?>					
								</a>
							</div>
							<div id="time_<?php echo $time->id ?>" class="changer hidden">
								<form role="form" method="post" action="<?php echo site_url("business_meeting/time_update/{$category_obj->id}/{$time->id}") ?>">
								   <div class="input-group">
									 <input type="text" class="form-control" name="time_string" id="chat" value="<?php echo $time->time_string ?>">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-info">Modificar Hor&aacute;rio</button>
									</span>
								   </div>
								 </form>
							</div>
						
					</h5>	
					<div class="clearfix"></div>
				</div>
				<div class="widget-body no-padd">
					<ul class="list-unstyled">
						<?php 
						$c=0;
						foreach ($companies_data as $company):
							if($company->meet_times_id == $time->id):?>
								<li class="c-list">
									<div class="contact-details">
										<div class="pull-left">
											<strong><?php echo $company->name ?></strong>
										</div>
										<div class="pull-right">
											<a href="<?php echo site_url("business_meeting/remove_company/$category_obj->id/{$company->id}") ?>" class="add_company btn btn-danger btn-xs" data_hide="parent_<?php echo $c."_".$time->id ?>" data_element="company_<?php echo $c."_".$time->id ?>"><i class="fa fa-times"></i></a>
										</div>
									<div class="clearfix"></div>
									</div>
								</li>        
						<?php 
							$c++;
							endif;
						endforeach; 
							if($c<=25):
								for(;$c<25;$c++):
						?>
							<li class="c-list">
								<div class="contact-details company" id="parent_<?php echo $c."_".$time->id ?>">
									<div class="pull-left">
										<small >Nenhuma</small>
									</div>
									<div class="pull-right">
										<button class="add_company btn btn-warning btn-xs" data_hide="parent_<?php echo $c."_".$time->id ?>" data_element="company_<?php echo $c."_".$time->id ?>"><i class="fa fa-edit"></i></button>
									</div>

									
									<div class="clearfix"></div>
								</div>

								<div class="contact-details hidden" id="edit_<?php echo $c."_".$time->id ?>">
									<div class="pull-left">
										<small class="change_name"></small>
									</div>
									<div class="pull-right edit">
										<button class="add_company btn btn-warning btn-xs" data_hide="parent_<?php echo $c."_".$time->id ?>" data_element="company_<?php echo $c."_".$time->id ?>"><i class="fa fa-edit"></i></button>
									</div>

										<div class="pull-right hidden" id="remove_<?php echo $c."_".$time->id ?>">
										<a href="#" class="add_company  btn btn-danger btn-xs" data_hide="parent_<?php echo $c."_".$time->id ?>" data_element="company_<?php echo $c."_".$time->id ?>"><i class="fa fa-times"></i></a>
									</div>
									<div class="clearfix"></div>
								</div>

								

								  <div class="company_create_hidden hidden" id="company_<?php echo $c."_".$time->id ?>">
								  <form role="form" class="form_submit"  data_parent_id="<?php echo $c."_".$time->id ?>" method="POST" data_timer="<?php echo $time->id ?>" action="<?php echo site_url("business_meeting/saveCompanyTime/{$time->id}") ?>">
								   <div class="input-group">
									 <input type="text" class="form-control" id="chat" placeholder="Digite o nome da Empresa">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-info">Salvar</button>
									</span>
								   </div>
								 </form>
							</li>
						<?php 
						
								endfor;
							endif; ?>
					  </ul>
				</div>
				<div class="widget-foot">
				</div>
			</div>
		</div>
	<?php endforeach; ?>
		
		
	
	</div>
</div>
<?php endif; ?>

