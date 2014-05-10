<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<!--<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					文章标题：<input type="text" name="keyword" />
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>-->
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/article/add_tag" target="dialog" mask="true" ><span>添加标签</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">标签</th>
				<th width="120">所属分类</th>
				<th width="120">操作</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($tagList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->tag_id; ?>">
				<td style="height:30px;"><?php echo $v->tag_id;?></td>
				<td><?php echo $v->name;?></td>
				<td><?php echo $v->catename;?></td>

			<td><a class="edit" target="dialog" mask=true href="/article/edit_tag?id=<?php echo $v->tag_id?>">修改</a> || 
				<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/article/del_tag?id=<?php echo $v->tag_id?>">删除</a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
