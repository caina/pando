<input type="hidden" id="url" value="<?php echo site_url("act/amostras/amostra") ?>">
<div class="col-md-12">
	<div class="row">
		<div class="col-lg-12">
			<div class="row" id="user-profile">
				<div class="col-lg-3 col-md-4 col-sm-4">
					<div class="main-box clearfix">
						<header class="main-box-header clearfix">
							<h2>Cadastrar Amostra</h2>
						</header>
						<div class="main-box-body clearfix">
							<form role="form" method="POST" class="validade_form" data-toggle="validator" action="<?php echo site_url("act/amostras/amostra/register") ?>">
								<input type="hidden" name="samples[code]" value="<?php echo $sample_fields->code ?>" />
								<div class="form-group">
									<label for="exampleAppend">Código</label>
									<div class="input-group">
										<input type="text" disabled value="<?php echo $sample_fields->code ?>" class="form-control" id="exampleAppend">
										<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
									</div>
								</div>

								<div class="form-group">
									<label for="exampleAppend">Data de envio</label>
									<div class="input-group">
										<input type="text" name="samples[date]" disabled value="<?php echo $sample_fields->date ?>" class="form-control" id="exampleAppend">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>

								<div class="form-group">
									<label for="exampleAppend">Tipo de amostra</label>
									<div class="input-group">
										<input type="text" required name="samples[sample_type]" value="<?php echo @$sample_fields->sample_type ?>" class="form-control" id="exampleAppend">
										<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
									</div>
								</div>
								
								<div class="form-group">
									<label for="exmaplePrependButton">Cliente</label>
									<div class="input-group">
										<span class="input-group-btn">
											<button class="btn btn-primary" id="new_client" type="button">Novo</button>
										</span>
										<select class="form-control" required name="samples[client_id]">
											<option value="0">Selecione</option>
											<?php foreach ($sample_fields->clients as $client):?>
												<option <?php echo @$sample_fields->client_id == $client->id?"selected='selected'":"" ?> value="<?php echo $client->id ?>"><?php echo $client->name ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div id="client_register">
									<inpu type="hidden" name="client[id]" value="<?php echo @$sample_fields->client_id ?>" />
									<div class="form-group">
										<label for="exampleAppend">Nome Cliente</label>
										<div class="input-group">
											<input type="text" name="client[name]" requred value="<?php echo @$sample_fields->client_name ?>" class="form-control" id="exampleAppend">
											<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
										</div>
									</div>
									<div class="form-group">
										<label for="exampleAppend">Email Cliente</label>
										<div class="">
											<input type="email" name="client[email]"  value="<?php echo @$sample_fields->client_email ?>" class="form-control" id="exampleAppend">
										</div>
									</div>
									<div class="form-group">
										<label for="exampleAppend">Telefone Cliente</label>
										<div class="">
											<input type="text" name="client[phone_number]"  value="<?php echo @$sample_fields->client_phone ?>" class="form-control" id="exampleAppend">
										</div>
									</div>
								</div>


								<div class="form-group">
									<div class="col-md-12" style="padding:0px !important">
										<button type="submit" class="btn btn-success"><?php echo !empty($sample_fields->sample_type)?"Editar":"Cadastrar" ?></button>
										<?php if(!empty($sample_fields->sample_type)){ ?>
											<a href="<?php echo site_url("act/amostras/amostra/index") ?>" class="btn btn-success">
												Novo
											</a>
										<?php } ?>
									</div>
								</div>
								
							</form>
						</div>
					</div>
				</div>
								
				<div class="col-lg-9 col-md-8 col-sm-8">
					<div class="main-box clearfix">
						<header class="main-box-header clearfix">
						<h2 class="pull-left">Filtros</h2>
						</header>
						<div class="main-box-body clearfix">
							<form class="form-inline" id="filter_samples" role="form" method="POST" action="<?php echo site_url() ?>">
								<div class="form-group">
									<label class="sr-only"  for="exampleInputEmail2">Código</label>
									<input type="text" class="form-control" name="code" id="exampleInputEmail2" placeholder="<?php echo $sample_fields->code ?>">
								</div>
								<div class="form-group">
									<label class="sr-only" for="exampleInputPassword2">Clientes</label>
									<select class="form-control" name="client_id">
										<option value="0">Selecione</option>
										<?php foreach ($sample_fields->clients as $client):?>
											<option value="<?php echo $client->id ?>"><?php echo $client->name ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								
								<button type="submit" class="btn btn-success">Pesquisar</button>
							</form>
						</div>
					</div>
					<div class="main-box clearfix">
						<header class="main-box-header clearfix">
						<h2 class="pull-left">Listagem</h2>
						
						
						</header>
						<div class="main-box-body clearfix" id="listagem_dados">
							
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>