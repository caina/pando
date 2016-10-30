<input type="hidden" id="api_url" value="<?php echo site_url("lib_generic/method/anuncios/ad/")?>">


<div class="row" style="opacity: 1;">
	<div class="col-lg-12">

		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="<?php echo site_url("act/anuncios/ad/home_page") ?>">Anuncios</a></li>
					<li class="active"><span>Cadastrar</span></li>
				</ol>

				<h1>Cadastrar novo Anúncio </h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-8">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Campos com * são obrigatórios</h2>
					</header>

					<div class="main-box-body clearfix">
						<form method="POST" id="new_ad" data-toggle="validator" action="<?php echo site_url("lib_generic/method/anuncios/ad/create_new_ad_action")?>"  role="form" class="validade_form">
							<input type="hidden" name="ad_id" id="ad_id" value="<?php echo view_evalute(@$ad->id)?>" />

							<h3><span>Categoria</span></h3>

							<div class="btn-group" data-toggle="buttons">
								<?php foreach ($categories as $category):?>
									<label class="btn btn-primary">
										<input
											type="radio"
											class="radio_category_handler"
											value="<?php echo $category->id?>"
											name="ad[category_id]"

											id="option<?php echo $category->id?>">
								<?php echo $category->title?>
								</label>
								<?php endforeach;?>
							</div>
							<br/>
							<br/>

							<div id="ajax_data">
							</div>


							<div class="form-group">
								<label for="title_ad">Titulo</label>
								<div class="input-group">
									<input type="text" required class="form-control" value="<?php echo view_evalute(@$ad->title)?>" name="ad[title]" id="title_ad">
									<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
								</div>
							</div>

							<div class="form-group">
								<label for="price_ad">Preço</label>
								<div class="input-group">
									<span class="input-group-addon">R$</span>
									<input type="text" required class="form-control" id="price_tag" value="<?php echo view_evalute(@$ad->formated_price)?>" name="ad[price]" id="price_ad">
									<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
								</div>
								<span class="help-block label" id="fip_description"></span>

							</div>

							<div class="form-group">
								<label for="description_text">Descrição</label>
								<textarea class="form-control" required data-minlength="30" name="ad[description]" id="description_text" rows="3"><?php echo view_evalute(@$ad->description)?></textarea>
								<span class="help-block"><i class="icon-ok-sign"></i>Coloque uma descrição completa</span>

							</div>

							<div class="form-group">
								<label for="exampleInputEmail1">Adicionar Imagem</label>
								<input type="file" name="image" data-url="<?php echo site_url("lib_generic/method/anuncios/ad/upload_image")?>" multiple id='adImageUpload' class="filestyle" data-buttonBefore="true" data-buttonText=" &nbsp;Procurar Arquivo" />
								<span class="help-block"><i class="icon-ok-sign"></i> Clique na imagem para marca-la como capa</span>
							</div>


							<div class="row">
								<div class="col-md-12">
									<div class='list-group gallery' id="image_listing">


							        </div>
								</div>
							</div>

                            <h3><span>Seus Dados</span></h3>
                            
                            <div class="form-group" id="phone__">
	                              <label class="" for="phone_">Telefone</label>
								<div class="input-group">
	                              <input type="text" id="phone_" required data-mask="(99) 9999-9999" name="user[phone]" value="<?php echo @$user->phone ?>" class="form-first-name form-control" >
									<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
								</div>
                            </div>

							<div class="form-group">
                            <label class="" for="zip_code">CEP</label>
								<div class="input-group">
	                              	<input type="text" required name="user[zip_code]" data-mask="99.999-999" value="<?php echo @$user->zip_code ?>" class="form-first-name form-control" id="zip_code">
		                      		<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
								</div>
                            </div>

							<div class="form-group">
								<label class="" for="estado">Estado</label>
									<select class="form-control" id="estado" required ng-model="estadoSelecionado" ng-change="stateChose()" >
									<option value='' >Selecione</option>
									  <?php foreach ($states as $state): ?>
									    <option value="<?php echo $state->id ?>" <?php echo $state->id == @$user->state_id?"selected":"" ?>><?php echo $state->title ?></option>
									  <?php endforeach; ?>
									</select>
							</div>

							<div class="form-group">
								<label class="" for="cidade">Região </label>
								<select class="form-control" required name="user[zone_id]" id="cidade"  >
									<option value=''>Selecione</option>
								</select>
							</div>

							<div class="form-group">
								<div class="col-md-12">
									<button type="submit" class="btn btn-success">Publicar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Ajuda</h2>
					</header>

					<div class="main-box-body clearfix">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
  
  jQuery(document).ready(function($) {


    toggle_user_data();
    update_estado();

    $(".radio_user_type").change(function(event) {
      toggle_user_data();
    });

    $("#estado").change(function(event) {
      update_estado();
    });
  });


  function update_estado(){
    $("#cidade").empty();
    $.post('<?php echo site_url("login/get_zones") ?>', {estado:$("#estado").val()}, function(data, textStatus, xhr) {
      $.each(data,function(index, el) {
        $("#cidade").append($("<option>").attr("selected",(el.id==<?php echo $this->data["user"]->zone_id ?>)).val(el.id).html(el.iso_name+" "+el.iso+" "+el.title));
      });
    });
  }

  function toggle_user_data(){
    var selected = $("#radio_combo input[type='radio']:checked").val();
    if(selected==1){
      $("#cpf_").parent().show();
      $("#cnpj_").parent().hide();
    }else{
      $("#cpf_").parent().hide();
      $("#cnpj_").parent().show();
    }
  }
</script>
