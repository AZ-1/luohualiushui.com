<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/comment" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					搜索评论内容：<input type="text" name="keyword" />
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
	<input type="button" class="deleteComment" value="批量删除">
</div>
<table style="text-align:center;" class="table" width="100%" layoutH="138">
	<tr >
		<td width="3%">评论 Id</td>
		<td width="8%">评论人</td>
		<td width="10%">评论所在文章</td>
		<td width="30%">评论内容</td>
		<td width="6%">发布时间</td>
		<td width="3%" >操作</td>
	</tr>
    <?php foreach($commentList as $k=>$v){?>
	<tr target="comment_id" rel="<?php echo $v->comment_id;?>">
		<td style="height:30px;"><?php echo $v->comment_id;?>
			<input type="checkbox" uid="<?php echo $v->user_id;?>" aid="<?php echo $v->article_id?>" value="<?php echo $v->comment_id;?>" name="comment_ids[]">
		</td>
		<td>
			<a target="_blank" href="<?php echo MEI_BASE_URL; ?>person/index?uid=<?php echo $v->user_id?>">
				<?php echo $v->user->realname;?>
			</a>
		</td>
		<td>
			<a target="_blank" href="<?php echo MEI_BASE_URL;?>article/index?aid=<?php echo $v->article_id?>">
				<?php if(isset($v->title)){echo $v->title;}else{echo '文章已删除!';}?>
			</a>
		</td>
		<td><?php echo $v->content;?></td>
		<td><?php echo $v->create_time;?></td>
		<td>
			<a  target="ajaxTodo" title="确定要删除吗?" href="/article/del_article_comment?article_id=<?php echo $v->article_id;?>&comment_id=<?php echo $v->comment_id;?>&is_delete=1&user_id=<?php echo $v->user_id;?>" aid="<?php echo $v->article_id ?>"><span>删除</span></a>
		</td>
	</tr>
	<?php }?>
</table>
	<?php $this->includeTemplate('', 'pages');?>
<script>
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
</script>
