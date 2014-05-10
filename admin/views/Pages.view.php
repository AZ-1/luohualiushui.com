
	<div class="panelBar">
		<div class="pages">
		<span>共<?php echo $page->totalNum ?>条</span>
		</div>
		
		<div class="pagination" targetType="navTab" totalCount="<?php echo $page->totalNum ?>" numPerPage="<?php echo $page->length ?>" pageNumShown="10" currentPage="<?php echo $page->pageNum  ?>"></div>

	</div>
