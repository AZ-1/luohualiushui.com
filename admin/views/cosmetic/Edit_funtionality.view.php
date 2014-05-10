<form id="pagerForm" method="post" action="">
	<input type="hidden" name="fun_id" value="<?php echo $fun_id;?>"/>
</form>
<div class="pageContent">
<form method="post" action="/cosmetic/edit_funtionality?isUp=1&fun_id=<?php echo $fun_id;?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone);" novalidate="novalidate">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr><td>
					<label>新名称</label>
					<input name="newName" type="text" value="<?php echo $cur_name;?>">
				</td></tr>
				<label>所属分类</label>
				<select name="select_id">
				<?php foreach($base_item as $base){?>
					<option value="<?php echo $base->id?>" ><?php echo $base->classify_name;?></option>
				<?php }?>
				</select>
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
