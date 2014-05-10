<div class="pageContent">
	<form method="post" action="/daren/edit_user" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $userInfo->user_id;?>" type="hidden"/>
			<p>
				<label>名字:</label>
				<input name="realname" class="required" value="<?php echo $userInfo->realname;?>" type="text" size="30"/>
			</p>
			<p>
				<label>达人身份</label>
					<select name="grade">
						<?php foreach($grade as $g){?>
							<option value="<?php echo $g->grade_id;?>" <?php if($userInfo->grade->grade_id == $g->grade_id) echo "selected";?>><?php echo $g->name?></option>
						<?php }?>
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
