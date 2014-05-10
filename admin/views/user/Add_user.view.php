<div class="pageContent">
	<form method="post" action="/user/add_user" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>真实名字:</label>
				<input name="realname" class="required" type="text" size="30"/>
			</p>
			<p>
				<label>密码:</label>
				<input name="password" type="text" size="30"/>
			</p>
			<p>
				<label>用户身份:</label>
				<select name="identity">
				<?php foreach($identity as $g){?>
				<option value="<?php echo $g->id?>"><?php echo $g->identity;?></option>
				<?}?>
				</select>
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
