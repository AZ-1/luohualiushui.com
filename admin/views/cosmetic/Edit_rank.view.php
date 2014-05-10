<div class="pageContent">
	<form method="post" action="/cosmetic/edit_rank" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
		<input type="hidden" value="<?php echo $rank->id?>" name="id" />
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr>	
					<td><label>名称</label></td>
					<td><input name="list_name" type="text" size="30" value="<?php echo $rank->list_name?>" /></td>
				</tr>
					
			</table>
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
