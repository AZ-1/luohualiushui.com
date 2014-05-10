<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/cosmetic/index" method="post">
	<div class="searchBar">
		<table class="searchContent">
		</table>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/cosmetic/add_rank" target="dialog" mask="true" ><span>添加排行</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="50%">名称</th>
				<th width="50%">操作</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($rank as $k=>$v){?>
				<tr target="article_id" rel="<?php echo $v->id; ?>">
					<td><?php echo $v->list_name;?></td>
					
					<td>
						<a class="edit" target="navTab" mask=true href="/cosmetic/rankgoods?cid=<?php echo $k;?>">排商品</a> || 
						<a class="edit" target="dialog" mask=true href="/cosmetic/edit_rank?id=<?php echo $k;?>">修改</a> || 
						<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_brand?brand_id=<?php echo $v->id;?>">删除</a>
					</td>
				</tr>
				<?php foreach($v->child as $ck=>$cv){?>
					<tr>
						<td><?php echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$cv->list_name?></td>
						<td>
							<a class="edit" target="navTab" href="/cosmetic/rankgoods?cid=<?php echo $ck;?>">排商品</a> || 
							<a class="edit" target="dialog" mask=true href="/cosmetic/edit_rank?id=<?php echo $ck;?>">修改</a> || 
							<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_brand?brand_id=<?php echo $v->id;?>">删除</a>
						</td>
					</tr>
				<?php }?>
			<?php }?>
		</tbody>
	</table>
</div>
<?php $this->includeTemplate('', 'pages');?>
