<div class="col-md-12 configurate">
	<div class="row">
		<form method="POST" id="template_data_update" action="<?php echo site_url("act/template/template/edit_informations") ?>" >
			<input type="hidden" name="id" value="<?php echo $current_theme->id ?>" />
			<div class="col-md-3">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Dominio</h2>
						<span class="help-block">
							Ele que define qual template exibir
						</span>
					</header>
					<div class="main-box-body clearfix">
						<div class="form-group">
							<div class="input-group">
								<input type="text" value="<?php echo $current_theme->domain ?>" name="domain" class="form-control" id="exampleAppend">
								<span class="input-group-btn"><button class="btn btn-primary save_form_ajax" type="button"><i class="fa fa-spinner fa-pulse"></i> Salvar </button></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Identificação do Template</h2>
						<span class="help-block">
							Vincula o conteudo com o template
						</span>
					</header>
					<div class="main-box-body clearfix">
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control" value="<?php echo $current_theme->identification ?>" name="identification" id="exampleAppend">
								<span class="input-group-btn"><button class="btn btn-primary save_form_ajax" type="button"><i class="fa fa-spinner fa-pulse"></i> Salvar </button></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		
		<div class="col-md-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Versões - Atual: <?php echo $current_theme->Version->name ?></h2>
					<span class="help-block">

					<span class="label label-<?php echo $current_theme->Version->published==1?'success':'danger' ?> label-small"><?php echo $current_theme->Version->published==1?'Publicado':'Desativado' ?></span>

						Crie versões do template sem alterar o que está no ar
					</span>
				</header>
				<div class="main-box-body clearfix">
					<div class="row">
						<div class="col-md-3">
							<a href="#" id="version_create_btn" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Nova Versão </a>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<select class="form-control" name="version_id" id="theme_version">
									<?php foreach ($current_theme->Version->Versions as $version): ?>
										<option name="version" value="<?php echo $version->id ?>" <?php echo $version->id == $current_theme->Version->id?"selected='selected'":""; ?>><?php echo $version->name ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<a href="#" id="version_configuration_btn" class="btn btn-info btn-sm"><i class="fa fa-cog"></i> Opções </a>
						</div>
					</div>
		</form>
					<div class="row">
						<div class="col-md-12 no_show" id="version_create">
							<form role="form" method="POST" action="<?php echo site_url("act/template/template/new_version/".$current_theme->id) ?>">
								<div class="form-group">
									<label for="name_placeholder">Nome da versão</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-flag"></i></span>
										<input type="text" name="name" class="form-control" id="name_placeholder">
									</div>
								</div>
								<div class="form-group">
									<label for="maskedDate">Data publicação</label>
									<span class="help-block">
										Deixe em branco para não utilizar
									</span>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
										<input type="text" name="publish_date" class="form-control maskedDate" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12">
										<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Salvar</button>
										<button type="submit" class="btn close_version btn-info"><i class="fa fa-angle-up"></i> Fechar</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-12 no_show" id="version_configuration">
							<form role="form" method="POST" action="<?php echo site_url("act/template/template/update_version/".$current_theme->id) ?>">
								<input type="hidden" name="id" value="<?php echo $current_theme->Version->id ?>" />
								<div class="form-group">
									<label>Publicar</label>
									<div class="checkbox-nice">
										<input type="checkbox" name="published" value="1" id="checkbox-novo" <?php echo $current_theme->Version->published==1?'checked="checked"':'' ?> >
										<label for="checkbox-novo">
											Marcando esta opção, será publicado o template
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="name_placeholder">Alterar nome da Versão Atual</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-flag"></i></span>
										<input type="text" name="name" value="<?php echo $current_theme->Version->name ?>" class="form-control" id="name_placeholder">
									</div>
								</div>
								<div class="form-group">
									<label for="maskedDate">Alterar Data publicação</label>
									<span class="help-block">
										Deixe em branco para não utilizar
									</span>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
										<input type="text" name="publish_date" <?php echo $current_theme->Version->published==1?'readonly="readonly"':'' ?> value="<?php echo date("d/m/Y",strtotime($current_theme->Version->publish_date)) ?>" class="form-control maskedDate" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12">
										<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Atualizar</button>
										<button type="submit" class="btn close_version btn-info"><i class="fa fa-angle-up"></i> Fechar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="main-box">
		<div class="main-box-body clearfix" id="template_content">
			<div id="loading" class="no_show">
				<i class="fa fa-spinner fa-3x fa-pulse"></i>
			</div>
			<div id="content">
			??
			</div>
		</div>
	</div>
</div>
