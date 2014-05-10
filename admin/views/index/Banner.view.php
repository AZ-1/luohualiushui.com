<script>

</script>

<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
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
			<li><a class="add" href="/index/add_banner" target="navTab"><span>添加焦点图</span></a></li>
			<li></li>
			<li class="line">line</li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th>标题</th>
				<th>链接路径</th>
				<th >图片</th>
				<th>操作</th>
				<th>排序</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($bannerList as $k=>$v){?>
			<tr target="article_id" rel="<?php echo $v->id ?>">
				<td><?php echo $v->title;?></td>
				<td><?php echo $v->link_url;?></td>
				<td><img  width="100" src="<?php echo $v->pic_url;?>" /></td>
				<td>
					<a class="edit" href="/index/edit_banner?id=<?php echo $v->id ?>" target="navTab"><span>修改</span></a> 
					|| <a class="delete" href="/index/del_banner?id=<?php echo $v->id ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a>
					<?php if($v->is_online){?>
						|| <a class="delete" href="/index/Is_online_banner?id=<?php echo $v->id ?>&online=0" target="ajaxTodo" ><span style='color:red'>下线</span></a>
					<?php }else{?>
						|| <a class="delete" href="/index/Is_online_banner?id=<?php echo $v->id ?>&online=1" target="ajaxTodo" ><span style='color:green'>上线</span></a> 
					<?php }?>
				</td>
				<td>
					<input class="topicSort" type='text' value="<?php echo $v->sort?>" id=<?php echo $v->id;?> >
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
	var url = '/index/edit_banner_sort';
	var data = {
		'id':id,
		'sort':sort
	}
	console.log(data);
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
