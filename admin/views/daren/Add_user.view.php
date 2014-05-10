<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return dialogSearch(this);" action="/daren/add_user" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索用户名：<input type="text" name="keyword" placeholder="用户ID/用户名字">(非达人用户)
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
			<li class="line">line</li>
		</ul>
	</div>
	<form method="post" action="/daren/add_user" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">名字</th>
				<th>达人身份</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($userList as $k=>$v){ if(empty($v->grade)){?>
			<tr target="article_id" rel="<?php echo $v->user_id ?>">
				<td class="userId"><?php echo $v->user_id;?></td>
				<td><?php echo $v->realname;?></td>
				<td>
				<select name="grade[<?php echo $v->user_id ?>]" class="grade">
						<option value="-1">请选择</option>
						<?php foreach($grade as $g){?>
							<option value="<?php echo $g->grade_id?>"><?php echo $g->name;?></option>
						<?php }?>
					</select>
				</td>
				<!--<td><a class="edit" href="/daren/edit_user?id=<?php echo $v->user_id; ?>" target="navTab"><span>修改达人身份</span></a> || <a class="delete" href="/daren/del_user?id=<?php echo $v->user_id; ?>" target="ajaxTodo" title="确定要取消吗?"><span>取消达人</span></a></td>-->
			</tr>
			<?php }}?>
			
		</tbody>
	</table>
	<div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div>
	<?php $this->includeTemplate('', 'pages');?>
</form>
</div>
