<li class="dropdown hidden-xs">
	<a class="btn dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-envelope-o"></i>
		<?php if($emails->total>0): ?>
			<span class="count"><?php echo $emails->total ?></span>
		<?php endif; ?>
	</a>
	<ul class="dropdown-menu notifications-list messages-list">
		<li class="pointer">
			<div class="pointer-inner">
				<div class="arrow"></div>
			</div>
		</li>
		<?php foreach ($emails->result as $email): ?>
			<li class="item first-item">
				<a href="<?php echo site_url("act/email/email/email_detail/".$email->id) ?>">
					<span class="content" style="padding-left:0px">
						<span class="content-headline">
							<?php echo word_limiter($email->subject,5) ?>
						</span>
						<span class="content-text">
							<?php echo word_limiter($email->message,10) ?>
						</span>
					</span>
					<span class="time"><i class="fa fa-clock-o"></i><?php echo date("d/m/y",strtotime($email->send_date)) ?></span>
				</a>
			</li>
		<?php endforeach; ?>
		<li class="item-footer">
			<a href="<?php echo site_url("act/email/email/index") ?>">
				Veja todos os emails
			</a>
		</li>
	</ul>
</li>

