<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="cosmetic_id" value="<?php if($cosmetic_id){echo $cosmetic_id;}?>"/>
	<input type="hidden" name="keyword" value="<?php if($keyword){echo $keyword;}?>"/>
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/cosmetic/comment" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索产品名称：<input type="text" name="keyword" />
				</td>
			</tr>
			<tr><td>搜索产品id(主): <input type="text" name="cosmetic_id"/></td></tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<table style="text-align:center;" class="table" width="100%" layoutH="138">
	<tr >
		<td width="50">评论人</td>
		<td width="50">产品id</td>
		<td width="300">产品名称</td>
		<td width="500">评论内容</td>
		<td width="100">发布时间</td>
		<td width="100" >操作</td>
	</tr>
    <?php if(isset($commentList) && $commentList){foreach($commentList as $k=>$v){?>
	<tr target="comment_id" rel="<?php echo $v->id;?>">
		<td>
			<?php echo $v->user_id;?>
		</td>
		<td><?php echo $v->goods_id;?></td>
		<td>
			<?php echo $v->info->pro_name;?>
		</td>
<!--
		<td style="height:30px;">
			<input type="checkbox" uid="<?php echo $v->user_id;?>" cosmetic_id="<?php echo $v->goods_id?>" value="<?php echo $v->comment_id;?>" name="comment_ids[]">
		</td>
-->
		<td><?php echo $v->content;?></td>
		<td><?php echo $v->publish_time;?></td>
		<td><a class="edit" target="dialog" mask=true href="/cosmetic/edit_comment?comment_id=<?php echo $v->id;?>">修改</a> || 
			<a title="确定要删除吗?" class="delete" target="ajaxTodo" href="/cosmetic/del_comment?del_id=<?php echo $v->id;?>">删除</a></td>
		<td>
		</td>
		<td></td>
	</tr>
	<?php }}?>
</table>
	<?php $this->includeTemplate('', 'pages');?>
<script>
/*
$('.deleteComment').bind('click',function(){
	if(confirm('是否删除?')){
		var checked = $('input:checked');
		var comment_ids = new Array();
		var article_ids = new Array();
		var user_ids = new Array();
		checked.each(function(i){
			comment_ids[i] =	$(this).val();
			article_ids[i] =	$(this).attr('aid');
			user_ids[i] = $(this).attr('uid');
		});
		var url = '/article/Del_article_comment';
		var data = {
			'is_delete' : 1,
			'comment_ids' : comment_ids,
			'article_ids' : article_ids,
			'user_ids' : user_ids
		};
		var callback = function(res){
			checked.each(function(i){
				$(checked[i]).parents('tr').remove();
			});
			alert(res.message);
		}
		$.post(url,data,callback,'json');
	}
});
 */
</script>
