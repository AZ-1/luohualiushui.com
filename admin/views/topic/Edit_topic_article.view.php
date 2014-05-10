<div class="pageContent">
	<form method="post" action="/topic/edit_topic_article?isUp=1&id=<?php echo $id;?>" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);" enctype="multipart/form-data" >
		<div class="pageFormContent" layoutH="56">
			<input type="hidden" name="aid" value="<?php echo $article_id?>" />
			<div>
				<label>图片:</label>
				<input type="file" name="pic" />
				<img width="200" src="<?php echo $title_pic->title_pic?>" />
			</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
