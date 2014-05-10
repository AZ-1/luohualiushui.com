<div class="content">

</div>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/user/user" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：<input type="text" name="keyword" placeholder="用户名字"/>
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
			<li><a class="add" href="/user/add_user" target="navTab"><span>添加</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="250">名字</th>
				<th width="250">创建时间</th>
				<th>用户身份</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($userList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->user_id;?></td>
				<td><?php echo $v->realname;?></td>
				<td><?php echo $v->create_time;?></td>
				<td><?php if(!is_object($v->identity)){echo '普通群众';}else{echo $v->identity->identity;}?></td>
				<td><a class="edit" href="/user/edit_user?id=<?php echo $v->user_id; ?>" target="navTab"><span>绑定账号</span></a> || <?php if($v->is_delete == 0){?><a style="color:red;" class="delete" href="/user/del_user?id=<?php echo $v->user_id; ?>" target="ajaxTodo" title="确定要禁用吗?"><span>禁用</span></a><?php }?><?php if($v->is_delete == 1){?><a style="color:green;" class="delete" href="/user/add_user?id=<?php echo $v->user_id; ?>" target="ajaxTodo" title="确定要解除吗?"><span>已禁用,解除</span></a><?php }?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
