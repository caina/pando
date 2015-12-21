
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



<div class="container">
		<div class="page-content page-statement">
			<div class="row">





			<div class="widget">
				<div class="panel panel-default">
				  <div class="panel-heading"><?php echo $comments_aprove["total_comments"] ?> novos comentários</div>
				  <div class="panel-body">
				   <div class="task-widget">

				   <?php 
				   $post_id = 0;
				   foreach ($comments_aprove['comments'] as $commentario):
				   	if($post_id!=$commentario->id_blog_post){
				   		$post_id = $commentario->id_blog_post;
				   	}else{
				   		continue;
				   	}
				   ?>
				  		<div class="panel panel-default">
						  <div class="panel-heading">
						    <h4>
						    <?php echo $commentario->post ?>
						    
						    </h4>
						  </div>
						  <div class="panel-body">
							  <ul>
								 <?php foreach ($comments_aprove['comments'] as $comment):
								 	if($comment->id_blog_post != $post_id){
								 		continue;
								 	}
								 ?>
									 <li class="task-normal" id="<?php echo "li_{$comment->id}" ?>">
									 	<p>Nome: <?php echo $comment->name ?></p>
									 	<p>Email: <?php echo $comment->email ?></p>
									 	<p><?php echo $comment->comment?></p>
									 	<p>
										 	<a class="ajax_request" data_id_remove="<?php echo "li_{$comment->id}" ?>"  href="<?php echo site_url("lib_generic/method/blog/blog/comment_actions/{$comment->id}/aprove") ?>">
										 		<i class="fa fa-check"></i>
										 	</a>
										 	&nbsp;&nbsp;
										 	<a class="ajax_request" data_id_remove="<?php echo "li_{$comment->id}" ?>" href="<?php echo site_url("lib_generic/method/blog/blog/comment_actions/{$comment->id}/reject") ?>">
										 		<i class="fa fa-times"></i>
										 	</a>
									 	</p>
									 </li>
								<?php endforeach; ?>
							  </ul>

						  </div>
						</div>
				   <?php endforeach; ?>


				  </div>
				  </div>
				  	
				</div>
			</div>
	</div>
</div>
