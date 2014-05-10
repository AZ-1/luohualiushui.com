<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="title" value="<?php echo $title?>" />
	<input type="hidden" name="realname" value="<?php echo $realname?>" />
	<input type="hidden" name="quality" value="<?php echo $quality?>" />
	<input type="hidden" name="category" value="<?php echo $searchCategory?>" />
	<input type="hidden" name="tag" value="<?php echo $tag?>" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/recycle" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
							文章标题<input type="text" name="title" value="<?php echo $title?>"/>
							文章作者<input type="text" name="realname" value="<?php echo $realname?>"/>
							<select name="quality">
								<option value="0" >审核状态</option>
								<option value="未审核" <?php if($quality==='未审核'){ echo 'selected';} ?>  >未审核</option>
								<option value="未通过" <?php if($quality==='未通过'){ echo 'selected';} ?> >未通过</option>
								<option value="通过" <?php if($quality==='通过') {echo 'selected';} ?> >通过</option>
								<option value="质量上" <?php if($quality==='质量上'){ echo 'selected';} ?>>质量上</option>
								<option value="质量中" <?php if($quality==='质量中'){ echo 'selected';} ?> >质量中</option>
								<option value="质量下" <?php if($quality==='质量下') echo 'selected' ?> >质量下</option>
							</select>
							<select name="category">
								<option value="0" >分类</option>
								<?php foreach($category as $c){?>
									<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
									<?php if(isset($c->child) && !empty($c->child)){
										foreach($c->child as $ch){?>
											<option value="<?php echo $ch->id;?>">&nbsp&nbsp&nbsp<?php echo $ch->name;?></option>
										<?php }?>
									<?php }?>
								<?php }?>
							</select>
							<!--<select name="tag">
								<option value="0" >标签</option>
								<?php foreach($tagList as $t){?>
									<option value="<?php echo $t->tag_id;?>"><?php echo $t->name;?></option>
								<?php }?>
							</select>-->
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
	<form method="post" action="/topic/add_topic_article"  class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
	<table style="text-align:center;" class="table" width="100%" layoutH="138">
			<tr >
				<td width="5%">选择 Id</td>
				<td width="10%">文章标题</td>
				<td width="10%">作者</td>
				<td width="10%">分类</td>
				<td width="10%">标签</td>
				<td width="10%">审核状态</td>
				<td width="10%">所属话题</td>
				<td width="15%">发布时间</td>
				<td width="20%" >操作</td>
				<!--<td width="150">发布时间</td>-->
			</tr>
            <?php foreach($articleList as $k=>$v){?>

			<tr target="article_id" rel="<?php echo $v->article_id?>">
				<td><?php echo $v->article_id?></td>
				<td><a target="_blank" href="<?php echo MEI_BASE_URL; ?>article?aid=<?echo $v->article_id?>"><?php echo $v->title;?></a></td>
				<td><a target="_blank" href="<?php echo MEI_BASE_URL; ?>person/index/?uid=<?php echo $v->user_id;?>"><?php echo $v->user->realname;?></a></td>
				<td><?php echo $v->category->name;?> | <?php echo $v->top_category_name;?></td>
				<td><?php echo $v->tagName?></td>
<?php 

	$href = "/article/check_article?id=".$v->article_id;
	if($v->is_check == 0){
		$str = "未审核";
		$color = "#000";
	}elseif($v->is_check == 1){
		$str = "未通过";
		$color = "#ff0000";
	}elseif($v->is_check == 2){
		if($v->quality == 1){
			$str = "质量上乘";
		}elseif($v->quality == 2){
			$str = "质量中等";
		}elseif($v->quality){
			$str = "质量下等";
		}else{
			$str = "";
		}
		$color = "green";
	}
?>
				<td><div style="color:<?php echo $color;?>"><?php echo $str.'|'.$v->no_pass_reason;?></div></td>
				<td><?php if($v->topic) echo $v->topic->title;?></td>
				<td><?php echo $v->create_time;?></td>
	<td>
		<a class="deleteArticleasd" href="#" aid="<?php echo $v->article_id ?>"><span>恢复</span></a>
		<a class="" href="/article/check_recycle?id=<?php echo $v->article_id ?>" target="navTab"><span>查看文章内容</span></a>
	</td>
			</tr>
			<?php }?>
	</table>
</form>
	<?php $this->includeTemplate('', 'pages');?>
</div>
<script>
	$('.deleteArticleasd').die('click').live('click' , function(){
		if(confirm('是否回收?')){
			var __this = this;
			console.log($(this).attr('aid'));
			$.post('/article/up_delete' , {'aid' : $(this).attr('aid')} , function(){
				$(__this).parent().parent().parent().remove();
			});
		}
	})
</script>
