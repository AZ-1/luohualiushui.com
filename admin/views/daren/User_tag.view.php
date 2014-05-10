<form id="pagerForm" method="post" action="/daren/user">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/daren/user_tag" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					推荐达人标签名称：<input type="text" name="name" placeholder=""/>
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
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/daren/add_user_tag" target="dialog" mask=true><span>添加推荐达人标签</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">推荐达人标签名称</th>
				<th width="120">推荐达人标签所属分类</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($userTagList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->tag_id;?></td>
				<td><?php echo $v->name;?></td>
				<td><?php echo $v->categoryName;?></td>
				<td> 
					<a class="edit" href="/daren/edit_user_tag?id=<?php echo $v->tag_id; ?>" target="dialog" mask=true><span>修改</span></a> 
					<a class="delete" href="/daren/del_user_tag?id=<?php echo $v->tag_id; ?>" target="ajaxTodo" title="确定要取消吗?"><span>删除</span></a> 
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
