<div class="pageContent">
	<form method="post" action="/feedback/add_feedback" class="pageForm required-validate" >
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>用户ID：</label>
				<input name="user_id" type="text" size="30" value="" />
				<label>反馈时间：</label>
				<input name="create_time" type="text" size="30" value="<?php echo date('Y-m-d H:i:s',time()); ?>" />
				<label>反馈类容：</label>
				<input name="content" type="text" size="30" value="" />
				<label>终端类型：</label>
				<input name="client_type" type="text" size="30" value="" />
			</p>
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
