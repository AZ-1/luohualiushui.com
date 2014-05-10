<div class="pageHeader">
	<form method="post" action="/daren/edit_user_category" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
	<input type="hidden" value="<?php echo $userCategory->id?>" name="id">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					推荐达人分类名称：<input type="text" name="name" value="<?php echo $userCategory->name;?>"/>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
