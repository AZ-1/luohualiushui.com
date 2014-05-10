<form id="pagerForm" method="post" action="">
	<input type="hidden" name="status" value="${param.status}">
	<input type="hidden" name="keywords" value="${param.keywords}" />
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="numPerPage" value="${model.numPerPage}" />
	<input type="hidden" name="orderField" value="${param.orderField}" />
</form>
<!--<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/index/article" method="post">
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
			<li><a class="add" href="/index/add_hot_article" target="navTab"><span>添加</span></a></li>
			<!--<li><a class="edit" href="/index/edit_article?id={article_id}" target="navTab"><span>修改</span></a></li>
			<li><a class="delete" href="/index/del_article?id={article_id}" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>-->
			<li class="line">line</li>
			<li style="display:none;"><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th >ID</th>
				<th >文章标题</th>
				<th >文章作者</th>
				<th >文章分类</th>
				<th >文章发布时间</th>
				<th>广场热门分类标签</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($hotArticle as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->article_id ?>">
				<td><?php echo $v->article_id;?></td>
				<td><a href="<?php echo MEI_BASE_URL; ?>article?aid=<?echo $v->article_id?>" target="_blank" title="<?php echo $v->title;?>"><?php echo $v->title;?></a></td>
				<td><?php echo $v->userInfo->realname;?></td>
				<td><?php echo $v->categoryName->name;?></td>
				<td><?php echo $v->create_time;?></td>
				<td><?php echo $v->tagName->name;?></td>

				<td><a class="delete" title="确定要删除吗" target="ajaxTodo" href="/index/del_hot_article?aid=<?php echo $v->article_id?>">删除</a></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
