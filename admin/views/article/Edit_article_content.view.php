<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1" />
<link href="/static/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/static/js/jquery.js"></script>
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta property="qc:admins" content="22716260406014176375" />
</head>
<body>
<div class="article_content" style="width:935px;margin:0 auto;">
	<form action="/article/Edit_article_content" method="POST">
		<input type="hidden" name="article_id" value="<?php echo $article->article_id;?>">
		<p class="article_bt">标题:</p>
		<div class="article_in_title">
		<input type="text" name="title" value="<?php echo $article->title?>" />
		</div>
		<div class="clear_f"></div>
		<div class="article_cata_list">
			<p><select id="categoryList" name="category_id"><option>请选择分类</option>
				<?php foreach($categoryTagList as $v): ?>
					<option top_cid="<?php echo $v->id ?>"  value="<?php echo $v->id ?>" 
							<?php 
								if($v->id == $article->category_id){
									echo 'SELECTED';
								}
							?>
					>
					<?php echo $v->name ;?>
					</option>
					<?php if(!empty($v->child)){
						foreach($v->child as $vChild){
					?>
							<option top_cid="<?php echo $v->id ?>"  value="<?php echo $vChild->id;?>"
								<?php 
									if($vChild->id == $article->category_id){
										echo 'SELECTED';
									}
								?>
							>
							<?php echo '&nbsp&nbsp&nbsp'.$vChild->name ;?>
							</option>
					<?php
						}
					}
					?>
				<?php endforeach; ?>
			</select></p>
			<div id="tag_list">
				<?php foreach($categoryTagList as $vCategory): ?>
					<?php if( isset($vCategory->tag) ): ?>
						<div class="none_f" id="cataTag_<?php echo $vCategory->id; ?>">
						<?php foreach($vCategory->tag as $vTag): ?>
							<p style="float:left;">
								<input class="tagCheckbox" type="checkbox" name="tag[]" value="<?php echo $vTag->tag_id; ?>"  id="tag_<?php echo $vTag->tag_id; ?>"  /> 
								<label for="tag_<?php echo $vTag->tag_id ?>"><?php echo $vTag->name ?></label>
							</p>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="clear_f"></div>
		<p class="article_zw">正文:</p>
		<script type="text/plain" id="myEditor"><?php echo $article->content;?></script>
		<!--<div id="myEditor" name="content"></div>-->
		<div class="clear_f"></div>
		<br/>
		<div class="release_btn"><input type="submit" value="发布文章" /></div>
		<br/>
		<br/>
	</form>
</div>
<script src="/static/ueditor/ueditor.config.js"></script>
<script src="/static/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
// 编辑器
var editor = new UE.ui.Editor();
    editor.render("myEditor");

$(function(){
	var articleTag = <?php echo json_encode($articleTagList)?>;
	var len = articleTag.length;
	var is_first = false;
	// 标签列表
	$("#categoryList").change(function(){
		$('#tag_list>.none_f').hide();
		$("#cataTag_"+$(this).find("option:selected").attr('top_cid')).show();
		if(is_first){
			$('.tagCheckbox').attr('checked' , false);
		}
		for(var i = 0 ; i<len; i++){
			$('#tag_'+ articleTag[i].tag_id).attr('checked' , "checked");
		}

	});
	$(".tagCheckbox").click(function(){
		is_first = true;
	});

	//		$('#tag_'+ articleTag[i].tag_id).attr('checked' , 'checked');
	$('#categoryList').find("option:selected").trigger('change');
});
</script>
</body>
</html>
