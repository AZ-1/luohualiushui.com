	<form method="post" action="/index/add_hot_topic" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>话题id：</label>
				<input type="text" name="topic_id" />(多个id用逗号','分开)
			</p>
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
