<div id="login-full-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="login-box">
					<div id="login-box-holder">
						<div class="row">
							<div class="col-xs-12">
								<header id="login-header">
									<div id="login-logo">
										<img src="<?php echo base_url("assets/img/panda-sistema.png") ?>" alt=""/>
									</div>
								</header>
								<div id="login-box-inner">
									<form role="form" action="<?php echo site_url("login/do_login")?>" method="POST">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input class="form-control" name="username" type="text" placeholder="Usuário">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" name="password" class="form-control" placeholder="Senha">
										</div>
										<div id="remember-me-wrapper">
											<div class="row">
												<div class="col-xs-6">
													<div class="checkbox-nice">
														<input type="checkbox" id="remember-me" checked="checked" />
														<label for="remember-me">
															Lembrar me
														</label>
													</div>
												</div>
												<!-- <a href="forgot-password-full.html" id="login-forget-link" class="col-xs-6">
													Forgot password?
												</a> -->
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<button type="submit" class="btn btn-success col-xs-12">Login</button>
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
	</div>
</div>







