<div class="pageContent">
<form method="post" action="/cosmetic/add_funtionality?base_id=<?php echo $cur_id;?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" novalidate="novalidate">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr>
				<input name="name" type="text" size="50">
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
