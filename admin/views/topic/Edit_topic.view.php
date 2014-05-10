<div class="pageContent">
	<form method="post" action="/topic/edit_topic" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);" enctype="multipart/form-data" >
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $topicInfo[0]->topic_id;?>" type="hidden"/>
			<div>
				<label>标题：</label>
				<input name="title" class="required" value="<?php echo $topicInfo[0]->title;?>" type="text" size="30"/>
			</div>
			<div>
				<label>图片:</label>
				<input type="file" name="pic" />
				<img width="200" src="<?php echo $topicInfo[0]->pic;?>" />
			</div>
			<div>
				<label>描述:</label>
				<input name="description" value="<?php echo $topicInfo[0]->description;?>" type="text" size="30"/>
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
