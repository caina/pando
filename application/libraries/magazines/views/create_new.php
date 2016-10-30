<div class="col-md-12">
	<form role="form" method="POST">
		<div class="row">
			<div class="col-md-4">
				<div class="main-box infographic-box">
					<i class="fa fa-floppy-o emerald-bg"></i>
					<span class="headline">&nbsp;</span>
					<span class="value">
						<span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
							<button type="button" class="btn btn-primary">Salvar</button>
							<a href="<?php echo site_url("act/magazines/magazine/dashboard") ?>" onclick="return confirm('Deseja sair sem salvar?')" type="button" class="btn btn-primary"><span class="fa fa-exclamation"></span> Voltar sem salvar</a>
						</span>
					</span>
				</div>
			</div>

			<div class="col-md-4">
				<div class="main-box infographic-box">
					<i class="fa fa-flag emerald-bg"></i>
					<span class="headline">Idioma</span>
					<span class="value">
						<div class="btn-group">
							<a type="button" href="#" class="btn btn-primary active">Potuguês</a>
							<a type="button" href="#" class="btn btn-primary">Ingles</a>
							<a type="button" href="#" class="btn btn-primary">Espanhol</a>
						</div>
					</span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Status</h2>
					</header>
					<div class="main-box-body clearfix">
						<div class="btn-group" data-toggle="buttons">
							<?php $options = array(array("id"=>2,"title"=>"Publicado"),array("id"=>1,"title"=>"Rascunho"),array("id"=>3,"title"=>"Deletar"))?>
							<?php foreach($options as $option): ?>
								<?php $selected = $magazine->status == $option['id']; ?>
								<label class="btn btn-primary <?php echo $selected?'active':"" ?>" >
									<input type="radio"  name="magazine['ma_category_id']" <?php echo $selected?"checked='checked'":"" ?> value="<?php echo $option['id']?>" id="option1"> <?php echo $option['title']?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" value="<?php echo $magazine->id  ?>" id="magazine_id" name="magazine[id]" />
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-md-8">
						<div class="main-box">
							<header class="main-box-header clearfix">
								<h2>Dados da matéria</h2>
							</header>
							
							<div class="main-box-body clearfix">
								
								<div class="form-group">
									<label for="titulo_materia">Título</label>
									<input type="text" class="form-control" value="<?php echo $magazine->title ?>" name="magazine[title]" id="titulo_materia">
								</div>

								<div class="form-group">
									<label for="magazine_eye">Olho</label>
									<textarea class="form-control" value="<?php echo $magazine->eye ?>" name="magazine[eye]" id="magazine_eye" rows="3"></textarea>
									<span class="help-block">Uma pequena chamada para a matéria</span>
								</div>

								<div class="form-group">
									<label for="exampleInputEmail1">Texto</label>
									<textarea class="form-control ckeditor"  name="magazine[entry]" rows="8" name="input">
										<?php echo $magazine->entry ?>
									</textarea>	
								</div>

								
							</div>
						</div>
					</div>
					<div class="col-md-4">
						
						<div class="main-box">
							<header class="main-box-header clearfix">
								<h2>Video Youtube / Vimeo</h2>
							</header>
							<div class="main-box-body clearfix">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">http://</span>
										<input type="text" class="form-control" name="magazine[media_link]" placeholder="prepend text here">
									</div>
								</div>
							</div>
						</div>

						<div class="main-box">
							<header class="main-box-header clearfix">
								<h2>Categoria</h2>
							</header>
							<div class="main-box-body clearfix">
								<div class="form-group">
									<select class="form-control">
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</div>
							</div>
						</div>

						<div class="main-box">
							<header class="main-box-header clearfix">
								<h2>Tags</h2>
							</header>
							<div class="main-box-body clearfix">
								
							</div>
						</div>

						

						<div class="main-box">
							<header class="main-box-header clearfix">
								<h2>Posição</h2>
							</header>
							<div class="main-box-body clearfix">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
										<label for="optionsRadios1">
											Option one is thisude why it's great
										</label>
									</div>
									<div class="radio">
										<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
										<label for="optionsRadios2">
											Oeselect option one
										</label>
									</div>
								</div>
							</div>
						</div>

						


					</div>

				</div>
			</div>
		</div>
	</form>
</div>