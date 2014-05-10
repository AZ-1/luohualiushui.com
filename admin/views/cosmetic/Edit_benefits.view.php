<form id="pagerForm" method="get" action="">
	<input type="hidden" name="benefit_id" value="<?php echo $benefit_id;?>"/>
</form>
<div class="pageContent">
<form method="get" action="/cosmetic/edit_benefits?isUp=1&benefit_id=<?php echo $benefit_id?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone);" novalidate="novalidate">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr><td>
					<label>新名称</label>
					<input name="newName" type="text" value="<?php echo $cur_name;?>">
				</td></tr>
				<label>所属分类</label>
				<select name="select_id">
				<?php foreach($base_item as $base){?>
					<option value="<?php echo $base->id?>" ><?php echo $base->des_info;?></option>
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
