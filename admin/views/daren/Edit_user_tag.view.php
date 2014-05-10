<div class="pageHeader">
	<form method="post" action="/daren/edit_user_tag" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)";>
	<input type="hidden" value="<?php echo $userTag->tag_id?>" name="id">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					推荐达人标签名称：<input type="text" name="name" value="<?php echo $userTag->name;?>"/>
				</td>
			</tr>
			<tr>
				<td>
					推荐达人标签分类：<select name="category">
										<?php foreach($categoryList as $cl):?>
											<option value="<?php echo $cl->id;?>" <?php if($userTag->hot_user_category_id == $cl->id) echo 'SELECTED';?>><?php echo $cl->name;?></option>
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
