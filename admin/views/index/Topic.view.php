<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="numPerPage" value="${model.numPerPage}" />
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
			<li><a class="add" href="/index/add_hot_topic" target="navTab"><span>添加热门话题</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="250">标题</th>
				<th>图片</th>
				<th>描述</th>
				<th>操作</th>
				<th>推荐排序</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($topicList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->topic_id; ?>">
				<td><?php echo $v->topic_id;?></td>
				<td><?php echo $v->title;?></td>
				<td><img width="200"  src="<?php echo $v->pic;?>" /></td>
				<td><?php echo $v->description;?></td>
				<td><a class="delete" href="/index/del_hot_topic?topic_id=<?php echo $v->topic_id;?>" target="ajaxTodo" title="确定要取消吗?"><span>取消热门</span></a></td>
				<td>
					<input class="topicSort" type='text' value="<?php echo $v->sort?>" topic_id=<?php echo $v->topic_id;?> >
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php $this->includeTemplate('', 'pages');?>
</div>
<script>
$('.topicSort').live('focusin',function(){
	var item = "<input class='topicButton' type='button' value='确定' />";
	$('body').find('.topicButton').detach();
	$('body').find('.tishi').detach();
	if($(this).parent().find('.topicButton').length == 0){
		$(this).parent().append(item);
	}
});
$('.topicButton').live('click',function(){
	var input = $(this).parent().find('.topicSort');
	var topic_id = input.attr('topic_id');
	var sort	= input.val();
	var url = '/index/edit_topic_sort';
	var data = {
		'topic_id':topic_id,
		'sort':sort
	}
	var callback = function(response){
		if(response){
			input.parent().find('.tishi').remove();
			input.parent().append("<span class='tishi' style='color:#F30C28'>修改成功</span>");	
		}else{
			input.parent().find('.tishi').remove();
			input.parent().append("<span class='tishi' style='color:#F30C28'>修改失败</span>");	
		}
	}
	$.post(url,data,callback,'json');
});
$('body').live('click',function(e){
	var target = $(e.target);
	var test = target.closest('.topicButton').length;
	var test2 = target.closest('.topicSort').length;
	if(test == 0 && test2 == 0)
	{
		$(this).parent().find('.topicButton').detach();
		$(this).parent().find('.tishi').detach();
	}
});



</script>
