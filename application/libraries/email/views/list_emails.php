	<div class="col-lg-12">
				<header id="email-header" class="clearfix">
						<div class="btn-group">
							<a class="btn btn-primary" href="<?php echo site_url("lib_generic/method/email/email/download_emails/") ?>">
								<i class="fa fa-cloud-download"></i>
								Baixar emails
							</a>
						</div>
				</header>
	</div>
	<div class="email-content-nano-content">
		<ul id="email-list">
		<?php foreach ($emails as $email):?>
			<li class="unread clickable-row" data-href="<?php echo site_url("lib_generic/method/email/email/email_detail/".$email->id) ?>">
				<div class="chbox">
					<div class="checkbox-nice">
						<input type="checkbox" <?php echo $email->notified==1?'checked="true"':'' ?> readonly>
						<label></label>
					</div>
				</div>
				<div class="star">
					<a></a>
				</div>
				<div class="name">
					<?php echo $email->name ?>
				</div>
				<div class="message">
					<span class="label label-<?php echo $email->error==0?"success":"danger" ?>"><?php echo ($email->error==0?"Sucesso":"Falha no envio") ?></span> 
					<span class="subject"><?php echo $email->subject ?></span>
					<span class="body"><?php echo  $email->error==0?word_limiter(strip_tags($email->message),5):word_limiter($email->error_message,5) ?></span>
				</div>
				<div class="meta-info">
					<span class="date"><?php echo date("d/m/Y",strtotime($email->send_date)) ?></span>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
