<?php $language = empty($language)?"pt":$language ?>
<?php
	$status = @$database_post->action;
	$titulo = "";
	if($status == 0){
		$status = "Rascunho";
		$titulo = '<label class="btn btn-default btn-xs"> <i class="fa fa-edit"></i> Rascunho</label>';
	}else{
		$status = "Publicado";
		$titulo = '<label class="btn btn-info btn-xs"><i class="fa fa-rocket"></i> Publicado</label>';
	}
	
?>





	<form action="<?php echo site_url("lib_generic/method/blog/blog/save_post") ?>" enctype="multipart/form-data" id="blog_save" method="POST">
		<input type="hidden" id="image_path" value="<?php echo $logged_user->upload_path ?>"/>
		<input type="hidden" id="url_ajax" value="<?php echo site_url("lib_generic/method/blog/blog/") ?>" />
		<input type="hidden" name="id" id="post_id" value="<?php echo @$database_post->id ?>" />
		<input type="hidden" id="action_hidden" name="action" value="0" />
		<input type="hidden" name="language_flag" value="<?php echo isset($language)?$language:@$database_post->language ?>" />
		<input type="hidden" name="id_blog_post" value="<?php echo @$parent_id ?>" />
		<!-- <input type="hidden" name="tags" id="tags" value="" /> -->
		
		<div class="col-md-8">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h3><span><i class="fa fa-quote-right"></i> Criar Post: <?php echo $titulo ?></span></h3>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleInputEmail1">Titulo</label>
						<input type="text" name="title" value="<?php echo @$database_post->title ?>" class="form-control" placeholder="Titulo">
					</div>
				</div>

				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleInputEmail1">Slug</label>
						<input type="text" name="slug" value="<?php echo @$database_post->slug ?>" class="form-control" placeholder="Titulo">
						<p>
						Rota que será mostrada na url do blog, separe por "-" e Não use "/".
					</div>
				</div>

				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleInputEmail1">Capa</label>
						<input type="file" class="filestyle" name="cover_photo" value="<?php echo @$database_post->cover_photo ?>" data-buttonBefore="true" data-buttonText=" &nbsp;Procurar Arquivo" />
					</div>
					<div class="col-md-2	">
					<?php if(isset($database_post->cover_photo)):?>
						<div class="bootstrap-filestyle input-group">
							<img src="<?php echo getImagesPath().$database_post->cover_photo ?>" class="img_holder" />
						</div>
					<?php endif; ?>
					</div>
				</div>

				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleInputEmail1">Texto</label>
						<textarea class="form-control ckeditor"  name="entry" rows="8" name="input">
							<?php echo @$database_post->entry ?>
						</textarea>	
					</div>
				</div>

				<div class="main-box-body clearfix">
					<button class="form_submiter btn btn-info" ><i class="fa fa-save"></i></button>
					<div class="pull-right">
						<a href="<?php echo site_url("lib_generic/method/blog/blog/listar")?>" class="btn btn-info btn-xs"> <i class="fa fa-chevron-left"></i> Voltar</a>
					</div>
				</div>
			</div>
		</div>

		<!-- WISGETS -->
		<div class="col-md-4">

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h5><i class="fa fa-save"></i> Opções de Salvar - <?php echo $status ?></h5>	
				</header>
				<div class="main-box-body clearfix">
					<button class="form_submiter btn btn-sm btn-<?php echo @$database_post->action==1?'success':'info'?> btn-md" value="1"     type="button"><i class="fa fa-rocket"></i> Publicar</button>
					<button class="form_submiter btn btn-sm btn-<?php echo @$database_post->action==0?'success':'default'?> btn-md" value="0"  type="button"><i class="fa fa-edit"></i> Rascunho</button> 

					<button type="button" class="btn-sm btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-exclamation"></i> Deletar Post</button>
					<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg">
					    <div class="modal-content">
					    	<div class="modal_data">
						    	<h2>Tem certeza que deseja deletar?</h2>
						    	<h3>clique no botão abaixo para deletar</h3>
						    	<button class='form_submiter btn btn-danger btn-md' value='2' type='button'><i class='fa fa-warning'></i> Deletar</button>
					    	</div>
					    </div>
					  </div>
					</div>
				</div>
			</div>

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h5><i class="fa fa-bullhorn"></i> Adicionar Tradução</h5>
				</header>
				
				<div class="main-box-body clearfix">
					<button type="button" value="" class="btn btn-sm btn-<?php echo (isset($language)?($language=='pt'?'info':'sucess'):'info') ?> form_submiter"><i class="fa fa-flag"></i> PT</button>
					<button type="button" value="en" class="btn btn-sm btn-<?php echo (isset($language)?($language=='en'?'info':'sucess'):'info') ?> form_submiter"> <i class="fa fa-flag"></i> EN</button>
					<button type="button" value="es" class="btn btn-sm btn-<?php echo (isset($language)?($language=='es'?'info':'sucess'):'info') ?> form_submiter"> <i class="fa fa-flag"></i> ES</button>
				</div>
			</div>
		
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h5><i class="fa fa-wrench"></i> Categorias e Tags</h5>
				</header>
				
				<div class="main-box-body clearfix">
					<h5><span>Categorias</span></h5>
					
					<div class="row">
						<div class="col-md-10" style="padding-left: 19px;">
							<div id="categorias_elementos">
							</div>
						</div>
					</div>

					<div class="form-group " >
				    <div class="col-sm-9 no_padding_left">
				      <input type="text" name="categories[]" value="" class="form-control" id="category" placeholder="Nova Categoria">
				    </div>
				    <button href="#" class="btn btn-default col-sm-3 control-label" id="add_event" data-loading-text="Carregando..." type="button" >Adicionar</button>
				  </div>
				</div>

				<div class="main-box-body clearfix">
					<h5><span>Tags</span></h5>
					<div id="tags_elements">
								  
					</div>
					<div class="form-group " >
				    <div class="col-sm-9 no_padding_left">
				      <input type="text" name="tag" value="" class="form-control" id="tag">
				    </div>
				    <button href="#" class="btn btn-default col-sm-3 control-label" id="add_tag_event" data-loading-text="Carregando..." type="button" >Adicionar</button>
				  </div>
				</div>
			</div>
	 </form>
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h5><i class="fa fa-facebook-official"></i> Facebook</h5>
				</header>
				
				<div class="main-box-body clearfix">
						
					<form role="form" method="POST" action="<?php echo site_url("lib_generic/method/blog/blog/update_facebook") ?>" class="facebook_ajax">

						<div class="form-group">
							<?php if(!isset($facebook_user["user_profile"])){ ?>
								<label for="exampleInputEmail1">Faça log in no facebook</label>
								<a href="<?php echo $facebook_user["login_url"] ?>" type="button" class="btn btn-primary btn-lg form-control">
									<span class="fa fa-facebook-official"></span> Logar
								</a>
							<?php }else{ ?>
								<label for="exampleInputEmail1">Logado como <?php echo $facebook_user["user_profile"]["first_name"] ?> <a href="<?php echo $facebook_user['logout_url'] ?>" onclick="return confirm('isso vai lhe tirar da pagina, tem certeza?')">sair</a></label>
							<?php  } ?>
						</div>
						<?php if(isset($facebook_user["user_profile"])){ ?>
							<?php foreach($facebook_user['permissoes']['data'] as $perm): ?>
								<?php if($perm["permission"]=="manage_pages"): ?>
									<?php echo $perm["status"]=="granted"?"":"App sem acesso ás suas paginas!" ?>
								<?php endif; ?>
							<?php endforeach; ?>
							<div class="form-group">
								<label>Selecione a página que irá publicar</label>
								<select name="page_code" class="form-control">
									<?php foreach ($facebook_user["user_pages"]["data"] as $page): ?>
										<option>Selecione</option>
										<?php if($page["category"] != 'Profile'):?>
											<option <?php echo @$facebook_data->page_code == ($page['access_token'].'-'.$page['id'])?"selected":"" ?> value="<?php echo $page['access_token'].'-'.$page['id'] ?>"><?php echo $page['name'] ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
						<?php  } ?>
						
						<div class="form-group">
							<label for="exampleInputEmail1">Facebook Código</label>

							<input type="text" disabled value="<?php echo @$facebook_data->page_code ?>" class="form-control" id="" placeholder="Link">
						</div>

						<div class="form-group">
							<label for="exampleInputEmail1">Link do blog em seu site</label>
							<input type="text" name="website_blog_page" value="<?php echo @$facebook_data->website_blog_page ?>"  class="form-control" id="exampleInputEmail1" placeholder="Link">
						</div>

						<div class="form-group">
							<div class="col-lg-12">
								<button type="submit" class="btn btn-success">Salvar</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>



 
<?php if($display_multiupload): ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="main-box">
				<header class="main-box-header">
					<h2>GALERIA DE IMAGENS</h2>
					<p>
					<i class="fa fa-info-circle"></i> Clique na caixa cinza, ou arraste imagens para cadastrar imagens para a galeria
					</p>
				</header>
				<div class="main-box-body">
					<div id="dropzone">
						<form id="demo_upload" class="dropzone2 dz-clickable" action="<?php echo site_url("act/blog/blog/galery_image_upload") ?>">
							<div class="dz-default dz-message">
								<span>Arraste imagens aqui</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
	<div class="col-lg-12">
		<div class="main-box">
			<header class="main-box-header">
				<h2><i class="fa fa-camera"></i>  Imagens cadastradas, clique nelas pare deletar</h2>
			</header>
			
			<div class="main-box-body">
				<div id="gallery-photos-wrapper">
					<ul id="gallery-photos" class="clearfix gallery-photos gallery-photos-hover">
						
					</ul>
				</div>
			</div>
		
		</div>
	</div>
</div>

<?php endif; ?>