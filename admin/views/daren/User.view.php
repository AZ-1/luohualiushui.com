<form id="pagerForm" method="post" action="/daren/user">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/daren/user" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索达人用户名：<input type="text" name="keyword" placeholder="用户名字"/>
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
			<li><a class="add" href="/daren/add_user" target="dialog" mask=true><span>添加达人</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">名字</th>
				<th width="120">文章数</th>
				<th width="120">粉丝数</th>
				<th>达人身份</th>
				<th>最后登录时间</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($userList as $k=>$v){ if(!empty($v->grade) && !$v->is_delete){?>
			<tr target="article_id" rel="<?php echo $v->user_id ?>">
				<td style="height:30px;"><?php echo $v->user_id;?></td>
				<td><?php echo $v->realname;?></td>
				<td><?php echo $v->article_num;?></td>
				<td><?php echo $v->fans_num;?></td>
				<td><?php echo $v->grade->name;?></td>
				<td><?php echo date('Y-m-d H:i:s',$v->last_login_time);?></td>
				<td><a class="edit" href="/daren/edit_user?id=<?php echo $v->user_id; ?>" target="navTab"><span>修改达人身份</span></a> || 
					<a class="delete" href="/daren/del_user?id=<?php echo $v->user_id; ?>" target="ajaxTodo" title="确定要取消吗?"><span>取消达人</span></a> ||
					<!--<?php if($v->is_recommend): ?>
						<a href="/daren/recommend?id=<?php echo $v->user_id; ?>&is_recommend=0" target="ajaxTodo"><span style="color:red">取消推荐</span></a>
					<?php else: ?>
						<a href="/daren/recommend?id=<?php echo $v->user_id; ?>&is_recommend=1" target="ajaxTodo"><span>推荐</span></a>
					<?php endif; ?>-->
				</td>
			</tr>
			<?php }}?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
