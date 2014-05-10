<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="" method="post">
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
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/index/add_hot_daren" target="dialog" mask=true><span>添加</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th >用户id</th>
				<th >达人姓名</th>
				<th>达人身份</th>
				<th>达人描述</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($hotDarenList as $k=>$v){
?>
						<tr target="article_id" rel="<?php echo $v->article_id ?>">
							<td style="height:30px;"><?php echo $v->userInfo[$v->user_id]->user_id;?></td>
							<td><?php echo $v->userInfo[$v->user_id]->realname;?></td>
							<td><?php echo $v->userInfo[$v->user_id]->grade->name;?></td>
							<td><?php echo $v->userInfo[$v->user_id]->description;?></td>

							<td><a  target="ajaxTodo" title="确定删除吗" class="delete" href="/index/del_daren?id=<?php echo $v->hot_id?>">删除</a></td>
						</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
