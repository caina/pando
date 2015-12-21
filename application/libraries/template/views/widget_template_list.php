<div id="widget_theme">
	<?php foreach ($current_theme->Templates as $template):  ?>
		<span class="label label-<?php echo $current_theme->id == $template->id?'success':'default' ?> label-large">
			<?php echo anchor(site_url("act/template/template/index/".$template->id), $template->identification);  ?>
		</span> &nbsp;
	<?php endforeach ?>
	<a href="<?php echo site_url("act/template/template/create_new") ?>" type="button" class="btn btn-info btn-sm">
		<span class="fa fa-plus"></span> Novo Template
	</a>
</div>