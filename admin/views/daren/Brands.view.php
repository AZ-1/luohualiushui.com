<div class="content">
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
			<li><a class="add" href="/daren/add_brands" target="navTab"><span>添加品牌</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">品牌名称</th>
				<th width="120">链接</th>
				<th>图片路径</th>
				<th>图片样式</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($brandsList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->brands_id;?></td>
				<td><?php echo $v->name;?></td>
				<td><?php echo $v->link_url;?></td>
				<td><?php echo $v->logo;?></td>
				<td><img width=150 src="<?php echo $v->logo;?>" /></td>
				<td><a class="edit" href="/daren/edit_brands?id=<?php echo $v->brands_id; ?>" target="navTab"><span>修改</span></a> || <a class="delete" href="/daren/del_brands?id=<?php echo $v->brands_id; ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
</div>
<script>
</script>
