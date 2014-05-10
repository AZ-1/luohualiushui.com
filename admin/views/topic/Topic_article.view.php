<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/list" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索：<input type="text" name="keyword" placeholder="话题标题"/>
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
		<li><a class="add" href="/topic/add_topic_article?id=<?php echo $id;?>" target="navTab"><span>添加文章到话题</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="400">标题</th>
				<th width="500">描述</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($getTopicArticle as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->article_id; ?>">
				<td><a target="_blank" href="<?php echo MEI_BASE_URL;?>article/index?aid=<?php echo $v->article_id;?>"><?php echo $v->article_id;?></td>
				<td><a target="_blank" href="<?php echo MEI_BASE_URL;?>article/index?aid=<?php echo $v->article_id;?>"><?php echo $v->title;?></a></td>
				<td><?php echo $v->description;?></td>
				<td>
					<a target="ajaxTodo" href="/topic/topic_article?id=<?php echo $id;?>&aid=<?php echo $v->article_id?>" title="确定要删除吗?">删除</a>
					<span>&nbsp;&nbsp;<span>
					<a target="navTab" href="/topic/edit_topic_article?aid=<?php echo $v->article_id;?>&id=<?php echo $id;?>">添加封面</a>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>

</div>
