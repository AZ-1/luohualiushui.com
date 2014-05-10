<form id="pagerForm" method="post" action="/daren/user">
	<input type="hidden" name="pageNum" value="1" />
</form>
<!--<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/daren/user_category" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					达人分类名称：<input type="text" name="name" />
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
			<li><a class="add" href="/daren/add_user_category" target="dialog" mask=true><span>添加推荐达人分类</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="80">ID</th>
				<th width="120">推荐达人分类名称</th>
				<th width="120">推荐达人分类排序</th>
				<th width="120">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($userCategoryList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td style="height:30px;"><?php echo $v->id;?></td>
				<td><?php echo $v->name;?></td>
				<td>
					<input class="topicSort" type='text' value="<?php echo $v->sort?>" id=<?php echo $v->id;?> >
				</td>
				<td> 
					<a class="edit" href="/daren/edit_user_category?id=<?php echo $v->id; ?>" target="dialog" mask=true><span>修改</span></a> 
<!--
					<a class="delete" href="/daren/del_user_category?id=<?php echo $v->id; ?>" target="ajaxTodo" title="确定要取消吗?"><span>删除</span></a> 
-->
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
	var id = input.attr('id');
	var sort	= input.val();
	var url = '/daren/edit_category_sort';
	var data = {
		'id':id,
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
