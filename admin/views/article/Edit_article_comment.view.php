<?php if(empty($commentList)):?>
<div style="text-align:center; padding-top:50px">本文章没有评论! </div>
<?php endif;?>

<?php if( !empty($commentList)):?>
	<table style="text-align:center;" class="table" width="100%" layoutH="138">
		<tr >
			<td width="5%">评论 Id</td>
			<td width="10%">评论人</td>
			<td width="30%">评论内容</td>
			<td width="15%">发布时间</td>
			<td width="10%" >操作</td>
		</tr>
		<?php foreach($commentList as $k=>$v){?>
		<tr target="comment_id" rel="<?php echo $v->comment_id;?>">
			<td style="height:30px;"><?php echo $v->comment_id;?></td>
			<td><a target="_blank" href="<?php echo MEI_BASE_URL; ?>person/index?uid=<?echo $v->user_id?>"><?php echo $v->user->realname;?></a></td>
			<td><?php echo $v->content;?></a></td>
			<td><?php echo $v->create_time;?></td>
			<td>
			<a  target="ajaxTodo" title="确定要删除吗?" href="/article/del_article_comment?article_id=<?php echo $v->article_id;?>&comment_id=<?php echo $v->comment_id;?>&is_delete=1&user_id=<?php echo $v->user_id;?>" aid="<?php echo $v->article_id ?>"><span>删除</span></a>
			</td>
		</tr>
		<?php }?>
	</table>

<?php endif;?>
