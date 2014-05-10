<form id="pagerForm" method="post" action="/topic/index">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/topic/index" method="post">
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
			<li><a class="add" href="/topic/add_topic" target="navTab"><span>添加话题</span></a></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">标题</th>
				<th>图片</th>
				<th>描述</th>
				<th>操作</th>
				<th>排序</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($getTopic as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->topic_id; ?>">
				<td><?php echo $v->topic_id;?></td>
				<td><?php echo $v->title;?></td>
				<td><img src="<?php echo $v->pic;?>" width="100" /></td>
				<td><?php echo $v->description;?></td>
				<td>
					<a class="edit" href="/topic/edit_topic?id=<?php echo $v->topic_id; ?>" target="navTab"><span>修改</span></a> 

					<?php if($v->is_delete){?>
						|| <a class="delete" href="/topic/Is_online_topic?topic_id=<?php echo $v->topic_id ?>&online=1" target="ajaxTodo" ><span style='color:green'>上线</span></a> 
					<?php }else{?>
						|| <a class="delete" href="/topic/Is_online_topic?topic_id=<?php echo $v->topic_id ?>&online=0" target="ajaxTodo" ><span style='color:red'>下线</span></a>
					<?php }?>

					|| <a href="/topic/topic_article?id=<?php echo $v->topic_id; ?>" target="navTab"><span>话题文章</span>
					</a>
				</td>
				<td>
					<input class="topicSort" type='text' value="<?php echo $v->sort?>" topic_id=<?php echo $v->topic_id;?> >
				</td>
<!--

>			<td><a href="/index/del_article?aid=<?php //echo $v->id?>">删除</a> | <a href="/index/edit_article?aid=<?php //echo $v->id?>">编辑</a></td>
-->
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
	var url = '/topic/edit_topic_sort';
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
