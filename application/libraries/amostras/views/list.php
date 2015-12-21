<div class="main-box-body clearfix">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th><span>Código</span></th>
					<th><span>Data</span></th>
					<th><span>Cliente</span></th>
					<th><span>Tipo</span></th>
					<th class="text-right"><span>Ações</span></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($samples_array as $sample): ?>
					<tr>
						<td>
							<?php echo $sample->code ?>
						</td>
						<td>
							<?php echo date("d/m/Y",strtotime($sample->date)) ?>
						</td>
						<td>
							<?php echo $sample->client_name ?>
						</td>
						<td>
							<?php echo (strlen($sample->sample_type) > 25?substr($sample->sample_type,0, 25)."...":$sample->sample_type) ?>
						</td>
						<td class="text-right">
							<a href="<?php echo site_url("act/amostras/amostra/detail/".$sample->code) ?>" class="table-link">
								<span class="fa-stack">
									<i class="fa fa-square fa-stack-2x"></i>
									<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
								</span>
							</a>
							<a href="<?php echo site_url("act/amostras/amostra/index/".$sample->code) ?>" class="table-link">
								<span class="fa-stack">
									<i class="fa fa-square fa-stack-2x"></i>
									<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
								</span>
							</a>
							
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<ul class="pagination pull-right">
		<li><a href="#" class="page" data_page="previous"><i class="fa fa-chevron-left " ></i></a></li>
		<?php for ($i=0; $i < $pages; $i++):?>
			<li>
				<a href="#" class="page" data_page="<?php echo $i ?>"><?php echo $i+1 ?></a>
			</li>
		<?php endfor; ?>
		<li><a href="#" class="page" data_page="next"><i class="fa fa-chevron-right" ></i></a></li>
	</ul>
</div>