<div class="pageContent">
	<form method="post" action="/user/edit_user" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)";>
		<div class="pageFormContent" layoutH="56">
				<input name="id" value="<?php echo $userInfo->user_id;?>" type="hidden"/>
				<input name="old_id" value="<?php if($userInfo->isBind){ echo $userInfo->old_user_id;}?>" type="hidden"/>
			<p>
				<label>原用户名:</label>
				<input readonly name="realname" class="required" value="<?php echo $userInfo->realname;?>" type="text" size="30"/>
			</p>
			<p>

				<label>用户身份</label>
					<select name="identity">
						<option value="0">请选择</option>
						<?php foreach($identity as $v){
?>
							<option value="<?php echo $v->id;?>" <?php if(is_object($userInfo->identity) && $userInfo->identity->identity_id == $v->id) echo "selected";?>><?php echo $v->identity?></option>
						<?php }?>
					</select>

			</p>
			<p>
				<?php 
					if($userInfo->isBind){
						echo "这个账号已绑定了$userInfo->user_id,您还可以---解除绑定<input type='checkbox' value='1' name='unbind'>";
						echo "<span>(原user_id:$userInfo->old_user_id )</span>";
					}else{
				?>
				<label>绑定到用户ID:</label>
				<input name="newId" class="required" type="text" size="30" placeholder="<?php if($userInfo->isBind == 1) echo "已绑定ID".$userInfo->user_id;?>" />
				<?php }?>
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
