<div class="pageContent">
	<form method="post" action="/article/edit_category" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>类目名称：</label>
				<input name="cid" type="hidden" value="<?php echo $category->id ?>"/>
				<input name="name" type="text" size="30" value="<?php echo $category->name ?>"/>
			</p>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
