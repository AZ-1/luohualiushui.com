<div class="pageContent">
	<form method="post" action="/daren/edit_grade" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $grade->grade_id;?>" type="hidden"/>
			<p>
				<label>名称:</label>
				<input name="name" value="<?php echo $grade->name;?>" type="text" size="30"/>
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
