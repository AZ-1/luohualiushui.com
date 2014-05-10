<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<!--<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/index" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：<input type="text" name="title" placeholder="文章标题"/>
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
	<form method="post" action="/article/add_article" id="myform" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="/article/add_category?cid=1" target="dialog" mask="true"><span>添加顶级类目</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="40">ID</th>
				<th width="300">分类名称</th>
				<th >操作</th>
				<!--<th width="150">发布时间</th>-->
			</tr>
		</thead>
		<tbody>
            <?php foreach($categoryList as $k=>$v){?>
				<tr target="article_id" rel="<?php echo $v->id;?>">
					<td>
						<?php echo $v->id;?>
					</td>
					<td>
						<?php if(isset($v->is_child)): ?>
							<?php echo '  &nbsp;  &nbsp; &nbsp; '; ?>	
						<?php endif;?>
						<?php echo $v->name;?>
					</td>
					<td>
						<a class="" target="dialog" mask="true"  href="/article/add_category?cid=<?php echo $v->id?>">添加子类</a> ||
						<a class="edit" target="dialog" mask="true"  href="/article/edit_category?cid=<?php echo $v->id?>">修改</a> || 
						<a class="delete"  title="确定要删除吗?" target="ajaxTodo" href="/article/del_category?cid=<?php echo $v->id?>">删除</a>

					</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
</form>
	<?php $this->includeTemplate('', 'pages');?>
</div>

