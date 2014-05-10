<link href="/static/css/core.css" type="text/css" rel="stylesheet"/>
<script src="/static/js/popup_layer.js" type="text/javascript" language="javascript"></script>
<script src="/static/js/article.js" type="text/javascript" language="javascript"></script>
<style>

.confirmCheck{width:400px;height:150px;margin-left:30px;position:absolute;top:200px;left:200px;background-color:#fff;display:none;border:2px solid #ccc;}
</style>
<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum"		value="1" />
	<input type="hidden" name="title"		value="<?php echo $title?>" />
	<input type="hidden" name="realname"	value="<?php echo $realname?>" />
	<input type="hidden" name="quality"		value="<?php echo $quality?>" />
	<input type="hidden" name="category"	value="<?php echo $searchCategory?>" />
	<input type="hidden" name="tag"			value="<?php echo $tag?>" />
	<input type="hidden" name="start_time"  value="<?php echo $start_time?>" />
	<input type="hidden" name="end_time"    value="<?php echo $end_time?>" />
</form>
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="/article/index" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
							<span style="float:left;">文章ID<input type="text" name="article_id" value="<?php ?>"/>
							文章标题<input type="text" name="title" value="<?php echo $title?>"/>
							文章作者<input type="text" name="realname" value="<?php echo $realname?>"/>
							</span>
							<span style="float:left;">起始时间<input type="text" name="start_time" value='<?php echo $start_time;?>' />                                                                                                  
							</span>
							<span style="float:left">终止时间<input type="text" name="end_time" value='<?php echo $end_time;?>' />
						<select name="quality">
								<option value="0" >审核状态</option>
								<option value="未审核" <?php if($quality==='未审核'){ echo 'selected';} ?>  >未审核</option>
								<option value="未通过" <?php if($quality==='未通过'){ echo 'selected';} ?> >未通过</option>
								<option value="通过" <?php if($quality==='通过') {echo 'selected';} ?> >通过</option>
								<option value="质量上" <?php if($quality==='质量上'){ echo 'selected';} ?>>质量上</option>
								<option value="质量中" <?php if($quality==='质量中'){ echo 'selected';} ?> >质量中</option>
								<option value="质量下" <?php if($quality==='质量下') echo 'selected' ?> >质量下</option>
							</select>
							</span>
							<span style="float:left"><select name="category">
								<option value="0" >分类</option>
								<?php foreach($category as $c){?>
									<option value="<?php echo $c->id;?>" <?php if($searchCategory == $c->id){echo 'selected';}?>><?php echo $c->name;?></option>
									<?php if(isset($c->child) && !empty($c->child)){
										foreach($c->child as $ch){?>
											<option value="<?php echo $ch->id;?>" <?php if($searchCategory == $ch->id){echo 'selected';}?>>&nbsp&nbsp&nbsp<?php echo $ch->name;?></option>
										<?php }?>
									<?php }?>
								<?php }?>
							</select>
							<select name="tag">
								<option value="0" >标签</option>
								<?php foreach($tagList as $t){?>
									<option value="<?php echo $t->tag_id;?>" <?php if($tag == $t->tag_id){echo 'selected';}?>><?php echo $t->name;?></option>
								<?php }?>
							</select>
							</span>
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
	<div class="panelBar">
		<ul class="toolBar">
			<!--<li><a class="edit" href="/article/edit_article?aid={article_id}" target="navTab"><span>修改</span></a></li>-->
<!--			<li><a class="add" href="/article/add_article?aid={article_id}" target="navTab"><span>添加到话题</span></a></li>  -->

			<li>
				<select name="id"  class="required  topic_list">
					<option value="" selected>话题</option>
                <?php foreach($topicList as $v){?>
					<option value="<?php echo $v->topic_id;?>"><?php echo $v->title;?></option>
				<?php }?>
				</select>
     		</li>
			<li>
				<input  type="button" value="添加到话题" class="addTopic"/>
			</li>

			<li>
				<a class="add moreCheck" href="javascript:void(0);"><span>批量审核</span></a>
			</li>
			<li>
				<a class="add moreDel" href="javascript:void(0);"><span>批量删除</span></a>
			</li>
			<li class="line">line</li>
		</ul>
	</div>
	<table style="text-align:center;" class="table" width="100%" layoutH="138">
		<thead>
			<tr >
				<th width="5%">选择 ID<input class="allCheckBox"  type="checkbox" name=""  value=""/></th>
				<th width="10%">文章标题</th>
				<th width="10%">作者</th>
				<th width="10%">分类</th>
				<th width="5%">标签</th>
				<th width="5%">审核状态</th>
				<th width="5%">所属话题</th>
				<th width="5%">阅读量</th>
				<th width="5%">评论数</th>
				<th width="5%">点赞数</th>
				<th width="10%">发布时间</th>
				<th width="5%">已是热门文章</th>
				<th width="5%">已是达人文章</th>
				<th width="20%" >操作</th>
			</tr>
		</thead>
		<tbody>
			<tr style="">
				<td><div style="overflow:hidden;"></div></td>
				<?php for($i = 0;$i < 13;$i++ ){?>	
					<td></td>
				<?php }?>

			</tr>
            <?php foreach($articleList as $k=>$v){?>
				<tr target="article_id" rel="<?php echo $v->article_id?>" class="article_<?php echo $v->article_id?>">
					<td style="height:30px;"><?php echo $v->article_id ?><input class="required"  type="checkbox" name="article[]"  value="<?php echo $v->article_id;?>"/></td>

					<td><a title="<?php echo $v->title;?>" target="_blank" href="<?php echo MEI_BASE_URL; ?>article?aid=<?echo $v->article_id?>"><?php echo $v->title;?></a></td>

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

					<td><div class="checkStatus" style="color:<?php echo $color;?>"><?php echo $str.'| '.$v->no_pass_reason;?></div></td>

					<td class="topic_<?php echo $v->article_id;?>"><?php if($v->topic) echo $v->topic->title;?></td>

					<td>999</td>

					<td><?php echo $v->comment_num?></td>

					<td><?php echo $v->like_num?></td>

					<td><?php echo $v->create_time;?></td>

					<td><?php if($v->hotArticle) echo '<span>是</span>'?></td>

					<td><?php if($v->hotUserArticle) echo '<span>是</span>';?></td>

					<td>
						<a class="edit" target="_blank" href="<?php echo MEI_BASE_URL; ?>article?aid=<?echo $v->article_id?>" target="navTab" >详情</a>||
						<a class="edit" href="/article/edit_article_content?article_id=<?php echo $v->article_id;?>" target="_blank">编辑</a>||
						<a class="edit" href="<?php echo $href;?>" target="navTab" >审核</a>||
						<a class="eidt" href="/article/edit_article_comment?article_id=<?php echo $v->article_id;?>"target="navTab">查看评论</a>||
						<a class="deleteArticleasd" href="javascript:void(0);" aid="<?php echo $v->article_id ?>"><span>删除</span></a>
					</td>
					
				</tr>
			<?php }?>
			</tbody>
	</table>
</form>
	<?php $this->includeTemplate('', 'pages');?>
</div>
<div class="pageContent confirmCheck">
		<div style="float:left;margin-left:20px;margin-top:10px;">
			<label>文章id:</label>
			<input name="article_id" class="articleIds" type="text" value=""/>(多个文章用逗号分隔)
		</div>
		<div style="clear:both"></div>
		
		<div style="float:left;margin-left:20px;">
			<label>审核通过:</label>
			质量: 
			<select class="qualitySelect" name="quality">
				<option value="0">请选择</option>
				<option value="1">质量上</option>
				<option value="2">质量中</option>
				<option value="3">质量下</option>
			</select>	
		</div>
		<div style="clear:both"></div>
		<div style="float:left;margin-left:20px;">
			<label>审核不通过:</label>
			不通过的原因:<input type="text" id="reason" name="reason" style="float:none"/>
		</div>
		<div style="clear:both"></div>
		<div style="margin-top:30px;margin-left:40px;">
			<button class="saveMoreCheck" type="button">保存</button>
			<button class="cancelMoreCheck" type="button">取消</button>
		</div>
	</div>


<script>
	$('.moreCheck').bind('click',function(){
		var check		=	$('input:checked');
		if(!check.length){
				alert('您没有选择内容');
				return false;
		}
		$('.confirmCheck').toggle(300);
	});
</script>
