<input type="hidden" id="url" value="<?php echo site_url("act/magazines/magazine/") ?>">
<div class="col-md-12">
	<div class="row">
			<div class="col-md-3">
				<div class="main-box infographic-box">
					<i class="fa fa-plus green-bg"></i>
					<span class="headline">&nbsp;</span>
					<span class="value">
						<span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
							<a href="<?php echo site_url('act/magazines/magazine/create_new') ?>" type="button" class="btn btn-primary">Adicionar matéria</a>
						</span>
					</span>
				</div>
			</div>

		<!-- 	<div class="col-md-4">
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
			</div> -->
			
		</div>

		<div class="row">
			
			<div class="col-md-8">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>materias mais acessadas</h2>
					</header>
					
					<div class="main-box-body clearfix">
						listar aqui
					</div>
				</div>
			
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Listagem</h2>
					</header>
					
					<div class="main-box-body clearfix">
						tabela e filtros aqui
					</div>
				</div>
			</div>


			<div class="col-md-4">
				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Categorias</h2>
					</header>
					
					<div class="main-box-body clearfix">
						<div id="category_list">

						</div>
						<form role="form" method="POST" action="<?php echo site_url("act/magazines/magazine/add_category") ?>" id="category_add">
							<div class="form-group">
								<label for="me_category[name]">Nome</label>
								<input type="text" class="form-control" name="name" id="me_category[name]" placeholder="Nome categoria">
							</div>
							<div class="form-group">
								<label for="me_category[hexa]">Cor tag</label>
								<input type="text" class="form-control" name="hexa" id="me_category[hexa]" placeholder="#fff000">
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<button type="submit" class="btn btn-success">Sign in</button>
								</div>
							</div>
							
							
							
							
						</form>
					</div>
				</div>

				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Opções configurar</h2>
					</header>
					
					<div class="main-box-body clearfix">
						form
					</div>
				</div>

				<div class="main-box">
					<header class="main-box-header clearfix">
						<h2>Posições das materias</h2>
					</header>
					
					<div class="main-box-body clearfix">
						form
					</div>
				</div>

			</div>
		</div>

</div>