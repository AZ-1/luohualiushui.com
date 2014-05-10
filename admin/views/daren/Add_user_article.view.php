<form id="pagerForm" method="post" action="">
	<input type="hidden" name="pageNum" value="1" />
</form>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li class="line">line</li>
		</ul>
	</div>
	<form method="post" action="/daren/add_user_article" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div>
			<div>填写文章ID，多个文章ID以,号隔开:<input type="text" name="articleId" /></div>

			<div style="margin-top:20px;">输入达人分类名称:
				<select id="category" name="hotUserCategory">
					<?php foreach($hotUserCategoryList as $huc):?>
						<option value="<?php echo $huc->id?>"><?php echo $huc->name?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div style="margin-top:20px;">输入达人标签名称:
				<select id="tag" name="hotUserTag">
					<?php 
						foreach($hotUserTagList as $hut):
							if($hotUserCategoryList[0]->id == $hut->hot_user_category_id){
					?>
						<option value="<?php echo $hut->tag_id?>"><?php echo $hut->name?></option>
					<?php } endforeach;?>
				</select>
			</div>

		</div>
		<div class="buttonActive" style="float:right;margin-top:30px;"><div class="buttonContent"><button type="submit">保存</button></div></div>
	</form>
</div>
<script>
$("#category").change(function(){
	var categoryId = $("#category").children('option:selected').val();
	$.post('/daren/add_user_article',{hotUserCategoryId:categoryId},function(){
		$("#tag").empty();
		<?php 
			foreach($hotUserTagList as $hutl):
		?>
			if(categoryId == <?php echo $hutl->hot_user_category_id?>)
				$("#tag").append("<option value=<?php echo $hutl->tag_id?>><?php echo $hutl->name?></option>");
		<?php endforeach;?>
	});
	
});
</script>
