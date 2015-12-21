<div class="main-box-body clearfix">
	<ul class="pagination">
		<!-- <li class="disabled"><a href="#" class="pager" data-page="previous"><i class="fa fa-chevron-left"></i></a></li> -->
    <?php for($i=0;$i<$pages;$i++): ?>
      <li class="<?php echo $i==0?"active":"" ?>">
        <a href="#" class="pager" data-page="<?php echo $i ?>" ><?php echo $i+1; ?></a>
      </li>
    <?php endfor; ?>
		<!-- <li><a href="#" class="pager" data-page="next" ><i class="fa fa-chevron-right"></i></a></li> -->
	</ul>
</div>
