<div class="pageContent">
	<form method="post" action="/index/add_hot_daren" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>用户id：</label>
				<input name="user_id" type="text" />(多个文章用逗号分隔)
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
