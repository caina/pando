<div class="col-lg-12">
	<div id="email-box" class="clearfix">
		<div class="row">
			<div class="col-lg-12">
					
				<div id="email-header-mobile" class="visible-xs visible-sm clearfix">
					<div id="email-header-title-mobile" class="pull-left">
						<i class="fa fa-inbox"></i> Emails
					</div>
				</div>
					
				<header id="email-header" class="clearfix">
					<div id="email-header-title" class="visible-md visible-lg">
						<i class="fa fa-inbox"></i> Inbox
					</div>
					
					<div id="email-header-tools">
						<div class="btn-group">
							<a href="<?php echo site_url("lib_generic/method/email/email/index") ?>" class="btn btn-primary">
								<i class="fa fa-chevron-left"></i> Voltar aos emails
							</a>
						</div>
					</div>
				</header>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				
				<div id="" class="email-detail-nano has-scrollbar" style="height: 414px;">
					<div class="email-detail-nano-content" tabindex="0" style="right: -15px;">
						<div id="email-detail-inner">
							<div id="email-detail-subject" class="clearfix">
								<span class="subject"><?php echo $email->subject ?></span>
							</div>
							<div id="email-detail-sender" style="padding:0 0;" class="clearfix">
								
								<div class="users">
									<div class="from clearfix">
										<div class="name">
											<?php echo $email->name ?>
										</div>
										<div class="email hidden-xs">
											&lt;<?php echo $email->email  ?>&gt;
										</div>
									</div>
									<div class="to">
										Para: <span><?php echo $email->email_to ?></span>
									</div>
								</div>
								
								<div class="tools">
									<div class="date">
										<?php echo date("d/m/Y",strtotime($email->send_date)) ?>
									</div>
								</div>
								
							</div>
							
							
							<div id="email-body">
								<?php if($email->error==1): ?>
									<p>
										Mensagem de erro: <?php echo $email->error_message ?>
									</p>
								<?php endif; ?>
								<p>
									<?php echo $email->message ?>
								</p>
							</div>
						</div>
					</div>
				<div class="nano-pane"><div class="nano-slider" style="height: 272px; transform: translate(0px, 0px);"></div></div></div>
			</div>
		</div>
	</div>
</div>