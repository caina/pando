<div class="col-lg-6">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2>Últimos Acessos</h2>
		</header>
		
		<div class="main-box-body clearfix">
		
			<ul class="widget-users row">
				<?php foreach($logs as $log):?>
					<li class="col-md-6" style="  padding: 9px 0;height: 66px;">
						
						<div class="details" style="margin-left: 0px; !important">
							<div class="name">
								<span class="label label-warning"><?php echo $log->company_name ?></span> <a href="#"><?php echo $log->name ?> </a>
							</div>
							<div class="time">
							<br/>
								<i class="fa fa-clock-o"></i> <?php echo date("d/m H:s",strtotime($log->login_date)) ?>
							</div>
							
						</div>
					</li>
				<?php endforeach; ?>
				
			</ul>
			
		</div>
	</div>
</div>

<div class="col-lg-6">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2><i class="fa fa-trophy"></i> Atualizações!</h2>
		</header>
		<div class="main-box-body clearfix">

			<p>A nova vers&atilde;o do Pando est&aacute; a&iacute;, adicionamos grandes recursos e fizemos muitas mudan&ccedil;as, se acharem qualquer erro, mande um email para <a href="mailto:socorro@apanda.com.br">socorro@apanda.com.br</a> que iremos resolver o quanto antes!</p>

			<p>das novidades:</p>

			<ul>
				<li><i class="fa fa-circle fa-1"></i> Salvar agora redireciona para a listagem de dados, e tem a opção de continuar criando</li>
				<li><i class="fa fa-circle fa-1"></i> Sistema avisa quantos emails novos foram recebidos</li>
				<li><i class="fa fa-circle fa-1"></i> Implementado analytics OAuth 2.0, caso você não tenha no dashboard os acessos, nos requisite a instalação</li>
				<li><i class="fa fa-circle fa-1"></i> Sistema de Permiss&otilde;es: no menu usu&aacute;rios, voc&ecirc; poder&aacute; dizer quais menus cada usu&aacute;rio seu tem acesso.</li>
				<li><i class="fa fa-circle fa-1"> </i> Perfil do usu&aacute;rio: No canto superior &aacute; direita, voc&ecirc; pode colocar mais informa&ccedil;&otilde;es sobre voc&ecirc;, vincular com o facebook para ter a sua foto aqui.</li>
				<li><i class="fa fa-circle fa-1"> </i> Op&ccedil;&otilde;es de formata&ccedil;&atilde;o no editor de texto, ATEN&Ccedil;&Atilde;O voc&ecirc; deve limpar o cache do navegador.</li>
				<li><i class="fa fa-circle fa-1"> </i> Mensagens de confirma&ccedil;&atilde;o ao salvar e editar um item.</li>
				<li><i class="fa fa-circle fa-1"> </i> Em blog: Adicionamos a op&ccedil;&atilde;o para deletar uma categoria.</li>
				<li><i class="fa fa-circle fa-1"> </i> Corrigimos detalhes da&nbsp;interface.</li>
			</ul>

			<p>Outro detalhe:&nbsp;A Google n&atilde;o est&aacute; mais dando suporte &aacute; uma biblioteca que usavamos para mostrar os dados do Google Analytics, estamos trabalhando para resolver isto o quanto antes.</p>

			<p>Att;</p>

		</div>
	</div>
</div>
