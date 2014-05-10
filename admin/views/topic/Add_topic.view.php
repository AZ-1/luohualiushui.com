<div class="pageContent">
	<!--
<form method="post" action="/topic/add_topic" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
-->
	<form method="post" action="/topic/add_topic" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this,navTabAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div>
				<label>标题：</label>
				<input name="title" class="required" type="text" size="30"/>
			</div>
			<div>
				<label>上传图片：</label>
				<input type="file" name="pic" />
			</div>
			<div>
				<label>描述:</label>
				<input name="description" type="text" size="30"/>
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
