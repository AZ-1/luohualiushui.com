<div class="pageHeader">
	<form method="post" action="/daren/add_user_tag" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					推荐达人标签名称：<input type="text" name="name" />
				</td>
				<td>
					推荐达人标签分类：<select name="category">
										<?php foreach($categoryList as $cl):?>
											<option value="<?php echo $cl->id;?>"><?php echo $cl->name;?></option>
										<?php endforeach;?>
									</select>
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
</div>
