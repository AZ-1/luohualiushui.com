<div class="pageContent">
	<form method="post" action="/index/edit_topic" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
	<div class="pageFormContent" layoutH="56">
			<p>
				<input type="hidden" name="topic_id" value="<?php echo $topic->topic_id;?>" />
				<label>标题：</label>
				<input name="title" class="required" type="text" size="30" value="<?php echo $topic->title;?>"/>
			</p>
			<p>
				<label>图片：</label>
				<input name="pic" type="text" size="30" value="<?php echo $topic->pic;?>"/>
			</p>
			<p>
				<label>描述：</label>
				<input name="description" type="text" size="30" value="<?php echo $topic->description;?>"/>
			</p>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
               <!--
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li> -->
			</ul>
		</div>
	</form>
</div>
