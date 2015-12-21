<div class="col-lg-4">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2><i class="fa fa-rocket"></i> Postagens</h2>
		</header>
		<div class="main-box-body clearfix">
			<h2> <?php echo $totalizadores->postagens ?> </h2>	
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2><i class="fa fa-quote-right"></i> Comentários</h2>
		</header>
		
		<div class="main-box-body clearfix">
			<p><?php echo $totalizadores->comentarios_novos ?> aguardando aprovação</p>                        
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2><i class="fa fa-binoculars"></i> Postagens Vistas</h2>
		</header>
		
		<div class="main-box-body clearfix">
			<p><?php echo $totalizadores->visualizacoes ?></p>		
		
		</div>
	</div>
</div>

<div class="col-lg-12">
<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2>
				<a href="<?php echo site_url("lib_generic/method/blog/blog/new_post") ?>" class="btn btn-info btn-lg"><i class="fa fa-plus"></i> Novo Post</a>
			</h2>
		</header>
		
		<div class="main-box-body clearfix">
			<div class="main-box-body clearfix">
				<div class="table-responsive">
					<table id="table-example" class="table table-hover">
						<thead>
							<tr>
							   <th>Data</th>
							   <th>Post</th>
							   <th>Comentários</th>
							   <th>Visuzalizações</th>
								<th>Controle</th>
							   <th>Status</th>
							</tr>
						</thead>
						<tbody>
							 <?php foreach ($database_posts as $post):
							   	$blog_edit_link = site_url("lib_generic/method/blog/blog/new_post/{$post->id}");
							   ?>
								 <tr>
								   <td><?php echo date("d/m/Y", strtotime($post->post_date)) ?></td>
								   <td><a href="<?php echo $blog_edit_link ?>"><?php echo word_limiter($post->title,20) ?></a></td>
								   <td><?php echo $post->comments ?></td>
								   <td><?php echo $post->post_view ?></td>
								   <td>
								   		<div class="modal fade bs-example-modal-lg" id="delete_<?php echo $post->id ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
										  <div class="modal-dialog modal-lg">
										    <div class="modal-content">
										    	<div class="modal_data">
											    	<h2>Tem certeza que deseja deletar o post:  </h2>
											    	<p><?php echo word_limiter($post->title,20) ?></p>
											    	<h3>clique no botão abaixo para deletar</h3>
											    	<a class='form_submiter btn btn-danger btn-md' href="<?php echo site_url("lib_generic/method/blog/blog/delete_post/{$post->id}") ?>"><i class='fa fa-warning'></i> Deletar</a>
										    		<br/>
										    	</div>

										    </div>
										  </div>
										</div>

									   <a class="btn btn-xs btn-info" href="<?php echo $blog_edit_link ?>" ><i class="fa fa-pencil"></i> </a>
									   <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete_<?php echo $post->id ?>" ><i class="fa fa-times"></i> </button>
								   </td>
								   <td>
								   	<?php if($post->action==0):?>
								   		<label class="btn btn-default btn-xs"> <i class="fa fa-edit"></i> Rascunho</label>
								   	<?php elseif($post->action==1): ?>
								   		<label class="btn btn-info btn-xs"><i class="fa fa-rocket"></i> Publicado</label>
								   	<?php endif; ?>
								   </td>
								 </tr>
								<?php endforeach;?>	
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</div>
</div>



<script>
	$(document).ready(function() {
		var table = $('#table-example').dataTable({
			'info': false,
			'sDom': 'lTfr<"clearfix">tip',
			'oTableTools': {
	            'aButtons': [
	                {
	                    'sExtends':    'collection',
	                    'sButtonText': '<i class="fa fa-cloud-download"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-caret-down"></i>',
	                    'aButtons':    [  ]
	                }
	            ]
	        }
		});
		
		
	});
	</script>