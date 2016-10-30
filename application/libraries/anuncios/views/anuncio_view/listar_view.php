<input type="hidden" id="api_url" value="<?php echo site_url("lib_generic/method/anuncios/ad/") ?>">

<div class="row">
	<div class="col-lg-12">
		<div class="main-box">
			<div class="main-box-body clearfix">
				anuncio aqui
			</div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-lg-12">
		<div class="main-box">
			<header class="main-box-header clearfix">
				<h2></h2>
			</header>

			<div class="main-box-body clearfix">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a href="<?php  ?>" >
								Meus Anuncios
							</a>
						</li>
						<li>
							<a href="<?php echo site_url("lib_generic/method/anuncios/ad/create_new_ad") ?>">
							Inserir Anúncio
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="ad_ajax_view">

</div>

<div class="row">
	<div class="col-lg-12">
		<div class="main-box" >
			<?php echo $pager_html ?>
		</div>
	</div>
</div>
